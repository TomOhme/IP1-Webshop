<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
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
 * @category    design
 * @package     default_default
 * @copyright   Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo Mage::helper('adminhtml')->__('Log into Magento Admin Page') ?></title>
    <link type="text/css" rel="stylesheet" href="<?php echo $this->getSkinUrl('reset.css') ?>" media="all" />
    <link type="text/css" rel="stylesheet" href="<?php echo $this->getSkinUrl('boxes.css') ?>" media="all" />
    <link rel="icon" href="<?php echo $this->getSkinUrl('favicon.ico') ?>" type="image/x-icon" />
    <link rel="shortcut icon" href="<?php echo $this->getSkinUrl('favicon.ico') ?>" type="image/x-icon" />

    <script type="text/javascript" src="<?php echo $this->getJsUrl('prototype/prototype.js') ?>"></script>
    <script type="text/javascript" src="<?php echo $this->getJsUrl('prototype/validation.js') ?>"></script>
    <script type="text/javascript" src="<?php echo $this->getJsUrl('scriptaculous/effects.js') ?>"></script>
    <script type="text/javascript" src="<?php echo $this->getJsUrl('mage/adminhtml/form.js') ?>"></script>
    <script type="text/javascript" src="<?php echo $this->getJsUrl('mage/captcha.js') ?>"></script>

    <!--[if IE]> <link rel="stylesheet" href="<?php echo $this->getSkinUrl('iestyles.css') ?>" type="text/css" media="all" /> <![endif]-->
    <!--[if lt IE 7]> <link rel="stylesheet" href="<?php echo $this->getSkinUrl('below_ie7.css') ?>" type="text/css" media="all" /> <![endif]-->
    <!--[if IE 7]> <link rel="stylesheet" href="<?php echo $this->getSkinUrl('ie7.css') ?>" type="text/css" media="all" /> <![endif]-->
</head>
<body id="page-login" onload="document.forms.loginForm.username.focus();">
<div class="login-container">
    <div class="login-box">
        <form method="post" action="" id="loginForm" autocomplete="off">
            <div class="login-form">
                <input name="form_key" type="hidden" value="<?php echo $this->getFormKey() ?>" />
                <h2><?php echo Mage::helper('adminhtml')->__('Log in to Admin Panel') ?></h2>
                <div id="messages">
                    <?php echo $this->getMessagesBlock()->toHtml() ?>
                </div>
                <div class="input-box input-left"><label for="username"><?php echo Mage::helper('adminhtml')->__('User Name:') ?></label><br/>
                    <input type="text" id="username" name="login[username]" value="" class="required-entry input-text" /></div>
                <div class="input-box input-right"><label for="login"><?php echo Mage::helper('adminhtml')->__('Password:') ?></label><br />
                    <!-- This is a dummy hidden field to trick firefox from auto filling the password -->
                    <input type="text" class="input-text no-display" name="dummy" id="dummy" />
                    <input type="password" id="login" name="login[password]" class="required-entry input-text" value="" /></div>
                <?php echo $this->getChildHtml('form.additional.info'); ?>
                <div class="clear"></div>
                <div class="form-buttons">
                    <a class="left" href="<?php echo Mage::helper('adminhtml')->getUrl('adminhtml/index/forgotpassword', array('_nosecret' => true)) ?>"><?php echo Mage::helper('adminhtml')->__('Forgot your password?') ?></a>
                    <input type="submit" class="form-button" value="<?php echo Mage::helper('core')->quoteEscape(Mage::helper('adminhtml')->__('Login')) ?>" title="<?php echo Mage::helper('core')->quoteEscape(Mage::helper('adminhtml')->__('Login')) ?>" /></div>
            </div>
            <p class="legal">"BLAAAA" <?php echo Mage::helper('adminhtml')->__('Yanick', date('Y')) ?></p>
        </form>
        <div class="bottom"></div>
        <script type="text/javascript">
            var loginForm = new varienForm('loginForm');
        </script>
    </div>
</div>
</body>
</html>

