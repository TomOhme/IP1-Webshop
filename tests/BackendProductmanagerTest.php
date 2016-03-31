<?php
/**
 * Created by IntelliJ IDEA.
 * User: Yanick
 * Date: 20.03.16
 * Time: 16:52
 */
class Webshop_BackendProductmanager_Helper_DataTest extends PHPUnit_Framework_TestCase{

    private $soap;

    /**
     * @before
     */
    public function setupSOAPAPI(){
        $this -> soap = Mage::helper("backendproductmanager");
        $this -> soap->openSoap();
    }

    /**
     * @test
     */
    public function testgetAllProducts(){
        $result = $this -> soap -> getAllProducts();
        $this->assertGreaterThan(0,$result[0]['product_id']);
        $this->assertNotEmpty($result);
    }

    /**
     * @test
     */
    public function testCRUDProduct(){
        $file = urlencode("http://www.theredcow.com.au/wp-content/uploads/2012/10/Sbrinz_MG_7707_W1-72dpi_large.png");
        $mime = 'image/png';
        $productEntity = $this -> soap->createCatalogProductEntity((array("Obst")), "Stück", array("1"), "Sbrinz", "Ich bin ein Käse", "Ich Sbrinz", "5", "1", "tomate"
            , "4", "10", "", "", "", "Sbrinz", "Sbrinz", "Sbrinz", "5");
        $pid = $this -> soap->createProduct("101",$productEntity);
        $this->assertNotNull($pid);
        $this->assertNotContains($pid, [100,102,104,105,106]);
        $img = $this->soap->createProductImage($file, $mime, 'sbrinz', $pid);
        $this->assertContains('sbrinz',$img);
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