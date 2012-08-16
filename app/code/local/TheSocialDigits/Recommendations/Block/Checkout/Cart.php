<?php

class TheSocialDigits_Recommendations_Block_Checkout_Cart extends
TheSocialDigits_Recommendations_Block_Recommendations {
  public function getProductIdJson(){
    $product_id = $this->getRequest()->getParam('product');
    return
      json_encode(array($product_id), JSON_NUMERIC_CHECK);
  }
}
