<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/__action.hideCategory.php.xml');

    $this->view->visibility = false;
    $this->view->curr_hid = $curr_hid;
    $Content = $this->view->getContents('groups/hierarchy/hierarchy.category.tpl');
    $objResponse = new xajaxResponse();
    $objResponse->addClear( "GroupHierarchyCategory", "innerHTML" );
    $objResponse->addAssign( "GroupHierarchyCategory", "innerHTML", $Content );
    $objResponse->addScript('categoryTabIsOpen = false;');
