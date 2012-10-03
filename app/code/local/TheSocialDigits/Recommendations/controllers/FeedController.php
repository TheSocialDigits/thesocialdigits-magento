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
    $json = json_encode($data, (strnatcmp(phpversion(),'5.3.3') >= 0 ?
    JSON_NUMERIC_CHECK : 0));
    header('Content-length: ' . strlen($json));
    echo $json;
  }
}
