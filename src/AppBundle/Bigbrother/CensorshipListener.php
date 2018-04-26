<?php

namespace AppBundle\Bigbrother;

class CensorshipListener
{
    protected $processor;
    protected $listUsers = array();

    public function __construct(CensorshipProcessor $processor, $listUsers)
    {
        $this->processor = $processor;
        $this->listUsers = $listUsers;
    }

    public function processMessage(MessagePostEvent $event)
    {
        // On envoie un e-mail à l'administrateur
        $this->processor->notifyEmail($event->getMessage());

        // On censure le message
        $message = $this->processor->censorMessage($event->getMessage());
        // On enregistre le message censuré dans l'event
        $event->setMessage($message);
    }
}