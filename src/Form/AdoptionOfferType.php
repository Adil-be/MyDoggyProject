<?php

namespace App\Form;

use App\Entity\AdoptionOffer;
use App\Entity\Dog;
use App\Repository\DogRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdoptionOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $idAnnonce = $options['idAnnonce'];
        $builder
            // ->add('CreatedAt', TextType::class)
            // ->add('isAccepted')
            ->add('dogs', EntityType::class, [
                // looks for choices from this entity
                'class' => Dog::class,
                'multiple' => true,
                'by_reference' => false,
                'expanded' => true,
                'choice_label' => 'name',
                'query_builder' => function (DogRepository $dr) use ($idAnnonce) {
                    return $dr->createQueryBuilder('d')
                        ->innerJoin('d.annonce', 'a')
                        ->andWhere('a.id = :id')
                        ->andWhere('d.isAdopted = FALSE')
                        ->setParameter('id', $idAnnonce)
                        ->orderBy('d.name', 'ASC');
                },
            ])
            ->add('adoptant', AdoptantType::class, ['withPassword' => false]);
        /** @var array<string, bool> $options */
        if ($options['isCreation']) {
            $builder->add('messages', CollectionType::class, [
                'entry_type' => MessageType::class,
                'entry_options' => ['label' => false],
                'label' => 'Message',
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AdoptionOffer::class,
            'idAnnonce' => null,
            'isCreation' => false,
        ]);
        $resolver->setAllowedTypes('idAnnonce', 'int');
        $resolver->setAllowedTypes('isCreation', 'bool');
    }
}