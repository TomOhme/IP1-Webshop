<?php

/**
 *
 * @author     Darko Goleš <darko.goles@inchoo.net> and Yanick Schraner
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
            'consumerKey' => '72cc900a4dbda91420b1cd34a7b76c27', //Consumer key registered in server administration
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

        if ($state == Webshop_RestConnect_Model_Oauthclient::OAUTH_STATE_ACCESS_TOKEN) {
            $acessToken = $oAuthClient->getAuthorizedToken();
        }

        $restClient = $acessToken->getHttpClient($params);

        // In Magento it is neccesary to set json or xml headers in order to work
        $restClient->setHeaders('Accept', 'application/json');

        // Set REST resource URL
        $restClient->setUri('http://127.0.0.1/magento/index.php/api/rest/products');
        // Get method
        $restClient->setMethod(Zend_Http_Client::GET);

        //Make REST request
        $response = $restClient->request();

        Zend_Debug::dump($response);
        return;
    }
    public function newAction(){
        $callbackUrl = "http://127.0.0.1/magento/restconnect/test/new";
        $temporaryCredentialsRequestUrl = "http://127.0.0.1/magento/oauth/initiate?oauth_callback=" . urlencode($callbackUrl);
        $adminAuthorizationUrl = 'http://127.0.0.1/magento/admin/oAuth_authorize';
        $accessTokenRequestUrl = 'http://127.0.0.1/magento/oauth/token';
        $apiUrl = 'http://127.0.0.1/magento/api/rest';
        $consumerKey = '9a7fe824b69dc81faea1a0668c1f0dec';
        $consumerSecret = 'eda306642929637bdb9ff5169f573dbb';

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
                $oauthClient->fetch($resourceUrl);
                $productsList = json_decode($oauthClient->getLastResponse());
                print_r($productsList);
            }
        } catch (OAuthException $e) {
            print_r($e);
        }
    }
}