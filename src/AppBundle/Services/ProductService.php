<?php

namespace AppBundle\Services;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductService extends AbstractEntityManagerService
{
	/**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

	
	/**
	 * Create a category
	 * @param string $name
	 * @return array
	 */
	public function createCategory($name)
	{
		if(empty($name)){
			return $this->buildData('false', '403', "Name  of category is required!");
		}else{
			$category = new Category();
			$category->setName($name);
			$this->persistAndFlush($category);
			return $this->buildData('true', '200', "Category create with success", $category);
		}
	}
	
	
	/**
	 * Create a category
	 * @return array
	 */
	public function getAllCategories()
	{
		$categories = $this->getCategoryRepository()->findAll();
		return $this->buildData('true', '200', "All Categories", $categories);
	}

	/**
     * @return \AppBundle\Repository\CategoryRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private function getCategoryRepository()
    {
        return $this->em->getRepository('AppBundle:Category');
    }


    /**
	 * Create a Product
	 * @param Request $request
	 * @return array
	 */
	public function createProduct(Request $request)
	{
		$name = $request->get('name');
		$price = $request->get('price');
		$stock = $request->get('stock');
		$categories = $request->get('categories');
		
		if(!$name || empty($name)){
			return $this->buildData('false', '403', "Name  of category is required!");
		}
		if(!$categories || empty($categories) || !is_array($categories)){
			return $this->buildData('false', '403', "Categories must be an array and not empty");
		}
		if($price && !empty($price)){
			$price = (double) $price;
			if ((!is_int($price) || !is_float($price)) && $price <= 0) {
			   return $this->buildData('false', '403', "Price not available");
			}
		}
		if($stock && !empty($stock)){
			$stock = (int) $stock;
			if (!is_int($stock) || $stock < 0) {
			   return $this->buildData('false', '403', "Stock must an integer positive");
			}
		}

		$arrayCategories = [];
		foreach($categories as $category){
			$categoryObj = $this->getCategoryRepository()->findByIdOrName($category);
			if($categoryObj){
				array_push($arrayCategories, $categoryObj);
			}
		}
		if(count($arrayCategories) <= 0){
			return $this->buildData('false', '403', "Categories Not Exist!");
		}
		
		$product = new Product();
		$product->setName($name);
		if($price && !empty($price)){
			$product->setPrice($price);
		}
		if($stock && !empty($stock)){
			$product->setStock($stock);
		}
		foreach($arrayCategories as $category){
			$product->addCategory($category);
		}
		$this->persistAndFlush($product);
		//dump($product);
		///exit;
		//$product->getCategories();
		return $this->buildData('true', '200', "Product create with success", $product);
	}
}