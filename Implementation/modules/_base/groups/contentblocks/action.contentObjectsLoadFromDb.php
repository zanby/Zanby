<?php
Warecorp::addTranslation('/modules/groups/contentblocks/action.contentObjectsLoadFromDb.php.xml');

$objResponse = new xajaxResponse();

$Script = "";

$result = Warecorp_CO_Content::loadFromDB($this->currentGroup->getId(), $this->currentGroup->EntityTypeId);
//if ( empty($result) || $result == "a:0:{}" ) {
if ( empty($result) ) {
   if ($this->currentGroup->isCongressionalDistrict()) {
        $cfgCO = Warecorp_Config_Loader::getInstance()->getAppConfig('co/cfg.contentblocks.xml')->{'district_group'};
   } else {
        $cfgCO = Warecorp_Config_Loader::getInstance()->getAppConfig('co/cfg.contentblocks.xml')->{'group'};
   }
   $result = $cfgCO->default_co_set;
   
   if ($this->currentGroup->getGroupType() == "family") {
      $cfgCO = Warecorp_Config_Loader::getInstance()->getAppConfig('co/cfg.contentblocks.xml')->{'family'};
      $result = $cfgCO->default_co_set;
     
   //FIXME need to implement using factory
   } elseif (HTTP_CONTEXT == 'at' && $this->currentGroup->getCategory() && $this->currentGroup->getCategory()->id == 49) {
        $result = $cfgCO->default_co_set_49;
    }
}
if ( !empty($result) ) {

    //$data = unserialize(str_replace("\n",'',$_v));
    if ( !empty($result) ){
        $data = unserialize($result);

        usort($data, "Warecorp_DDPages::ddpages_sort_items");
        //print_r($data);die;
        if ( sizeof($data) != 0 ) {
            foreach ( $data as $item ) {
                $Code = "var CreateItem = new Array();";
                Warecorp_CO_Content::phpArr2jsArr($Code, "CreateItem", $item);
                $Script .= $Code;
                $Script .= "DDCBlockFactory.load(CreateItem);";
            }
        }
    }
}
$objResponse->addScript($Script);
