<?php
class TheSocialDigits_Recommendations_FeedController extends
Mage_Core_Controller_Front_Action {
  public function indexAction(){
    $data = array(
      'products' => array(),
      'categories' => array(),
      'sales' => array(),
    );
 
    $language = 'english'; //this is used througout the module

    //All of the following shit should be in a helper

    //Retreive all enabled products
    $products = Mage::getModel('catalog/product')
      ->getCollection()
      ->addAttributeToSelect('*')
      ->addAttributeToFilter('status',1) //maybe is_salable in stead?
      ->getItems();

    foreach($products as $product){
      $product_data = array();
      $product_data['id'] = $product['entity_id'];
      $product_data['name'] = array(
        $language => $product['name'],
      );
      $product_data['description'] = array(
        $language => $product['description'],
      );

      $product_data['price'] = $product['price'];
      $product_data['rating'] = null;
      $product_categories = $product->getCategoryIds();
      $product_data['category'] = sizeof($product_categories) ?
      $product_categories[0] : 0;
      
      //Retrieve the category data
/*      foreach($product_categories as $category_id){
        $category =
        Mage::getModel('catalog/category')->load($category_id);
        $category_data = array();
        $category_data['id'] = $categoryId;
        $category_data['name'] = array(
          $language => $category->getName(),
        );
        $category_data['subcategories'] = array();
        $sub_categories = $category->getChildrenCategories();
        foreach($sub_categories as $sub_category){
          $category_data['subcategories'][] = $sub_category->getId();
        }
        $product_data['category'][] = $category_data;
      }*/
      //Product definition is finished, add to data array
      $data['products'][] = $product_data;
//      $data['products'][] = $product->getData(); //for safekeeping
    }

    //Retrieve all the categories
    $categories = Mage::getModel('catalog/category')
      ->getCollection()
      ->getItems();
    
    foreach($categories as $category){
      $category_data = array();
      $category->load();
      $category_data['id'] = $category->getId();
      $category_data['name'] = array(
        $language => $category->getName(),
      );
      $category_data['subcategories'] = array();
      $sub_categories = $category->getChildrenCategories();
      foreach($sub_categories as $sub_category){
        $category_data['subcategories'][] = $sub_category->getId();
      }
      $data['categories'][] = $category_data;
    }

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
        $sale['timestamp'] = strtotime($order->getCreatedAt());
        $data['sales'][] = $sale;
      }
    }
    // Print out the jsoon encoded object
    echo json_encode($data);
  }
}
