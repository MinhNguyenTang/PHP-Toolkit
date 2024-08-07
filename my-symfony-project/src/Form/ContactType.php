<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter your name',
                ],
                'required' => true,
                'label' => 'Name',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter your email address (e.g. example@example.com)',
                ],
                'required' => true,
                'label' => 'Email',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
            ])
            ->add('subject', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => true,
                'label' => 'Subject',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('message', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter your message',
                    'rows' => '5',
                ],
                'required' => true,
                'label' => 'Message',
                'label_attr' => [
                    'class' => 'form-label mt-4',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4',
                ],
                'label' => 'Submit',
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'contact',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            'sanitize_html' => true,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'unique_identifier'
        ]);
    }
}
