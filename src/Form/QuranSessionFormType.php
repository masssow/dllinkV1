<?php

namespace App\Form;

use App\Entity\QuranSession;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Form\Type\VichImageType;

class QuranSessionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // =========================
            // Champs communs
            // =========================
            ->add('title', TextType::class, [
                'label' => 'Titre / Nom de la session',
                'constraints' => [
                    new Assert\NotBlank(message: 'Veuillez renseigner un titre.'),
                    new Assert\Length(max: 255),
                ],
                'attr' => [
                    'placeholder' => 'Ex. Khatm du vendredi / Salatou ‘ala Nabi collective',
                    'class' => 'dl-input',
                ],
            ])

            ->add('type', ChoiceType::class, [
                'label' => 'Type de session',
                'choices' => [
                    'Khatm (lecture du Coran)' => 'khatm',
                    'Du‘a collective / Zikr / Khassaides' => 'dua',
                ],
                'placeholder' => 'Choisir un type',
                'expanded' => false,
                'multiple' => false,
                'constraints' => [
                    new Assert\NotBlank(message: 'Veuillez choisir un type de session.'),
                ],
                'attr' => [
                    'class' => 'dl-input js-session-type',
                ],
            ])

            ->add('description', TextareaType::class, [
                'label' => 'Description / Intentions',
                'required' => false,
                'constraints' => [
                    new Assert\Length(max: 2000),
                ],
                'attr' => [
                    'rows' => 4,
                    'placeholder' => 'Ex. Session organisée pour la communauté, avec intention de prières, zikr et bénédictions...',
                    'class' => 'dl-input',
                ],
            ])

            ->add('imageFile', VichImageType::class, [
                'label' => 'Image de la session (facultatif)',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'image_uri' => false,
                'help' => 'Ajoutez une image pour mieux présenter votre session dans le partage.',
                'attr' => [
                    'class' => 'dl-input',
                ],
            ])

            ->add('scheduledAt', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'constraints' => [
                    new Assert\NotBlank(message: 'Veuillez renseigner une date de début.')

                ],
                
                'attr' => [
                    'class' => 'dl-input',
                ],
            ])

            // =========================
            // Champs spécifiques DUA
            // =========================
            ->add('duaLabel', TextType::class, [
                'label' => 'Zikr / Du‘a / Khassida principale',
                'required' => false,
                'constraints' => [
                    new Assert\Length(max: 255),
                ],
                'attr' => [
                    'placeholder' => 'Ex. Astaghfirullah / Salatou ‘ala Nabi / Khassida X',
                    'class' => 'dl-input js-dua-field',
                ],
                'row_attr' => [
                    'class' => 'js-dua-row',
                ],
            ])

            ->add('totalTarget', IntegerType::class, [
                'label' => 'Objectif total de répétitions',
                'required' => false,
                'constraints' => [
                    new Assert\Positive(message: 'L’objectif doit être un nombre positif.'),
                ],
                'attr' => [
                    'placeholder' => 'Ex. 10000',
                    'min' => 1,
                    'class' => 'dl-input js-dua-field',
                ],
                'row_attr' => [
                    'class' => 'js-dua-row',
                ],
            ])

            ->add('expiresAt', DateType::class, [
                'label' => 'Date de clôture souhaitée',
                'required' => false,
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'attr' => [
                    'class' => 'dl-input js-dua-field',
                ],
                'row_attr' => [
                    'class' => 'js-dua-row',
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
