<?php

require_once 'TheSocialDigits/Recommendations/Block/Recommendations.php';

class TheSocialDigits_Recommendations_Block_Related extends
TheSocialDigits_Recommendations_Block_Recommendations {
  public function _construct(){
    $this->setCarouselArguments(array(
      'visible' => $this->getStoreConfig('visible',3,'0'),
      'width' => $this->getStoreConfig('width',180,'0'),
      'height' => $this->getStoreConfig('height',150,'0'),
    ));
  }

  public function getType(){
    return 'related';
  }
}
