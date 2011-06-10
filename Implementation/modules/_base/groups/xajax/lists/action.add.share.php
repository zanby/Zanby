<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.add.share.php.xml');

    $AccessManager = Warecorp_List_AccessManager_Factory::create();
    
    $objResponse = new xajaxResponse();
    
    if (!$AccessManager->canCreateLists($this->currentGroup, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
        return;
    }
    
    $objResponse->addScript("unlock_content();");
    $this->view->action = 'add';
    
    if (isset($_SESSION['list_new']) && !empty($share_id)) {
        $list_new = &$_SESSION['list_new'];
        list($target, $id) = explode('_', $share_id);
        if(!isset($list_new['share'])) $list_new['share'] = array();
        switch ($target) {
            case "u":
                if (Warecorp_User::isUserExists('id',$id) && !isset($list_new['share'][$share_id]) ) {
                    $user = new Warecorp_User('id',$id);
                    
                    $this->view->name = $user->getLogin();
                    $this->view->share_id = $share_id;
                    $output = $this->view->getContents('groups/lists/lists.share.div.tpl');
                    $objResponse->addCreate("shared_with", "div", $share_id);
                    $objResponse->addAssign($share_id,'innerHTML', $output);
                    $list_new['share'][$share_id] = $user->getLogin();
                }
                break;
            case "g":
                if (Warecorp_Group_Simple::isGroupExists('id',$id) && !isset($list_new['share'][$share_id])) {
                    $group = new Warecorp_Group_Simple('id',$id);
                    
                    $this->view->name = $group->getName();
                    $this->view->share_id = $share_id;
                    $output = $this->view->getContents('groups/lists/lists.share.div.tpl');
                    $objResponse->addCreate("shared_with", "div", $share_id);
                    $objResponse->addAssign($share_id,'innerHTML', $output);
                    $list_new['share'][$share_id] = $group->getName();
                }
                break;
            default:
                break;
        }
        
    }
