<?php
error_reporting(E_ALL ^E_NOTICE);
ini_set('display_errors',1);

class TheSocialDigits_Recommendations_Block_Moresell extends
Mage_Core_Block_Template {
  
  private $_id, $_action, $_columns = 4;

  private $_api_arguments = array(
    'products' => array(),
    'limit' => 20,
//    'visitor' => null,
    'exclude' => array(),
//    'filter' => "",
  );

  public function _construct(){
    $this->setApiArguments(array(
      'limit' => $this->getStoreConfig('limit',20,'0'),
      'filter' => $this->getStoreConfig('filter'),
    ));
    $this->setUiArguments(array(
      'columns' => $this->getStoreConfig('columns',4,'0'),
      'width' => $this->getStoreConfig('width',130,'0'),
      'height' => $this->getStoreConfig('height',150,'0'),
      'margin' => $this->getStoreConfig('margin',5,'0'),
    ));
  }

  public function getType(){
    return 'moresell';
  }

  public function getStoreConfig($variable, $default_value=null, $zero_value=false){
    $arg =
    implode('/',array('recommendations_options',$this->getType(),$variable));
    $value =
    Mage::getStoreConfig($arg);
    return $value === $zero_value ? $default_value : $value;
  }

  public function getColumnWidth(){
    return (100 / (int) $this->getUiArgument('columns',4)) . '%';
  }

  public function _prepareLayout(){
    $this->addJs('jquery-1.7.2.min.js');
    $this->addJs('thesocialdigits-js/json2.min.js');
    $this->addJs('thesocialdigits-js/jquery.thesocialdigits.min.js');
    $this->addJs('thesocialdigits-js/config.thesocialdigits.js');

    $this->addCss('css/thesocialdigits-styles.css');
  }

  public function addJs($path){
    $head = $this->getLayout()->getBlock('head');
    return $head->addJs($path);
  }

  public function addCss($path){
    $head = $this->getLayout()->getBlock('head');
    return $head->addCss($path);
  }

  public function getHtmlTemplate(){
    $template = '<div><a href="{product_url}"><img src="{thumbnail_url}" /><br
    />{name}<br />{price} ,-</a></div>';
    return $this->getStoreConfig('template',$template,'');
  }

  /**
   * Get the product id of the current showing product
   */   
  public function getProductId(){
    return $this->getRequest()->getParam('product',null);
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

  public function setApiArguments($args){
    if(is_array($args)){
      $result = array();
      foreach($args as $argument => $value){
        $result[$argument] = $this->setApiArgument($argument,$value);
      }
      return $result;
    }
    return null;
  }


  public function getApiArgument($argument,$default_value=NULL){
    return isset($this->_api_arguments[$argument]) ?
      $this->_api_arguments[$argument] : $default_value;
  }

  protected function _prepareApiArguments(){
    $arguments = array();
    foreach($this->_api_arguments as $arg => $val){
      switch($arg){
        case 'limit':
          $limit = ((int) $val) - fmod($val, $this->getUiArgument('columns',4));
          $arguments['limit'] = $limit;
        break;
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
        case 'filter':
          if(!$val)
            unset($arguments['filter']);
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

  protected function _validateUiArgument($argument, $value){
    return true;
  }

  public function setUiArgument($argument, $value){
    if($this->_validateUiArgument($argument, $value)){
      $this->_ui_arguments[$argument] = $value;
      return true;
    }
    return false;
  }

  public function setUiArguments($args){
    if(is_array($args)){
      $result = array();
      foreach($args as $argument => $value){
        $result[$argument] = $this->setUiArgument($argument,$value);
      }
      return $result;
    }
    return null;
  }

  public function getUiArgument($argument, $default_value=NULL){
    return isset($this->_ui_arguments[$argument]) ?
      $this->_ui_arguments[$argument] : $default_value;
  }

  protected function _prepareUiArguments(){
    $arguments = array();
    foreach($this->_ui_arguments as $arg => $val){
      switch($arg){
        default:
          $arguments[$arg] = $val;
      }
    }
    return $arguments;
  }

  protected function _getUiArguments(){
    return $this->_prepareUiArguments();
  }

  public function getUiArgumentsJson(){
    return json_encode($this->_getUiArguments(),JSON_NUMERIC_CHECK);
  }
}
