<?php
namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Credentials
{
   /**
    * @Assert\NotBlank(message="Login Required!")
    */
    protected $login;

   /**
    * @Assert\NotBlank(message="Password Required!")
    */
    protected $password;

    public function getLogin()
    {
        return $this->login;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
}