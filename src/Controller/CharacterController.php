<?php

namespace App\Controller;

use App\Entity\Build;
use App\Entity\Character;
use App\Repository\CharacterRepository;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

class CharacterController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
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
     * @Route("/character/{id}", name="character")
     * @param int $id
     * @return Response
     */
    public function characterPage(int $id): Response
    {
        $character = $this->entityManager->getRepository(Character::class)->find($id);

        // Get All official builds for the current character
        $officialBuilds = $this->entityManager->getRepository(Build::class)->findBy(['gameCharacter' => $character->getId(), 'buildCategory' => 'OFFICIAL']);

        $allCommunityBuild = $this->entityManager->getRepository(Build::class)->findBy(['gameCharacter' => $character->getId(), 'buildCategory' => 'COMMUNITY']);

        return $this->render('character/character.html.twig', [
            'character' => $character,
            'officialBuilds' => $officialBuilds
        ]);
    }


    /**
     * @Route("/character/{id}/community-build/index", name="community-build")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function communityBuildIndex(Request $request, int $id): Response
    {
        $character = $this->entityManager->getRepository(Character::class)->find($id);

        /* @var Character $character */
        $allCommunityBuild = $this->entityManager->getRepository(Build::class)->findBy(['gameCharacter' => $character->getId(), 'buildCategory' => 'COMMUNITY']);

        return $this->render('character/form/index.community-build.html.twig', [
            'charactersBuild' => $allCommunityBuild,
            'character' => $character
        ]);
    }


    /**
     * @Route("/character/{id}/community-build/new", name="community-build-new")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function newCommunityBuild(Request $request, int $id): Response
    {
        $character = $this->entityManager->getRepository(Character::class)->find($id);
        /* @var Character $character */

        $build = new Build();
        $build->setGameCharacter($character)
            ->setBuildCategory('COMMUNITY');

        $form = $this->createFormBuilder($build)
            ->add('name', \Symfony\Component\Form\Extension\Core\Type\TextType::class)
            ->add('description', TextareaType::class)
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            $build = $form->getData();

            // ... perform some action, such as saving the task to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($build);
            $entityManager->flush();

            return $this->redirectToRoute('character', ['id' => $character->getId()]);
        }

        return $this->render('character/form/new.community-build.html.twig', [
            'character' => $character,
            'new' => $form->createView()
        ]);
    }


    /**
     * Returns all characters from the database
     *
     * @return object[]
     */
    public function getAllCharacters(): array
    {
        return $characters = $this->entityManager->getRepository(Character::class)->findAll();
    }

}
