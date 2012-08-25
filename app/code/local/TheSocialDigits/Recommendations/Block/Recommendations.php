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
//    'visible' => 3,
//    'width' => 130,
//    'height' => 140,
//    'speed' => 1000,
//    'margin' => 0,
    'orientation' => 'horizontal',
//    'auto_enabled' => TRUE,
    'auto_direction' => 'next',
//    'auto_interval' => 5000,
    'startAtPage' => 0,
//    'navigation_prev' => false,
//    'navigation_next' => false,
  );

  public function _construct(){
    $this->setApiArguments(array(
      'limit' => $this->getStoreConfig('limit',20,'0'),
      'filter' => $this->getStoreConfig('filter'),
    ));
    $this->setCarouselArguments(array(
      'visible' => $this->getStoreConfig('visible',3,'0'),
      'step' =>
        $this->getStoreConfig('step',$this->getStoreConfig('visible',3,'0'),'0'),
      'width' => $this->getStoreConfig('width',130,'0'),
      'height' => $this->getStoreConfig('height',140,'0'),
      'speed' => $this->getStoreConfig('transition_speed',1000,'0'),
      'margin' => $this->getStoreConfig('margin',0,'0'),
      'auto_enabled' => $this->getStoreConfig('auto',false,'0'),
      'auto_interval' => $this->getStoreConfig('auto_interval',5000,'0'),
      'navigation_prev' => $this->getStoreConfig('navigation_prev',false,''),
      'navigation_next' => $this->getStoreConfig('navigation_next',false,''),
    ));
  }

  public function getType(){
    return 'related';
  }

  public function getStoreConfig($variable, $default_value=null, $zero_value=false){
    $arg =
    implode('/',array('recommendations_options',$this->getType(),$variable));
    $value =
    Mage::getStoreConfig($arg);
    return $value === $zero_value ? $default_value : $value;
  }

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

  public function getHtmlTemplate(){
    $template = '<div><a href="{product_url}"><img src="{thumbnail_url}" /><br
    />{name}<br />{price} ,-</a></div>';
    return $this->getStoreConfig('template',$template,'');
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

  public function setCarouselArguments($args){
    if(is_array($args)){
      $result = array();
      foreach($args as $argument => $value){
        $result[$argument] = $this->setCarouselArgument($argument,$value);
      }
      return $result;
    }
    return null;
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
          if($val)
            $arguments['auto']['enabled'] = $val;
        break;
        case 'auto_direction':
          if($this->getCarouselArgument('auto_enabled',false,'0') || 
            (isset($argument['auto']['enabled']) && 
              $arguments['auto']['enabled']))
            $arguments['auto']['direction'] = $val;
          
        break;
        case 'auto_interval':
          if($this->getCarouselArgument('auto_enabled',false,'0') || 
            (isset($argument['auto']['enabled']) && 
              $arguments['auto']['enabled']))
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
    if($this->getStoreConfig('navigation') &&
      $this->getStoreConfig('navigation_prev',false,'')){
      $orientation = $this->getCarouselArgument('orientation');
      $base_class = 'recommendations-' . $orientation;
      if($this->getCarouselArgument('navigation_prev')){
      $output .= '<a href="#" id="' . $this->getElementId() . '-prev"
        class="recommendations-' . $orientation .
        '-prev-btn"><img src="'.
        $this->getSkinUrl('images/tango/prev-' . $orientation . '.gif') . '"
        class="recommendations-navigation-img" alt="' .
        $this->__('Prev') . '" /></a>';
      }
    }
    return $output;
  }

  public function getNextButton(){
    $output = '';
    if($this->getStoreConfig('navigation') &&
      $this->getStoreConfig('navigation_prev',false,'')){
      $orientation = $this->getCarouselArgument('orientation');
      $base_class = 'recommendation-' . $orientation;
      if($this->getCarouselArgument('navigation_next')){
        $output .= '<a href="#" id="' . $this->getElementId() . '-next"
        class="recommendations-' . $orientation .
        '-next-btn"><img src="'.
        $this->getSkinUrl('images/tango/next-' . $orientation . '.gif') . '"
        class="recommendations-navigation-img" alt="' .
        $this->__('Next') . '" /></a>';
      }
    }
    return $output;
  }

}
