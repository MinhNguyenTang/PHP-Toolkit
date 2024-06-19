<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home', methods: ['GET'])]
    public function index(RecipeRepository $repository): Response
    {
        return $this->render('pages/ingredient/home.html.twig', [
            'recipes' => $repository->findPublicRecipe(6)
        ]);
    }
}
