<?php

require_once 'Mage/CatalogSearch/Block/Layer.php';

class TheSocialDigits_Recommendations_Block_CatalogSearch_Layer extends
Mage_CatalogSearch_Block_Layer {
  public function getLayer(){
    return Mage::getSingleton('recommendations/catalogsearch_layer');
  }
}
