<?php
require_once 'Mage/Checkout/controllers/CartController.php';
class TheSocialDigits_Recommendations_CartController extends
Mage_Checkout_CartController {

    /**
     * Add product to shopping cart action
     */

    protected function rewriteUrl(){
      $this->getRequest()->setParam('return_url',Mage::getUrl('checkout/cart/added'));
    }
    public function addAction()
    {
      $this->rewriteUrl();
      parent::addAction();
    }

    public function addgroupAction()
    {
      $this->rewriteUrl();
      parent::addgroupAction();
    }

    public function addedAction(){
      $messages = Mage::getSingleton('checkout/session')->getMessages(true);
      $messages = $this->_getSession()->getMessages(true);
      $this->loadLayout();
      $this->getLayout()->getMessagesBlock()->addMessages($messages);
      $this->_initLayoutMessages('checkout/session');
      $this->_initLayoutMessages('catalog/session');
      $this->renderLayout();
    }

}
