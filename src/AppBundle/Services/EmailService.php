<?php

namespace AppBundle\Services;

class EmailService
{
    private $swm;

    public function __construct($swm)
    {
        $this->swm = $swm;
    }

    public function send($from, $name, $to, $subject, $body)
    {
        $msg = $this->swm->createMessage()
            ->setSubject($subject)
            ->setFrom($from, $name)
            ->setTo($to)
            ->setBody($body, 'text/html')
        ;
        $this->swm->send($msg);
    }
}
