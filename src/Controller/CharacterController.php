<?php

namespace App\Controller;

use App\Entity\Character;
use App\Repository\CharacterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CharacterController extends AbstractController
{
    private CharacterRepository $characterRepository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/characters", name="characters")
     * @return Response
     */
    public function index(): Response
    {

        return $this->render('character/index.html.twig', [
            'controller_name' => 'CharacterController',
            'characters' => $this->getAllCharacters()
        ]);
    }

    /**
     * Returns all characters from the database
     *
     * @return JsonResponse
     */
    public function getAllCharacters(): JsonResponse
    {
        $characters = $this->entityManager->getRepository(Character::class)->findAll();

        return $this->json($characters);
    }

}
