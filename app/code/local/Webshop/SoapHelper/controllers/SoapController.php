<?php
/**
 * Created by IntelliJ IDEA.
 * User: Yanick
 * Date: 26.11.15
 * Time: 14:44
 */
class Webshop_SoapHelper_SoapController extends Mage_Core_Controller_Front_Action {
    public function indexAction(){
        $client = new SoapClient('http://127.0.0.1/magento/api/soap/?wsdl=1');
        $session = $client->login('soap', 'webshop12');


        $attributeSets = $client->call($session, 'product_attribute_set.list');
        $attributeSet = current($attributeSets);
        var_dump ($attributeSet);

        $client->endSession($session);
    }

    public function createproductAction(){
        $soap = MAGE::helper("workshop/soaphelper");
        $soap->initSoap();
        $productEntity = $soap->createCatalogProductEntity((array("Gemüse")), array("1"), "Tomate", "Ich bin eine Tomate", "Ich Tomate", "5", "1", "tomate"
    , "1", "10", "", "", "", "TOMATE", "tomate", "Ich bin eine Tomate");
        echo $soap->createProduct("10",$productEntity);
        $soap->closeSoap;
    }
}