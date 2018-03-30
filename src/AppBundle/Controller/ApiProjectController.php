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
     *  resource="/api/category",
     *  description="Add Category",
     *  section="Categories",
     *  parameters={
     *      {"name"="name", "description"="Name of Category", "required"=true, "dataType"="string"}
     *  },
     *  statusCodes={
     *      200="Successful",
     *      403="Validation errors",
     *      500="Error Server"
     *  }
     * )
     * 
     * POST Route annotation.
     * @POST("/category.{_format}", defaults={"_format": "json"})
     */
    public function postCategoryAction(Request $request)
    {
        try{
            $productService = $this->get('product_service');
            $result = $productService->createCategory($request->get('name'));
            return $this->buildResponse($request, $result);
        }catch(\Exception $e){
            return $this->buildResponse($request, array(
                'success' => 'false',
                'code' => '500',
                'message' => 'Error : '.$e->getMessage(),
                'data' => null
            ));
        }
    }

    /**
     * Get All Categories
     * @ApiDoc(
     *  resource="/api/categories",
     *  description="Get Categories",
     *  section="Categories",
     *  statusCodes={
     *      200="Successful",
     *      500="Error Server"
     *  }
     * )
     * 
     * Get Route annotation.
     * @GET("/categories.{_format}", defaults={"_format": "json"})
     */
    public function getCategoriesAction(Request $request)
    {
        try{
            $productService = $this->get('product_service');
            $result = $productService->getAllCategories();
            return $this->buildResponse($request, $result);
        }catch(\Exception $e){
            return $this->buildResponse($request, array(
                'success' => 'false',
                'code' => '500',
                'message' => 'Error : '.$e->getMessage(),
                'data' => null
            ));
        }
    }

    /**
     * Create a new Product
     * @ApiDoc(
     *  resource="/api/products",
     *  description="Add Product",
     *  section="Products",
     *  parameters={
     *      {"name"="name", "description"="Name of product", "required"=true, "dataType"="string"},
     *      {"name"="price", "description"="Price of product", "required"=false, "dataType"="string"},
     *      {"name"="stock", "description"="Information Stock", "required"=false, "dataType"="string"},
     *      {"name"="categories[0]", "description"="Categories", "required"=true, "dataType"="array"}
     *  },
     *  statusCodes={
     *      200="Successful",
     *      403="Validation errors",
     *      500="Error Server"
     *  }
     * )
     * 
     * POST Route annotation.
     * @POST("/product.{_format}", defaults={"_format": "json"})
     */
    public function postProductAction(Request $request)
    {
        try{
            $productService = $this->get('product_service');
            $result = $productService->createProduct($request);
            return $this->buildResponse($request, $result);
        }catch(\Exception $e){
            return $this->buildResponse($request, array(
                'success' => 'false',
                'code' => '500',
                'message' => 'Error : '.$e->getMessage(),
                'data' => null
            ));
        }
    }
}
