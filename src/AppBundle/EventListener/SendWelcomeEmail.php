<?php
namespace AppBundle\EventListener;

use AppBundle\Controller\EmailsController;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SendWelcomeEmail implements EventSubscriberInterface
{
    public function __construct()
    {
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        $email = $event->getForm()->getData()->getEmail();
        $username = $event->getForm()->getData()->getUsername();
        $a = new EmailsController();
        $a->sendWelcomeEmail($email, $username);
    }

    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess'
        ];
    }
}