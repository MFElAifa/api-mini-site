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
        
        $jsonCrawler = json_decode($client->getResponse()->getContent());
       
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(200, $jsonCrawler->code);
    }

    /**
     * Test create category - api format Xml
     * @group Functional
     */
    public function testPostCategoryXml()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/category.xml', ['name' => 'Category 1']);
        
        $xmlCrawler = new Crawler(str_replace('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>', '',$client->getResponse()->getContent()));

        $entrys = $xmlCrawler->filterXPath('//result/entry')->extract(array('_text'));
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(200, $entrys[1]);
    }

    /**
     * Test create category without name - api format Json
     * @group Functional
     */
    public function testPostCategoryWithoutName()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/category');
        
        $jsonCrawler = json_decode($client->getResponse()->getContent());
       
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(403, $jsonCrawler->code);
    }


    /**
     * Test create category without name - api format Xml
     * @group Functional
     */
    public function testPostCategoryWithoutNameXml()
    {
        $client = static::createClient();

        $crawler = $client->request('POST', '/api/category.xml');
        
        $xmlCrawler = new Crawler(str_replace('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>', '',$client->getResponse()->getContent()));

        $entrys = $xmlCrawler->filterXPath('//result/entry')->extract(array('_text'));
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(403, $entrys[1]);
    }

    /**
     * Test get categories - api format Json
     * @group Functional
     */
    public function testGetCategories()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/categories');
        
        $jsonCrawler = json_decode($client->getResponse()->getContent());
       
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(200, $jsonCrawler->code);
    }

   /**
     * Test get categories - api format Xml
     * @group Functional
     */
    public function testGetCategoriesXml()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/categories.xml');
        
        $xmlCrawler = new Crawler(str_replace('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>', '',$client->getResponse()->getContent()));

        $entrys = $xmlCrawler->filterXPath('//result/entry')->extract(array('_text'));
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(200, $entrys[1]);
    }
}
