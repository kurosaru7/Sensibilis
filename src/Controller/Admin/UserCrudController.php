<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use App\EventSubscriber\EasyAdminSubscriber;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            hiddenField::new('id'),
            TextField::new('username', 'Nom d\'utilisateur'),
            ChoiceField::new('origine')->setChoices([
                'PREF' => 'PREF',
                'DDTM' => 'DDTM',
                'DDPP' => 'DDPP'
                ]),
                ChoiceField::new('role')->setChoices([
                    'Administrateur' => 'ROLE_ADMIN',
                    'Utilisateur' => 'ROLE_USER',
                    'Editeur' => 'ROLE_EDITOR'
                    ]),
            TextField::new('password', 'Mot de passe (hashé)'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
    return $crud
    // ->setEntityLabelInSingular('Product')
    ->setPageTitle('index', 'Gestion des utilisateurs')
    ;
    }

    public function hashPassword(UserPasswordEncoderInterface $passwordEncoder): void
    {
        $this->password = $passwordEncoder->encodePassword($this, $this->password);
    }

}
