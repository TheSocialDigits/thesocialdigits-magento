<?php
class TheSocialDigits_Recommendations_Block_Recommendations extends
Mage_Core_Block_Template {

  private $_id, $_action;

  private $_api_arguments = array(
    'products' => array(),
    'limit' => 20,
//    'visitor' => null,
    'exclude' => array(),
//    'filter' => "",
  );

  private $_carousel_arguments = array(
    'visible' => 3,
    'step' => 1,
    'width' => 130,
    'height' => 140,
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

  public function _prepareLayout(){
    $this->addJs('jquery-1.7.2.min.js');
    $this->addJs('jquery.ui.core.min.js');
    $this->addJs('jquery.ui.widget.min.js');
    $this->addJs('jquery.ui.rcarousel.min.js');
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

  public function getHtmlTemplate($tpl){
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

  protected function _validateCarouselArgument($argument, $value){
    return true;
  }

  public function setCarouselArgument($argument, $value){
    if($this->_validateCarouselArgument($argument, $value)){
      $this->_carousel_arguments[$argument] = $value;
      return true;
    }
    return false;
  }

  public function getCarouselArgument($argument, $default_value=NULL){
    return isset($this->_carousel_arguments[$argument]) ?
      $this->_carousel_arguments[$argument] : $default_value;
  }

  protected function _prepareCarouselArguments(){
    $arguments = array();
    foreach($this->_carousel_arguments as $arg => $val){
      switch($arg){
        case 'step':
          if($val > $this->getCarouselArgument('visible',$val))
            $arguments['step'] = $this->getCarouselArgument('visible',$val);
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

  protected function _getCarouselArguments(){
    return $this->_prepareCarouselArguments();
  }

  public function getCarouselArgumentsJson(){
    return json_encode($this->_getCarouselArguments(),JSON_NUMERIC_CHECK);
  }

  public function getPrevButton(){
    $output = '';
    $base_class = 'recommendations-' .
      $this->getCarouselArgument('orientation');
    if($this->getCarouselArgument('navigation_prev')){
    $output .= '<a href id="' . $this->getElementId() . '-prev"
      class="recommendations-' . $this->getCarouselArgument('orientation') .
      '-prev-btn"><img src="'.
      $this->getSkinUrl('images/tango/prev-vertical.gif') . '"
      class="recommendations-navigation-img" alt="' .
      $this->__('Prev') . '" /></a>';
    }
    return $output;
  }

  public function getNextButton(){
    $output = '';
    $base_class = 'recommendations-' .
      $this->getCarouselArgument('orientation');
    if($this->getCarouselArgument('navigation_next')){
    $output .= '<a href id="' . $this->getElementId() . '-next"
      class="recommendations-' . $this->getCarouselArgument('orientation') .
      '-next-btn"><img src="'.
      $this->getSkinUrl('images/tango/next-vertical.gif') . '"
      class="recommendations-navigation-img" alt="' .
      $this->__('Next') . '" /></a>';
    }
    return $output;
  }

}
