<?php
namespace AppBundle\EventListener;

use AppBundle\Controller\EmailsController;
use AppBundle\Services\EmailService;
use Doctrine\ORM\EntityManager;
use Twig_Environment;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SendWelcomeEmail implements EventSubscriberInterface
{
    private $entityManager;
    private $twig;
    private $emailService;

    public function __construct(EntityManager $entityManager, Twig_Environment $twig, EmailService $emailService)
    {
        $this->entityManager = $entityManager;
        $this->twig = $twig;
        $this->emailService = $emailService;
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        $email = $event->getForm()->getData()->getEmail();
        $username = $event->getForm()->getData()->getUsername();

        $a = new EmailsController($this->entityManager, $this->twig, $this->emailService);
        $a->sendWelcomeEmail($email, $username);
    }

    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess'
        ];
    }
}