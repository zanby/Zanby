<?php
Warecorp::addTranslation('/modules/groups/gallery/action.popup.php.xml');

$this->params['photo'] = (isset($this->params['photo']))? $this->params['photo'] : 1;
$photo = Warecorp_Photo_Factory::loadById($this->params['photo']);
$this->view->photo = $photo;//->photo_path."_orig.jpg";
print $this->_page->Template->GetContents("photopopup.tpl");
exit;
