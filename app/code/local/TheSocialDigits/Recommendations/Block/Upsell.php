<?php

require_once 'TheSocialDigits/Recommendations/Block/Recommendations.php';

class TheSocialDigits_Recommendations_Block_Upsell extends
TheSocialDigits_Recommendations_Block_Recommendations {

  public function _construct(){
    parent::_construct();
    $this->setCarouselArguments(array(
      'visible' => $this->getStoreConfig('visible',4,'0'),
      'width' => $this->getStoreConfig('width',115,'0'),
      'height' => $this->getStoreConfig('height',150,'0'),
      'speed' => $this->getStoreConfig('transition_speed',2000,'0'),
    ));
  }

  public function getType(){
    return 'upsell';
  }
}
