<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter your name',
                    'pattern' => '/^(?=.*[a-z])(?=.*[A-Z]).{2, 50}$/',
                    'minlength' => '2',
                    'maxlength' => '50'
                ],
                'label' => 'Name',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    New Assert\Length(['min' => 2, 'max' => 50])
                ]
            ])
            ->add('pseudo', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'e.g. Jack_Sp@Row',
                    'minlength' => '2',
                    'maxlength' => '50'
                ],
                'required' => false,
                'label' => 'Username (optional)',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter your email address (e.g. example@example.com)',
                    'minlength' => '2',
                    'maxlength' => '50'
                ],
                'required' => true,
                'label' => 'Email',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Password',
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&;*_=+\-]).{10, 65}$/'
                    ],
                    'required' => true,
                    'label' => 'Password',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ],
                ],
                'second_options' => [
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Confirm password'
                    ],
                    'required' => true,
                    'label' => 'Confirm Password',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ],
                ],
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'constraints' => [
                    New Assert\NotBlank()
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary col-md-12 mt-4',
                ],
                'label' => 'Submit'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'sanitize_html' => true,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'unique_identifier'
        ]);
    }
}