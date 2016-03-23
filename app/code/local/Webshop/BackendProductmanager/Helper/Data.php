<?php
/**
 * Created by IntelliJ IDEA.
 * User: Yanick
 * Date: 21.03.16
 * Time: 12:18
 */
class Webshop_BackendProductmanager_Helper_Data extends Mage_Core_Helper_Abstract {

    private $session;
    private $client;
    
    public function openSoap(){
        $soap = Mage::helper("soaphelper");
        $this -> session = $soap->initSoap();
        $this -> client = new SoapClient('http://127.0.0.1/magento/api/soap/?wsdl=1');
    }

    public function closeSoap(){
        $soap = Mage::helper("soaphelper");
        $this -> session = $soap->closeSoap();
    }

    /**
     * Get all Products
     * @return Array of catalogProductEntity
     * More: http://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProduct/catalog_product.list.html
     */
    public function getAllProducts()
    {
        return $this->client->call($this->session, 'product.list', array());

    }

    /**
     * Get all information of a specific product by it's id
     * @param $ID
     * @return Array with all product information
     * More: http://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProduct/catalog_product.info.html
     */
    public function getProductByID($ID)
    {
        return $this->client->call($this->session, 'catalog_product.info', $ID);
    }

    /**
     * Create new Product by SKU and an Array of catalogProductEntity
     * @param $sku string
     * @param $productData Array
     * @return ID of the created product
     */
    public function createProduct($sku, $productData)
    {
        $attributeSets = $this->client->call($this->session, 'product_attribute_set.list');
        $attributeSet = current($attributeSets);
        return $this->client->call($this->session, 'catalog_product.create', array('simple', $attributeSet['set_id'], $sku, $productData));
    }

    /**
     * Updates specific product by it's IDxs
     * @param $ID
     * @param $productData
     * @return boolean
     */
    public function updateProductByID($ID, $productData)
    {
        return $this->client->call($this->session, 'catalog_product.update', array($ID, $productData));
    }

    /**
     * Delete product by it's ID
     * @param $ID
     * @return boolean
     */
    public function deleteProductByID($ID)
    {
        return $this->client->call($this->session, 'catalog_product.delete', $ID, 'ID');
    }

    /**
     * Setup product attributes
     * @param $categories       Array of Categories (String)
     * @param $websites         Array of Website ID's (Int) visible Website ID (Default: array("1"))
     * @param $prodName         String
     * @param $description      String
     * @param $shortDescription String
     * @param $weight           String
     * @param $status           String (Default: 1)
     * @param $url_key          String (Default lower case product name)
     * @param $visibility       String (Default 4)
     * @param $price            String
     * @param $special_price    String Optional
     * @param $special_from_date String Optional
     * @param $special_to_date  String Optional
     * @param $meta_title       String
     * @param $meta_keyword    String
     * @param $meta_description String
     * @param $stock            String
     * @return array with all product values
     */
    public function createCatalogProductEntity($categories, $unit, $websites, $prodName, $description, $shortDescription, $weight, $status, $url_key
        , $visibility, $price, $special_price, $special_from_date, $special_to_date, $meta_title, $meta_keyword, $meta_description, $stock)
    {
        return array(
            'categories' => $categories,
            'unit' => $unit,
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