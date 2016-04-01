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
        $productEntity = $this -> soap->createCatalogProductEntity((array("Obst")), "Stück", "Sbrinz", "Ich bin ein Sbrinz", "5", "10", "50");
        $pid = $this -> soap->createProduct("101",$productEntity);
        $this->assertNotNull($pid);
        $this->assertNotContains($pid, [100,102,104,105,106]);
        $img = $this->soap->createProductImage($file, $mime, 'sbrinz', $pid);
        $this->assertContains('sbrinz',$img);

        $productInfo = $this -> soap -> getProductByID($pid);
        $this->assertNotEmpty($productInfo);
        $this->assertEquals($pid, $productInfo['product_id']);
        $img = $this->soap->getProductImage($pid);
        $this->assertContains('sbrinz',$img[0]['file']);

        $productEntity = $this -> soap -> createCatalogProductEntity((array("Gemüse")), "Stück", "Birne", "Ich bin eine Birne", "5", "10", "45");
        $this -> assertTrue($this -> soap -> updateProductByID($pid, $productEntity));
        $file = urlencode("http://images.eatsmarter.de/sites/default/files/styles/576x432/public/birne.jpg");
        $mime = 'image/jpg';
        $img = $this->soap->updateProductImage($file, $mime, 'birne', $img[0]['file'], $pid);
        $this->assertContains('birne',$img);

        $this->assertTrue($this->soap->removeProductImage($pid, $img));
        $this->assertTrue($this -> soap->deleteProductByID($pid));
    }

    /**
     * @after
     */
    public function tearDownCloseConnection(){
        $this -> soap->closeSoap();
    }
}