<?php

namespace App\Form;

use App\Entity\QuranSession;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class QuranSessionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            ->add('title', TextType::class, [
                'label' => 'Titre / Nom',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => 255]),
                ],
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type de session',
                'choices' => [
                    'Khatm (lecture du Coran)' => 'khatm',
                    'Du‘a collective' => 'dua',
                ],
                'expanded' => false,
                'multiple' => false,
                'constraints' => [new Assert\NotBlank()],
            ])

            ->add('description', TextareaType::class, [
                'label' => 'Description / Intentions',
                'required' => false,
                'attr' => ['rows' => 4],
            ])

            ->add('scheduledAt', DateTimeType::class, [
                'label' => 'Date de début prévue',
                'widget' => 'single_text',
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuranSession::class,
        ]);
    }
}
