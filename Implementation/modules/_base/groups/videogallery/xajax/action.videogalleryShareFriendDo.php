<?php
Warecorp::addTranslation('/modules/groups/videogallery/xajax/action.videogalleryShareFriendDo.php.xml');

$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Video_Gallery_Factory::loadById($galleryId);

if ( $gallery->getId() !== null && 
     Warecorp_Video_AccessManager_Factory::create()->canShareGallery($gallery, $this->currentGroup, $this->_page->_user) ) {

         $friend = new Warecorp_User('id', $data);
         if ($friend->getId() !== null) {
             $gallery->share($friend);
         }
         
/*    if ( $application == 'PGLApplication' ) {
        $Script = '';
        $Script .= 'if ( YAHOO.util.Dom.get("showHistory'.$gallery->getId().'") ) {';
        $Script .= '  YAHOO.util.Dom.get("showHistory'.$gallery->getId().'").value = "'.(($gallery->isShareHistoryExists())?1:0).'"';
        $Script .= '}';
        $objResponse->addScript($Script);
    } else {
        if ( $gallery->isShareHistoryExists() ) $objResponse->addScript($application.'.enableSaherHistoryView();');
        else $objResponse->addScript($application.'.disableSaherHistoryView();');
    } */
    if (SINGLEVIDEOMODE){
        $objResponse->showAjaxAlert(Warecorp::t('Video is shared'));
    }else{
        $objResponse->showAjaxAlert(Warecorp::t('Collection is shared'));    
    }    
	$objResponse->addScript($application.'.showShareNew(null);');
} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}
