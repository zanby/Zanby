<?php
Warecorp::addTranslation('/modules/groups/promotion/action.brandgalleryupload.php.xml');

$objResponse = new xajaxResponse ( ) ;

$form = new Warecorp_Form('UploadForm','POST', $this->currentGroup->getGroupPath('brandgalleryuploadsave/upload/1'));
$this->view->form = $form;

$Content = $this->view->getContents ( 'groups/promotion/brandgalleryupload.tpl' ) ;

$popup_window = Warecorp_View_PopupWindow::getInstance();
$popup_window->title(Warecorp::t('Upload Image'));
$popup_window->content($Content);
$popup_window->width(500)->height(350)->open($objResponse);

$this->view->errors = array(Warecorp::t('Please select files to upload'));
$errorcontent = $this->view->getContents('_design/form/form_errors_summary.tpl');
$objResponse->addClear('swferror', 'innerHTML');
$objResponse->addAssign('swferror', 'innerHTML', $errorcontent);
$objResponse->addScript ( 'turnOnSWFUpload(); ' ) ;
