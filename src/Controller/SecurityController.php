<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\Bug;
use App\Form\RegistrationType;
use App\Form\SignalBugType;



class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {   
        $this->denyAccessUnlessGranted('IS_ANONYMOUS');
            $user = new User();
            $form = $this->createForm(RegistrationType::class, $user);

            $form->handleRequest($request);
            $errors = $form->getErrors(true);

            if($form->isSubmitted() && $form->isValid())
            {   
                $hash = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($hash);
                $user->setRole(User::ROLE_USER);

                $manager->persist($user);
                $manager->flush();


                $this->addFlash('notice','Votre compte a bien été créer, connectez-vous !');

                return $this->redirectToRoute('security_login');
            }

            return $this->render('security/registration.html.twig',[
                'form' => $form->createView(),
                'errors' => $errors
            ]);
    }

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login()
    {
        $this->denyAccessUnlessGranted('IS_ANONYMOUS');
            return $this->render('security/login.html.twig');
    }

    /**
     *  @Route("/signaler", name="signal")
     */
    public function signal(Request $request, EntityManagerInterface $manager)
    {
        $bug = new Bug();
        $form = $this->createForm(SignalBugType::class, $bug);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {  
            $date= new \DateTime();
            $bug->setStatus(0);
            $bug->setDate($date);
            $manager->persist($bug);
            $manager->flush();

            $this->addFlash('notice','Votre signalement à bien été pris en compte, merci de nous aider à améliorer la plateforme !');

            return $this->redirectToRoute('home');
        }
        return $this->render('security/signal.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout() {}
}
