<?php
Warecorp::addTranslation('/modules/groups/contentblocks/action.removeBCKGImage.php.xml');

$objResponse = new xajaxResponse();


$_tmp = explode('/',$path);
if (!is_array($_tmp)) $_tmp = array();
if (count($_tmp)>0 && 
    file_exists('./upload/theme_background_images/'.$_tmp[count($_tmp)-1]) && 
    is_file('./upload/theme_background_images/'.$_tmp[count($_tmp)-1]) &&
    substr($_tmp[count($_tmp)-1],0,strpos($_tmp[count($_tmp)-1],'_g'))==$this->currentGroup->getId()   
   ){
    @unlink('./upload/theme_background_images/'.$_tmp[count($_tmp)-1]);
    @unlink(str_replace('_orig.','_medium.','./upload/theme_background_images/'.$_tmp[count($_tmp)-1]));
    @unlink(str_replace('_orig.','_small.','./upload/theme_background_images/'.$_tmp[count($_tmp)-1]));
}

//$objResponse->showAjaxAlert(Warecorp::t('Removed')); 
