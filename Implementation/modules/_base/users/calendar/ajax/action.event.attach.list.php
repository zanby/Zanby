<?php
    Warecorp::addTranslation("/modules/users/calendar/xajax/action.event.attach.list.php.xml");
    $objResponse = new xajaxResponse();
    
    $form = new Warecorp_Form('list_select_form');
    
    if ( !$handle ) {
        $_SESSION['_calendar_']['_lists_'] = ( !isset($_SESSION['_calendar_']) || !isset($_SESSION['_calendar_']['_lists_']) ) ? array() : $_SESSION['_calendar_']['_lists_'];
        
        $list = new Warecorp_List_List($this->currentUser);
        $list->setExcludeIds($_SESSION['_calendar_']['_lists_']);
        $listsList = $list->getList();

        $this->view->lstLists = $listsList;
        $this->view->form = $form;
        $content = $this->view->getContents('users/calendar/ajax/action.event.attach.list.tpl');

        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t("Select List"));
        $popup_window->content($content);
        $popup_window->width(400)->height(160)->open($objResponse);

        $objResponse->addScript('if ( YAHOO.util.Dom.get("list") ) {YAHOO.util.Dom.get("list").selectedIndex = 0;}');
    } else {
        if ( $mode == 'ADD' ) {
            $_SESSION['_calendar_']['_lists_'] = ( !isset($_SESSION['_calendar_']) || !isset($_SESSION['_calendar_']['_lists_']) ) ? array() : $_SESSION['_calendar_']['_lists_'];
            $_SESSION['_calendar_']['_lists_'][$handle] = $handle;
            $lstLists = array();
            foreach ( $_SESSION['_calendar_']['_lists_'] as $listId ) {
                $lstLists[] = new Warecorp_List_Item($listId);
            }
            $this->view->lstLists = $lstLists;
            $content = $this->view->getContents('users/calendar/action.event.template.lists.tpl');
            $objResponse->addAssign('eventListsContent', 'innerHTML', $content);
            $objResponse->addScript('popup_window.close()');
        } elseif ( $mode == 'DELETE' ) {
            if ( isset($_SESSION['_calendar_']['_lists_'][$handle]) ) {
                unset($_SESSION['_calendar_']['_lists_'][$handle]);
            }
            $lstLists = array();
            foreach ( $_SESSION['_calendar_']['_lists_'] as $listId ) {
                $lstLists[] = new Warecorp_List_Item($listId);
            }
            $this->view->lstLists = $lstLists;
            $content = $this->view->getContents('users/calendar/action.event.template.lists.tpl');
            $objResponse->addAssign('eventListsContent', 'innerHTML', $content);
            $objResponse->addScript('popup_window.close()');
        }
    }
