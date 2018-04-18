<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
    
/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Groups({"list"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     *
     * @Assert\NotBlank(message="Name Required!")
     * @Serializer\Groups({"detail", "list"})
     */
    private $name;


    /**
     * @ORM\Column(name="price", type="float", precision=3, scale=2)
     *
     * @Assert\NotBlank(message="Price Required!")
     * @Assert\Type(type="float", message="Price is not valide")
     * @Serializer\Groups({"detail", "list"})
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="stock", type="integer")
     *
     * @Assert\NotBlank(message="Stock Required!")
     * @Assert\Type(type="integer", message="Stock is not a valid .")
     * @Serializer\Groups({"detail", "list"})
     */
    private $stock;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Category")
     * @ORM\JoinTable(name="products_categories",
     *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="categoy_id", referencedColumnName="id")}
     *   )
     *
     * @Serializer\Groups({"detail", "list"})
     * @Serializer\Type("ArrayCollection<AppBundle\Entity\Category>")
     */
    private $categories;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set stock
     *
     * @param integer $stock
     *
     * @return Product
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return integer
     */
    public function getStock()
    {
        return $this->stock;
    }
    
    /**
     * Add category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Product
     */
    public function addCategory(\AppBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \AppBundle\Entity\Category $category
     */
    public function removeCategory(\AppBundle\Entity\Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @Assert\Callback
     */
    public function isProductValid(ExecutionContextInterface $context)
    {
        if (count($this->categories)==0) {
            $context->buildViolation('You have to add any category for product!')
                    ->atPath('categories')
                    ->addViolation();
        }
    }
}
