<?php

class TheSocialDigits_Recommendations_Block_Checkout_Multishipping_Success extends
Mage_Core_Block_Template {

  /**
   * Make the order id available so we can send it
   */
  public function getOrderIds() {
    $ids = Mage::getSingleton('core/session')->getOrderIds(true);
    if($ids && is_array($ids)) {
      return $ids;
    }
    return false;
  }

  /**
   * Load the entire order
   */

  private function getOrder($id) {
    $order = Mage::getModel('sales/order')->loadByIncrementId($id);
    return $order;
  }

  /**
   * Get the customer id for the order
   */
  public function getCustomerId($id){
    $order_data = $this->getOrder($id);
   
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
  public function getProducts($id){
    $ids = array();
    $products = $this->getOrder($id)->getAllItems();
    foreach($products as $product) {
      $ids[] = $product->getProductId();
    }
    return $ids;
  }

  public function getJson($id){
    $json = array(
      'customer' => $this->getCustomerId($id),
      'sale' => $this->getOrderId($id),
      'products' => $this->getProducts($id),
    );
    $options = 0;
    if (strnatcmp(phpversion(),'5.3.3') >= 0) 
    { 
        $options = JSON_NUMERIC_CHECK;
    } 
    return json_encode($json,$options);
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
