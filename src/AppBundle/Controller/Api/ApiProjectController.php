<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Form\CategoryType;
use AppBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as REST;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiProjectController extends Controller
{
    /**
     * Creat a new Category

     * @ApiDoc(
     *    description="Add Category",
     *    section="Categories",
     *    input={"class"=CategoryType::class, "name"=""},
     *    statusCodes = {
     *        201 = "Creation with success",
     *        400 = "Formulaire invalide"
     *    },
     *    responseMap={
     *         201 = {"class"=Category::class, "groups"={"place"}},
     *         400 = { "class"=CategoryType::class, "form_errors"=true, "name" = ""}
     *    }
     * )
     * 
     * @REST\View(statusCode=Response::HTTP_CREATED, serializerGroups={"list"})
     * @REST\Post("/category")
     */
    public function postCategoryAction(Request $request)
    {
        $productService = $this->get('product_service');
        
        $category = new Category();
        
        $form = $this->createForm(CategoryType::class, $category);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $productService->persistAndFlush($category);
            return $category;
        } else {
            return $form;
        }
    }

    /**
     * Get All Categories
     * @ApiDoc(
     *  resource="/api/categories",
     *  description="Get Categories",
     *  section="Categories",
     *  output= { "class"=Category::class, "collection"=true, "groups"={"category"} }
     * )
     * 
     * @REST\View()
     * @REST\Get("/categories")
     */
    public function getCategoriesAction(Request $request)
    {
        
        $productService = $this->get('product_service');
        return $productService->getAllCategories();
    }


    /**
     * Remove a Category
     * @ApiDoc(
     *  resource="/api/categories",
     *  description="Delete Category",
     *  section="Categories"
     * )
     * @QueryParam(name="id", requirements="\d+", default="", description="Id for category to delete")
     * @REST\View(statusCode=Response::HTTP_NO_CONTENT)
     * @REST\Delete("/categories")
     */
    public function deleteCategoriesAction(Request $request)
    {
        $productService = $this->get('product_service');
        $category = $productService->getCategorybyId($request->get('id'));
        if($category){
            $productService->removeAndFlush($category);
        }else{
            throw new NotFoundHttpException('Category not found');
        }
    }

    /**
     * Create a new Product
     * @ApiDoc(
     *    resource="/api/product",
     *    description="Add Product",
     *    section="Products",
     *    input={"class"=ProductType::class, "name"=""}
     * )
     * 
     * @REST\View(statusCode=Response::HTTP_CREATED, serializerGroups={"list"})
     * @REST\Post("/product")
     */
    public function postProductAction(Request $request)
    {
        $productService = $this->get('product_service');
        
        $product = new Product();
        
        $form = $this->createForm(ProductType::class, $product);

        $form->submit($request->request->all());
        
        if ($form->isValid()) {
            foreach($product->getCategories() as $category){
                $product->removeCategory($category);
                $objCategory = $productService->getCategoryRepository()->findByIdOrName($category->getName());
                if($objCategory)
                    $product->addCategory($objCategory);
            }
            if(count($product->getCategories()) <= 0){
                throw new NotFoundHttpException('No Category valid for Product');
            }
            $productService->persistAndFlush($product);
            return $product;
        } else {
            return $form;
        }
    }


    /**
     * Get All Products By category
     * @ApiDoc(
     *  resource="/api/categories/{idCategory}/products/{page}",
     *  description="Get All Products By category",
     *  section="Products",
     *  output= { "class"=Product::class, "collection"=true, "groups"={"product"} }
     * )
     * 
     * @REST\View(serializerGroups={"list"}))
     * @REST\Get("/categories/{idCategory}/products/{page}", defaults={"page":1})
     * @QueryParam(name="idCategory", requirements="\d+", default="", description="Id For Category")
     * @QueryParam(name="page", requirements="\d+", default="1", description="Number pager")
     */
    public function getProductsByCategoryAction(Request $request, $idCategory, $page)
    {
        $productService = $this->get('product_service');
        $category =  $productService->getCategorybyId($idCategory);
        if(!$category){
            throw new NotFoundHttpException('Category not found');
        }
        return $productService->getAllProductsByCategory($category, $page);
    }

    /**
     * Remove a product
     * @ApiDoc(
     *  resource="/api/products",
     *  description="Delete Category",
     *  section="Products"
     * )
     * @QueryParam(name="id", requirements="\d+", default="", description="Id for product to delete")
     * @REST\View(statusCode=Response::HTTP_NO_CONTENT)
     * @REST\Delete("/products")
     */
    public function deleteProductsAction(Request $request)
    {
        $productService = $this->get('product_service');
        $product = $productService->getProductbyId($request->get('id'));
        if($product){
            $productService->removeAndFlush($product);
        }else{
            throw new NotFoundHttpException('Product not found');
        }
    }

}