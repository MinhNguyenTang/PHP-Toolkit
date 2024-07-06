<?php

namespace App\Controller;

use App\Entity\Mark;
use App\Entity\Recipe;
use App\Form\MarkType;
use App\Form\RecipeType;
use Psr\Log\LoggerInterface;
use App\Repository\MarkRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeController extends AbstractController
{
    /**
     * Controller that displays all recipes
     *
     * @param RecipeRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/recipe', name: 'app_recipe', methods: ['GET'])]
    public function index(
        RecipeRepository $repository, 
        PaginatorInterface $paginator, 
        Request $request
    ): Response
    {
        $recipes = $paginator->paginate(
            $repository->findBy(['user' => $this->getUser()]), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('pages/recipe/recipe.html.twig', [
            'recipes' => $recipes
        ]);
    }

    /**
     * Undocumented function
     *
     * @param RecipeRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/recipe/public_recipe', name: 'app_public_recipe', methods: ['GET'])]
    public function indexPublic(
        RecipeRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        $recipes = $paginator->paginate(
            $repository->findPublicRecipe(null),
            $request->query->getInt('page', 1),
            10
        );
        
        return $this->render('pages/recipe/public.html.twig', [
            'recipes' => $recipes
        ]);
    }

    /**
     * Controller that shows all public 
     *
     * @param Recipe $recipe
     * @return Response
     */
    #[Route('/recipe/{id}', name: 'app_show_recipe', methods: ['GET'])]
    public function showRecipe(
        Recipe $recipe, 
        Request $request,
        MarkRepository $repository,
        EntityManagerInterface $manager
    ): Response
    {
        $mark = new Mark();
        $form = $this->createForm(MarkType::class, $mark);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $mark->setUser($this->getUser())
                ->setRecipe($recipe);
            
            $existingMark = $repository->findByOne([
                'user' => $this->getUser(),
                'recipe' => $recipe
            ]);

            if(!$existingMark) {
                $manager->persist($mark);
                $manager->flush();
            }
        }

        $minutes = $recipe->getTime();
        $hours = intdiv($minutes, 60);
        $exptTime = $minutes % 60;

        return $this->render('pages/recipe/show.html.twig', [
            'recipe' => $recipe,
            'hours' => $hours,
            'minutes' => $exptTime,
            'form' => $form->createView()
        ]);
    } 

        /**
     * Controller that creates all recipes
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/recipe/new', name: 'app_recipe_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager 
    ): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $recipe = $form->getData();
            $recipe->setUser($this->getUser());

            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash(
                'success',
                'Your recipe has been successfully added!'
            );

            return $this->redirectToRoute('app_recipe');
        }

        return $this->render('pages/recipe/new.html.twig', [
            'form' => $form->CreateView()
        ]);
    }

    /**
     * Controller that edits a recipe
     *
     * @param Recipe $recipe
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/recipe/edition/{id}', name: 'app_recipe_edit', methods: ['GET', 'POST'])]
    public function edit(
        Recipe $recipe,
        Request $request,
        EntityManagerInterface $manager
    ): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $updated = new \DateTimeImmutable();
            $recipe = $form->getData();
            $recipe->setUpdatedAt($updated);

            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash(
                'success',
                'Your recipe has been successfully edited!'
            );

            return $this->redirectToRoute('app_recipe');
        }

        return $this->render('pages/recipe/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Controller that deletes a recipe from list
     *
     * @param EntityManagerInterface $manager
     * @param Recipe $recipe
     * @param Request $request
     * @return Response
     */
    #[Route('/recipe/deletion/{id}', name: 'app_recipe_delete', methods: ['GET', 'POST'])]
    public function delete(
        EntityManagerInterface $manager,
        Recipe $recipe,
        Request $request
    ): Response
    {
        $manager->remove($recipe);
        $manager->flush();

        $this->addFlash(
            'success',
            'Your recipe has been successfully deleted!'
        );

        return $this->redirectToRoute('app_recipe');
    }
}