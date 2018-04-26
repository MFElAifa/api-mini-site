<?php

namespace AppBundle\Listener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;


class CategoryListener
{
    protected $endDate;

    public function __construct($endDate)
    {
        $this->endDate  = new \Datetime($endDate);
    }

    public function processCatg(FilterResponseEvent $event)
    {
        $remainingDays = $this->endDate->diff(new \Datetime())->format('%d');
        $now = new \Datetime();
        dump($now->format('d/m/Y')); exit;
        $response = $event->getResponse();
        //dump($response); //exit;
        ///$event->setResponse(new Response("Hello"));
        //dump($event->getResponse()); exit;

    }
}