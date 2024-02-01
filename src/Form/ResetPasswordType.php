<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le Mot de passe et la  Confirmation doivent etre Identiques !',
                'label' => 'mon nouveau Mot de Passe',
                'required' => true,
                'first_options' => [
                    'label' => 'Mon Nouveau Mot de Passe',
                    'attr' => [
                        'placeholder' => 'Merci de saisir Votre Nouveau Mot de Passe',
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez Votre  Nouveau Mot de Passe',
                    'attr' => [
                        'placeholder' => 'Merci de confirmer votre Nouveau Mot de Passe',
                    ]
                ],

            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Mettre à Jour mon Mot de Passe',
                'attr' => [
                    'class' => 'btn-block btn-info'
                ]

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
