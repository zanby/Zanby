<?php
Warecorp::addTranslation("/modules/groups/xajax/message.send.to.host.php.xml");
$objResponse = new xajaxResponse();

if ( !$this->_page->_user->getId() ) {
    return $objResponse;
}

$form           = new Warecorp_Form('messageSendForm', 'post', 'javascript:void(0);');
$additionalText = Warecorp::t('The message you enter will be sent to the host of the group %s', array($this->currentGroup->getName()));
$title          = Warecorp::t('Send message to host of %s', array($this->currentGroup->getName()));

if ( !isset($params['_wf__messageSendForm']) ) {
    $this->view->form = $form;
    $this->view->additionalText = $additionalText;

    $this->view->subject = isset($params['subject'])? $params['subject'] : '';
    $this->view->message = isset($params['message'])? $params['message'] : '';

    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title($title);
    $popup_window->content($this->view->getContents('groups/xajax/message.send.to.host.tpl'));
    $popup_window->open($objResponse);

} else {
    $_REQUEST['_wf__messageSendForm'] = $params['_wf__messageSendForm'];
    $form->addRule('subject', 'required',   Warecorp::t('Enter please Subject'));
    $form->addRule('subject', 'maxlength',  Warecorp::t('Subject of your message is too big (maximum %s letters)', 100), array('max' => 100));
    $form->addRule('message', 'required',   Warecorp::t('Enter please Text'));
    $form->addRule('message', 'maxlength',  Warecorp::t('Body of your message is too big (maximum %s letters)', 65535), array('max' => 65535));

    if ( $form->validate($params) ) {
        $members = $this->currentGroup
            ->getMembers()
            ->returnAsAssoc(true)
            ->setMembersRole(array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST,Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST))
            ->getList();

        foreach ( $members as $uid => $login ) {
            $messageStandard = new Warecorp_Message_Standard();
            $messageStandard->setSenderId($this->_page->_user->getId());
            $messageStandard->setOwnerId($uid);
            $messageStandard->setSubject(htmlentities($this->currentGroup->getName().': '.$params['subject'], ENT_COMPAT, 'utf-8'));
            $messageStandard->setRecipientsListFromStringId($uid);
            $messageStandard->setBody(htmlentities($params['message'], ENT_COMPAT, 'utf-8'));
            $messageStandard->setIsRead(0);
            $messageStandard->setFolder(Warecorp_Message_eFolders::INBOX);
            $messageStandard->save();
        }

        $messageStandard->setOwnerId($this->_page->_user->getId());
        $messageStandard->setFolder(Warecorp_Message_eFolders::SENT);
        $messageStandard->save();
        $objResponse->addScript("popup_window.close();");
        $objResponse->showAjaxAlert(Warecorp::t('Sent'));
    } else {
        $this->view->form           = $form;
        $this->view->additionalText = $additionalText;
        $this->view->subject        = $params['subject'];
        $this->view->message        = $params['message'];

        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title($title);
        $popup_window->content($this->view->getContents('groups/xajax/message.send.to.host.tpl'));
        $popup_window->open($objResponse);
     }
}

