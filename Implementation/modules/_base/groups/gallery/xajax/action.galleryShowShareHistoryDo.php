<?php
Warecorp::addTranslation('/modules/groups/gallery/xajax/action.galleryShowShareHistoryDo.php.xml');

$objResponse = new xajaxResponse();
    
$gallery = Warecorp_Photo_Gallery_Factory::loadById($this->params['gallery_id']);

if ( $gallery->getId() !== null && 
     Warecorp_Photo_AccessManager_Factory::create()->canUnShareFromHistoryGallery($gallery, $this->currentGroup, $this->_page->_user) ) {
    if ( isset($this->params['history']) && sizeof($this->params['history']) != 0 ) {
        foreach ( $this->params['history'] as $_id ) {
            $history = $gallery->getShareHistoryById($_id);
            if ( $history ) {
                $history['owner_ids'] = unserialize($history['owner_ids']);
                switch ( $history['owner_type'] ) {
                	case 'user' :
                		foreach ( $history['owner_ids'] as $_ownerId ) {
                            $tmpOwner = new Warecorp_User('id', $_ownerId);
                            $gallery->unshare($tmpOwner);
                            $gallery->deleteShareHistoryById($_id);
                		} 
                		break;
                    case 'group' : 
                        foreach ( $history['owner_ids'] as $_ownerId ) {
                            $tmpOwner = Warecorp_Group_Factory::loadById($_ownerId);
                            $gallery->unshare($tmpOwner);
                            $gallery->deleteShareHistoryById($_id);
                        } 
                    	break;
                }
            }
        }
    }
    /**
     * show or hide show history link
     */
    if ( $this->params['JsApplication'] == 'PGLApplication' ) {
        $Script = '';
        $Script .= 'if ( YAHOO.util.Dom.get("showHistory'.$gallery->getId().'") ) {';
        $Script .= '  YAHOO.util.Dom.get("showHistory'.$gallery->getId().'").value = "'.(($gallery->isShareHistoryExists())?1:0).'"';
        $Script .= '}';
        $objResponse->addScript($Script);
    } else {
        if ( $gallery->isShareHistoryExists() ) $objResponse->addScript($this->params['JsApplication'].'.enableSaherHistoryView();');
        else $objResponse->addScript($this->params['JsApplication'].'.disableSaherHistoryView();');
    }
    /**
     * show ajax alert
     */
    $objResponse->showAjaxAlert(Warecorp::t('Gallery unshared'));
} else {
    $objResponse->showAjaxAlert(Warecorp::t('You can not unshare gallery'));  
}  

$sContentHeader = "Content-type: text/xml;";
if ($this->_page->Xajax->sEncoding && strlen(trim($this->_page->Xajax->sEncoding)) > 0) $sContentHeader .= " charset=".$this->_page->Xajax->sEncoding;
if (is_a($objResponse, "xajaxResponse")) $objResponse = $objResponse->getXML();
header($sContentHeader);
print $objResponse; exit;