<?php

require_once 'Mage/CatalogSearch/Model/Layer.php';

class TheSocialDigits_Recommendations_Model_CatalogSearch_Layer extends
Mage_CatalogSearch_Model_Layer {
  public function getProductCollection(){
    $collection = parent::getProductCollection();
    $items = Mage::app()->getRequest()->getParam('items',array());
//    print_r($items);
    if(is_array($items) && sizeof($items))
      $collection->addAttributeToFilter('entity_id',array('in'=>$items));
    return $collection;
  }
}
