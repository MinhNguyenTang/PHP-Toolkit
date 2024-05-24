<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
}
