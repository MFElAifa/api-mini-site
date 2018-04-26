<?php

namespace AppBundle\Bigbrother;

use Symfony\Component\Security\Core\User\UserInterface;

class CensorshipProcessor
{
    protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    // Méthode pour notifier par e-mail un administrateur
    public function notifyEmail($message)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject("Nouveau message d'un utilisateur surveillé")
            ->setFrom('admin@votresite.com')
            ->setTo('medferjani.elifa@gmail.com')
            ->setBody("L'utilisateur surveillé a posté le message suivant : '".$message."'");

        $this->mailer->send($message);
    }

    // Méthode pour censurer un message (supprimer les mots interdits)
    public function censorMessage($message)
    {
        $message = str_replace(array('top secret', 'mot interdit'), '', $message);

        return $message;
    }
}