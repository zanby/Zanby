<?php

$objResponse = new xajaxResponse();

$Script = "";

$result = Warecorp_DDPages::loadFromDB($this->currentUser->getId(), $this->currentUser->EntityTypeId);

//if ( empty($result) || $result == "a:0:{}" ) {
if ( empty($result) ) {

    $cfgCO = Warecorp_Config_Loader::getInstance()->getAppConfig('co/cfg.contentblocks.xml')->{'user'};
    $result = $cfgCO->default_co_set;

    $_headline = $this->currentUser->getHeadline(); 
    if (!empty($_headline) )
    {
        $_ta = unserialize($result);
        $_ta[2]['Data']['Content'] = '<div align="center"><strong>'.$_headline.'</strong></div>';
        $result = serialize($_ta);
    }
    $_intro = $this->currentUser->getIntro(); 
    if (!empty($_intro) )
    {
        $_ta = unserialize($result);
        $_ta[4]['Data']['Content'] = '<div align="center"><strong>'.$_intro.'</strong></div>';
        $result = serialize($_ta);
    }
}

if ( !empty($result) ){
    $data = unserialize($result);
    usort($data, "Warecorp_CO_Content::ddpages_sort_items");
    if ( sizeof($data) != 0 ) {
        foreach ( $data as $item ) {
            $Code = "var CreateItem = new Array();";
            Warecorp_DDPages::phpArr2jsArr($Code, "CreateItem", $item);
            $Script .= $Code;
            $Script .= "DDCBlockFactory.load(CreateItem);";
        }
    }
}
$objResponse->addScript($Script);
