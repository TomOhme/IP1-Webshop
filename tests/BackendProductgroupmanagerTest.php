<?php
/**
 * Created by IntelliJ IDEA.
 * Author: Yanick Schraner
 * Date: 01.04.16
 * Time: 12:36
 */
class Webshop_BackendProductgroupmanager_Helper_DataTest extends PHPUnit_Framework_TestCase
{

    private $soap;

    /**
     * @before
     */
    public function setupSOAPAPI()
    {
        $this->soap = Mage::helper("backendproductgroupmanager");
        $this->soap->openSoap();
    }

    /**
     * @test
     */
    public function testGetProductgrout(){
        $tree = $this->soap->getTree();
        $this->assertNotNull($tree);
        $this->assertArrayHasKey('category_id',$tree['children'][0]['children'][0]);
        $this->assertGreaterThan(2,$tree['children'][0]['children'][0]['category_id']);
        $category = $this->soap->getCategory($tree['children'][0]['children'][0]['category_id']);
        $this->assertArrayHasKey('parent_id',$category);
        $this->assertEquals($tree['children'][0]['children'][0]['name'], $category['name']);
    }
    /**
     * @test
     */
    public function testProductgroupCRUD(){
        $category = $this->soap->createCategory('Fischh',"2");
        $this->assertGreaterThan(0,$category);
        $bool = $this->soap->updateCategory($category,'Fisch');
        $this->assertTrue($bool);
        $this->assertNotFalse($bool);
        $category2 = $this->soap->createCategory('frischer Fisch',"2");
        $bool = $this->soap->moveCategory($category2,$category);
        $this->assertTrue($bool);
        $this->assertNotFalse($bool);
        $this->assertTrue($this->soap->deleteCategory($category2));
        $this->assertTrue($this->soap->deleteCategory($category));
    }

    /**
     * @after
     */
    public function tearDownCloseConnection()
    {
        $this->soap->closeSoap();
    }
}