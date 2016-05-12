<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Captcha
 * @copyright  Copyright (c) 2006-2016 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Captcha Observer
 *
 * @category    Mage
 * @package     Mage_Captcha
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Webshop_Captcha_Model_Observer extends Mage_Captcha_Model_Observer
{
    /**
     * Check Captcha On Checkout as Guest Page
     *
     * @param Varien_Event_Observer $observer
     * @return Mage_Captcha_Model_Observer
     */
    public function checkGuestCheckout($observer)
    {
        $formId = 'user_contact';
        $captchaModel = Mage::helper('captcha')->getCaptcha($formId);
        if ($captchaModel->isRequired()) {
            $controller = $observer->getControllerAction();
            if (!$captchaModel->isCorrect($this->_getCaptchaString($controller->getRequest(), $formId))) {
                Mage::getSingleton('customer/session')->addError(Mage::helper('captcha')->__('Incorrect CAPTCHA.'));
                // insert form data to session, allowing to re-populate the contact us form
                $data = $controller->getRequest()->getPost();
                Mage::getSingleton('customer/session')->setFormData($data);
                $controller->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
                $controller->getResponse()->setRedirect(Mage::getUrl('*/*/'));
            }
        }

        return $this;
    }

    /**
     * Check Captcha On Checkout Register Page
     *
     * @param Varien_Event_Observer $observer
     * @return Mage_Captcha_Model_Observer
     */
    public function checkRegisterCheckout($observer)
    {
        $formId = 'user_contact';
        $captchaModel = Mage::helper('captcha')->getCaptcha($formId);
        if ($captchaModel->isRequired()) {
            $controller = $observer->getControllerAction();
            if (!$captchaModel->isCorrect($this->_getCaptchaString($controller->getRequest(), $formId))) {
                Mage::getSingleton('customer/session')->addError(Mage::helper('captcha')->__('Incorrect CAPTCHA.'));
                // insert form data to session, allowing to re-populate the contact us form
                $data = $controller->getRequest()->getPost();
                Mage::getSingleton('customer/session')->setFormData($data);
                $controller->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
                $controller->getResponse()->setRedirect(Mage::getUrl('*/*/'));
            }
        }

        return $this;
    }
}
