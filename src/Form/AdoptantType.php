<?php

namespace App\Form;

use App\Entity\Adoptant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdoptantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, ['required' => true])
            ->add('password', TextType::class, ['required' => true])
            ->add('username', TextType::class, ['required' => true])
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('phoneNumber', TextType::class, )
            ->add('city', TextType::class)
            ->add('zipCode', TextType::class)


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adoptant::class,
        ]);
    }
}