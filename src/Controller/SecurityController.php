<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/account/login", name="app_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $securityContext = $this->container->get('security.authorization_checker');

        /* If the user is logged, then redirect */
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirectToRoute('home');

            /* else give access to the form*/
        } else {

            // get the login error if there is one
            $error = $authenticationUtils->getLastAuthenticationError();
            // last username entered by the user
            $lastUsername = $authenticationUtils->getLastUsername();

            return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        }
    }

    /**
     * @Route("/account/register", name="register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $securityContext = $this->container->get('security.authorization_checker');

        /* If the user is logged, then redirect */
        if ($securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->redirectToRoute('home');

            /* else give access to the form*/
        } else {


            $user = new User();

            $form = $this->createFormBuilder($user);

            $registrationForm = $form
                ->add('name', \Symfony\Component\Form\Extension\Core\Type\TextType::class, ['label' => 'Nom d\'utilisateur', 'attr' => ['placeholder' => 'Nom d\'utilisateur']])
                ->add('email', \Symfony\Component\Form\Extension\Core\Type\TextType::class, ['label' => 'Adresse mail', 'attr' => ['placeholder' => 'exemple@gmail.com']])
                ->add('password', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'mapped' => false,
                    'first_options' => array('label' => 'Nouveau mot de passe'),
                    'second_options' => array('label' => 'Confirmation du mot de passe'),
                    'invalid_message' => 'Les mots de passe ne sont pas identiques',
                ))
                ->add('submit', SubmitType::class, ['label' => 'Créer le compte'])
                ->getForm();

            $time = new \DateTime();

            $registrationForm->handleRequest($request);
            if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {

                $userData = $registrationForm->getData();

                /* @var User $userData */
                $userData->setPassword(
                    $passwordEncoder->encodePassword(
                        $userData,
                        $registrationForm->get('password')->getData()
                    )
                );

                $userData->setRoles(['ROLE_USER']);
                $userData->setCreationDate($time);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($userData);
                $entityManager->flush();

                return $this->redirectToRoute('home');

            }


            return $this->render('security/register.html.twig', [
                'form' => $registrationForm->createView()
            ]);
        }
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
