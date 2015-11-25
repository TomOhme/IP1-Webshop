<?php

/**
 *
 * @author     Darko Goleš <darko.goles@inchoo.net>
 * @package    Webshop
 * @subpackage RestConnect
 *
 * Url of controller is: http://127.0.0.1/magento/restconnect/test/[action]
 */
class Webshop_RestConnect_TestController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {

        //Basic parameters that need to be provided for oAuth authentication
        //on Magento
        $params = array(
            'siteUrl' => 'http://127.0.0.1/magento/oauth',
            'requestTokenUrl' => 'http://127.0.0.1/magento/oauth/initiate',
            'accessTokenUrl' => 'http://127.0.0.1/magento/oauth/token',
            'authorizeUrl' => 'http://127.0.0.1/magento/admin/oAuth_authorize', //This URL is used only if we authenticate as Admin user type
            'consumerKey' => '9a7fe824b69dc81faea1a0668c1f0dec', //Consumer key registered in server administration
            'consumerSecret' => 'eda306642929637bdb9ff5169f573dbb', //Consumer secret registered in server administration
            'callbackUrl' => 'http://127.0.0.1/magento/restconnect/test/callback', //Url of callback action below
        );


        $oAuthClient = Mage::getModel('restconnect/oauthclient');
        $oAuthClient->reset();

        $oAuthClient->init($params);
        $oAuthClient->authenticate();

        return;
    }

    public function callbackAction() {

        $oAuthClient = Mage::getModel('restconnect/oauthclient');
        $params = $oAuthClient->getConfigFromSession();
        $oAuthClient->init($params);

        $state = $oAuthClient->authenticate();

        if ($state == Webshop_RestConnect_Model_Oauth_Client::OAUTH_STATE_ACCESS_TOKEN) {
            $acessToken = $oAuthClient->getAuthorizedToken();
        }

        $restClient = $acessToken->getHttpClient($params);
        // Set REST resource URL
        $restClient->setUri('http://127.0.0.1/magento/api/rest/products');
        // In Magento it is neccesary to set json or xml headers in order to work
        $restClient->setHeaders('Accept', 'application/json');
        // Get method
        $restClient->setMethod(Zend_Http_Client::GET);
        //Make REST request
        $response = $restClient->request();
        // Here we can see that response body contains json list of products
        Zend_Debug::dump($response);

        return;
    }

}