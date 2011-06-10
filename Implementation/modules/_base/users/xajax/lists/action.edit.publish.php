<?php
    Warecorp::addTranslation("/modules/users/xajax/lists/action.edit.publish.php.xml");

    $objResponse = new xajaxResponse();

    if (!Warecorp_List_AccessManager_Factory::create()->canManageLists($this->currentUser, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
        return;
    }

    $objResponse->addClear('list_errors', 'innerHTML');
    $objResponse->addAssign('list_errors', 'innerHTML', '');
    $this->view->action = 'edit';

    if (!isset($_SESSION['list_edit']['post_save']) || $_SESSION['list_edit']['post_save']!='publish') {
        if (empty($data['title'])                           ||
            mb_strlen($data['description'], 'UTF-8') > 1024 ||
            mb_strlen($data['title'], 'UTF-8') > 200        ||
            mb_strlen($data['tags'], 'UTF-8') > 200
        ) {
            if (empty($data['title']))                           $record['errors'][] = Warecorp::t("Please enter name of your list");
            if (mb_strlen($data['description'], 'UTF-8') > 1024) $record['errors'][] = Warecorp::t("Description too long (max %s)", 1024);
            if (mb_strlen($data['title'], 'UTF-8') > 200)        $record['errors'][] = Warecorp::t("Name of your list too long (max %s)", 200);
            if (mb_strlen($data['tags'], 'UTF-8') > 200)         $record['errors'][] = Warecorp::t("Tags of your list too long (max %s)", 200);

            $this->view->record = $record;
            $this->view->width = '538px';
            $output = $this->view->getContents('users/lists/errors.tpl');
            $objResponse->addClear('list_errors', 'innerHTML');
            $objResponse->addAssign('list_errors', 'innerHTML', $output);
            $objResponse->addAssign('listTitle', 'className', 'znbErrorRow');
            $objResponse->addScript('window.scroll (0,-100000);');
            $objResponse->addScript('unlock_content();');
        } else {
            $_SESSION['list_edit']['title']      = $data['title'];
            $_SESSION['list_edit']['description']= $data['description'];
            $_SESSION['list_edit']['tags']       = $data['tags'];
            $_SESSION['list_edit']['private']    = $data['private'];
            $_SESSION['list_edit']['ranking']    = (empty($data['ranking'])) ? 0 : 1;
            $_SESSION['list_edit']['adding']     = (empty($data['adding'])) ? 0 : 1;
            $_SESSION['list_edit']['post_save']    = 'publish';
            $objResponse->addAssign('listTitle', 'className', '');
            $objResponse->addScript("xajax_list_edit_save()");
        }
    } else {
        $list_edit = &$_SESSION['list_edit'];
        $editList = new Warecorp_List_Item($list_edit['id']);

        $editList->setListType($list_edit['type']);
        $editList->setTitle($list_edit['title']);
        $editList->setDescription($list_edit['description']);
        $editList->setIsPrivate($list_edit['private']);
        $editList->setRanking($list_edit['ranking']);
        $editList->setAdding($list_edit['adding']);

        $editList->save();
        $editList->deleteTags();
        $editList->addTags($list_edit['tags']);

        $editList->unshareAllFromList();
        
        if ( FACEBOOK_USED ) {
            $params = array(
                'title' => htmlspecialchars($editList->getTitle()), 
                'orgname' => htmlspecialchars(SITE_NAME_AS_STRING)
            );
            $action_links[] = array('text' => 'View List', 'href' => $this->currentUser->getUserPath('listsview/listid/'.$editList->getId()));
            $objMessage = Warecorp_Facebook_Feed::getStreamActionMessage(Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_CHANGED_LIST, $params); 
            $result = Warecorp_Facebook_Feed::postStream($objMessage, null, $action_links);
        }
        if (isset($list_edit['share']) && is_array($list_edit['share'])) {
            foreach ($list_edit['share'] as $share_id=>&$name) {
                list($target, $id) = explode('_', $share_id);
                switch ($target) {
                    case "u":
                        if (Warecorp_User::isUserExists('id',$id) && !Warecorp_List_Item::isListShared($editList->getId(), 'user', $id)) {
                            $editList->shareList('user', $id);
                        }
                        break;
                    case "g":
                        if (Warecorp_Group_Simple::isGroupExists('id',$id) && !Warecorp_List_Item::isListShared($editList->getId(), 'group', $id)) {
                            $editList->shareList('group', $id);
                        }
                        break;
                    default:
                        break;
                }
            }
        }


        $_records = $editList->getRecordsListAssoc();

        if (isset($list_edit['records']) && is_array($list_edit['records'])) {
            foreach ($list_edit['records'] as $id => &$record) {
                if (isset($record['data']['item_fields']) && isset($record['title']) ) {
                    if (isset($_records[$id])) {
                        $editRecord = new Warecorp_List_Record($id);
                        unset($_records[$id]);
                    } else {
                        $editRecord = new Warecorp_List_Record();
                        $editRecord->setCreationDate(new Zend_Db_Expr('NOW()'));
                        $editRecord->setCreatorId($this->_page->_user->getId());

                    }

                    $editRecord->setListId($editList->getId());
                    $editRecord->setTitle($record['title']);
                    $domXml             = $editList->arrayToXml($record['data']['item_fields']);

                    $editRecord->setXml($domXml->saveXML());
                    $editRecord->setEntry($record['data']['item_entry']);
                    $editRecord->save();
                    $editRecord->deleteTags();
                    $editRecord->addTags($record['data']['item_tags']);
                }
            }
        }
        if (is_array($_records) && count($_records)) {
            foreach ($_records as $id=>&$record) {
            	$editRecord = new Warecorp_List_Record($id);
            	$editRecord->delete();
            }
        }

    	$_url = $this->currentUser->getUserPath(null, false);
    	$objResponse->addRedirect($_url."/listsview/listid/{$list_edit['id']}/");
        unset($_SESSION['list_edit']);

    }
