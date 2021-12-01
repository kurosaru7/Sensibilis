<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class AdminRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('password', PasswordType::class)
            ->add('confirmPassword', PasswordType::class)
            ->add('origine', ChoiceType::class, [
                'choices' => [
                'Préfecture' => User::PREF,
                'DDTM' => User::DDTM,
                'DDPP' => User::DDPP
            ]])
            ->add('role', ChoiceType::class, [
                'choices' => [
                'Administrateur' => User::ROLE_ADMIN,
                'Editeur' => User::ROLE_EDITOR,
                'Utilisateur' => User::ROLE_USER
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
