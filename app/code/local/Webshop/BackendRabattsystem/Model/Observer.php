<?php
/**
 * Created by IntelliJ IDEA.
 * User: Yanick
 * Date: 28.04.16
 * Time: 13:57
 * Sets the discount to the displayed product if discount criteria fulfilled
 */

class Webshop_BackendRabattsystem_Model_Observer
{

    public function get_final_price($observer)
    {
        /* Check if the customer is logged in, if so, then we proceed */
        if(Mage::getSingleton('customer/session')->isLoggedIn())
        {
            //set up needed variables
            $customer_id = Mage::getSingleton('customer/session')->getCustomerId();
            $fromDate = date('Y-m-d H:i:s', strtotime(date('Y-01-01')));
            $toDate = date('Y-m-d H:i:s');
            $totalOrderAmount = 0;
            $discount = 0;
            $event = $observer->getEvent();
            $product = $event->getProduct();
            $product->original_price = $product->getPrice();
            $productID = $product->getId();

            //receive orders of loged in user containing displayed product
            $orders = Mage::getResourceModel('sales/order_item_collection')
                ->addFieldToSelect('*')
                ->addAttributeToFilter('product_id', array('eq' => $productID))
            ;
            $orders->getSelect()->join(array('sales_order' => Mage::getSingleton('core/resource')->getTableName('sales/order')), 'main_table.order_id = sales_order.entity_id and customer_id=' . $customer_id, array('sales_order.entity_id'));
            $orders = $orders->getData();
            foreach($orders as $order){
                if($fromDate <= $order['created_at'] && $order['created_at'] <= $toDate){
                    $totalOrderAmount += $order['base_row_total_incl_tax'];
                }
            }

            //Get discount data from the database
            $resource = Mage::getSingleton('core/resource');
            $readConnection = $resource->getConnection('core_read');
            $query = 'SELECT * FROM custom_discount WHERE productID = ' . $productID .';';
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