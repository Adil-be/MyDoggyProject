<?php

namespace App\Controller\Admin;

use App\Entity\Annonceur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AnnonceurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Annonceur::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            EmailField::new('email'),
            TextField::new('username'),
            TextField::new('name'),
            CollectionField::new('annonces'),
            TextField::new('password')->onlyOnForms(),
            TextField::new('phoneNumber')->onlyOnForms(),
            TextField::new('city')->onlyOnForms(),
            TextField::new('zipCode')->onlyOnForms(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // ...
            ->showEntityActionsInlined()
        ;
    }
}
