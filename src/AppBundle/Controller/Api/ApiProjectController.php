<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Form\CategoryType;
use AppBundle\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
#use Nelmio\ApiDocBundle\Annotation\ApiDoc;
#use FOS\RestBundle\Controller\Annotations as REST;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Delete;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiProjectController extends Controller
{
    /*
     * @ApiDoc(
     *    description="Add Category",
     *    section="Categories",
     *    input={"class"=CategoryType::class, "name"=""},
     *    statusCodes = {
     *        201 = "Create with success",
     *        400 = "Formulaire invalide"
     *    },
     *    responseMap={
     *         201 = {"class"=Category::class, "groups"={"place"}},
     *         400 = { "class"=CategoryType::class, "form_errors"=true, "name" = ""}
     *    }
     * )
    */
    /**
     * Createa new Category
     *
     *
     * @View(statusCode=Response::HTTP_CREATED, serializerGroups={"list"})
     * @Post("/category")
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

    /*
     *
     * @ApiDoc(
     *  resource="/api/categories",
     *  description="Get Categories",
     *  section="Categories",
     *  output= {"class"=Category::class, "collection"=true, "collectionName"="categories", "groups"={"list"}},
     *  statusCodes = {
     *        200 = "Categories List",
     *        500 = "Erreur Server"
     *  }
     * )
     */

    /**
     * Get All Categories
     *
     * @View()
     * @Get("/categories")
     */
    public function getCategoriesAction(Request $request)
    {
        
        $productService = $this->get('product_service');
        return $productService->getAllCategories();
    }

    /*
     * @ApiDoc(
     *  resource="/api/categories",
     *  description="Delete Category",
     *  section="Categories",
     *  parameters={
     *      {"name"="id", "dataType"="integer", "required"=true, "description"="Category id"}
     *  },
     *  statusCodes = {
     *        204 = "Category removed",
     *        404 = "Category not found"
     *  }
     * )
     */
    /**
     * Remove a Category
     * @View(statusCode=Response::HTTP_NO_CONTENT)
     * @Delete("/categories")
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

    /*
     * @ApiDoc(
     *    resource="/api/product",
     *    description="Add Product",
     *    section="Products",
     *    input={"class"=ProductType::class, "name"=""},
     *    statusCodes = {
     *        201 = "Create with success",
     *        400 = "Formulaire invalide"
     *    },
     *    responseMap={
     *         201 = {"class"=Product::class, "groups"={"place"}},
     *         400 = { "class"=ProductType::class, "form_errors"=true, "name" = ""}
     *    }
     * )
     */
    /**
     * Create a new Product
     *
     * @View(statusCode=Response::HTTP_CREATED, serializerGroups={"list"})
     * @Post("/product")
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

    /*
     * @ApiDoc(
     *  resource="/api/categories/{idCategory}/products/{page}",
     *  description="Get All Products By category",
     *  section="Products",
     *  requirements={
     *      {
     *          "name"="idCategory",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Category id"
     *      },
     *      {
     *          "name"="page",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Number page"
     *      }
     *  },
     *  output= { "class"=Product::class, "collection"=true, "collectionName"="products", "groups"={"list"}},
     *  statusCodes = {
     *        200 = "Products List",
     *        500 = "Erreur Server"
     *  }
     * )
     */
    /**
     * Get All Products By category
     *
     * @View(serializerGroups={"list"})
     * @Get("/categories/{idCategory}/products/{page}", defaults={"page":1})
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

    /*
     * @ApiDoc(
     *  resource="/api/products",
     *  description="Delete Category",
     *  section="Products",
     *  parameters={
     *       {"name"="id", "dataType"="integer", "required"=true, "description"="Product id"}
     *  },
     *  statusCodes = {
     *        204 = "Product Deleted",
     *        404 = "Product not found"
     *  }
     * )
     */
    /**
     * Remove a product
     * @View(statusCode=Response::HTTP_NO_CONTENT)
     * @Delete("/products")
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
