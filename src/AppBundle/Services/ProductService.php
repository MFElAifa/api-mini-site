<?php

namespace AppBundle\Services;

use AppBundle\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

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
	 * @return JsonResponse
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
	

}