<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.view.delete.comment.php.xml');

    $AccessManager = Warecorp_List_AccessManager_Factory::create();
    
    $objResponse = new xajaxResponse();
    $objResponse->addScript("unlock_content();");
    
    if (!isset($comment_id) || !( $comment = new Warecorp_Data_Comment($comment_id) )) {
    	return;
    }

	$context = !empty($contextId)?Warecorp_Group_Factory::loadById(intval($contextId)):null;
	
    if (!$AccessManager->canManageComment($comment, $this->currentGroup, $this->_page->_user->getId())) {
        return;
    }

    $this->view->action = 'view';
    
    $comment->delete();
    $objResponse->addScript("lock_content(); xajax_list_view_expand({$comment->entityId});");
