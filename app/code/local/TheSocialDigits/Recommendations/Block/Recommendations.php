<?php
class TheSocialDigits_Recommendations_Block_Recommendations extends
Mage_Core_Block_Template {

  private $_api_arguments = array(
    'products' => array(),
    'limit' => 3,
//    'visitor' => null,
    'exclude' => array(),
//    'filter' => "",
  );

  private $_slider_arguments = array(
    'visible' => 1,
    'step' => 1,
    'width' => 100,
    'height' => 100,
    'speed' => 1000,
    'margin' => 0,
    'orientation' => 'horizontal',
    'auto_enabled' => TRUE,
    'auto_direction' => 'next',
    'auto_interval' => 5000,
    'startAtPage' => 0,
    'navigation_next' => false,
    'navigation_prev' => false,
  );

  private $_id;

  public function _prepareLayout(){
    $this->addJs('jquery-1.7.2.min.js');
    $this->addJs('jquery.ui.core.min.js');
    $this->addJs('jquery.ui.widget.min.js');
    $this->addJs('jquery.ui.rcarousel.min.js');
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

  /**
   * Get the product id of the current showing product
   */   
  public function getProductId(){
    $product = Mage::registry('current_product');
    if($product)
      return $product->getId();
    return null;
  }

  public function getProductIdJson(){
    $product_id = $this->getProductId();
    if(!is_null($product_id))
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

  protected function _validateApiArgument($argument, $value){
    return true;
  }

  public function setApiArgument($argument,$value){
    if($this->_validateApiArgument($argument,$value)){
      $this->_api_arguments[$argument] = $value;
      return true;
    }
    return false;
  }

  public function getApiArgument($argument,$default_value=NULL){
    return isset($this->_api_arguments[$argument]) ?
      $this->_api_arguments[$argument] : $default_value;
  }

  protected function _prepareApiArguments(){
    $arguments = array();
    foreach($this->_api_arguments as $arg => $val){
      switch($arg){
        case 'products':
          $product_id = $this->getProductId();
          if(!is_null($product_id)){
            $arguments['products'] = array($this->getProductId());
          } else {
            $arguments['products'] = array();
          }
        break;
        case 'exclude':
          $arguments['exclude'] = $this->getCartContents();
        break;
        default:
          $arguments[$arg] = $val;
      }
    }
    return $arguments;
  }

  protected function getApiArguments(){
    return $this->_prepareApiArguments();
  }

  public function getApiArgumentsJson(){
    return json_encode($this->getApiArguments(),JSON_NUMERIC_CHECK);
  }

  protected function _validateSliderArgument($argument, $value){
    return true;
  }

  public function setSliderArgument($argument, $value){
    if($this->_validateSliderArgument($argument, $value)){
      $this->_slider_arguments[$argument] = $value;
      return true;
    }
    return false;
  }

  public function getSliderArgument($argument, $default_value=NULL){
    return isset($this->_slider_arguments[$argument]) ?
      $this->_slider_arguments[$argument] : $default_value;
  }

  protected function _prepareSliderArguments(){
    $arguments = array();
    foreach($this->_slider_arguments as $arg => $val){
      switch($arg){
        case 'step':
          if($val > $this->getSliderArgument('visible',$val))
            $arguments['step'] = $this->getSliderArgument('visible',$val);
          else
            $arguments['step'] = $val;
        break;
        case 'auto_enabled':
          $arguments['auto']['enabled'] = $val;
        break;
        case 'auto_direction':
          $arguments['auto']['direction'] = $val;
        break;
        case 'auto_interval':
          $arguments['auto']['interval'] = $val;
        break;
        case 'navigation_prev':
          if($val)
            $arguments['navigation']['prev'] = '#' . $this->getElementId() .
              '-prev';
        break;
        case 'navigation_next':
          if($val)
            $arguments['navigation']['next'] = '#' . $this->getElementId() .
              '-next';
        break;
        default:
          $arguments[$arg] = $val;
      }
    }
    return $arguments;
  }

  protected function _getSliderArguments(){
    return $this->_prepareSliderArguments();
  }

  public function getSliderArgumentsJson(){
    return json_encode($this->_getSliderArguments(),JSON_NUMERIC_CHECK);
  }
}
