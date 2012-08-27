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
        if($product->getTypeId() == 'simple') {  
          $configurable_product_model_obj =
            Mage::getModel('catalog/product_type_configurable');  
          $parents =
            $configurable_product_model_obj->getParentIdsByChild($product->getId() );
        }
        $item_data = $product->getData();
        $item_data['id'] = $item_data['entity_id'];
        $item_data['thumbnail_url'] = $product->getThumbnailUrl();
        if(sizeof($parents))
          $item_data['product_url'] = Mage::getModel('catalog/product')
          ->load($parents[0])
          ->getUrlPath();
        else
          $item_data['product_url'] = $product->getUrlPath();
        $items[] = $item_data;

      }
      echo json_encode($items,JSON_NUMERIC_CHECK);
    } else {
      echo 'Please supply a list of item ids';
    }
  }
}
