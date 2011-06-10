<?php
Warecorp::addTranslation('/modules/groups/xajax/action.branditemsDeleteDo.php.xml');

$objResponse = new xajaxResponse ( ) ;


$id = (int)floor($id);
//print $id; exit;
$oBrandPhotosList = new Warecorp_Group_BrandPhoto_List($this->currentGroup->getId());
$oBrandPhotosList->returnAsAssoc(true);

if (! array_key_exists($id, $oBrandPhotosList->getList())){
	$objResponse->addRedirect("/");
	//$this->_redirectError(Warecorp::t("Error. Invalid id."));
} else {

	$gallery = new Warecorp_Group_BrandPhoto_Item($id);
	$gallery->delete();

	$objResponse->addScript ( "popup_window.close();" ) ;
	$this->_page->showAjaxAlert(Warecorp::t('Deleted'));

	$_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
	$objResponse->addRedirect ( $this->currentGroup->getGroupPath('brandgallery') ) ;
}