<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;

abstract class AbstractEntityManagerService
{
    /**@var EntityManager $em **/
    protected $em;

    protected function persistAndFlush($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    protected function removeAndFlush($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    /**
     * @param string $success
     * @param string $code
     * @param string $message
     * @param $data
     * @return array
     */
    protected function buildData(string $success, string $code, string $message = null, $data = null){
        return array(
            'success' => $success,
            'code' => $code,
            'message' => $message,
            'data' => $data,
        );
    }
}