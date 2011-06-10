<?php

    $objResponse = new xajaxResponse();
    
    if (!Warecorp_List_AccessManager_Factory::create()->canManageLists($this->currentUser, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
        return;
    }
    
    $objResponse->addScript("unlock_content();");
    $objResponse->addScript("xajax_reload_share_whom();");

    $this->view->action = 'edit';
    
    if (isset($_SESSION['list_edit']) && !empty($share_id)) {
        $list_edit = &$_SESSION['list_edit'];
        list($target, $id) = explode('_', $share_id);
        if(!isset($list_edit['share'])) $list_edit['share'] = array();
        switch ($target) {
            case "u":
                if (Warecorp_User::isUserExists('id',$id) && !isset($list_edit['share'][$share_id]) ) {
                    $user = new Warecorp_User('id',$id);
                    
                    $this->view->name = $user->getLogin();
                    $this->view->share_id = $share_id;
                    $output = $this->view->getContents('users/lists/lists.share.div.tpl');
                    $objResponse->addCreate("shared_with", "div", $share_id);
                    $objResponse->addAssign($share_id,'innerHTML', $output);
                    $list_edit['share'][$share_id] = $user->getLogin();
                    unset($list_edit['canshareusers'][$id]);
                }
                break;
            case "g":
                if (Warecorp_Group_Simple::isGroupExists('id',$id) && !isset($list_edit['share'][$share_id])) {
                    $group = new Warecorp_Group_Simple('id',$id);
                    
                    $this->view->name = $group->getName();
                    $this->view->share_id = $share_id;
                    $output = $this->view->getContents('users/lists/lists.share.div.tpl');
                    $objResponse->addCreate("shared_with", "div", $share_id);
                    $objResponse->addAssign($share_id,'innerHTML', $output);
                    $list_edit['share'][$share_id] = $group->getName();
                    unset($list_edit['cansharegroups'][$id]);
                }
                break;
            default:
                break;
        }
        
    }
