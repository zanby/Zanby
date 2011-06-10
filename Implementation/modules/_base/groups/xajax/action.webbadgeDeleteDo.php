<?php
Warecorp::addTranslation('/modules/groups/xajax/action.webbadgeDeleteDo.php.xml');

$objResponse = new xajaxResponse ( ) ;

$id = (int)floor($id);


$oBadgesList = new Warecorp_Group_WebBadges_List($this->currentGroup->getId());
$oBadgesList->returnAsAssoc(true);
if (! array_key_exists($id, $oBadgesList->getList())){
	$objResponse->addRedirect("/");

} else {

	$badgeItem = new Warecorp_Group_WebBadges_Item($id);
	$badgeItem->delete();

	$objResponse->addScript ( "popup_window.close();" ) ;
	$this->_page->showAjaxAlert(Warecorp::t('Deleted'));

	$_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
	$objResponse->addRedirect ( $this->currentGroup->getGroupPath('webbadges') ) ;
}