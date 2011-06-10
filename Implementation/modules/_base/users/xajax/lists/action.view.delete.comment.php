<?php

    $objResponse = new xajaxResponse();
    $objResponse->addScript("unlock_content();");
    
    if (!isset($comment_id) || !( $comment = new Warecorp_Data_Comment($comment_id) )) {
    	return;
    }
    
    if (!Warecorp_List_AccessManager_Factory::create()->canManageComment($comment, $comment->getCreator(), $this->_page->_user->getId())) {
        return;
    }

    $this->view->action = 'view';
    
    $comment->delete();
    $objResponse->addScript("lock_content(); xajax_list_view_expand({$comment->entityId});");
