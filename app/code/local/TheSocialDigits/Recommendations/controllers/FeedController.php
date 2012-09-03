<?php
class TheSocialDigits_Recommendations_FeedController extends
Mage_Core_Controller_Front_Action {
  public function indexAction(){
    $helper = Mage::helper('recommendations/data');

    $data = array(
      'products' => $helper->getProducts(),
      'categories' => $helper->getCategories(),
      'sales' => $helper->getSales(),
    );

    // Print out the jsoon encoded object
    $json = json_encode($data, JSON_NUMERIC_CHECK);
    header('Content-length: ' . strlen($json));
    echo $json;
  }
}
