<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Bigbrother\MessagePostEvent;
use AppBundle\Bigbrother\BigbrotherEvents;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
    	//return $this->redirectToRoute('nelmio_api_doc_index');
        //return $this->redirectToRoute('app.swagger_ui');
        return $this->render('default\index.html.twig', [
            'base_dir' => 'XXXX'
        ]);
    }

    /**
     * @Route("/categories", name="get_all_categories")
     */
    public function getCategoriesAction()
    {
        $productService = $this->get('product_service');
        $categories = $productService->getCategoryRepository()->findAll();
        //dump($categories); exit;
        return $this->render(
            'AppBundle::categories.html.twig',[
                'categories' => $categories
            ]
        );
    }

    /**
     * @Route("/message/{msg}", name="set_messages")
     */
    public function setMessageAction(string $msg)
    {
        $event = new MessagePostEvent($msg);
        // On déclenche l'évènement
        $this->get('event_dispatcher')->dispatch(BigbrotherEvents::onMessagePost, $event);

        return new Response("Message : ".$msg);
    }
}
