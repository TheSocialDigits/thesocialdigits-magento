<?php

class TheSocialDigits_Recommendations_Helper_Data extends
Mage_Core_Helper_Abstract {
  
  public function getProducts(){
    $retval = array();

    $language = Mage::getStoreConfig('recommendations_options/settings/language');
    if(!$language)
      $language = 'english'; //Should be fixed 

    //Retreive all enabled products
    $products = Mage::getModel('catalog/product')
      ->getCollection()
      ->addAttributeToSelect('*')
      ->addAttributeToFilter('status',1) //maybe is_salable in stead?
      ->getItems();

    foreach($products as $product){

      if($product['type_id'] == 'simple') {  
          $configurable_product_model_obj =
            Mage::getModel('catalog/product_type_configurable');  
          $parents =
            $configurable_product_model_obj->getParentIdsByChild($product['entity_id']);
          if(sizeof($parents))
            continue;
      }
 
      $product_data = array();
      $product_data['id'] = $product['entity_id'];
      $product_data['name'] = array(
        $language => $product['name'],
      );
      $product_data['description'] = array(
        $language => $product['description'],
      );

      $product_data['price'] = (float) $product['price'];
      $product_data['rating'] = null;
      $product_categories = $product->getCategoryIds();
      $product_data['categories'] = $product_categories;

      //get additional product attributes
      $product_attributes = $product->getAttributes();
      $product_data['weight'] = $product->getData('weight');
      $product_data['color'] = $product->getData('color');
      $product_data['SKU'] = $product->getData('sku');

      //add the product to the array of products
      $retval[] = $product_data;
    }
    return $retval;
  }

  public function getCategories(){
    $retval = array();

    $language = Mage::getStoreConfig('recommendations_options/settings/language');
    if(!$language)
      $language = 'english'; //Should be fixed 

      //Retrieve all the categories
    $categories = Mage::getModel('catalog/category')
      ->getCollection()
      ->getItems();
    
    foreach($categories as $category){
      $category_data = array();
      $category->load();
      $category_data['id'] = $category->getId();
      $category_name = $category->getName();
      if(is_null($category_name))
        continue;
      $category_data['name'] = array(
        $language => $category_name,
      );
      $category_data['subcategories'] = array();
      $sub_categories = $category->getChildrenCategories();
      foreach($sub_categories as $sub_category){
        $category_data['subcategories'][] = $sub_category->getId();
      }
      $retval[] = $category_data;
    }
    return $retval;
  }

  public function getSales(){
    $retval = array();
    //Retrieve all the sales
    $orders = Mage::getModel('sales/order')
      ->getCollection()
      ->getItems();
    foreach($orders as $order){
      $order_items = $order->getAllItems();
      foreach($order_items as $order_item){
        $sale = array();
        $sale['product'] = $order_item->getProductId();
        $sale['sale'] = $order->getId();
        $sale['customer'] = $order->getCustomerId();
        $sale['time'] = strtotime($order->getCreatedAt());
        $retval[] = $sale;
      }
    }
    return $retval;
  }
}
