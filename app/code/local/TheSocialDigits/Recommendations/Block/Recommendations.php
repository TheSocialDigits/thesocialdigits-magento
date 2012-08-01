<?php
class TheSocialDigits_Recommendations_Block_Recommendations extends
Mage_Core_Block_Template {
  public function getApiKey(){
    //this should be a setting
    return
    Mage::getStoreConfig('recommendations_options/settings/api_key');
  }

  public function getTrackingCategory(){
    return Mage::getStoreConfig('recommendations_options/settings/ga_tracking');
  }

  public function addJs($path){
    $head = $this->getLayout()->getBlock('head');
    return $head->addJs($path);
  }

  public function getHtmlTemplate(){
    return
    Mage::getStoreConfig('recommendations_options/settings/template');
  }

  public function getProductId(){
    $product = Mage::registry('current_product');
    if($product)
      return $product->getId();
    return 'null';
  }
}
