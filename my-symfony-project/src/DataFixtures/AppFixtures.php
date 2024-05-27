<?php

namespace App\DataFixtures;

use Faker\Factory;
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
        // Ingredients
        $ingredients = [];
        for($i = 1; $i <= 50; $i++)
        {
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word())
                ->setPrice(mt_rand(0, 100));
            $ingredients[] = $ingredient;
            $manager->persist($ingredient);
        }

        // Recipes
        for($j = 0; $j < 25; $j++)
        {
            $recipe = new Recipe();
            $recipe->setNameRecipe($this->faker->word())
                ->setTime(mt_rand(0, 1) == 1 ? mt_rand(0, 1440) : null)
                ->setNumberPeople(mt_rand(0, 1) == 1 ? mt_rand(0, 50) : null)
                ->setDifficulty(mt_rand(0, 1) == 1 ? mt_rand(0, 5) : null)
                ->setDescription($this->faker->word(300))
                ->setPrice(mt_rand(0, 1) == 1 ? mt_rand(0, 1000) : null)
                ->setFavorite(mt_rand(0, 1) == 1 ? true : false);
            
            for($k = 0; $k < mt_rand(5, 15); $k++)
            {
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
            }

            $manager->persist($recipe);
        }

        //Users
        for($k = 0; $k < 18; $k++)
        {
            $user = new User();
            $user->setFullName($this->faker->name())
                ->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->firstName() : null)
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('PasswordTest');
                
            $manager->persist($user);
        }

        $manager->flush();
    }
}