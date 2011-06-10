<?php
if (!Warecorp_User_AccessManager::canViewFriends($this->currentUser, $this->_page->_user->getId())) {
    $this->_redirect($this->_page->_user->getUserPath('friends'));
}
$this->_page->Xajax->registerUriFunction ( "deleteFriend", "/users/deleteFriend/" ) ;
$this->_page->Xajax->registerUriFunction ( "deleteFriendDo", "/users/deleteFriendDo/" ) ;
$this->_page->Xajax->registerUriFunction ( "acceptFriendRequest", "/users/acceptFriendRequest/" ) ;
$this->_page->Xajax->registerUriFunction ( "declineFriendRequest", "/users/declineFriendRequest/" ) ;
$this->_page->Xajax->registerUriFunction ( "declineFriendRequestConfirm", "/users/declineConfirm/" ) ;
$this->_page->Xajax->registerUriFunction ("sendMessage", "/ajax/sendMessage/");
$this->_page->Xajax->registerUriFunction ("sendMessageDo", "/ajax/sendMessageDo/");
$this->_page->Xajax->registerUriFunction ("addToFriends", "/ajax/addToFriends/");
$this->_page->Xajax->registerUriFunction ("addToFriendsDo", "/ajax/addToFriendsDo/");
$this->_page->Xajax->registerUriFunction("bookmarkit", "/ajax/bookmarkit/");
$this->_page->Xajax->registerUriFunction("addbookmark", "/ajax/addbookmark/");


$_page          = (isset ( $this->params [ 'page' ] ))      ? $this->params [ 'page' ]      : 1 ;
$_requests      = (isset ( $this->params [ 'requests' ] ))  ? $this->params [ 'requests' ]  : null ;
$_seeRequest    = (isset ( $this->params [ 'request' ] ))   ? $this->params [ 'request' ]   : null ;
$_order         = (isset ( $this->params [ 'order' ] ))     ? $this->params [ 'order' ]     : 'name' ;
$_direction     = (isset ( $this->params [ 'direction' ] )) ? $this->params [ 'direction' ] : 'asc' ;

$_url           = $this->currentUser->getUserPath ( 'friends') ;

if (! $_requests && ! $_seeRequest) { //if not requests and not view detached request
	// get friends list
	$oFriendsList = new Warecorp_User_Friend_List ( ) ;
	$oFriendsList->setUserId ( $this->currentUser->getId () )->setListSize ( 10 )->setCurrentPage ( $_page ) ;
	$nums = $oFriendsList->getCount ();
	if ( $nums ) { //if friends more then 0
		if ($_order) {
			$oFriendsList->setJoinView ( true ) ;
			switch ( $_order) {
				case 'name' : // if order by name
					{
						$oFriendsList->setOrder ( "login $_direction" ) ;
					}
				break ;
				case 'location' : // if order by location
					{
						$oFriendsList->setOrder ( "zlc.name $_direction, zlst.name $_direction, zlcit.name $_direction" ) ;
					}
				break ;
				default : //default order by name 
					{
						$oFriendsList->setOrder ( "login $_direction" ) ;
					}
				break ;
			}
		}
		$this->view->order = $_order ;
		$this->view->direction = $_direction ;
		$this->view->friends = $oFriendsList->getList () ;
	} else $this->view->friends = array() ;
    $oFriendsRequests = new Warecorp_User_Friend_Request_List ( ) ;
	$this->view->frrNums = $oFriendsRequests->setIsSender ( false )->setRecipientId ( $this->_page->_user->getId () )->getCount();
    $this->view->frNums = $nums ;
	$template = 'users/friends.tpl' ;
	
} elseif ($_seeRequest) { // if view detached request
	// check access
	if ($this->_page->_user->getId () !== $this->currentUser->getId ()) {
		$this->_redirect ( "/" ) ;
	}
    // get request
	$oFriendRequest = new Warecorp_User_Friend_Request_Item ( $_seeRequest ) ;
    if ($oFriendRequest->getId() === null)
        $this->_redirect($this->_page->_user->getUserPath('friends'));
	$aFriendslist = new Warecorp_User_Friend_Request_List ( ) ;
	$aFriendslist->returnAsAssoc ( true ) ;
	
	// check requests utensils
	
	// if sent requests
	if ($this->_page->_user->getId () == $oFriendRequest->getSenderId ()) { 
		$aFriendslist->setIsSender ( true )->setSenderId ( $this->_page->_user->getId () ) ;
		$template = 'users/friend_viewSent.tpl' ;
	} // if received requests
	 elseif ($this->_page->_user->getId () == $oFriendRequest->getRecipientId ()) {
		$aFriendslist->setIsSender ( false )->setRecipientId ( $this->_page->_user->getId () ) ;
		$template = 'users/friend_viewReceived.tpl' ;
	}
	
	// create paginator ("prev", "next") on view request page 
	if ($aFriendslist->getCount () > 1) {
		$prev = 0 ;
		$next = 0 ;
		$a1 = $aFriendslist->getList () ;
		foreach ( $a1 as $key => $val ) {
			if ($key == $_seeRequest) {
				$next = key ( $a1 ) ;
				break ;
			} else {
				$prev = $key ;
			}
		}
		if ($prev > $next || $prev == $next)
			$next = 0 ;
		
		$this->view->prev = $prev ;
		$this->view->next = $next ;
	}
	$this->view->request = $oFriendRequest ;
} else { // if requests
	// check access
	if ($this->_page->_user->getId () !== $this->currentUser->getId ()) {
		$this->_redirect ( "/" ) ;
	}
	
	// get requests list
	$oFriendsRequests = new Warecorp_User_Friend_Request_List ( ) ;
	$oFriendsRequests->returnAsAssoc ( false ) ;
	switch ( $_requests) {
		case 'received' : // if received request
			{
				$oFriendsRequests->setIsSender ( false )->setRecipientId ( $this->_page->_user->getId () ) ;
				$template = 'users/friends_received.tpl' ;
			}
		break ;
		case 'sent' : // if sent request
			{
				$oFriendsRequests->setIsSender ( true )->setSenderId ( $this->_page->_user->getId () ) ;
				$template = 'users/friends_sent.tpl' ;
			}
		break ;
		default : // default receaived request
			{
				$oFriendsRequests->setIsSender ( false )->setRecipientId ( $this->_page->_user->getId () ) ;
				$template = 'users/friends_received.tpl' ;
			}
	}

	//dump($oFriendsRequests->getList ());
    $this->view->friends = $oFriendsRequests->getList () ;
	$form = new Warecorp_Form ( 'friends' ) ;
	$this->view->form = $form ;
}

// make paginator
if (isset ( $nums )) {
	$P = new Warecorp_Common_PagingProduct ( $nums, 10, $this->currentUser->getUserPath ('friends',false) ) ;
	$this->view->paging = $P->makePaging ( $_page ) ;
}

$this->view->friendsAssoc = $this->_page->_user->getId() ?  $this->currentUser->getFriendsList()->returnAsAssoc()->getList() : array();


$this->view->_url = $_url ;
$this->view->bodyContent = $template ;
