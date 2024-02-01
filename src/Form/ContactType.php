<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label'=>'votre Nom',
                'attr' => [
                    'placeholder'=> 'merci de renseigner votre nom'
                ]
            ])
            ->add('prenom', TextType::class, [
                'label'=>'votre Prenom',
                'attr' => [
                    'placeholder'=> 'merci de renseigner votre prenom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label'=>'votre Adresse Email',
                'attr' => [
                    'placeholder'=> 'merci de renseigner votre adresse email'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label'=>'votre Message',
                'attr' => [
                    'placeholder'=> 'Dites nous comment nous pouvons vous aider'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label'=>'Envoyer',
                'attr' => [
                    'class'=> 'btn btn-block btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
