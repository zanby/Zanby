<?php
    Warecorp::addTranslation("/modules/ajax/action.addFriend.php.xml");
$objResponse = new xajaxResponse ( ) ;

if (null !== $userId) {
	if (Warecorp_User::isUserExists ( 'id', floor ( $userId ) )) {

		$objResponse->addAssign ( "ajaxMessagePanelTitle", "innerHTML", Warecorp::t("Add to friends" ) );

        $alreadySend = false;
		$allowResendRequest = false;

		$oFriendsRequests = new Warecorp_User_Friend_Request_List ( ) ;
		if ($oFriendsRequests->setSenderId ( $this->_page->_user->getId () )->setRecipientId ( $userId )->getCount ()) {
			$this->view->alredySent = 1;
			$alreadySend = true;
			$oFriendsRequests->setOrder('request_date DESC');
			$friendRequests = $oFriendsRequests->getList();
			$latestRequest = reset($friendRequests);
			if ($latestRequest->getRequestDate() < date('Y-m-d H:i:s', time()-60*60*24)) {
				$allowResendRequest = true;
			}
		}

		if (Warecorp_User_Friend_Item::isUserFriend ( $this->_page->_user->getId (), $userId )) {
			$oFriend = new Warecorp_User ( 'id', $userId ) ;
			$infoMessage [] = Warecorp::t("%s is already your friend.", array($oFriend->getLogin())) ;
			$this->view->errors = $infoMessage;
		} elseif ($this->_page->_user->getId () == $userId) {
			$infoMessage [] = Warecorp::t("You can not add to friends yourself." );
			$this->view->errors = $infoMessage;
		} elseif ($alreadySend && !$allowResendRequest) {
            $oFriend = new Warecorp_User ( 'id', $userId ) ;
            $infoMessage[] = Warecorp::t("You have already sent an invitation to %s.", array($oFriend->getLogin()));
            //$infoMessage[] = '<a>'.Warecorp::t("You have already sent an invitation to %s.", array($oFriend->getLogin()))."</a>";
            $this->view->errors = $infoMessage;
		} else {
			$this->view->friend = new Warecorp_User ('id', $userId);
		}
                                    
		$this->view->empty = rand();
		$Content = $this->view->getContents ( 'users/addfriend.popup.tpl' ) ;

        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t('Add to Friends'));
        $popup_window->content($Content);
        $popup_window->width(500)->height(150)->open($objResponse);

	} else {
		$this->_redirect ( $BASE_HTTP_HOST ) ;
	}
}

?>
