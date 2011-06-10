<?php
    Warecorp::addTranslation("/modules/users/xajax/lists/action.confirm.popup.show.php.xml");
    $objResponse = new xajaxResponse();

    $list =(isset($list_id)) ? new Warecorp_List_Item($list_id) : new Warecorp_List_Item($list_id);
    $action = (isset($action) && in_array($action, array('offwatch','delete', 'unshare'))) ? $action : "";
	$_title='';

    switch ($action) {
        case 'offwatch':
            if (!Warecorp_List_AccessManager_Factory::create()->canManageLists($this->currentUser, $this->_page->_user->getId())) {
                $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
                return;
            }
			$_title=Warecorp::t('Offwatch');
            break;
    	case 'unshare':
            if (!Warecorp_List_AccessManager_Factory::create()->canUnshareList($list, $this->currentUser, $this->_page->_user->getId())) {
                $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
                return;
            }
			$_title=Warecorp::t('Unshare');
    		break;
        case 'delete':
            if (!Warecorp_List_AccessManager_Factory::create()->canManageList($list, $this->currentUser, $this->_page->_user->getId())) {
                $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
                return;
            }
			$_title=Warecorp::t('Delete');
            break;
        default:
            $objResponse->addRedirect($this->currentUser->getUserPath('lists'));
            return;
        	break;
    }

    if ($list->getId() && isset($action)) {

        $form = new Warecorp_Form('confirmForm');
        $this->view->form = $form;
        $this->view->list = $list;
        $this->view->action = $action;

        $content = $this->view->getContents('users/lists/lists.confirm.popup.tpl');

        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t($_title." List"));
        $popup_window->content($content);
        $popup_window->width(500)->height(100)->open($objResponse);

    }
