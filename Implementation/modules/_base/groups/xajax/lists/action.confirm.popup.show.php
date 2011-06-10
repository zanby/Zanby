<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/action.confirm.popup.show.php.xml');

    $AccessManager = Warecorp_List_AccessManager_Factory::create();

    $objResponse = new xajaxResponse();
    
	$context = !empty($contextId)?Warecorp_Group_Factory::loadById(intval($contextId)):null;
	
    $list =(isset($list_id)) ? new Warecorp_List_Item($list_id) : new Warecorp_List_Item($list_id);
    $action = (isset($action) && in_array($action, array('offwatch','delete', 'unshare'))) ? $action : ""; 
	$_title='';
	
    switch ($action) {
        case 'offwatch':
            if (!$AccessManager->canManageLists($this->currentGroup, $this->_page->_user->getId())) {
                $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
                return;
            }
			$_title=Warecorp::t('Offwatch');
            break;
    	case 'unshare':
            if (!$AccessManager->canUnshareList($list, $this->currentGroup, $this->_page->_user->getId())) {
                $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
                return;
            }
			$_title=Warecorp::t('Unshare');
    		break;
        case 'delete':
            if (!$AccessManager->canManageList($list, $this->currentGroup, $this->_page->_user->getId())) {
                $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
                return;
            }
			$_title=Warecorp::t('Delete');
            break;
        default: 
            $objResponse->addRedirect($this->currentGroup->getGroupPath('lists'));
            return;
        	break;
    }

    if ($list->getId() && isset($action)) {

        $form = new Warecorp_Form('confirmForm');
        $this->view->form = $form;
        $this->view->list = $list;
        $this->view->action = $action;

        $content = $this->view->getContents('groups/lists/lists.confirm.popup.tpl');
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title($_title." ".Warecorp::t("List"));
        $popup_window->content($content);
        $popup_window->width(500)->height(350)->open($objResponse);

    }
