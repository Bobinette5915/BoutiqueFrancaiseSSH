<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Quel nom souhaitez vous donner a votre adresse?',
                'attr' => [
                    'placeholder' => 'nomer votre adresse'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Votre Prenom',
                'attr' => [
                    'placeholder' => 'Entrez votre Prenom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre Nom',
                'attr' => [
                    'placeholder' => 'Entrez votre Nom'
                ]
            ])
            ->add('company', TextType::class, [
                'label' => 'Votre Societe',
                'attr' => [
                    'placeholder' => '(facultatif) Nom de votre Societe'
                ]
            ])
            ->add('adress', TextType::class, [
                'label' => 'Votre Adresse',
                'attr' => [
                    'placeholder' => '8 rue des Lilas...'
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'Votre Code Postale',
                'attr' => [
                    'placeholder' => 'Entrer Votre Code Postale'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Votre Ville',
                'attr' => [
                    'placeholder' => 'Entrez votre Ville'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Votre pays',
                'attr' => [
                    'placeholder' => 'Entrez votre Pays'
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Quel est votre Numero de Telephone?',
                'attr' => [
                    'placeholder' => 'Votre Numero de Telephone'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter une Adresse'
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adress::class,
        ]);
    }
}
