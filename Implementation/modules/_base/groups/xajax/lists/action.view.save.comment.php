<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.view.save.comment.php.xml');
    
    $AccessManager = Warecorp_List_AccessManager_Factory::create();
    
    $objResponse = new xajaxResponse();

    $comment_id = isset($comment_id) ? (int)$comment_id : 0;
    
    $commentText = isset($commentText) ? trim($commentText) : "";
    $comment = new Warecorp_Data_Comment($comment_id);
    $record = new Warecorp_List_Record($comment->entityId);
    $list = new Warecorp_List_Item($record->getListId());

	$context = !empty($contextId)?Warecorp_Group_Factory::loadById(intval($contextId)):null;
    
    if ($comment->entityTypeId != $record->EntityTypeId || !$AccessManager->canManageComment($comment, $this->currentGroup, $this->_page->_user)) {
        $objResponse->showAjaxAlert(Warecorp::t('Access denied'));
        return;
    }
    
    if ($record->getId() && $list->getId()) {
        $comment->content = $commentText;
        $comment->save();
        Warecorp::loadSmartyPlugin('modifier.wordwrap.php');

        $objResponse->addAssign("commentText".($comment->id),'innerHTML', smarty_modifier_wordwrap( htmlspecialchars($commentText), 30, "\n", true));
    }
    $objResponse->addScript("editCommentCancel({$comment->id});");
