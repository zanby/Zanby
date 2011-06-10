<?php

$objResponse = new xajaxResponse();

$popupWidth = 320;
$popupHeigth = 210;
$xajaxPopup = new xajaxPopup('ColorPickerPopup');
$xajaxPopup->setPosition($mousex, $mousey);

$xajaxPopup->setTitle($title);

$xajaxPopup->setSize($popupWidth, $popupHeigth); 

$smarty_vars['title'] = $title;
$smarty_vars['refer'] = $refer;


$this->view->assign($smarty_vars);
$content = $this->view->getContents('content_objects/theme/ddpages_color_picker.tpl');

$xajaxPopup->setBody($content);
$xajaxPopup->addBlockLayer();
$objResponse = $xajaxPopup->getOpen();
