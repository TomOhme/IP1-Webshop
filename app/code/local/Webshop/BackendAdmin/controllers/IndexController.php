<?php
require_once "Mage/Adminhtml/controllers/IndexController.php";
class Webshop_BackendAdmin_IndexController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Render specified template
     *
     * @param string $tplName
     * @param array $data parameters required by template
     */
    protected function _outTemplate($tplName, $data = array())
    {
        $this->_initLayoutMessages('adminhtml/session');
        $block = $this->getLayout()->createBlock('adminhtml/template')->setTemplate("$tplName.phtml");
        foreach ($data as $index => $value) {
            $block->assign($index, $value);
        }
        $html = $block->toHtml();
        Mage::getSingleton('core/translate_inline')->processResponseBody($html);
        $this->getResponse()->setBody($html);
    }
    
    public function indexAction()
    {
        $session = Mage::getSingleton('admin/session');
        $url = $session->getUser()->getStartupPageUrl();
        if ($session->isFirstPageAfterLogin()) {
            // retain the "first page after login" value in session (before redirect)
            $session->setIsFirstPageAfterLogin(true);
        }
        $this->_redirect("backendadmin");
    }
}