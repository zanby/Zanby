<?php
    Warecorp::addTranslation("/modules/users/xajax/action.friendRequest.decline.confirm.php.xml");
    if (null == $redirect) {
        $header = Warecorp::t("Decline Requests");
    } elseif ('sent' == $redirect) {
    	$redirect = ', \''.$redirect.'\'';
    	$header = Warecorp::t("Delete Requests");
    }

    if ('all' == $requestId) {
    	$requestId = '\''. $requestId. '\'';
    }

    $objResponse = new xajaxResponse();    

    $this->view->requestId = $requestId;
    $this->view->redirect = $redirect;
    $Content = $this->view->getContents ( 'users/friends_declineConfirm.tpl' ) ;
 
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title($header);
    $popup_window->content($Content);
    $popup_window->width(500)->height(350)->open($objResponse);
