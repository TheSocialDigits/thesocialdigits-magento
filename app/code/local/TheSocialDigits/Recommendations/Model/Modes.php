<?php 

class TheSocialDigits_Recommendations_Model_Modes {
  public function toOptionArray(){
    return array(
      array('value' => 'grid', 'label' => 'Grid'),
      array('value' => 'list', 'label' => 'List'),
    );
  }
}
