<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ThemeRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Theme;
use App\Entity\Make;
use App\Entity\DataUser;
use App\Entity\Error;
use App\Repository\MakeRepository;
use App\Repository\OptionRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;

class QuestionnaireController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ThemeRepository $repo, MakeRepository $mRepo): Response
    {
        $themes = $repo->findBy(array('active' => 1));
        $getProgress = false;
        $getFinish = false;

        if($this->getUser())
        {
            foreach($themes as $theme)
            {
                $line = $mRepo->findOneBy(array('user' => $this->getUser()->getId(), 'theme' => $theme->getId()), array('id' => 'DESC'));
                if($line)
                {
                    $getProgress[] = ($line)->getProgress();
                    $getFinish[] = ($line)->getFinish();
                }
                else
                {
                    $getProgress[] = null;
                    $getFinish[] = null;
                }
            }
        }
        return $this->render('questionnaire/index.html.twig', [
            'controller_name' => 'QuestionnaireController',
            'themes' => $themes,
            'getProgress' => $getProgress,
            'getFinish' => $getFinish
        ]);
    }

    /**
     * @Route("/start/{id}", name="start")
     */
    public function start(Theme $theme, MakeRepository $mRepo, QuestionRepository $qRepo, Request $request, OptionRepository $oRepo, EntityManagerInterface $manager): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $session = $request->getSession();

        $speReponse = 'userResponse'.$theme->getId();
        $speShowResp = 'showResponse'.$theme->getId();

        $date = new \Datetime();
        $date->format('d-m-Y');
        $userId = $this->getUser()->getId();
        $currentMake = $mRepo->findOneBy(array('user' => $userId, 'theme' => $theme->getId()), array('id' => 'DESC'));

        // currentMake ne vaut rien si l'on à jamais fait ce questionnaire
        if(!$currentMake)
        {
            $currentQuestion = $qRepo->findOneBy(array('indexQ' => 1, 'theme' => $theme->getId()));
            $currentMake = new make();
            $alreadyStarted = false;
        }

        // Si on à déja fini le questionnaire
       
        elseif($currentMake && $currentMake->getFinish())
        {
            $currentQuestion = $qRepo->findOneBy(array('indexQ' => 1, 'theme' => $theme->getId()));
            $currentMake = new make();
            $alreadyStarted = false;
        }
        else
        {
            $currentQuestion = $qRepo->findOneBy(array('indexQ' => $currentMake->getProgress(), 'theme' => $theme->getId()));
            $alreadyStarted = true;
        }

        $response = $request->request->all();


        // Si une réponse est envoyée
        if($response)
        {
            $options = $oRepo->findBy(array('question' => $currentQuestion->getId(),'trueFalse' => 1));
            $countValidOptions = count($options);

            if(count($response) !== $countValidOptions)
            {
                $test = false;
            }
            else
            {
                foreach($options as $option)
                {
                    if(!in_array($option->getId(), $response))
                    {
                        $test = false;
                    }
                }
            }
            
            if(!$currentMake->getScore())
            {
                $newScore = 0;
                $currentMake->setScore($newScore);
            }

            // $make = new Make();

            //On enregistre la réponse de l'utilisateur pour les statistiques

            $dataUser = new DataUser();
            $dataUser->setQuestion($currentQuestion->getId());
            $dataUser->setUoption($response[array_key_first($response)]);

            // Si elle est juste
            if(!isset($test))
            {
            // Vérif si on à pas déjà répondu à la question
                if(!$this->get('session')->get($speReponse))
                {
                    $newScore = $currentMake->getScore() + 10;
                    $currentMake->setScore($newScore);
                    $dataUser->setChoice(true);
                    $this->get('session')->set($speReponse, True);

                }
            }

            // Si elle est fausse
            else
            {
                $this->get('session')->set($speReponse, False);
                $newScore = $currentMake->getScore();
                $currentMake->setScore($newScore);
                $error = new error();
                $error->setQuestion((int) $currentMake->getProgress());
                $formatedAnswers = implode(',', $response);
                $error->setAnswers($formatedAnswers);
                $dataUser->setChoice(false);
            }


            // On enregistre la réponse en bdd

            $progress = $currentMake->getProgress();
            if(!$currentMake->getProgress())
            {
                $progress = 1;
            }

         
            $currentMake->setProgress($progress);

            if(!$alreadyStarted)
            {
                $currentMake->setDate($date);
                $currentMake->setTheme($theme);
                $currentMake->setUser($this->getUser());
                $manager->persist($currentMake);
            }
            $manager->persist($dataUser);
            $manager->flush();
            $this->get('session')->set($speShowResp, True);

        }

        $speCurrent = 'currentMake'.$theme->getId();
        $this->get('session')->set($speCurrent, $currentMake);

        return $this->render('questionnaire/show.html.twig', [
            'theme' => $theme,
            'currentQuestion' => $currentQuestion,
            'showResponse' => $this->get('session')->get($speShowResp),
            'userResponse' => $this->get('session')->get($speReponse),
        ]);


    }

    /**
     * @Route("/next/{id}", name="next")
     */
    public function next(Theme $theme, MakeRepository $mRepo, EntityManagerInterface $manager)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $date = new \Datetime();
        $date->format('d-m-Y');
        $speReponse = 'userResponse'.$theme->getId();
        $speShowResp = 'showResponse'.$theme->getId();

        $this->get('session')->remove($speReponse);
        $this->get('session')->remove($speShowResp);

        $userId = $this->getUser()->getId();
        $currentMake = $mRepo->findOneBy(array('user' => $userId, 'theme' => $theme->getId()), array('id' => 'DESC'));
        $progress = $currentMake->getProgress();
        if(!$currentMake->getProgress())
        {
            $progress = 1;
        }

        $max = (int) count($theme->getQuestions());
        
        if((int) $currentMake->getProgress() == $max ) 
        {
            $currentMake->setFinish(True);
            $currentMake->setDate($date);
            $manager->flush();
            $this->addFlash('notice','Bravo vous avez fini ce questionnaire et avait obtenu '. $currentMake->getScore(). '% de bonnes réponses.');
            return $this->redirectToRoute('home');
        }
        else
        {
            $currentMake->setProgress($progress + 1);
        }
        $manager->flush();

        return $this->redirectToRoute('start', ['id' => $theme->getId()]);
    }

}
