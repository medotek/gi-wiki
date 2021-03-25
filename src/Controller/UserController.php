<?php

namespace App\Controller;

use App\Entity\Artifact;
use App\Entity\Build;
use App\Entity\CommunityBuild;
use App\Entity\User;
use App\Entity\Weapon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
        /* @var CommunityBuild $userCommunityBuild */

        $allCommunityBuild = [];
        foreach ($this->getCommunityBuildForTheCurrentUser() as $userCommunityBuild) {
            $allCommunityBuild[] = $this->entityManager->getRepository(Build::class)->findBy(['id' => $userCommunityBuild->getBuild()]);
        }

        $array = array_map(null, (array)$this->getCommunityBuildForTheCurrentUser(), (array)$allCommunityBuild);

        dump($array);
        return $this->render('user/index.html.twig', [
            'userBuild' => $array
        ]);

    }


    /**
     * @Route("/account/profile/build/remove/{id}", name="delete-build-profile")
     * @param int $id
     * @return Response
     */
    public function removeBuild(int $id): Response
    {
        /* @var CommunityBuild $buildEntity */
        $buildEntity = $this->entityManager->getRepository(CommunityBuild::class)->findOneBy(['build' => $id]);

        /* @var User $user */
        $user = $this->security->getUser();

        if ($buildEntity->getAuthor() === $user) {
            $remove = $this->getDoctrine()->getManager();

            $remove->remove($buildEntity);
            $remove->flush();
            $this->addFlash('build-removed-success', 'Le build a bien été supprimé');

            return $this->redirectToRoute('profile-builds');
        } else {
            $this->addFlash('build-removed-error', 'Le build n\'a pas pu être supprimé!');

            return $this->redirectToRoute('user');
        }
    }

    /**
     * Returns the community builds of the current user
     *
     * @return array
     */
    public function getCommunityBuildForTheCurrentUser(): array
    {
        return $this->entityManager->getRepository(CommunityBuild::class)->findBy(['author' => $this->security->getUser()], ['id' => 'DESC']);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     *
     * @Route("/account/profile/builds", name="profile-builds")
     */
    public function viewBuilds(Request $request)
    {


        /* @var CommunityBuild $userCommunityBuild */


        $allCommunityBuild = [];
        foreach ($this->getCommunityBuildForTheCurrentUser() as $userCommunityBuild) {
            $allCommunityBuild[] = $this->entityManager->getRepository(Build::class)->findBy(['id' => $userCommunityBuild->getBuild()], ['id' => 'DESC']);
        }

        $array = array_map(null, (array)$this->getCommunityBuildForTheCurrentUser(), (array)$allCommunityBuild);

        dump($array);

        return $this->render('user/builds/view.html.twig', [
            'userBuild' => $array
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return RedirectResponse|Response
     *
     * @Route("/account/profile/build/edit?{id}", name="profile-builds-edit")
     */
    public function editBuild(Request $request, int $id)
    {
        $build = $this->entityManager->getRepository(Build::class)->find($id);

        $form = $this->createFormBuilder($build, ['allow_extra_fields' => true, 'method' => 'put']);


        /* @var Build $build */
        /* @var CommunityBuild $communityBuild */
        $communityBuild = $this->entityManager->getRepository(CommunityBuild::class)->findOneBy(['build' => $build]);

        $weapons = $this->entityManager->getRepository(Weapon::class)->findBy(['type' => $build->getGameCharacter()->getWeaponType()]);

        $formReal = $form
            ->add('name', TextType::class, ['label' => 'Titre', 'attr' => ['placeholder' => 'Titre']])
            ->add('description', TextareaType::class, ['label' => 'Description', 'attr' => ['placeholder' => 'description']])
            ->add(
                $form->create('tags', TextType::class, ['label' => 'tags', 'attr' => ['placeholder' => 'Ex : Supports dps, new meta, ...', 'value' => implode(',', $communityBuild->getTags())]])
                    ->addModelTransformer(new CallbackTransformer(
                        function ($originalDescription) {
                            return $originalDescription;
                        },
                        function ($submittedDescription) {
                            return explode(',', $submittedDescription);
                        }
                    )))
            ->add('weapons', EntityType::class, ['label' => 'Armes', 'class' => Weapon::class, 'choices' => $weapons, 'multiple' => true, 'expanded' => 'true', 'choice_attr' => function ($choice, $key, $value) {
                return ['image' => $choice->getImage()];
            }])
            ->add('artifacts', EntityType::class, ['label' => 'Artéfacts', 'class' => Artifact::class, 'multiple' => true, 'expanded' => true, 'choice_attr' => function($choice, $key, $value) {
                return ['image' => $choice->getImage()];
            }])
            ->add('submit', SubmitType::class, ['label' => 'Modifier le build']);

        $formRealSubmit = $formReal->getForm();
        $formRealSubmit->handleRequest($request);

        if ($formRealSubmit->isSubmitted() && $formRealSubmit->isValid()) {

            $build = $formRealSubmit->getData();


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($build);
            $entityManager->flush();

            $tagsField = $formRealSubmit->get('tags')->getData();

            dump($tagsField);
            $communityBuild->setTags($tagsField);

            $entityManager->persist($communityBuild);
            $entityManager->flush();

            $this->addFlash('build-edit-success', 'Le Build a été édité avec succès!');

            return $this->redirectToRoute('profile-builds');
        } else {
            if ($formRealSubmit->isSubmitted()) {
                $this->addFlash('build-edit-error', 'Le Build n\'a pas pu être édité');
            }
        }

        return $this->render('user/builds/edit.html.twig', [
            'build' => $build,
            'edit' => $formRealSubmit->createView()
        ]);
    }
}
