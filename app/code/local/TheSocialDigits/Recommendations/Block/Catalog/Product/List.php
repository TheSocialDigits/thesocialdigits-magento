<?php

ini_set('display_errors',1);
error_reporting(E_ALL ^ E_NOTICE);

require_once 'Mage/Catalog/Block/Product/List.php';

class TheSocialDigits_Recommendations_Block_Catalog_Product_List extends
  Mage_Catalog_Block_Product_List {

  private $_id, $_action;

  private $_api_arguments = array(
    'limit' => 20,
//    'visitor' => null,
    'exclude' => array(),
    'filter' => '',
  );

  private $_ui_arguments = array(
  );

  public function _construct(){
    $this->setUiArguments(array(
      'columns' => $this->getStoreConfig('columns',3,'0'),
      'mode' => $this->getStoreConfig('mode','grid',''),
      'width' => $this->getStoreConfig('width',140,'0'),
      'height' => $this->getStoreConfig('height',150,'0'),
      'margin' => $this->getStoreConfig('margin',5,'0'),
    ));
  }

  public function getStoreConfig($variable, $default_value=null, $zero_value=false){
    $arg =
    implode('/',array('recommendations_options','search',$variable));
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

    $this->setApiArgument('query',$this->getRequest()->getParam('q',''));
    $this->setApiArgument('limit', $this->getRequest()->getParam('limit',10));
    $language =
      Mage::getStoreConfig('recommendations_options/settings/language');
    if(!$language)
      $language = 'english';

    $this->setApiArgument('language',$language);
  }

  public function addJs($path){
    $head = $this->getLayout()->getBlock('head');
    return $head->addJs($path);
  }

  public function getHtmlTemplate(){
    $mode = $this->getUiArgument('mode','grid');
    $tpl = Mage::getStoreConfig('recommendations_options/search/template_' .
    $mode);

    if($mode == 'grid'){
      $output = '<div class="tsd-grid">' . $tpl . '</div>';
    } else {
      $output = $tpl;
    }
    return $output;
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
    return isset($this->_action) ? $this->_action : 'search';
  }

  public function getFilter(){
    $params = $this->getRequest()->getParams();
    $filter = array();;
    $ignore = array('q','limit','p');
    foreach($params as $param => $value){
      if(!in_array($param,$ignore)){
        if($param == 'cat'){
          $filter[] = 'categories = ' . $value;
          continue;
        }
      $dashpos = strpos($value,'-');
      if($dashpos === false){
        //no dash in string
        $filter[] = $param . ' = "' . $value . '"';
      } else {
        if($dashpos == 0){
          //dash is first
          $filter[] = $param . ' >= ' . $value;
        } else {
          if($dashpos == strlen($value)){
            //dash is last
            $filter[] = $param . ' <= ' . $value;
          } else {
            //dash is in the middle
            $values = explode('-',$value);
            $filter[] = $param . ' > ' . $values[0];
            $filter[] = $param . ' < ' . $values[1];
          }
        }
      }
      }
    }
    return implode(' and ',$filter);
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
    $this->_prepareApiArgument($argument,
      $this->_api_arguments[$argument]) : $default_value;
  }

  protected function _prepareApiArgument($arg, $val){
    $retval = $val;

    switch($arg){
        case 'limit':
          $limit = ((int) $val) - fmod($val, $this->getUiArgument('columns',4));
          $retval = $limit;
        break;
        case 'products':
          $product_id = $this->getProductId();
          if(!is_null($product_id)){
            $retval = array($this->getProductId());
          } else {
            $retval = array();
          }
        break;
        case 'exclude':
          $retval = $this->getCartContents();
        break;
        case 'filter':
          $retval = $this->getFilter();
        break;
      }
      return $retval;
  }

  protected function _prepareApiArguments(){
    $arguments = array();
    foreach($this->_api_arguments as $arg => $val){
      $value = $this->getApiArgument($arg);
      if($value)
        $arguments[$arg] = $value;
    }
    return $arguments;
  }

  protected function getApiArguments(){
    return $this->_prepareApiArguments();
  }

  public function getApiArgumentsJson(){
    return json_encode($this->getApiArguments(),JSON_NUMERIC_CHECK);
  }

  public function getToolbarHtml(){
    $output ="<div class=\"pager\" style=\"clear:both;\">
    <p class=\"amount\"></p>
    <div class=\"limiter\">
      <label>Show</label>

      <select onchange=\"setLocation(this.value)\">";
      
          $params = $this->getRequest()->getParams();
          $current_limit = $this->getRequest()->getParam('limit',10);

          for($i = 0; $i < 5; $i++){
            if($this->getStoreConfig('mode','grid','') == 'grid'){
              $limit = ($i*2+$this->getUiArgument('columns',3))*$this->getUiArgument('columns',3);
            } else {
              print 'wth' . $this->getStoreConfig('mode','grid','');
              $limit = ($i+1)*5;
            }
            $params['limit'] = $limit;
            $option = '<option ';
            if($current_limit == $limit)
              $option .= 'selected ';
            $option .= 'value="' .
            Mage::getUrl('catalogsearch/result/index',array('_query' =>
            $params)) . '">' . $limit . '</option>';
            $output .= $option;
          }
    $output .="
      </select>
      per page
    </div>
    <div class=\"pages\">
      <strong>Page:</strong>
      <ol></ol>
    </div>
  </div>";

    return $output;
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
