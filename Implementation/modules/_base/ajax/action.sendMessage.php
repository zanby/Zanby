<?php
    Warecorp::addTranslation("/modules/ajax/action.sendMessage.php.xml");
$objResponse = new xajaxResponse();

if (null !== $userId) {
    $form = new Warecorp_Form('messageSendForm', 'post', 'javascript:void(0);');

    if (isset($params['_wf__messageSendForm'])) {
        $_REQUEST['_wf__messageSendForm'] = $params['_wf__messageSendForm'];
    }


    $this->view->form = $form;
    $this->view->userId = $userId;

    $user = new Warecorp_User('id', $userId);

    $Content = $this->view->getContents ( 'users/messages/messages.popup/sendmessage.popup.tpl' ) ;

    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title(Warecorp::t('Send Message to ').' '.$user->getLogin());
    $popup_window->content($Content);
    $popup_window->width(500)->height(140)->open($objResponse);

}
//else $this->_redirectToLogin();
else $objResponse->addRedirect('/en/login/');
