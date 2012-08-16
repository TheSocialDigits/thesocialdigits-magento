<?php
class TheSocialDigits_Recommendations_Block_Recommendations extends
Mage_Core_Block_Template {

  private $_arguments = array('limit' => 3);

  public function _prepareLayout(){
    $this->addJs('jquery-1.7.2.min.js');
    $this->addJs('thesocialdigits-js/json2.min.js');
    $this->addJs('thesocialdigits-js/jquery.thesocialdigits.min.js');
    $this->addJs('thesocialdigits-js/config.thesocialdigits.js');
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
    return null;
  }

  public function getProductIdJson(){
    $product_id = $this->getProductId();
    if(!is_null)
      return json_encode(array($product_id),JSON_NUMERIC_CHECK);
    return '[]';
  }

  public function getCartContents(){
    $contents = array();
    $quote = Mage::helper('checkout/cart')->getQuote();
    foreach ($quote->getAllItems() as $item) {
      $contents[] =
        Mage::getModel('catalog/product')->getIdBySku($item->getSku());
    }
    return $contents;
  }

  public function getCartContentsJson(){
    return json_encode($this->getCartContents(), JSON_NUMERIC_CHECK);
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
    return json_encode($this->getArguments(),JSON_NUMERIC_CHECK);
  }
}
