<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/__action.hideConstraints.php.xml');

    $this->view->visibility = false;
    $this->view->curr_hid = $curr_hid;
    $Content = $this->view->getContents('groups/hierarchy/hierarchy.constraints.tpl');
    $objResponse = new xajaxResponse();
    $objResponse->addClear( "GroupHierarchyConstraints", "innerHTML" );
    $objResponse->addAssign( "GroupHierarchyConstraints", "innerHTML", $Content );
