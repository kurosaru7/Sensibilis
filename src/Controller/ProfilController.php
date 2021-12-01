<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\EditMeType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ThemeRepository;
use App\Repository\MakeRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     */
    public function index(EntityManagerInterface $manager, 
                          ThemeRepository $Trepo, 
                          MakeRepository $Mrepo, 
                          UserPasswordEncoderInterface $encoder, 
                          Request $request
                          ): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $user = $this->getUser();
        $form = $this->createForm(EditMeType::class, $user);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {   
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setUsername($this->getUser()->getUsername());
            $user->setRole(User::ROLE_USER);
            $manager->flush();

            return $this->redirectToRoute('profil');
        }

        $themes = $Trepo->findBy(array('active' => 1));
        $idCurrentUser = $user->getId();
        $make = $Mrepo->findBy(array('user' => $idCurrentUser), array('id' => 'DESC'));

        $nbThemes = count($themes);
        $diplome = false;

        $countPerfectScore = 0;
       foreach($make as $one)
       {
           if($one->getScore() == 100)
           {
               $countPerfectScore++;
           }
       }
       if($nbThemes == $countPerfectScore)
       {
           $diplome = true;
       }

        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
            'themes' => $themes,
            'makes' => $make, 
            'diplome' => $diplome,
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}
