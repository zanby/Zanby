<?php
    Warecorp::addTranslation( "/modules/users/xajax/action.friendRequest.accept.php.xml" );
    
    $objResponse = new xajaxResponse( );
    
    if ( $requestId == 'all' ) {
        $oFriendsRequests = new Warecorp_User_Friend_Request_List( );
        $oFriendsRequests->setRecipientId( $this->_page->_user->getId() )->setIsSender( false )->returnAsAssoc( false );
        $rFriends = $oFriendsRequests->getList();
    } else {
        $rFriends[] = new Warecorp_User_Friend_Request_Item( intval( $requestId ) );
    }
    
    if ( $rFriends ) {
        foreach ( $rFriends as $oFriendRequest ) {
            //@todo Добавить отправку мыла о том, что запрос приенят !??
            $oFriend = new Warecorp_User_Friend_Item( $oFriendRequest->getSenderId(), $oFriendRequest->getRecipientId() );
            $oFriend->setUserId( $oFriendRequest->getSenderId() );
            $oFriend->setFriendId( $oFriendRequest->getRecipientId() );
            if ( !$oFriend->isUserFriend() ) {
                $oFriend->setCreatedDate( time() );
                $oFriend->save();
            }
            if ( $requestId == 'all' ) {
                $oFriendRequest->delete();
            } else {
                $oFriendRequest->deleteAll();
            }
        }
        if ( FACEBOOK_USED ) {
            $objUser = new Warecorp_User( 'id', $oFriendRequest->getSenderId() );
            $paramsFB = array ('title' => htmlspecialchars( $objUser->getLogin() ), 'orgname' => htmlspecialchars( SITE_NAME_AS_STRING ));
            $action_links[] = array ('text' => 'View User', 'href' => $objUser->getUserPath( 'profile/' ));
            $objMessage = Warecorp_Facebook_Feed::getStreamActionMessage( Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_FRIEND, $paramsFB );
            Warecorp_Facebook_Feed::postStream( $objMessage, null, $action_links );
        }
        $this->_page->showAjaxAlert( Warecorp::t( 'Accepted' ) );
        $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
    }
    
    $objResponse->addRedirect( $this->_page->_user->getUserPath( 'friends/requests/received' ) );
