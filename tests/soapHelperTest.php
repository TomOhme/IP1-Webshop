<?php
/**
 * Created by IntelliJ IDEA.
 * User: Yanick
 * Date: 20.03.16
 * Time: 16:52
 */
class Webshop_SoapHelper_Helper_DataTest extends PHPUnit_Framework_TestCase{

    private $soap;

    /**
     * @before
     */
    public function setupSOAPAPI(){
        $this -> soap = Mage::helper("soaphelper");
        $this -> soap->initSoap();
    }

    /**
     * @test
     */
    public function testgetAllProducts(){
        $result = $this -> soap -> getAllProducts();
        $this->assertNotEmpty($result);
    }

    /**
     * @test
     */
    public function testCRUDProduct(){
        $productEntity = $this -> soap->createCatalogProductEntity((array("Gemüse")), "Stück", array("1"), "Tomate", "Ich bin eine Tomate", "Ich Tomate", "5", "1", "tomate"
            , "4", "10", "", "", "", "TOMATE", "tomate", "Ich bin eine Tomate", "5");
        $pid = $this -> soap->createProduct("11",$productEntity);
        $this->assertNotNull($pid);
        $this->assertNotContains($pid, [100,102,104,105,106]);
        $productInfo = $this -> soap -> getProductByID($pid);
        $this->assertNotEmpty($productInfo);
        $this->assertEquals($pid, $productInfo['product_id']);
        $productEntity = $this -> soap -> createCatalogProductEntity((array("Gemüse")), "Stück", array("1"), "Birne", "Ich bin eine Birne", "Ich Birne", "5", "1", "tomate"
            , "4", "10", "", "", "", "BIRNE", "birne", "Ich bin eine Birne", "5");
        $this -> assertTrue($this -> soap -> updateProductByID($pid, $productEntity));
        $this->assertTrue($this -> soap->deleteProductByID($pid));
    }

    /**
     * @after
     */
    public function tearDownCloseConnection(){
        $this -> soap->closeSoap();
    }
}