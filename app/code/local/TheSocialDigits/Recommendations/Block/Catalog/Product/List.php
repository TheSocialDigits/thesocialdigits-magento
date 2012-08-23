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
//    'filter' => "",
  );

  public function _prepareLayout(){
    $this->addJs('jquery-1.7.2.min.js');
    $this->addJs('thesocialdigits-js/json2.min.js');
    $this->addJs('thesocialdigits-js/jquery.thesocialdigits.min.js');
    $this->addJs('thesocialdigits-js/config.thesocialdigits.js');

    $this->setApiArgument('query',$this->getRequest()->getParam('q',''));
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
    return isset($this->_action) ? $this->_action : 'search';
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

  protected function _validateCarouselArgument($argument, $value){
    return true;
  }

  public function getToolbarHtml(){
    $output ="<div class=\"pager\">
    <p class=\"amount\"></p>
    <div class=\"limiter\">
      <label>Show</label>
      <select onchange=\"setLocation(this.value)\">";
      
          $params = $this->getRequest()->getParams();
          $current_limit = $this->getRequest()->getParam('limit',10);

          for($i = 0; $i < 5; $i++){
            $limit = ($i+1)*5;
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
}
