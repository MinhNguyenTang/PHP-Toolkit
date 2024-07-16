<?php

namespace App\Form;

use App\Entity\Mark;
use App\Entity\User;
use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MarkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mark', ChoiceType::class, [
                'choices' => [
                    'Select a rating' => null,
                    'Great' => 5,
                    'Good' => 4,
                    'Okay' => 3,
                    'Bad' => 2,
                    'Terrible' => 1
                ],
                'label' => 'How would you rate this recipe?',
                'label_attr' => [
                    'form-select mt-4',   
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4',
                ],
                'label' => 'Submit',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mark::class,
            'sanitize_html' => true,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'unique_identifier'
        ]);
    }
}
