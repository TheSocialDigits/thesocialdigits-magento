<?php

require_once 'Mage/Catalog/Block/Layer/State.php';

class TheSocialDigits_Recommendations_Block_Catalog_Layer_State extends
Mage_Catalog_Block_Layer_State {
    public function getClearUrl()
    {   
        $filterState = array();
        foreach ($this->getActiveFilters() as $item) {
            $filterState[$item->getFilter()->getRequestVar()] = $item->getFilter()->getCleanValue();
        }   
        $params['_current']     = true;
        $params['_use_rewrite'] = true;
        $params['_query']       = $filterState;
        $params['_escape']      = true;
        return Mage::getUrl('catalogsearch/result', $params);
    }   

}
