<?php

namespace AppBundle\Services;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ProductService
{
	/**@var EntityManager $em **/
    protected $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function persistAndFlush($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function removeAndFlush($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }


	/**
     * @return \AppBundle\Repository\CategoryRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    public function getCategoryRepository()
    {
        return $this->em->getRepository('AppBundle:Category');
    }


	/**
     * @return \AppBundle\Repository\ProductRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private function getProductRepository()
    {
        return $this->em->getRepository('AppBundle:Product');
    }
	
	/**
	 * Create a category
	 * @return array
	 */
	public function getAllCategories()
	{
		return $this->getCategoryRepository()->findAll();
	}

	/**
	 * Get a category
	 * @param integer $id
	 * @return Category
	 */
	
	public function getCategorybyId($idCategory){
		return $this->getCategoryRepository()->findOneBy(['id' => $idCategory]);
	}

	/**
	 * Get a category
	 * @param integer $id
	 * @return Category
	 */
	public function getProductbyId($id){
		return $this->getProductRepository()->findOneBy(['id' => $id]);
	}
	
	/**
	 * Get a category
	 * @param Category $category
	 * @param Integer $page
	 * @param Integer $nbItems
	 * @return Array|Category
	 */
	public function getAllProductsByCategory(Category $category, $page, $nbItems=10){
		
		return $this->getProductRepository()->findByCategory($category, $page, $nbItems);
	}
}