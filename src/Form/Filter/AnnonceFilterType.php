<?php

namespace App\Form\Filter;

use App\Entity\Annonceur;
use App\Entity\Breed;
use App\Filter\AnnonceFilter;
use App\Repository\AnnonceurRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('annonceurs', EntityType::class, [
                'class' => Annonceur::class,
                'multiple' => true,
                'query_builder' => function (AnnonceurRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('a')
                        ->join('a.annonces', 'annonce')
                        ->andWhere('annonce.isAvailable = true')
                        ->orderBy('a.name', 'ASC');
                },
                'by_reference' => false,
                'choice_label' => 'name',
                'label' => 'Annonceurs',
                'help' => 'you can choose multiple',
                'required' => false,
                'placeholder' => 'Please choose a contact person',
            ])
            ->add('breeds', EntityType::class, [
                'required' => false,
                'multiple' => true,
                'help' => 'you can choose multiple',
                'class' => Breed::class,
                'by_reference' => false,
                'label' => 'breeds',
            ])
            ->add('isLof', CheckboxType::class, [
                'required' => false,
            ])
            ->add('search', TextType::class, [
                'required' => false,
                'help' => "search for a word in the annonce's title",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AnnonceFilter::class,
        ]);
    }
}
