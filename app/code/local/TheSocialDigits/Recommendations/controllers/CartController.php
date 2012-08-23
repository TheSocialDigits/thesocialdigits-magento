<?php
require_once 'Mage/Checkout/controllers/CartController.php';
class TheSocialDigits_Recommendations_CartController extends
Mage_Checkout_CartController {

    /**
     * Add product to shopping cart action
     */

    protected function rewriteUrl(){
      $this->getRequest()->setParam('return_url',
        Mage::getUrl('checkout/cart/moresell',array(
          '_query'=>array(
            'product' => $this->getRequest()->getParam('product'),
            'qty' => $this->getRequest()->getParam('qty'),
            'return_url' => $this->_getRefererUrl(),
          )
        ))
      );
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

    public function moresellAction(){
      $product = $this->_initProduct();
      if($product) {
        $message = $this->__("%s was added to your shopping cart.",
          Mage::helper('core')->escapeHtml($product->getName()));
        Mage::getSingleton('core/session')->addSuccess($message);
      }
      $this->loadLayout();
      $this->renderLayout();
    }

}
