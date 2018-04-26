<?php

namespace AppBundle\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\Response;

class CategorySubscriber implements EventSubscriberInterface
{
    // La méthode de l'interface que l'on doit implémenter, à définir en static
    static public function getSubscribedEvents()
    {
        // On retourne un tableau « nom de l'évènement » => « méthode à exécuter »
        return array(
            'kernel.response' => 'processCat'
        );
    }

    public function processCat(FilterResponseEvent $event)
    {
        //dump($event->getResponse());exit;
        $event->setResponse(new Response("ABC"));
    }

    public function autreMethode()
    {
        // ...
    }
}