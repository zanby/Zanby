<?php
Warecorp::addTranslation("/modules/users/xajax/action.deleteMessage.php.xml");

$objResponse = new xajaxResponse();
$showing = true;

$popup_window = Warecorp_View_PopupWindow::getInstance();
$popup_window->width(350)->height(100);

if (is_numeric($messageId)) {
    $this->view->messageId = $messageId;
    $template = 'users/messages/messages.popup/message_delete.tpl';
    $capture = Warecorp::t('Delete Message');
}
elseif (is_array($messageId)) {
    if (count($messageId)>0) {
        if (count($messageId)>1) {
            $deleteQuestion = Warecorp::t('Are you sure you want to delete these messages?');
        }
        elseif (count($messageId)==1) $deleteQuestion = Warecorp::t('Are you sure you want to delete this message?');
        $this->view->messageId = implode(',', $messageId);
        $template = 'users/messages/messages.popup/messages_delete.tpl';
        $this->view->deleteQuestion = $deleteQuestion;
    }
    else {
        $template = 'users/messages/messages.popup/messages_empty.tpl';
        $popup_window->width(250)->height(90);
    }
    $capture = Warecorp::t('Delete Messages');
}
elseif (is_string($messageId)) {
    $this->view->messageId = $messageId;
    //$template = 'users/messages/folder_delete.tpl';

    $messageManager = new Warecorp_Message_List();
    $messageManager->setFolder(Warecorp_Message_eFolders::TRASH);
    $messages = $messageManager->findAllByOwner($this->_page->_user->getId());
    $capture = Warecorp::t('Empty Trash');
    $template = 'users/messages/messages.popup/empty_trash.popup.tpl';
    if ($showDialog == 'true' && count($messages)>0) {
        //$objResponse->addScript("popup_window.close();");
        $redirectUrl = $this->_page->_user->getUserPath('messagedelete/folder/trash/activefolder/' . $messageId . '/');
        $report = Warecorp::t('Trash is empty');
        $objResponse->showAjaxAlert($report);
        $objResponse->addRedirect($redirectUrl);
        $showing = false;
    }
    if (count($messages) == 0) {
        $popup_window->width(250)->height(90);
        $template = 'users/messages/messages.popup/trash_empty.tpl';
        $capture = Warecorp::t('Empty Trash');
    }
}
if ($showing) {
    $Content = $this->view->getContents ( $template ) ;
    
    $popup_window->title($capture);
    $popup_window->content($Content);
    $popup_window->open($objResponse);
}
