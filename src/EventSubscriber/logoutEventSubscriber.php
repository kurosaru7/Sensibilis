<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Doctrine\ORM\EntityManagerInterface;


class logoutEventSubscriber implements EventSubscriberInterface
{
    protected $manager;
    function __construct(EntityManagerInterface $em)
    {
        $this->manager = $em;
    }

    public function onBeforeLogoutEvent(LogoutEvent $event)
    {
       
        $request = $event->getRequest();
        $session = $request->getSession();
        dump($session);
        // $test = 1/0;

        if($session->get('showResponse'))
        {
         $currentMake = $session->get('currentMake');
         
         //On récupère le questionnaire courant 
         $progress = (int) $currentMake->getProgress();

         // Si le questionnaire n'avais jamais été fait avant
         if(!$currentMake->getProgress())
         {
             $progress = 1;
         }

         // On monte l'avancment du questionnaire pour pas pouvoir le refaire en boucle

         $currentMake->setProgress($progress + 1);
         dump($currentMake);
         $this->manager->flush();

        }
    }

    public static function getSubscribedEvents()
    {
        return [
            LogoutEvent::class => 'onBeforeLogoutEvent',
        ];
    }
}
