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
        return $this->session;
    }

    /**
     * Close the SOAP session
     */
    public function closeSoap(){
        $this ->client->endSession($this -> session);
    }
}