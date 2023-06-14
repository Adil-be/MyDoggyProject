<?php

namespace App\Controller\Admin;

use App\Entity\AdoptionOffer;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AdoptionOfferCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AdoptionOffer::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
