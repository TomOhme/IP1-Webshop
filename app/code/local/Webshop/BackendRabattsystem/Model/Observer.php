<?php
/**
 * Created by IntelliJ IDEA.
 * User: Yanick
 * Date: 28.04.16
 * Time: 13:57
 * Sets the discount to the displayed product if discount criteria fulfilled
 */

class Webshop_BackendRabattsystem_Model_Observer{

    public function get_final_price($observer){
        /* Check if the customer is logged in, if so, then we proceed */
        if(Mage::getSingleton('customer/session')->isLoggedIn()){
            $event = $observer->getEvent();
            $product = $event->getProduct();
            $product->original_price = $product->getPrice();
            //Check if product is allready discounted
            if($product->getSpecialPrice() == null){
                //Setup needed values
                $customer_id = Mage::getSingleton('customer/session')->getCustomerId();
                $fromDate = date('Y-m-d H:i:s', strtotime(date('Y-01-01')));
                $toDate = date('Y-m-d H:i:s');
                $totalOrderAmount = 0;
                $discount = 0;

                //receive orders of loged in user containing displayed product
                $orders = Mage::getResourceModel('sales/order_collection')
                    ->addFieldToSelect('base_grand_total')
                    ->addFieldToFilter('customer_id', $customer_id)
                    ->addFieldToFilter('status', 'complete')
                    ->addAttributeToFilter('created_at', array('from' => $fromDate, 'to' => $toDate))
                ;
                $orders = $orders->getData();
                foreach ($orders as $order){
                    $totalOrderAmount += $order['base_grand_total'];
                }
                $totalOrderAmount += Mage::helper('checkout')->formatPrice(Mage::getSingleton('checkout/cart')->getQuote()->getGrandTotal());

                //Get discount data from the database
                $resource = Mage::getSingleton('core/resource');
                $readConnection = $resource->getConnection('core_read');
                $query = 'SELECT * FROM custom_discount;';
                $results = $readConnection->fetchAll($query);

                //Check if the discount criteria are fulfilled
                foreach ($results as $result){
                    if($discount < $result['discount'] && $totalOrderAmount > $result['setAfter']){
                        $discount = $result['discount'];
                    }
                }

                //Update Price as discount price
                $final_price = $product->original_price * (1 - $discount);
                $product->setFinalPrice($final_price);

                return $this;
            }
        }
    }
}