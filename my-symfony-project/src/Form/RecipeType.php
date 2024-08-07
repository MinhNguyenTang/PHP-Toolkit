<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Ingredient;
use Doctrine\ORM\QueryBuilder;
use App\Repository\IngredientRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RecipeType extends AbstractType
{
    private $token;

    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nameRecipe', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50'
                ],
                'label' => 'Name of recipe',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    New Assert\NotBlank(),
                    New Assert\Length(['min' => 2, 'max' => 50])
                ]

            ])
            ->add('time', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 1440
                ],
                'required' => false,
                'label' => 'Preparation Time (in minutes)',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    New Assert\Positive(),
                    New Assert\LessThan(1441)
                ]
            ])
            ->add('numberPeople', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
                'label' => 'Number of people',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    New Assert\Positive(),
                    New Assert\LessThan(51)
                ]
            ])
            ->add('price', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
                'label' => 'Price',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    New Assert\Positive(),
                    New Assert\LessThan(1001)
                ]
            ])
            ->add('difficulty', ChoiceType::class, [
                'choices' => [
                    'Select a difficulty' => null,
                    'Easier than easy' => 1,
                    'Easy' => 2,
                    'Medium' => 3,
                    'Difficult' => 4,
                    'Harder than hard' => 5
                ],
                'required' => false,
                'label' => 'Recipe difficulty',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    New Assert\Positive(),
                    New Assert\LessThan(6)
                ]
            ])
            ->add('isFavorite', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'required' => false,
                'value' => false,
                'label' => 'Is Favorite?',
                'constraints' => [
                    New Assert\NotNull([
                        'message' => 'Please indicate if this is a favorite'
                    ])
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Choose an image of recipe',
                'required' => false,
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('ingredients', EntityType::class, [
                'class' => Ingredient::class,
                'query_builder' => function (IngredientRepository $r): QueryBuilder {
                    return $r->createQueryBuilder('i')
                        ->where('i.user = :user')
                        ->orderBy('i.name', 'ASC')
                        ->setParameter('user', $this->token->getToken()->getUser());
                },
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => '4'
                ],
                'label' => 'Recipe description',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    New Assert\NotBlank()
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Submit'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
            'sanitize_html' => true,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'unique_identifier'
        ]);
    }
}