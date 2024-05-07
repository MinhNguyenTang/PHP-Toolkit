<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IngredientController extends AbstractController
{
    /**
     * Controller that displays all ingredients
     * 
     * @param IngredientRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'app_ingredient', methods: ['GET'])]
    public function index(
        IngredientRepository $repository, 
        PaginatorInterface $paginator, 
        Request $request
    ) : Response
    {
        $ingredients = $paginator->paginate(
            $repository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('ingredient/ingredient.html.twig', [
            'ingredients' => $ingredients
        ]);
    }

    /**
     * Controller that creates a form to add an ingredient and handles any request
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ingredient/new', name: 'app_ingredient_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $manager
    ) : Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $ingredient= $form->getData();

            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Your ingredient has been successfully added!'
            );

            return $this->redirectToRoute('app_ingredient');
        }

        return $this->render('ingredient/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/ingredient/edition/{id}', name: 'app_ingredient_edit', methods: ['GET', 'POST'])]
    public function edit(
        Ingredient $ingredient, 
        Request $request, 
        EntityManagerInterface $manager
        ) : Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient= $form->getData();

            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Your ingredient has been successfully edited!'
            );

            return $this->redirectToRoute('app_ingredient');
        }

        return $this->render('ingredient/edit.html.twig', [
            'form' => $form->createView()
        ]);

    }

    #[Route('/ingredient/deletion/{id}', name: 'app_ingredient_delete', methods: ['GET', 'POST'])]
    public function delete(
        EntityManagerInterface $manager, 
        Ingredient $ingredient
        ) : Response
    {
        if(!$ingredient) {
            $this->addFlash(
                'warning',
                'Ingredient not found!'
            );
            return $this->redirectToRoute('app_ingredient');
        } else {
            $manager->remove($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Your ingredient has been successfully deleted!'
            );

            return $this->redirectToRoute('app_ingredient');
        }
    }
}