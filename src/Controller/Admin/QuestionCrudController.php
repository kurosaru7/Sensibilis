<?php

namespace App\Controller\Admin;

use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use \App\Form\AdminQuestionType;

use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationRequestHandler;
use Symfony\Component\HttpFoundation\Request;

class QuestionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Question::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            HiddenField::new('id'),
            TextField::new('titre'),
            AssociationField::new('theme'),
            AssociationField::new('options')->hideOnForm(),
            TextEditorField::new('answer', 'Réponse')->hideOnIndex(),
            IntegerField::new('indexQ', 'Numéro de question')->hideOnIndex()
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $form = Action::new('form', 'Modifier les options')->linkToCrudAction('updateOptions');
    
    return $actions
        // ...
        ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ->add(Crud::PAGE_EDIT, $form)

    ;
    }

    public function updateOptions(Request $request)
    {
        $question = (int) $request->get('entityId');

        return $this->redirectToRoute('modifOptions',['question' => $question]);
    }
}
