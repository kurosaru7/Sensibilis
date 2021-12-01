<?php

namespace App\Controller\Admin;

use App\Entity\Bug;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;




class BugCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Bug::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            TextField::new('content'),
            DateTimeField::new('date'),
            BooleanField::new('status'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
    
    return $actions
        // ...
        ->add(Crud::PAGE_INDEX, Action::DETAIL)
    ;
    }
}
