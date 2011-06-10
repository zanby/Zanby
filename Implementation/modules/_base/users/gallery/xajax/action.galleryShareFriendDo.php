<?php
    Warecorp::addTranslation("/modules/users/gallery/xajax/action.galleryShareFriendDo.php.xml");
$objResponse = new xajaxResponse () ;
$gallery = Warecorp_Photo_Gallery_Factory::loadById($galleryId);

if ( !empty($gallery) && $gallery->getId() !== null &&
     Warecorp_Photo_AccessManager_Factory::create()->canShareGallery($gallery, $this->currentUser, $this->_page->_user) ) {

     	$friend = new Warecorp_User('id', $data);
     	if ($friend->getId() !== null) {
     		$gallery->share($friend);
     	}
     	
	/**
	 * share with all friends
	 */
/*	if ( $data['users'] == 0 ) {
		$friendsListObj = new Warecorp_User_Friend_List();
		$friendsListObj->setUserId($this->_page->_user->getId());
		$friendsList = $friendsListObj->getList();
		$friendsIds = array();
		if ( sizeof($friendsList) != 0 ) {
		   foreach ( $friendsList as $friend ) {
                if ( !$gallery->isWatched($friend->getFriend()) ) {	   	
                    $gallery->share($friend->getFriend());		           
                }
                $friendsIds[] = $friend->getFriend()->getId();
*/		       /**
		        * send message if need
		        */
/*		   }
		}
		$gallery->saveShareHistory($this->_page->_user, 'All Friends', 'user', $friendsIds);	
	}*/ 
	/**
	 * share with selected friends
	 */
/*	elseif ( is_array($data['users']) && sizeof($data['users']) != 0 ) {
        $friendsIds = array();
        $friendsNames = array();
        foreach ($data['users'] as $userId) {
            $tmpUser = new Warecorp_User('id', $userId);
            if ($tmpUser->getId() !== null) {
                if (! $gallery->isWatched($tmpUser)) {
                    $gallery->share($tmpUser);
                }
                $friendsIds[] = $userId;
                $friendsNames[] = $tmpUser->getLogin();*/
            /**
             * send message if need
             */
/*            }
        }
        $gallery->saveShareHistory($this->_page->_user, join(', ', $friendsNames), 'user', $friendsIds);
	}
*/	/**
	 * show or hide show history link
	 */
	if ( $application == 'PGLApplication' ) {
        $Script = '';
        $Script .= 'if ( YAHOO.util.Dom.get("showHistory'.$gallery->getId().'") ) {';
        $Script .= '  YAHOO.util.Dom.get("showHistory'.$gallery->getId().'").value = "'.(($gallery->isShareHistoryExists())?1:0).'"';
        $Script .= '}';
        $objResponse->addScript($Script);
    } else {
        if ( $gallery->isShareHistoryExists() ) $objResponse->addScript($application.'.enableSaherHistoryView();');
        else $objResponse->addScript($application.'.disableSaherHistoryView();');
    }
	/**
	 * show ajax alert
	 */
	$objResponse->showAjaxAlert(Warecorp::t('Gallery is shared'));
	$objResponse->addScript($application.'.showShareNew();');
} else {
    $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
}
