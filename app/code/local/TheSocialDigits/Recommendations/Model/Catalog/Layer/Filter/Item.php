<?php 

require_once 'Mage/Catalog/Model/Layer/Filter/Item.php';

class TheSocialDigits_Recommendations_Model_Catalog_Layer_Filter_Item extends
Mage_Catalog_Model_Layer_Filter_Item {
  public function getUrl(){
    $query = Mage::app()->getRequest()->getParams();
    unset($query['items']);
    $query[$this->getFilter()->getRequestVar()] = $this->getValue();
    
  return Mage::getUrl('catalogsearch/result',array(
    '_use_rewrite' => true, 
'_query'=>$query));
  }

  public function getRemoveUrl(){
    $query = Mage::app()->getRequest()->getParams();
    unset($query['items']);
    $query[$this->getFilter()->getRequestVar()] = $this->getFilter()->getResetValue();
        $params['_use_rewrite'] = true;
        $params['_query']       = $query;
        $params['_escape']      = true;
        return Mage::getUrl('catalogsearch/result', $params);
  }

    public function getClearLinkUrl()
    {
        $clearLinkText = $this->getFilter()->getClearLinkText();
        if (!$clearLinkText) {
            return false;
        }
    $query = Mage::app()->getRequest()->getParams();
    unset($query['items']);
    $query[$this->getFilter()->getRequestVar()] = null;
        $urlParams = array(
            '_use_rewrite' => true,
            '_query' => $query,
            '_escape' => true,
        );
        return Mage::getUrl('catalogsearch/result', $urlParams);
    }

}
