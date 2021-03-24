<?php

namespace App\Controller;

use App\Entity\Build;
use App\Entity\CommunityBuild;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Serializer;

class UserController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    /** @var Security */
    private Security $security;


    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security
    )
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    /**
     * @Route("/account/profile", name="user")
     */
    public function index(): Response
    {
        /* @var CommunityBuild $userCommunityBuilds */

        $userCommunityBuilds = $this->entityManager->getRepository(CommunityBuild::class)->findBy(['author' => $this->security->getUser()]);

        /* @var CommunityBuild $userCommunityBuild */


        $allCommunityBuild = [];
        foreach ($userCommunityBuilds as $userCommunityBuild) {
            $allCommunityBuild[] = $this->entityManager->getRepository(Build::class)->findBy(['id' => $userCommunityBuild->getBuild()]);
        }

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'userBuild' => $allCommunityBuild
        ]);

    }
}
