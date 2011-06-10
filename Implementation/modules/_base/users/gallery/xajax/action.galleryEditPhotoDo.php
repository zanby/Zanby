<?php
    Warecorp::addTranslation("/modules/users/gallery/xajax/action.galleryEditPhotoDo.php.xml");
$objResponse = new xajaxResponse();

$gallery = Warecorp_Photo_Gallery_Factory::loadById($this->params['gallery_id']);
$photo = Warecorp_Photo_Factory::loadById($this->params['photo_id']);

if ( $gallery->getId() !== null && 
     $photo->getId() !== null &&
     Warecorp_Photo_AccessManager_Factory::create()->canEditGallery($gallery, $this->currentUser, $this->_page->_user) ) {

    $form = new Warecorp_Form('editPhotoForm', 'post', $this->currentUser->getUserPath('galleryEditPhotoDo'));
    $form->addRule('title', 'required', Warecorp::t('Enter please title'));

    if ( $form->validate($this->params) ) {
        $photo->setTitle($this->params["title"]);
        $photo->setDescription($this->params["description"]);
        $photo->setAdditionalInfo('');
    	$photo->save();
        $photo->deleteTags();
        $photo->addTags($this->params['tags']);

    	$objResponse->addScript('popup_window.close();');
    	$this->_page->showAjaxAlert(Warecorp::t('Changes saved'));
        $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
        $objResponse->addRedirect($this->currentUser->getUserPath('galleryView/id').$photo->getId().'/');

    } else {
        $photo->setTitle($this->params["title"]);
        $photo->setDescription($this->params["description"]);
        $photo->setAdditionalInfo('');
	    $tags_str = $this->params['tags'];

	    $this->view->form = $form;
	    $this->view->gallery = $gallery;
	    $this->view->photo = $photo;
	    $this->view->photoTags = $tags_str;
	    $this->view->JsApplication = $this->params['JsApplication'];
	    $content = $this->view->getContents('users/gallery/xajax.edit.photo.tpl');
	    $objResponse->addAssign('editPhotoPanelContent', 'innerHTML', $content);
    }
} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}

$sContentHeader = "Content-type: text/xml;";
if ($this->_page->Xajax->sEncoding && strlen(trim($this->_page->Xajax->sEncoding)) > 0) $sContentHeader .= " charset=".$this->_page->Xajax->sEncoding;
if (is_a($objResponse, "xajaxResponse")) $objResponse = $objResponse->getXML();
header($sContentHeader);
print $objResponse; exit;
