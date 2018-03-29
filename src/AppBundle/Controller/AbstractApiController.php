<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JSend\JSendResponse;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use JMS\Serializer\SerializationContext;


abstract class AbstractApiController extends Controller
{

    const DEFAULT_FORMAT = "json";
    
    /**
     * @param Request $request
     * @param $data
     * @return Serializer
     */
    protected function buildResponse(Request $request, $data)
    {
        $serializer = $this->get('jms_serializer');
    
        $format = $request->get('_format');
        if (empty($format)) {
            $format = static::DEFAULT_FORMAT;
        }
        //only xml and json format are available
        if ($format !== "json" && $format !== "xml") {
            throw new BadRequestHttpException("only xml and json format are available");
        }
        
        $data = $serializer->serialize($data, $format, SerializationContext::create()->setGroups(array('list')));
        
        $response = new Response($data);
        
        if($format !== "json" && $format === "xml"){
            $response->headers->set('Content-Type', 'application/xml');    
        }elseif($format === "json"){
            $response->headers->set('Content-Type', 'application/xml');    
        }
        
        return $response;
    }

}
