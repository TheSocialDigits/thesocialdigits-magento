<?php

class TheSocialDigits_Recommendations_IndexController extends
Mage_Core_Controller_Front_Action {
  public function indexAction(){
    die('You\'ve come to the wrong place');
  }

  public function searchAction(){
    //produce a Layered navigation block and present it
    $this->loadLayout();
    $this->renderLayout();
  }

}
