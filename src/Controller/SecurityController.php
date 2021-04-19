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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

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
     * @param \Swift_Mailer $mailer
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer): Response
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
                    'first_options' => [
                        'constraints' => [
                            new NotBlank([
                                'message' => 'Veuillez entrer un mot de passe',
                            ]),
                            new Length([
                                'min' => 6,
                                'minMessage' => 'Le mot de passe doit comporter au minimum 6 charactères.',
                                // max length allowed by Symfony for security reasons
                                'max' => 30,
                            ]),
                        ],
                        'label' => 'Nouveau mot de passe',
                        'attr' => ['placeholder' => 'Nouveau mot de passe']],
                    'second_options' => ['label' => 'Confirmer le mot de passe', 'attr' => ['placeholder' => 'Confirmation du mot de passe']],
                    'invalid_message' => 'Les mots de passe ne sont pas identiques',
                ))
                ->add('submit', SubmitType::class, ['label' => 'Créer le compte'])
                ->getForm();

            $time = new \DateTime();

            $registrationForm->handleRequest($request);
            if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {

                $userData = $registrationForm->getData();

                /* Added a success message*/
                $this->addFlash('success', 'Compte créé avec succès!');

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

                /* @var User $user */


                $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $userData->getEmail()]);


                $message = (new \Swift_Message('Inscription - gudako.club'))
                    ->setFrom('gudako.club@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                        // templates/emails/registration.html.twig
                            'email/registration.html.twig',
                            ['name' => $user->getName()]
                        ),
                        'text/html'
                    );

                $mailer->send($message);
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
