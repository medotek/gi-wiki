<?php

namespace App\Controller;

use App\Entity\Artifact;
use App\Entity\Build;
use App\Entity\CommunityBuild;
use App\Entity\User;
use App\Entity\Weapon;
use App\Extra\ApiService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UserController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    /** @var Security */
    private Security $security;

    private HttpClientInterface $client;

    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security,
        HttpClientInterface $client
    )
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->client = $client;
    }

    /**
     * Request = 'getuserinfo' or 'getusercharacters' or 'getabyssinfo'
     * @param string $request
     * @param int $uid
     * @return string[]
     */
    public function getProfileByUID(string $request, int $uid): array
    {
        try {
            $response = $this->client->request(
                'GET',
                'http://localhost:3000/' . $request . '?uid=' . $uid
            );

            $statusCode = $response->getStatusCode();

            $contentType = $response->getHeaders()['content-type'][0];

            $content = $response->getContent();

            $content = $response->toArray();


        } catch (TransportExceptionInterface |
        RedirectionExceptionInterface |
        ServerExceptionInterface |
        ClientExceptionInterface |
        DecodingExceptionInterface $e) {
            $content = ['errors' => 'error'];
        }
        return $content;

    }

    /**
     * Returns the current user
     *
     * @return User
     */
    public function getCurrentUser(): User
    {
        $user = $this->security->getUser();
        /* @var User $user */

        $currentUser = $this->entityManager->getRepository(User::class)->find($user->getId());
        /* @var User $currentUser */

        return $currentUser;
    }

    /**
     * @Route("/account/profile/set/uid", name="profile-set-uid")
     *
     * @param Request $request
     * @param ApiService $apiService
     * @return JsonResponse
     */
    public function newUid(Request $request, ApiService $apiService)
    {

        $em = $this->getDoctrine()->getManager();
        if ($request->isXmlHttpRequest()) {
            $data = $request->getContent();


            $uidData = $apiService->validateAndCreate($data, User::class);

//            dump($uidData);
            $uid = $this->getCurrentUser();
            if ($uidData == 'error') {
                return new JsonResponse('invalid uid or uid already used (please, contact an administrator if your issue persists)', Response::HTTP_NOT_FOUND);
            } elseif (/* @var User $uidData */
                $uidData->getUid() == (0 || null)) {
                $uidAjax = null;

                return new JsonResponse('invalid uid or uid already used (please, contact an administrator if your issue persists)', Response::HTTP_NOT_FOUND);
            } else {
                /* @var User $uidData */
                $uidAjax = $uidData->getUid();
                $uid->setUid($uidAjax);

                $em->persist($uid);
                $em->flush();
                $uidProfile = $this->getProfileByUID('getuserinfo', $this->getCurrentUser()->getUid());

                return new JsonResponse($uidProfile);
            }


        }
        return new JsonResponse('form error', Response::HTTP_NOT_FOUND);
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


        $uidMap = [];
        if ($this->getCurrentUser()->getUid() != (null || 0)) {
            $isUidAvailable[] = 1;
            $uidProfile = $this->getProfileByUID('getuserinfo', $this->getCurrentUser()->getUid());
            $uidCharacters = $this->getProfileByUID('getusercharacters', $this->getCurrentUser()->getUid());

            $charactersSet = [];
            $characterKey = 0;
            $newUidCharacters = [];

//            dump($uidCharacters);
            if (array_key_exists("code", $uidCharacters) & $uidCharacters['code'] === -1 ||
                array_key_exists("code", $uidCharacters) & $uidCharacters['code'] === 10102) {
                $charactersSet['code'] = $uidCharacters['code'];
            } else {
                foreach ($uidCharacters as $characters) {

                    $characterKey++;
                    $characterReliquaries[] = $characters['reliquaries'];
                    /*access to the current key of the characterReliquaries array*/

                    $reliquariesId = [];
                    $reliquariesSet = [];
                    foreach ($characterReliquaries[$characterKey - 1] as $reliquary) {
                        $reliquariesId[] = $reliquary['set']['id'];
                        $reliquariesSet[] = $reliquary['set'];
                    }

                    $counts = array_count_values($reliquariesId);

                    $setEffectId = [];
                    $setsEffectId = [];
                    /*Verify if there is any duplicate values on an array given*/
                    foreach ($counts as $id => $count) {
                        /*if there is more than 1 duplicate values execute : */
                        if ($count > 1 & $count < 4) {
                            $setEffectId[] = $id;
                        } else {
                            if ($count >= 4) /*if there is 4 or more than 4 duplicate values execute : */ {
                                $setsEffectId[] = $id;
                            }
                        }

                    }

                    if (!empty($setEffectId)) {
                        $keys = [];

                        /*Get keys for a value given in an array*/
                        $characterSets = [];
                        foreach ($setEffectId as $id) {
                            $keys[] = array_search($id, array_column($reliquariesSet, 'id'));
                        }

                        /* push sets of $reliquariesSet by an extracted key in a new array*/
                        foreach ($keys as $key) {
                            $characterSets[] = $reliquariesSet[$key]['affixes'][0];
                        }

                        /*Add the new column to the character array*/
                        $characters['extra'] = ['sets' => $characterSets];
                    } else {
                        if (!empty($setsEffectId)) {
                            $keys = [];
                            $characterSets = [];

                            foreach ($setsEffectId as $id) {
                                $keys[] = array_search($id, array_column($reliquariesSet, 'id'));
                            }

                            foreach ($keys as $key) {
                                $characterSets[] = $reliquariesSet[$key]['affixes'][1];

                            }

                            /*Add the new column to the character array*/
                            $characters['extra'] = ['sets' => $characterSets];


                        }
                    }

                    /*if the array hasn't got the extra column, push one in it which is empty */
                    if (!array_key_exists("extra", (array)$characters)) {
                        $characters['extra'] = [];
                    }
                    /*Push characters (with a new column) on a new array*/
                    $newUidCharacters[] = $characters;

                }
            }

            $uidMap[] = array_map(null, $isUidAvailable, $uidProfile, $charactersSet);

        } else {
            $uidProfile = [
                'Erreur' => 'Veuillez renseigner votre UID Genshin'
            ];
            $isUidAvailable[] = 0;

            $uidMap[] = array_map(null, $isUidAvailable, $uidProfile);
//            dump($uidMap);
        }


        return $this->render('user/index.html.twig', [
            'userBuild' => $array,
            'uidProfile' => $uidMap,
            'uidNumber' => $this->getCurrentUser()->getUid(),
            'uidCharacters' => $newUidCharacters
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
            ->add('artifacts', EntityType::class, ['label' => 'Artéfacts', 'class' => Artifact::class, 'multiple' => true, 'expanded' => true, 'choice_attr' => function ($choice, $key, $value) {
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
