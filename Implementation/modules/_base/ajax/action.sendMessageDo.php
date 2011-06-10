<?php
    Warecorp::addTranslation("/modules/ajax/action.sendMessageDo.php.xml");
$objResponse = new xajaxResponse ( ) ;
$form = new Warecorp_Form('messageSendForm', 'post', 'javascript:void(0);');
if (isset($params['_wf__messageSendForm'])) {
    $_REQUEST['_wf__messageSendForm'] = $params['_wf__messageSendForm'];
}
$form->addRule('userId', 'required', Warecorp::t('Invalid User Id'));
$form->addRule('subject', 'required', Warecorp::t('Enter please Subject'));
$form->addRule('message', 'required', Warecorp::t('Enter please Text'));
$form->addRule('subject', 'maxlength', Warecorp::t('Subject of your message is too big (maximum %s letters)', 100), array('max' => 100));
$form->addRule('message', 'maxlength', Warecorp::t('Body of your message is too big (maximum %s letters)', 65535), array('max' => 65535));
if (empty($params['userId']) || !($user = new Warecorp_User('id', $params['userId']))) {
    $form->addRule('custom_error', 'required', Warecorp::t('Invalid User Id'));
}

if ($form->validate($params)) {
    $user = new Warecorp_User('id', $params['userId']);
	$messageStandard = new Warecorp_Message_Standard();
	$messageStandard->setSenderId($this->_page->_user->getId());
	$messageStandard->setOwnerId($params['userId']);
	$messageStandard->setSubject($params['subject']);
	$messageStandard->setRecipientsListFromStringId($params['userId']);
	$messageStandard->setBody(htmlentities($params['message'], ENT_COMPAT, 'utf-8'));
	$messageStandard->setIsRead(0);
	$messageStandard->setFolder(Warecorp_Message_eFolders::INBOX);
	$messageStandard->save();
	
	$messageStandard->setOwnerId($this->_page->_user->getId());
	$messageStandard->setFolder(Warecorp_Message_eFolders::SENT);
	$messageStandard->save();
	$objResponse->addScript ( "popup_window.close();" ) ;
	$objResponse->showAjaxAlert (Warecorp::t('Sent') );
}
 else {
    if ($params['userId']) {
        $user = new Warecorp_User('id', $params['userId']);
        $this->view->form = $form;
        $this->view->assign($params);
        $content = $this->view->getContents('users/messages/messages.popup/sendmessage.popup.tpl');
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title('');
        $popup_window->content($content);
        $popup_window->width(500)->height(350)->open($objResponse);

    } else {
    	$objResponse->addScript("popup_window.close();");
    	$objResponse->showAjaxAlert(Warecorp::t("Error"));
    }
 }
