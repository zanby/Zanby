<?php
Warecorp::addTranslation('/modules/groups/contentblocks/action.themeSave.php.xml');

$objResponse = new xajaxResponse();
//$theme = new Warecorp_Theme();  
$theme = new Warecorp_CO_Theme_Item();
//$_arr = Zend_Json::decode($themeString);
//print_r($themeString); die;
foreach ($themeString as $_k=>$_v){
    if ($_k == 'backgroundUrl') {
        $_v = parse_url($_v,PHP_URL_PATH);
    }
    $theme->$_k = $_v;
}

$theme->saveThemeToDB($this->currentGroup, $clear); 
$objResponse->showAjaxAlert(Warecorp::t('Changes saved')); 
