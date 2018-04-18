<?php

namespace AppBundle\Controller;

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


/**
 * @Route("/api")
 */
class ApiProjectController extends Controller
{
    /**
     * Creat a new Category
     * @ApiDoc(
     *    description="Add Category",
     *    section="Categories",
     *    input={"class"=CategoryType::class, "name"=""}
     * )
     * 
     * @REST\View(statusCode=Response::HTTP_CREATED, serializerGroups={"list"})
     * @REST\Post("/category")
     */
    public function postCategoryAction(Request $request)
    {
        $name = $request->get('name');

        $category = new Category();
        $category->setName(null); 
        $form = $this->createForm(CategoryType::class, $category);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($category);
            $em->flush();
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
     * @REST\View(serializerGroups={"list"}))
     * @REST\Get("/categories")
     */
    public function getCategoriesAction(Request $request)
    {
        
        $productService = $this->get('product_service');
        $result = $productService->getAllCategories();
        return $result;
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
        $name = $request->get('name');
        $price = $request->get('price');
        $stock = $request->get('stock');
        $categories = $request->get('categories');
        
        $product = new Product();
        $product->setName($name); 
        $product->setPrice($price); 
        $product->setStock($stock); 
        foreach($categories as $id){
            $category = $this->em->getRepository(Category::class)->findOneBy(['id' => $id]);
            if($category)
                $product->addCategory($category);
        }
        $form = $this->createForm(CategoryType::class, $product);

        if ($form->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($product);
            $em->flush();
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
        return $productService->getAllProductsByCategory($idCategory, $page);
    }

}
