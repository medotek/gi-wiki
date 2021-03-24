<?php

namespace App\Controller;

use App\Entity\Build;
use App\Entity\Character;
use App\Entity\CommunityBuild;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Serializer;

class CharacterController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var Security
     */
    private Security $security;

    private Serializer $serializer;

    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security
    )
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
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

        $allCommunityBuild = $this->entityManager->getRepository(Build::class)->findBy(['gameCharacter' => $character->getId(), 'buildCategory' => 'COMMUNITY'], ['id' => 'DESC'], 4);

        return $this->render('character/character.html.twig', [
            'character' => $character,
            'officialBuilds' => $officialBuilds,
            'communityBuilds' => $allCommunityBuild
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

        $communityBuildEntity = $this->entityManager->getRepository(CommunityBuild::class)->find($allCommunityBuild->getId());
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

        //TODO: Is user logged condition before view the form

        $character = $this->entityManager->getRepository(Character::class)->find($id);
        /* @var Character $character */

        $build = new Build();
        $build->setGameCharacter($character)
            ->setBuildCategory('COMMUNITY');

        $communityBuild = new CommunityBuild();
        $communityBuild->setVotes(0);

        $time = new \DateTime();
        $communityBuild->setCreationDate($time);

        $user = $this->security->getUser();

        $currentUser = $this->entityManager->getRepository(User::class)->find($user);

        /* @var User $currentUser*/
        $communityBuild->setAuthor($currentUser);

        $form = $this->createFormBuilder($build, ['allow_extra_fields' => true,'method' => 'put']);

        $formReal = $form
            ->add('name', \Symfony\Component\Form\Extension\Core\Type\TextType::class)
            ->add('description', TextareaType::class)
            ->add(
                $form->create('tags', \Symfony\Component\Form\Extension\Core\Type\TextType::class)
                    ->addModelTransformer(new CallbackTransformer(
                        function ($originalDescription) {
                            return $originalDescription;
                        },
                        function ($submittedDescription) {
                            return explode(',', $submittedDescription);
                        }
                    )))
            ->add('submit', SubmitType::class);

        $formRealSubmit =$formReal->getForm();

        $formRealSubmit->handleRequest($request);
//        $formCommunityBuildReal->handleRequest($request);
        if ($formRealSubmit->isSubmitted() && $formRealSubmit->isValid()) {
            // $form->getData() holds the submitted values
            $build = $formRealSubmit->getData();

            // ... perform some action, such as saving the task to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($build);
            $entityManager->flush();



            /* Connaitre l'id du build avant de l'insérer dans la db*/
            $communityBuild->setBuild($build);

            /* On set le tag après que le formulaire soit soumis*/
            $tags = $formRealSubmit->get('tags')->getData();

            $communityBuild->setTags($tags);

            $entityManager->persist($communityBuild);
            $entityManager->flush();

            return $this->redirectToRoute('character', ['id' => $character->getId()]);
        }

        return $this->render('character/form/new.community-build.html.twig', [
            'character' => $character,
            'new' => $formRealSubmit->createView()
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
