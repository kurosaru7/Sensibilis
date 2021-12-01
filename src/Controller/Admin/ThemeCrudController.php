<?php

namespace App\Controller\Admin;

use App\Entity\Theme;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class ThemeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Theme::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            hiddenField::new('id'),
            TextField::new('name', 'Nom'),
            TextEditorField::new('description', 'Description')->hideOnIndex(),
            AssociationField::new('questions')->hideOnForm(),
            BooleanField::new('active'),
            // TextEditorField::new('description'),
        ];
    }
    

    public function configureCrud(Crud $crud): Crud
    {
    return $crud
    // ->setEntityLabelInSingular('Product')
    ->setPageTitle('index', 'Gestion des thèmes')
    // ->showEntityActionsAsDropdown()
    ;
    }
}
