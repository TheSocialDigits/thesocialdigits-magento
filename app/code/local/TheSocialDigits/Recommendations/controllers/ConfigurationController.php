<?php

class TheSocialDigits_Recommendations_ConfigurationController extends
Mage_Core_Controller_Front_Action {
  public function indexAction(){
    $settings = array(
      'key' =>
      Mage::getStoreConfig('recommendations_options/settings/api_key'),
      'ga_tracking' =>
      Mage::getStoreConfig('recommendations_options/settings/ga_tracking')
      or NULL,
      'datasource'=> '__FUNC1__',
    );

    $json = json_encode($settings);
    //Insert the function
    $json = str_replace('"__FUNC1__"',"function(products,callback){var url
    = '/index.php/recommendations/list'; var data = {'products':
    products}; $.getJSON(url,data,callback);}",$json);

    echo $json;
  }
}
