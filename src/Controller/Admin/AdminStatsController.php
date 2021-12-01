<?php

namespace App\Controller\Admin;

use App\Entity\Make;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ThemeRepository;
use App\Repository\UserRepository;
use App\Repository\MakeRepository;
use App\Repository\QuestionRepository;
use App\Repository\DataUserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Controller\StatsController;
use App\Entity\DataUser;

class AdminStatsController extends AbstractController
{

    /**
    * @Route("/admin/stats", name="adminStats")
    */
    public function index(MakeRepository $mRepo, ThemeRepository $tRepo, UserRepository $uRepo, QuestionRepository $qRepo, DataUserRepository $dURepo)
    {
        $dataUser = $dURepo->findAll();
        $themes = $tRepo->findAll();
        $users = $uRepo->findAll();
        $questions = $qRepo->findAll();

        $origines = ['PREF', 'DDTM', 'DDPP'];





         // On récupère les moyennes par cas

        $globalResults = StatsController::getAverageResultsFrom($tRepo, $uRepo,$mRepo,'global');
        $prefResults = StatsController::getAverageResultsFrom($tRepo, $uRepo,$mRepo,'PREF');
        $DDPPResults = StatsController::getAverageResultsFrom($tRepo, $uRepo,$mRepo,'DDPP');
        $DDTMResults = StatsController::getAverageResultsFrom($tRepo, $uRepo,$mRepo,'DDTM');

        $data1 = new \DateTime("now");
        $data2 = new \DateTime("now");
        $data3 = new \DateTime("now");

        $today = $data1->setTime(0,0);
        $lastweek = $data2->modify(' - 7 days');
        $last30Days = $data3->modify(' - 30 days');


        // Pour chaque réponses on calcule le taux de réussite

        $test = [];
        foreach($dataUser as $line)
        {
        
         if(!in_array($line->getQuestion(), $test))
         {
            $tab[] = $line->getQuestion();
         }
         $test[] = $line->getQuestion();
        }

        foreach($tab as $oneQuestion)
        {
            foreach($dataUser as $line)
            {
                if($line->getQuestion() == $oneQuestion)
                {
                    if($line->getChoice())
                    {
                        $value = 100;
                    }
                    else
                    {
                        $value = 0;
                    }
                    $dataResults[$line->getQuestion()][] = $value;
                }
            }
        }

        foreach($dataResults as $key=>$value)
        {
            $results[$key] = round(array_sum($value)/count($value));
        }

        foreach($themes as $theme)
        {
            // On calcule les moyennes pour chaque question

            // $successQuestion[$theme->getId()] = $dURepo->findBy(array('question' => ))

            $nbend[$theme->getId()] = $mRepo->findBy(array('finish' => 1, 'theme' => $theme->getId()), array('date' => 'ASC'));
            $nbstart[$theme->getId()] = $mRepo->findBy(array('theme' => $theme->getId()), array('date' => 'ASC'));

            $allMakes[$theme->getId()] = $mRepo->findBy(array('theme' => $theme->getId()), array('date' => 'ASC'));
            // Tri par temporalité
            
     

            foreach($allMakes[$theme->getId()] as $make)
            {
                if($make->getDate() > $today)
                {
                    $startToday[$theme->getId()][] = $make->getDate();
                }

                if($make->getDate() > $lastweek)
                {
                    $startThisWeek[$theme->getId()][] = $make->getDate();
                }

                if($make->getDate() > $last30Days)
                {
                    $start30DaysAgo[$theme->getId()][] = $make->getDate();
                }

            }

        }   

        // Permet d'éliminer les doublons pour le même utilisateur car compliqué depuis le findBy

        $test = [];
        foreach($nbend as &$tab)
        {
            foreach($tab as $key=>$entity)
            {
                if(in_array($entity->getUser(), $test))
                {
                    unset($tab[$key]);
                }else{
                    $test[] = $entity->getUser();
                }
            }
            $test = [];
        }

        $test = [];

        foreach($nbstart as &$tab)
        {
            foreach($tab as $key=>$entity)
            {
                if(in_array($entity->getUser(), $test))
                {
                    unset($tab[$key]);
                }else{
                    $test[] = $entity->getUser();
                }
            }
            $test = [];
        }

            if(!isset($startToday)){
                $startToday[] = 0;
            }

            if(!isset($startToday)){
                $startToday[] = 0;
            }

            if(!isset($startThisWeek)){
                $startThisWeek[] = 0;
            }

            if(!isset($start30DaysAgo)){
                $start30DaysAgo[] = 0;
            }

        return $this->render('admin/adminStats.html.twig', [
            'themes' => $themes,
            'questions' => $questions,
            'stats' => $tab,
            'nbend' => $nbend,
            'nbstart' => $nbstart,
            'startToday' => $startToday,
            'startThisWeek' => $startThisWeek,
            'start30DaysAgo' => $start30DaysAgo,
            'globalResults' => $globalResults,
            'prefResults' => $prefResults,
            'DDPPResults' => $DDPPResults,
            'DDTMResults' => $DDTMResults,
            'results' => $results
        ]);
    }
}

