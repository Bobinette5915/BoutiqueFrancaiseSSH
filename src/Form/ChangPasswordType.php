<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,[
                'disabled'=> true,
                'label'=>'mon adresse email'
            ])
            
            ->add('firstname', TextType::class,[
                'disabled'=> true,
                'label'=>'mon prenom'
            ])
            ->add('lastname', TextType::class,[
                'disabled'=> true,
                'label'=>'mon nom'
            ])
            ->add('old_password', PasswordType::class, [
                'label' =>'mon mot de passe actuel',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre mot de passe actuel'
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'type'=> PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Le Mot de passe et la  Confirmation doivent etre Identiques !',
                'label'=> 'mon nouveau Mot de Passe',
                'required' => true,
                'first_options'=> [
                    'label'=> 'mon Nouveau Mot de Passe',
                    'attr'=> [
                        'placeholder'=> 'Merci de saisir Votre Nouveau Mot de Passe',
                    ]
                ],
                'second_options'=> [
                    'label'=> 'Confirmez Votre  Nouveau Mot de Passe',
                    'attr'=>[
                        'placeholder'=> 'Merci de confirmer votre Nouveau Mot de Passe',
                    ]
                ],
                
            ])
            ->add( 'submit', SubmitType::class,[
                'label' => 'Mettre A Jour',
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
