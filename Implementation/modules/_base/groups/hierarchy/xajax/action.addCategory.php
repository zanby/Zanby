<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/action.addCategory.php.xml');

    //$catid, $groupid, $hid

    $h = Warecorp_Group_Hierarchy_Factory::create($hid);
    if ( $catid != 0 ) {
        $cur_cat_id = $h->addCustomCategory('Custom Category', $catid);
    } else {
        $cur_cat_id = $h->addCustomCategory('Custom Category', $groupid);
    }
    $current_grouping = $h->getNode($groupid);
    //$current_category = $h->getNode($cur_cat_id);

    //
    
    $this->view->current_hierarchy = $h;
    $this->view->g = $current_grouping;
    $this->view->maxCategoryDepth = $h->maxCategoryDepth;
    $Content = $this->view->getContents('groups/hierarchy/hierarchy.grouping.inputs.template.tpl');

    $objResponse = new xajaxResponse();
    $objResponse->addAssign('GroupingInputs'.$groupid, 'innerHTML', $Content);
