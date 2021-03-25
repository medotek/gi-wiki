<?php

namespace App\Controller;

use App\Entity\Artifact;
use App\Entity\Build;
use App\Entity\Character;
use App\Entity\CommunityBuild;
use App\Entity\Set;
use App\Entity\User;
use App\Entity\Weapon;
use App\Form\SetType;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\TextEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;


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
        $allCommunityBuild = $this->entityManager->getRepository(Build::class)->findBy(['gameCharacter' => $character->getId(), 'buildCategory' => 'COMMUNITY'], ['id' => 'ASC']);

        /* @var Build $allCommunityBuild */
        $communityBuildEntities = $this->entityManager->getRepository(CommunityBuild::class)->findBy(['build' => $allCommunityBuild], ['build' => 'ASC'] );

        /* @var CommunityBuild $communityBuildEntities */

        $builds = array_map(null, (array)$communityBuildEntities, $allCommunityBuild);

        dump($builds);

        return $this->render('character/form/index.community-build.html.twig', [
            'charactersBuild' => $builds,
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
        //TODO: Remember to not hang yourself while working on this function


        //// Recuperation des informations de base ////

        // Personnage
        $character = $this->entityManager->getRepository(Character::class)->find($id);
        /* @var Character $character */
        // Armes
        $weapons = $this->entityManager->getRepository(Weapon::class)->findBy(['type' => $character->getWeaponType()]);


        //// Creation de l'objet build ////
        $build = new Build();
        //Attribution du personnage associé et de la catégorie correspondante
        $build->setGameCharacter($character)
            ->setBuildCategory('COMMUNITY');

        //// Création de l'objet CommunityBuild ////
        $communityBuild = new CommunityBuild(0,new DateTime());

        //Récupération des informations de l'auteur
        $user = $this->security->getUser();
        $currentUser = $this->entityManager->getRepository(User::class)->find($user);

        /* @var User $currentUser*/
        $communityBuild->setAuthor($currentUser);

        $form = $this->createFormBuilder($build, ['allow_extra_fields' => true,'method' => 'put']);


        //Formulaire du build
        $formReal = $form
            ->add('name', TextType::class, ['label' => 'Titre', 'attr' => ['placeholder' => 'Titre']])
            ->add('description', TextareaType::class, ['label' => 'Description', 'attr' => ['placeholder' => 'description']])
            ->add(
                $form->create('tags', TextType::class, ['label' => 'tags', 'attr' => ['placeholder' => 'Ex : Supports dps, new meta, ...']])
                    ->addModelTransformer(new CallbackTransformer(
                        function ($originalDescription) {
                            return $originalDescription;
                        },
                        function ($submittedDescription) {
                            return explode(',', $submittedDescription);
                        }
                    )))
            ->add('weapons',EntityType::class, ['label' => 'Armes', 'class' => Weapon::class, 'choices' => $weapons, 'multiple' => true, 'expanded' => true])
            ->add('artifacts', EntityType::class, ['label' => 'Artéfacts', 'class' => Artifact::class, 'multiple' => true, 'expanded' => true])
            ->add('submit_build', SubmitType::class, ['label' => 'Terminer le build']);

        $formRealSubmit = $formReal->getForm();

        //Tratement du formulaire de build
        $formRealSubmit->handleRequest($request);
//        $formCommunityBuildReal->handleRequest($request);
        if ($formRealSubmit->isSubmitted() && $formRealSubmit->isValid()) {
            if ($formRealSubmit->get('submit_build')->isClicked()) {
                // $form->getData() holds the submitted values

                $build = $formRealSubmit->getData();

                /* Added a success message after creating a build*/
                $this->addFlash('build-success', 'Build créé avec succès!');

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
        }

        return $this->render('character/form/new.community-build.html.twig', [
                'character' => $character,
                'new' => $formRealSubmit->createView(),
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
