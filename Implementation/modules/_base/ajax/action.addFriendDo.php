<?php
    Warecorp::addTranslation("/modules/ajax/action.addFriendDo.php.xml");
$objResponse = new xajaxResponse();
if (null !== $userId) {
    if (Warecorp_User::isUserExists('id', floor($userId))) {
        if ($this->_page->_user->getId() !== $userId) {
            $oUser = new Warecorp_User('id', $userId);
            $sendRequest = false;
            if (false === $sendAgain) {
                $oFriendsRequests = new Warecorp_User_Friend_Request_List();
                if ($oFriendsRequests->setSenderId($this->_page->_user->getId())->setRecipientId($userId)->getCount()) {
                    $this->view->alredySent = 1;
                    $sendRequest = false;
                } else {
                    $sendRequest = true;
                }
            } else {
            	$oFriendsRequests = new Warecorp_User_Friend_Request_List();
            	if ($oFriendsRequests->setSenderId($this->_page->_user->getId())->setRecipientId($userId)->getCount()) {
                    $this->view->alredySent = 1;
            		$oFriendsRequests->setOrder('request_date DESC');
            		$friendRequests = $oFriendsRequests->getList();
            		$latestRequest = reset($friendRequests);
            		if ($latestRequest->getRequestDate() > date('Y-m-d H:i:s', time()-60*60*24)) {
            			$infoMessage[] = Warecorp::t("You have already sent invitation to %s.", array($oUser->getLogin()));
            			$this->view->errors = $infoMessage;
                        $sendRequest = false;
            		} else {
                        $sendRequest = true;
            		}
            	}
            }
            if ($sendRequest) {
                $oFriends = new Warecorp_User_Friend_Request_Item();
                $oFriends->setSenderId($this->_page->_user->getId());
                $oFriends->setRecipientId($userId);
                $oFriends->setRequestDate(time());
                if ($oFriends->save()) {
                    $objUser = new Warecorp_User('id', $userId);
                    $objUser->sendFriendInvite( $this->_page->_user, $objUser, $oFriends, $message );
                    $infoMessage = Warecorp::t("An invitation sent");
                    $objResponse->addScript("popup_window.close();");
                    $objResponse->showAjaxAlert($infoMessage);
                }
            } else {
                $this->view->friend = $oUser;
                $Content = $this->view->getContents('users/addfriend.popup.tpl');

                $popup_window = Warecorp_View_PopupWindow::getInstance();
                $popup_window->title(Warecorp::t("Add to friends"));
                $popup_window->content($Content);
                $popup_window->width(500)->height(350)->open($objResponse);

            }
        } else {
            $objResponse->addRedirect(BASE_HTTP_HOST);
        }
    } else {
        $objResponse->addRedirect(BASE_HTTP_HOST);
    }
}
