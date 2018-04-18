<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
//use PHPUnit\Framework\TestCase;

class ApiProjectControllerTest extends WebTestCase
{
    /**
     * Test create category - api format Json
     * @group Functional
     */
    public function testPostCategory()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/category', ['name' => 'Category 1']);
        
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    /**
     * Test create category - api format Xml
     * @group Functional
     */
   public function testPostCategoryXml()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/category.xml', ['name' => 'Category 1']);
        
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    /**
     * Test create category without name - api format Json
     * @group Functional
     */
    public function testPostCategoryWithoutName()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/category.json');
        
        $jsonCrawler = json_decode($client->getResponse()->getContent());
       
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }


    /**
     * Test create category without name - api format Xml
     * @group Functional
     */
    public function testPostCategoryWithoutNameXml()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/category.xml');
        
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    /**
     * Test get categories - api format Json
     * @group Functional
     */
    public function testGetCategories()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/categories.json');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * Test get categories - api format Xml
     * @group Functional
     */
    public function testGetCategoriesXml()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/categories.xml');
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }


    /**
     * Test delete categories - api format Json
     * @group Functional
     */
    public function testDeleteCategories()
    {
        $client = static::createClient();
        
        $crawler = $client->request('POST', '/api/category.json', ['name' => 'Category 1']);
        
        $content = json_decode($client->getResponse()->getContent());

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        
        $crawler = $client->request('DELETE', '/api/categories.json', ['id' => $content->id]);

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    /**
     * Test get categories - api format Xml
     * @group Functional
     */
    public function testDeleteCategoriesXml()
    {
        $client = static::createClient();
        
        $crawler = $client->request('POST', '/api/category.json', ['name' => 'Category 1']);
        
        $content = json_decode($client->getResponse()->getContent());

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $crawler = $client->request('DELETE', '/api/categories.xml', ['id' => $content->id]);
        
        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    /**
     * Test create product - api format Json
     * @group Functional
     */
    public function testPostProduct()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/product', [
            'name' => 'Product 1',
            'price' => 125,
            'stock' => 100,
            'categories' => [
                ["name" => "TEST"]
            ]
        ]);
        
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    /**
     * Test create product - api format Xml
     * @group Functional
     */
    public function testPostProductXml()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/product.xml', [
            'name' => 'Product 1',
            'price' => 125,
            'stock' => 100,
            'categories' => [
                ["name" => "TEST"]
            ]
        ]);
        
        /*$xmlCrawler = new Crawler(str_replace('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>', '',$client->getResponse()->getContent()));
        $entrys = $xmlCrawler->filterXPath('//result/entry')->extract(array('_text'));*/
        
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    /**
     * Test create product without name - api format Json
     * @group Functional
     */
    public function testPostProductWithoutName()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/product', [
            'price' => 125,
            'stock' => 100
        ]);
        
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    /**
     * Test create product without name - api format Xml
     * @group Functional
     */
    public function testPostProductWithoutNametXml()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/product.xml', [
            'price' => 125,
            'stock' => 100
        ]);
        
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    /**
     * Test create product with price error - api format Json
     * @group Functional
     */
    public function testPostProductWithoutPrice()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/product', [
            'name' => 'Product 1',
            'price' => 'ABC'
        ]);
        
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    /**
     * Test create product with price error - api format Xml
     * @group Functional
     */
    public function testPostProductWithoutPricetXml()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/product.xml', [
            'name' => 'Product 1',
            'price' => 'ABC'
        ]);
        
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    /**
     * Test create product with stock error - api format Json
     * @group Functional
     */
    public function testPostProductWithoutStock()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/product', [
            'name' => 'Product 1',
            'price' => 125
            //,'stock' => 'ABC'
        ]);
        
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    /**
     * Test create product with stock error - api format Xml
     * @group Functional
     */
    public function testPostProductWithoutStocktXml()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/product.xml', [
            'name' => 'Product 1',
            'price' => 125,
            'stock' => 'ABC'
        ]);
        
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    /**
     * Test create product with category error - api format Json
     * @group Functional
     */
    public function testPostProductWithoutCategory()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/product', [
            'name' => 'Product 1',
            'price' => 125,
            'stock' => 100
        ]);
        
        $jsonCrawler = json_decode($client->getResponse()->getContent());
        
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    /**
     * Test create product with category error - api format Xml
     * @group Functional
     */
    public function testPostProductWithoutCategorytXml()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/product.xml', [
            'name' => 'Product 1',
            'price' => 125,
            'stock' => 100
        ]);
        
        /*$xmlCrawler = new Crawler(str_replace('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>', '',$client->getResponse()->getContent()));
        $entrys = $xmlCrawler->filterXPath('//result/entry')->extract(array('_text'));*/
        
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    /**
     * Test create product with category error - api format Json
     * @group Functional
     */
    public function testPostProductWithCategoryNotFound()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/product', [
            'name' => 'Product 1',
            'price' => 125,
            'stock' => 100,
            'categories' => [
                [
                    'name' => 'ABCDFEHGEL'
                ]
            ]
        ]);
        
        $jsonCrawler = json_decode($client->getResponse()->getContent());
        
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * Test create product with category error - api format Xml
     * @group Functional
     */
    public function testPostProductWithCategoryNotFoundXml()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/product.xml', [
            'name' => 'Product 1',
            'price' => 125,
            'stock' => 100,
            'categories' => [
                [
                    'name' => 'ABCDFEHGEL'
                ]
            ]
        ]);
        
        /*$xmlCrawler = new Crawler(str_replace('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>', '',$client->getResponse()->getContent()));
        $entrys = $xmlCrawler->filterXPath('//result/entry')->extract(array('_text'));*/
        
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }


    /**
     * Test get products by category - api format Json
     * @group Functional
     */
    public function testGetProducts()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/categories/1/products/1');
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

   /**
     * Test get products by category - api format Xml
     * @group Functional
     */
    public function testGetProductsXml()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/categories/1/products/1.xml');
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

     /**
     * Test get products by category not found - api format Json
     * @group Functional
     */
    public function testGetProductsByErrorCatgory()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/categories/99999999/products/1');
        
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

   /**
     * Test get products by category not found - api format Xml
     * @group Functional
     */
    public function testGetProductsByErrorCatgoryXml()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/categories/99999999/products/1.xml');
        
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }


     /**
     * Test delete product - api format Json
     * @group Functional
     */
    public function testDeleteProducts()
    {
        $client = static::createClient();
        
        $crawler = $client->request('POST', '/api/product.json', [
            'name' => 'Product 1',
            'price' => 123.55,
            'stock' => 100,
            'categories' => [
                [
                    'name' => 'TEST'
                ]
            ]
        ]);
        
        $content = json_decode($client->getResponse()->getContent());

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        
        $crawler = $client->request('DELETE', '/api/products.json', ['id' => $content->id]);

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    /**
     * Test get categories - api format Xml
     * @group Functional
     */
    public function testDeletProductXml()
    {
        $client = static::createClient();
        
        $crawler = $client->request('POST', '/api/product.json', [
            'name' => 'Product 1',
            'price' => 123.55,
            'stock' => 100,
            'categories' => [
                [
                    'name' => 'TEST'
                ]
            ]
        ]);
        
        $content = json_decode($client->getResponse()->getContent());

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $crawler = $client->request('DELETE', '/api/products.xml', ['id' => $content->id]);
        
        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }
}
