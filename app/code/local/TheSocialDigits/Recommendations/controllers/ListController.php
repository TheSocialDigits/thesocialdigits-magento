<?php

class TheSocialDigits_Recommendations_ListController extends
Mage_Core_Controller_Front_Action {
  public function indexAction(){
    $params = $this->getRequest()->getParams();
    if(isset($params['products']) && $params['products'] <> ''){
      $item_ids = explode(',',$params['products']);
      $items = array();
      foreach($item_ids as $item_id){
        $product = Mage::getModel('catalog/product')
          ->load($item_id);
        $items[] = $product->getData();
      }
      echo json_encode($items);
    } else {
      echo 'Please supply a list of item ids';
    }
  }
}
