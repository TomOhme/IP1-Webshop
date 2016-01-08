<?php
/**
 * Created by IntelliJ IDEA.
 * User: Yanick
 * Date: 26.11.15
 * Time: 15:51
 * @Author: Yanick Schraner
 * Description: SOAP API Helper
 */
class Webshop_SoapHelper_Helper_Data extends Mage_Core_Helper_Abstract{
    private $client;
    private $session;

    /**
     * Start a SOAP Session
     */
    public function initSoap(){
        $this -> client = new SoapClient('http://127.0.0.1/magento/api/soap/?wsdl=1');
        $this -> session = $this -> client->login('soap', 'webshop12');
    }

    /**
     * Close the SOAP session
     */
    public function closeSoap(){
        $this ->client->endSession($this -> session);
    }

    /**
     * Get all Products
     * @return Array of catalogProductEntity
     * More: http://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProduct/catalog_product.list.html
     */
    public function getAllProducts(){
        return $products = $this -> client->call($this -> session, 'product.list', array());
    }

    /**
     * Get all information of a specific product by it's id
     * @param $ID
     * @return Array with all product information
     * More: http://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProduct/catalog_product.info.html
     */
    public function getProductByID($ID){
        return $product = $this -> client->call($this -> session, 'catalog_product.info', $ID);
    }

    /**
     * Create new Product by SKU and an Array of catalogProductEntity
     * @param $sku string
     * @param $productData Array
     * @return ID of the created product
     */
    public function createProduct($sku, $productData){
        $attributeSets = $this -> client->call($this -> session, 'product_attribute_set.list');
        $attributeSet = current($attributeSets);
        return $this -> client->call($this -> session, 'catalog_product.create', array('simple', $attributeSet['set_id'], $sku, $productData));
    }

    public function updateProductByID($ID, $productData){
        return $this -> client->call($this -> session, 'catalog_product.update', array($ID,$productData));
    }

    public function updateProductBySKU($SKU, $productData){
        return $this -> client->call($this -> session, 'catalog_product.update', array($SKU,$productData));
    }

    /**
     * Setup product attributes
     * @param $categories
     * @param $websites
     * @param $prodName
     * @param $description
     * @param $shortDescription
     * @param $weight
     * @param $status
     * @param $url_key
     * @param $url_key
     * @param $visibility
     * @param $price
     * @param $special_price
     * @param $special_from_date
     * @param $special_to_date
     * @param $meta_title
     * @param $meta_keyword
     * @param $meta_description
     * @param $stock
     * @return array with all product values
     */
    public function createCatalogProductEntity($categories, $websites, $prodName, $description, $shortDescription, $weight, $status, $url_key
    , $visibility, $price, $special_price, $special_from_date, $special_to_date, $meta_title, $meta_keyword, $meta_description, $stock){
        return array(
            'categories' => $categories,
            'unit' => 'Stueck',
            'websites' => $websites,
            'name' => $prodName,
            'description' => $description,
            'short_description' => $shortDescription,
            'weight' => $weight,
            'status' => $status,
            'url_key' => $url_key,
            'visibility' => $visibility,
            'price' => $price,
            'special_price' => $special_price,
            'special_from_date' => $special_from_date,
            'special_to_date' => $special_to_date,
            'tax_class_id' => 1,
            'meta_title' => $meta_title,
            'meta_keyword' => $meta_keyword,
            'meta_description' => $meta_description,
            array(
                'qty' => $stock,
                'is_in_stock' => "1"
            )
        );
    }
}