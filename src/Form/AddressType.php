<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('street',TextType::class,[
                'label' => 'Rue et numéro'
            ])
            ->add('postalCode',TextType::class,[
                'label' => 'Code postal'
            ])
            ->add('city',TextType::class,[
                'label' => 'Ville'
            ])
            ->add('country',TextType::class,[
                'label' => 'Pays'
            ])
            
            ->add('commentary',TextareaType::class,[
                'label' => 'Commentaire supplémentaire (non requis)'
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
