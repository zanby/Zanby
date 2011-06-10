<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/__action.showOptions.php.xml');

    $h = Warecorp_Group_Hierarchy_Factory::create($curr_hid);

    $this->view->visibility = true;
    $this->view->curr_hid = $curr_hid;
    $this->view->curr_hierarchy = $h;
    $Content = $this->view->getContents('groups/hierarchy/hierarchy.options.tpl');
    $objResponse = new xajaxResponse();
    $objResponse->addClear( "GroupHierarchyOptions", "innerHTML" );
    $objResponse->addAssign( "GroupHierarchyOptions", "innerHTML", $Content );
    $objResponse->addScript('optionTabIsOpen = true;');
