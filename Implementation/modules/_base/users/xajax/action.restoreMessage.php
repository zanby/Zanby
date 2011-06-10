<?php
    Warecorp::addTranslation("/modules/users/xajax/action.restoreMessage.php.xml");

    $objResponse = new xajaxResponse();
    if (count($messageId)>0) {
        $result = true;
        foreach($messageId as $message_id) {
            $message = new Warecorp_Message_Standard($message_id);
            if (($message) && ($message->getOwnerId() == $this->_page->_user->getId())) {
            	$result = $result && $message->recovery();
            }
        }
        if ($result) {
            $report = Warecorp::t("Restored");
        }
        else {
            $report = Warecorp::t("Access denied");
        }
        $objResponse->showAjaxAlert($report);
        $objResponse->addRedirect(LOCALE . '/messagelist/folder/trash/');
    }
    else {
        $template = 'users/messages/messages.popup/messages_empty.tpl';
        $Content = $this->view->getContents ( $template ) ;
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t("Restore Message"));
        $popup_window->content($Content);
        $popup_window->width(250)->height(90)->open($objResponse);
    }