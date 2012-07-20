<?php

class TheSocialDigits_Recommendations_Block_Checkout_Onepage_Success extends
Mage_Core_Block_Template {

  /**
   * Make the order id available so we can send it
   */
  public function getOrderId() {
    $orderId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
    return $orderId;
  }

  /**
   * Load the entire order
   */

  private function getOrder() {
    $order = Mage::getModel('sales/order')->loadByIncrementId($this->getOrderId());
    return $order;
  }

  /**
   * Get the customer id for the order
   */
  public function getCustomerId(){
    $order_data = $this->getOrder();
   
    $customerId = $order_data->getCustomerId();
    if(!$customerId) {
      //The user was not logged in, return a custom made id
      return $this->getOrderId() . strtotime($order->getCreatedAt());
    }
    return $customerId;
  }

  /**
   * Get the product ids in the order
   */
  public function getProducts(){
    $ids = array();
    $products = $this->getOrder()->getAllItems();
    foreach($products as $product) {
      $ids[] = $product->getProductId();
    }
    return $ids;
  }

  public function getJson(){
    $json = array(
      'customer' => $this->getCustomerId(),
      'sale' => $this->getOrderId(),
      'products' => $this->getProducts(),
    );
    return json_encode($json);
  }

  /**
   * Action to import javascript files
   */
  public function addJs($path){
    $head = $this->getLayout()->getBlock('head');
    return $head->addJs($path);
  }

  public function getApiKey(){
    //this should be a setting
    return
    Mage::getStoreConfig('recommendations_options/settings/api_key');
  }

}
