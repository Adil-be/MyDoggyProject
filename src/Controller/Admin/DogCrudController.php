<?php

namespace App\Controller\Admin;

use App\Entity\Dog;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class DogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Dog::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            IdField::new('name'),
            TextEditorField::new('description'),
            TextEditorField::new('antecedant'),
            CollectionField::new('breeds')->allowAdd(true)->allowDelete(true)->renderExpanded(),
            CollectionField::new('images')->allowAdd()
                ->allowDelete(),
            AssociationField::new('annonce'),
            BooleanField::new('acceptAnimmals')->onlyOnForms(),
            BooleanField::new('isAdopted')->onlyOnForms(),
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
