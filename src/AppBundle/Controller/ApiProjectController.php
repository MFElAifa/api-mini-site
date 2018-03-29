<?php

namespace AppBundle\Controller;

use AppBundle\Services\ProductService; 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/api")
 */
class ApiProjectController extends AbstractApiController
{
    /**
     * Create a new Category
     * @ApiDoc(
     *  resource="/api/categories",
     *  description="Add Categorys",
     *  section="Categories",
     *  parameters={
     *      {"name"="name", "description"="Name of Category", "required"=true, "dataType"="string"}
     *  },
     *  statusCodes={
     *      200="Successful",
     *      403="Validation errors"
     *  }
     * )
     * 
     * POST Route annotation.
     * @POST("/categories.{_format}", defaults={"_format": "json"})
     */
    public function postCategoryAction(Request $request)
    {
        $productService = $this->get('product_service');
        $result = $productService->createCategory($request->get('name'));
        return $this->buildResponse($request, $result);
    }
}
