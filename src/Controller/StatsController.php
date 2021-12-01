<?php

namespace App\Controller;

use App\Entity\Make;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ThemeRepository;
use App\Repository\UserRepository;
use App\Repository\MakeRepository;
class StatsController extends AbstractController
{
    /**
     * @Route("/stats", name="stats")
     */
    public function index(ThemeRepository $tRepo, MakeRepository $mRepo, UserRepository $uRepo): Response
    {  
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $globalResults = self::getAverageResultsFrom($tRepo, $uRepo,$mRepo,'global');
        $prefResults = self::getAverageResultsFrom($tRepo, $uRepo,$mRepo,'PREF');
        $DDPPResults = self::getAverageResultsFrom($tRepo, $uRepo,$mRepo,'DDPP');
        $DDTMResults = self::getAverageResultsFrom($tRepo, $uRepo,$mRepo,'DDTM');

        $userResults = self::getResultsFromUser($this->getUser(), $mRepo, $tRepo);

        // Si l'utilisateur n'a jamais fait de thèmes


        $tab = [];
        $count = 0;
        foreach($userResults as $userResultsPerTheme => $allMakes)
        {
            $tab[$count]['date']  = [];
            $tab[$count]['score'] = [];
            $tab[$count]['theme'] = [];
            foreach($allMakes as $make)
            {
                $tab[$count]['date'][]  = $make->getDate()->format('d-m-Y à H:i');
                $tab[$count]['score'][] = $make->getScore();
                $tab[$count]['theme'][] = $make->getTheme()->getId();
            }
            $count++;
        }

        $allThemes = $tRepo->findAll();

        foreach($allThemes as $theme)
        {
            $themeNames[] = $theme->getName();
        }


        return $this->render('stats/index.html.twig', [
            // Moyenne des utilisateurs
            'globalResults' => $globalResults,

            // Informations de l'utilisateur courant
            'userResults' => $tab,
            'themesNames' => $themeNames
        ], );
    }

    public static function getAverageResultsFrom($themeRepository, $usersrepository, $usersmakesrepository, $where)
    {
      
        // On récupère toutes les réponses des utilisateurs
        $allThemes = $themeRepository->findAll();
        $allMakes = $usersmakesrepository->findAll();  

        // On récupère les makes par thème
        foreach($allThemes as $theme)
        {
            $makeByTheme[] = $usersmakesrepository->findBy(array('theme' => $theme->getId(), 'finish' => 1), array('id' => 'DESC'));
        }

        // On récupère les utilisateurs pour chaque thème

        $doneBySomeone = false;
        $count = 0;
        $test = [];
        foreach($makeByTheme as $all)
        {
            foreach($all as $oneByTheme)
            {
                if($where == 'global')
                {
                    $doneBySomeone = true;
                    if(!in_array($oneByTheme->getUser(), $test))
                    {
                        $score[] = $oneByTheme->getScore();
                    }
                    $test[] = $oneByTheme->getUser();
                }
                else
                {
                    if($oneByTheme->getUser()->getOrigine() == $where)
                    {
                        $doneBySomeone = true;
                        if(!in_array($oneByTheme->getUser(), $test))
                        {
                            $score[] = $oneByTheme->getScore();
                        }
                        $test[] = $oneByTheme->getUser();
                    }
                }
            }
            if($doneBySomeone)
            {
                $average[$oneByTheme->getTheme()->getId()] = array_sum($score)/count($score);
            }
            $test = array();
            $score = array();
            $doneBySomeone = false;
            $count++;
        }

        if(isset($average))
        {
            return $average;
        }
        else{
            return 0;
        }
    }

    public static function getResultsFromUser($user, $usersmakesrepository, $themeRepository)
    {
        // On récupère les résultats de l'utilisateur renseigné
        $allThemes = $themeRepository->findBy(array("active" => 1));

        foreach($allThemes as $theme)
        {
            $userMake[$theme->getName()] = $usersmakesrepository->findBy(array('user' => $user->getId(), 'theme' => $theme->getId(), 'finish' => 1));
        }
        return $userMake;
    }
}

    