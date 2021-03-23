<?php

namespace App\Controller;

use App\Entity\Weapon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeaponController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/weapons", name="weapons")
     */
    public function index(): Response
    {
        return $this->render('weapon/index.html.twig', [
            'controller_name' => 'WeaponController',
            'weapons' => $this->getAllWeapons(),
        ]);
    }

    /**
     * Returns all weapons from the database
     *
     * @return Weapon[]
     */
    public function getAllWeapons(): array
    {
        return $characters = $this->entityManager->getRepository(Weapon::class)->findAll();
    }
}
