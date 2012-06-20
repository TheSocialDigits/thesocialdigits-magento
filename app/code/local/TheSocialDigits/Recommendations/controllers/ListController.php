<?php

class TheSocialDigits_Recommendations_ListController extends
Mage_Core_Controller_Front_Action {
  public function indexAction(){
    $params = $this->getRequest()->getParams();
    if(isset($params['products']) && $params['products'] <> ''){
      $items = array();
      foreach($params['products'] as $item_id){
        $product = Mage::getModel('catalog/product')
          ->load($item_id);
        $item_data = $product->getData();
        $item_data['id'] = $item_data['entity_id'];
        $item_data['thumbnail_url'] = $product->getThumbnailUrl();
        $items[] = $item_data;
      }
      echo json_encode($items);
    } else {
      echo 'Please supply a list of item ids';
    }
  }
}
