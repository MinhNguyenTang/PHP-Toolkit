<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/user/edition/{id}', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(
        User $user, 
        Request $request,
        EntityManagerInterface $manager
        ): Response
    {
        if(!$this->getUser()) 
        {
            return $this->redirectToRoute('app_login');
        } 
        
        if($this->getUser() !== $user) 
        {
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();
            
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Your information have been updated!'
            );

            return $this->redirectToRoute('app_ingredient');
        }

        return $this->render('pages/user/edit_profile.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('user/edition-password/{id}', name: 'app_user_edit-pwd', methods: ['GET', 'POST'])]
    public function editPassword(
        User $user,
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $passwordHasher,
    ): Response
    {
        if(!$this->getUser()) 
        {
            return $this->redirectToRoute('app_login');
        } 
        
        if($this->getUser() !== $user) 
        {
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(UserPasswordType::class, $user);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $newPwd = $user->getPassword();
            $newHashed = $passwordHasher->hashPassword(
                $user,
                $newPwd
            );

            $user = $form->getData();
            $user->setPassword($newHashed);

            $this->addFlash(
                'success',
                'Your password has been successfully modified!'
            );

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('app_ingredient');

        }
        return $this->render('pages/user/edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
