<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('pages/security/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(Security $security)
    {
        $response = $security->logout();
        return $this->redirectToRoute('app_home');
    }

    #[Route('/registration', name: 'app_registration', methods: ['GET', 'POST'])]
    public function registration(
        EntityManagerInterface $manager, 
        UserPasswordHasherInterface $passwordHasher,
        Request $request
        ) : Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) 
        {
            //$options = ['cost' => 13];
            //$hashed = password_hash($user->getPassword(), PASSWORD_BCRYPT, $options);

            $password = $user->getPassword();
            $hashed = $passwordHasher->hashPassword(
                $user,
                $password
            );
        
            $user = $form->getData();
            $user->setPassword($hashed);
            $user->setRoles(['ROLE_USER']);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Your account has been successfully created!'
            );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('pages/security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
