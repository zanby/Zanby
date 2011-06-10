<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/__action.showConstraints.php.xml');

    $h = Warecorp_Group_Hierarchy_Factory::create($curr_hid);
    $c_hierarchy_type = $h->getConstraintsAssoc(1);
    $c_category_type = $h->getConstraintsAssoc(2, $h->getHierarchyType());
    $c_category_focus = $h->getConstraintsAssoc(3, $h->getCategoryType());

    $this->view->visibility = true;
    $this->view->curr_hid = $curr_hid;
    $this->view->curr_hierarchy = $h;
    $this->view->c_hierarchy_type = $c_hierarchy_type;
    $this->view->c_category_type = $c_category_type;
    $this->view->c_category_focus = $c_category_focus;
    $Content = $this->view->getContents('groups/hierarchy/hierarchy.constraints.tpl');

    $objResponse = new xajaxResponse();
    $objResponse->addClear( "GroupHierarchyConstraints", "innerHTML" );
    $objResponse->addAssign( "GroupHierarchyConstraints", "innerHTML", $Content );
