<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use App\Security\LoginFormAuthenticator;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class UserController extends AbstractController
{
    private UserService $userService;

    private MailerInterface $mailer;

    public function __construct(UserService $userService, MailerInterface $mailer)
    {
        $this->userService = $userService;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/register", name="app_register")
     * @param LoginFormAuthenticator $authenticator
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function register(LoginFormAuthenticator $authenticator, Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user->setPassword($passwordEncoder->encodePassword($user, $form->get('plainPassword')->getData()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->userService->notifyUser($user, $this->mailer);

            return $guardHandler->authenticateUserAndHandleSuccess($user, $request, $authenticator, 'main');
        }

        return $this->render('security/index.html.twig', ['userForm' => $form->createView()]);
    }
}