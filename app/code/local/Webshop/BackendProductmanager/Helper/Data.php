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
     * @return array of catalogProductEntity
     * More: http://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProduct/catalog_product.list.html
     */
    public function getAllProducts()
    {
        return $this->client->call($this->session, 'product.list', array());

    }

    /**
     * Get all information of a specific product by it's id
     * @param $ID
     * @return array with all product information
     * More: http://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProduct/catalog_product.info.html
     */
    public function getProductByID($ID)
    {
        return $this->client->call($this->session, 'catalog_product.info', $ID);
    }
    /**
     * Get all product images of a specific product by it's id
     * @param $ProductID
     * @return array of of catalogProductImageEntity
     * More: http://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductAttributeMedia/catalog_product_attribute_media.list.html
     */
    public function getProductImage($ProductID)
    {
        return $this->client->call($this->session, 'catalog_product_attribute_media.list', $ProductID);
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
     * Creates a new product image an assignes it to the according product
     * @param $filename
     * @param $mime (this can be image/jpeg, image/jpg, image/png, image/gif)
     * @param $name (display name in magento)
     * @param $productId
     * @return string stored image file name with path
     * More: http://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductAttributeMedia/catalog_product_attribute_media.create.html
     */
    public function createProductImage($filename, $mime, $name, $productId)
    {
        if(pathinfo(urldecode($filename), PATHINFO_EXTENSION) == "jpg"){
            $img = imagecreatefromstring(file_get_contents(urldecode($filename)));
            imagepng($img,$name . ".png");
            $content = base64_encode($name . ".png");
            $mime = "image/png";
        } else{
            $content = base64_encode(file_get_contents(urldecode($filename)));
        }

        $file = array(
            'content' => $content,
            'mime' => $mime,
            'name' => $name
        );

        return $this->client->call(
            $this->session,
            'catalog_product_attribute_media.create',
            array(
                $productId,
                array('file'=>$file, 'label'=>$name, 'position'=>'1', 'types'=>array('image','small_image','thumbnail'), 'exclude'=>null)
            )
        );
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
     * Creates a new product image an assignes it to the according product
     * @param $newFilename
     * @param $mime (this can be image/jpeg, image/jpg, image/png, image/gif)
     * @param $name (display name in magento)
     * @param $oldFilename (Magento stored path and filename)
     * @param $productId
     * @return string stored image file name with path
     */
    public function updateProductImage($newFilename, $mime, $name, $oldFilename, $productId)
    {
        $this->removeProductImage($productId,$oldFilename);
        return $this->createProductImage($newFilename,$mime,$name,$productId);
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
     * Delete product image by it's magento filename (+path) and it's product id
     * @param $productID
     * @param $filename
     * @return boolean / int
     * More: http://devdocs.magento.com/guides/m1x/api/soap/catalog/catalogProductAttributeMedia/catalog_product_attribute_media.remove.html
     */
    public function removeProductImage($productID, $filename)
    {
        return $this->client->call($this->session, 'catalog_product_attribute_media.remove', array('product' => $productID, 'file' => $filename));
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
     * @param $visibility       String (Default 4)
     * @param $price            String
     * @param $special_price    String Optional
     * @param $special_from_date String Optional
     * @param $special_to_date  String Optional
     * @param $stock            String
     * @return array with all product values
     */
    public function createCatalogProductEntity($categories, $unit, $prodName, $shortDescription, $weight, $price, $stock
        , $websites = array("1"), $description = '', $status = '1', $visibility = "4", $special_price = '', $special_from_date = '', $special_to_date = '')
    {
        $prodNameURL = strtolower($prodName);
        return array(
            'categories' => $categories,
            'unit' => $unit,
            'websites' => $websites,
            'name' => $prodName,
            'description' => $description,
            'short_description' => $shortDescription,
            'weight' => $weight,
            'status' => $status,
            'url_key' => $prodNameURL,
            'visibility' => $visibility,
            'price' => $price,
            'special_price' => $special_price,
            'special_from_date' => $special_from_date,
            'special_to_date' => $special_to_date,
            'tax_class_id' => 1,
            'meta_title' => $prodName,
            'meta_keyword' => $prodName,
            'meta_description' => $shortDescription,
            array(
                'qty' => $stock,
                'is_in_stock' => "1"
            )
        );
    }
}
