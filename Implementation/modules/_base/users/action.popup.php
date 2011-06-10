<?php

$this->params['photo'] = (isset($this->params['photo']))? $this->params['photo'] : 1;
$photo = new Warecorp_Photo_Item($this->params['photo']);
$this->view->photo = $photo;//->photo_path."_orig.jpg";
print $this->_page->Template->GetContents("photopopup.tpl");
exit;
