<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/__action.hideOptions.php.xml');

    $this->view->visibility = false;
    $this->view->curr_hid = $curr_hid;
    $Content = $this->view->getContents('groups/hierarchy/hierarchy.options.tpl');
    $objResponse = new xajaxResponse();
    $objResponse->addClear( "GroupHierarchyOptions", "innerHTML" );
    $objResponse->addAssign( "GroupHierarchyOptions", "innerHTML", $Content );
    $objResponse->addScript('optionTabIsOpen = false;');
