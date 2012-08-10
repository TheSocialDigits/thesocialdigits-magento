<?php
class TheSocialDigits_Recommendations_Block_Recommendations extends
Mage_Core_Block_Template {

  private $_arguments = array('limit' => 3);

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

  public function setElementId($id){
    if(is_string($id)){
      $this->_id = $id;
      return true;
    }
    return false;
  }

  public function getElementId(){
    return isset($this->_id) ? $this->_id : 'tsd-products';
  }

  public function setAction($action){
    if(is_string($action)){
      $this->_action = $action;
      return true;
    }
    return false;
  }

  public function getAction(){
    return isset($this->_action) ? $this->_action : 'related';
  }

  public function setHtmlTemplateId($id){
    $this->_htmlTemplateId = $id;
  }

  public function getHtmlTemplateId(){
    return isset($this->_htmlTemplateId) ? $this->_htmlTemplateId :
    'productsTemplate';
  }

  public function setArgument($argument,$value){
    $this->_arguments[$argument] = $value;
    return true;
  }

  public function getArgument($argument){
    return isset($this->_arguments[$argument]) ?
    $this->_arguments[$argument] : NULL;
  }

  public function getArguments(){
    return $this->_arguments;
  }

  public function getArgumentsJson(){
    return json_encode($this->_arguments,JSON_NUMERIC_CHECK);
  }
}
