<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/action.removeCategory.php.xml');

    $h = Warecorp_Group_Hierarchy_Factory::create($hid);
    $current_grouping = $h->getNode($groupid);
    $current_category = $h->getNode($catid);
    $h->removeCategory($catid);

    $this->view->current_hierarchy = $h;
    $this->view->g = $current_grouping;
    $this->view->maxCategoryDepth = $h->maxCategoryDepth;
    $Content = $this->view->getContents('groups/hierarchy/hierarchy.grouping.inputs.template.tpl');

    $objResponse = new xajaxResponse();
    $objResponse->addAssign('GroupingInputs'.$groupid, 'innerHTML', $Content);
