<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\UserPassportInterface;
use \App\Entity\User;

class EasyAdminSubscriber implements EventSubscriberInterface
{

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public static function hashPassword($encoder, $password)
    {
       $user = new User();
       return $hashPassword = $encoder->encodePassword($user, $password);
    }
    public function onBeforeEntityPersistedEvent(BeforeEntityPersistedEvent $event)
    {
        if (!$event->getEntityInstance() instanceof User) {
            return;
        }

        $entity = $event->getEntityInstance();
        $hashPassword = self::hashPassword($this->encoder, $entity->getPassword());
        $entity->setPassword($hashPassword);
    }

    public function onBeforeEntityUpdatedEvent(BeforeEntityUpdatedEvent $event)
    {
        if (!$event->getEntityInstance() instanceof User) {
            return;
        }

        $entity = $event->getEntityInstance();
        $hashPassword = self::hashPassword($this->encoder, $entity->getPassword());
        $entity->setPassword($hashPassword);
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => 'onBeforeEntityPersistedEvent',
            BeforeEntityUpdatedEvent::class => 'onBeforeEntityUpdatedEvent'
        ];
    }
}
