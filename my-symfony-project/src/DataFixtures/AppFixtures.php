<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Mark;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Recipe;
use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    /**
     * Undocumented function
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        //Users
        $users = [];
        for($k = 0; $k < 18; $k++)
        {
            $user = new User();
            $user->setFullName($this->faker->name())
                ->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->firstName() : null)
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('PasswordTest');

            $users[] = $user;   
            $manager->persist($user);
        }

        // Ingredients
        $ingredients = [];
        for($i = 1; $i <= 50; $i++)
        {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word())
                ->setPrice(mt_rand(0, 100))
                ->setUser($users[mt_rand(0, count($users) - 1)]);

            $ingredients[] = $ingredient;
            $manager->persist($ingredient);
        }

        // Recipes
        $recipes = [];
        for($j = 0; $j < 25; $j++)
        {
            $recipe = new Recipe();
            $recipe->setNameRecipe($this->faker->word())
                ->setTime(mt_rand(0, 1) == 1 ? mt_rand(0, 1440) : null)
                ->setNumberPeople(mt_rand(0, 1) == 1 ? mt_rand(0, 50) : null)
                ->setDifficulty(mt_rand(0, 1) == 1 ? mt_rand(0, 5) : null)
                ->setDescription($this->faker->text(300))
                ->setPrice(mt_rand(0, 1) == 1 ? mt_rand(0, 1000) : null)
                ->setFavorite(mt_rand(0, 1) == 1 ? true : false)
                ->setPublic(mt_rand(0, 1) == 1 ? true : false)
                ->setUser($users[mt_rand(0, count($users) - 1)]);
            
            for($k = 0; $k < mt_rand(5, 15); $k++)
            {
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
            }

            $recipes[] = $recipe;
            $manager->persist($recipe);
        }

        //Mark
        foreach($recipes as $recipe)
        {
            for($l = 0; $l < mt_rand(0, 5); $l++)
            {
                $mark = new Mark();
                $mark->setMark(mt_rand(1, 5))
                    ->setUser($users[mt_rand(0, count($users) - 1)])
                    ->setRecipe($recipe);

                $manager->persist($mark);
            }
        }
        
        $manager->flush();
    }
}
