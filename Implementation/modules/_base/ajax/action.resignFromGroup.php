<?php
    Warecorp::addTranslation("/modules/ajax/action.resignFromGroup.php.xml");
    $objResponse = new xajaxResponse ( ) ;
    $group = Warecorp_Group_Factory::loadById($groupId);

    if ($group->getId() && $group->getGroupType() == Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE ) {
    	
    	$this->view->group = $group;
    	$this->view->groupTypeName = 'group' ;
    	$Content = $this->view->getContents ( 'users/resign_from_group.tpl' ) ;

        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t('Resign from %s', array($this->view->groupTypeName)));
        $popup_window->content($Content);
        $popup_window->width(400)->height(90)->open($objResponse);

    } else {
        $this->_redirect ( $BASE_HTTP_HOST ) ;
    }
