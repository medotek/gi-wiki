<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class MaintenanceListener
{
    private $maintenance, $ipAuthorized;
    private ContainerInterface $container;
    private Environment $twig;

    public function __construct($maintenance, ContainerInterface $container, Environment $twig)
    {
        $this->twig = $twig;
        $this->container = $container;
        $this->maintenance = $maintenance["statut"];
        $this->ipAuthorized = $maintenance["ipAuthorized"];
    }

    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     */
    public function onKernelRequest(RequestEvent $event)
    {
        // This will get the value of our maintenance parameter
        $maintenance = $this->maintenance;
        $currentIP = $_SERVER['REMOTE_ADDR'];
        // This will detect if we are in dev environment (app_dev.php)
        // $debug = in_array($this->container->get('kernel')->getEnvironment(), ['dev']);
        // If maintenance is active and in prod environment
        if ($maintenance and !in_array($currentIP, $this->ipAuthorized)) {
            //            return;
            // We load our maintenance template
//            $engine = $this->container->get('templating');
            // We send our response with a 503 response code (service unavailable)
            $event->setResponse(new Response($this->twig->render('maintenance/maintenance.html.twig'), 503));
            $event->stopPropagation();

        }
    }
} 
