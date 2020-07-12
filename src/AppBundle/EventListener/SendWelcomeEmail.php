<?php
namespace AppBundle\EventListener;

use AppBundle\Controller\EmailsController;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SendWelcomeEmail implements EventSubscriberInterface
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        $email = $event->getForm()->getData()->getEmail();
        $username = $event->getForm()->getData()->getUsername();

        $a = new EmailsController($this->entityManager);
        $a->sendWelcomeEmail($email, $username);
    }

    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess'
        ];
    }
}