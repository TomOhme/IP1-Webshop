<?php

/**
 * Created by IntelliJ IDEA.
 * User: Yanick Schraner
 * Date: 26.11.15
 * Time: 09:04
 * @Author: Yanick Schraner
 */
class Webshop_RestConnect_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Returns all Products with all attributes
     * More Information: http://devdocs.magento.com/guides/m1x/api/rest/Resources/Products/products.html
     * @return JSON Products
     */

    public function initOauth(){
        /*
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
    }

    public function getAllProducts(){
        $oAuthClient = Mage::getModel('restconnect/oauthclient');
        $params = $oAuthClient->getConfigFromSession();
        $oAuthClient->init($params);

        $state = $oAuthClient->authenticate();

        if ($state == Webshop_RestConnect_Model_Oauthclient::OAUTH_STATE_ACCESS_TOKEN) {
            $acessToken = $oAuthClient->getAuthorizedToken();
        }

        $restClient = $acessToken->getHttpClient($params);
        // In Magento it is neccesary to set json or xml headers in order to work
        $restClient->setHeaders('Accept', 'application/json');
        // Set REST resource URL
        $restClient->setUri('http://127.0.0.1/magento/api/rest/products');
        // Get method
        $restClient->setMethod(Zend_Http_Client::GET);

        //Make REST request
        $response = $restClient->request();
        return $response;

*/






        // $callbackUrl is a path to your file with OAuth authentication example for the Admin user
        $callbackUrl = "http://127.0.0.1/restconnect/test";
        $temporaryCredentialsRequestUrl = "http://127.0.0.1/magento/oauth/initiate?oauth_callback=" . urlencode($callbackUrl);
        $adminAuthorizationUrl = 'http://127.0.0.1/magento/admin/oAuth_authorize';
        $accessTokenRequestUrl = 'http://127.0.0.1/magento/oauth/token';
        $apiUrl = 'http://127.0.0.1/magento/api/rest';
        $consumerKey = '9a7fe824b69dc81faea1a0668c1f0dec';
        $consumerSecret = 'eda306642929637bdb9ff5169f573dbb';

        //session_start();
        if (!isset($_GET['oauth_token']) && isset($_SESSION['state']) && $_SESSION['state'] == 1) {
            $_SESSION['state'] = 0;
        }
        try {
            $authType = ($_SESSION['state'] == 2) ? OAUTH_AUTH_TYPE_AUTHORIZATION : OAUTH_AUTH_TYPE_URI;
            $oauthClient = new OAuth($consumerKey, $consumerSecret, OAUTH_SIG_METHOD_HMACSHA1, $authType);
            $oauthClient->enableDebug();

            if (!isset($_GET['oauth_token']) && !$_SESSION['state']) {
                $requestToken = $oauthClient->getRequestToken($temporaryCredentialsRequestUrl);
                $_SESSION['secret'] = $requestToken['oauth_token_secret'];
                $_SESSION['state'] = 1;
                header('Location: ' . $adminAuthorizationUrl . '?oauth_token=' . $requestToken['oauth_token']);
                exit;
            } else if ($_SESSION['state'] == 1) {
                $oauthClient->setToken($_GET['oauth_token'], $_SESSION['secret']);
                $accessToken = $oauthClient->getAccessToken($accessTokenRequestUrl);
                $_SESSION['state'] = 2;
                $_SESSION['token'] = $accessToken['oauth_token'];
                $_SESSION['secret'] = $accessToken['oauth_token_secret'];
                header('Location: ' . $callbackUrl);
                exit;
            } else {
                $oauthClient->setToken($_SESSION['token'], $_SESSION['secret']);

                $resourceUrl = "$apiUrl/products";
                $oauthClient->fetch($resourceUrl, array(), 'GET', array('Content-Type' => 'application/json'));
                $productsList = json_decode($oauthClient->getLastResponse());
                print_r($productsList);
            }
        } catch (OAuthException $e) {
            print_r($e->getMessage());
            echo "&lt;br/&gt;";
            print_r($e->lastResponse);
        }
    }

    public function getProductByID(){

    }

    public function getAllCustomers(){

    }

    public function getAllCategories(){

    }
}