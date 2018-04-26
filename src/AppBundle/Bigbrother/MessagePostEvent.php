<?php

namespace AppBundle\Bigbrother;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;

class MessagePostEvent extends Event
{
    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    // Le listener doit avoir accès au message
    public function getMessage()
    {
        return $this->message;
    }

    // Le listener doit pouvoir modifier le message
    public function setMessage($message)
    {
        return $this->message = $message;
    }

}