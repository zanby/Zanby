<?php
Warecorp::addTranslation('/modules/groups/xajax/action.resignShowSendForm.php.xml');

    $objResponse = new xajaxResponse();
    $isValid        = true;
    //  @todo проверить на существование текущей группы и текущего пользователя
    if ( $this->currentGroup->getId() === null || $this->_page->_user->getId() === null ) {
        $objResponse->addRedirect("/");
        $isValid = false;
    }
    //  @todo проверить права на хоста
    if ( !$this->currentGroup->getMembers()->isHost($this->_page->_user->getId()) ) {
        $objResponse->addRedirect("/");
        $isValid = false;
    }
    if ( $isValid ) {
        $this->view->visibility = true;
        $this->view->resign_send_message_form = true;
        $this->view->resign_send_message_subject = "Resignation";

        $Content = $this->view->getContents('groups/settings.resign.tpl');

        $objResponse->addClear( "GroupSettingsResign_Content", "innerHTML" );
        $objResponse->addAssign( "GroupSettingsResign_Content", "innerHTML", $Content );
    }
