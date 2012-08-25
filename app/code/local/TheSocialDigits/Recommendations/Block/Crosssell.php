<?php

require_once "TheSocialDigits/Recommendations/Block/Recommendations.php";

class TheSocialDigits_Recommendations_Block_Crosssell extends
TheSocialDigits_Recommendations_Block_Recommendations {

  public function _construct(){
    parent::_construct();

    $this->setCarouselArguments(array(
      'width' => $this->getStoreConfig('width',250,'0'),
      'height' => $this->getStoreConfig('height',80,'0'),
      'visible' => $this->getStoreConfig('visible',4,'0'),
    ));
  }

  public function getType(){
    return 'crosssell';
  }
}
