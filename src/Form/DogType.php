<?php

namespace App\Form;

use App\Entity\Breed;
use App\Entity\Dog;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('antecedant', TextareaType::class);
        if (!$options['isCreation']) {
            $builder->add('isAdopted', CheckboxType::class);
        }
        $builder
            ->add('acceptAnimmals', CheckboxType::class)
            ->add('isLof', CheckboxType::class)
            ->add('breeds', EntityType::class, [
                'class' => Breed::class,
                'multiple' => true,
                'by_reference' => false,
                'choice_label' => 'name',
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'entry_options' => ['label' => false],
                'label' => 'Images',
                'allow_add' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dog::class,
            'isCreation' => false
        ]);
    }
}