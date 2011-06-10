<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/action.addGrouping.php.xml');

    $h = Warecorp_Group_Hierarchy_Factory::create($curr_hid);
    $gId = $h->addCustomGrouping();
    $cId1 = $h->addCustomCategory('Custom Level 1', $gId);
    $cId2 = $h->addCustomCategory('Custom Level 2', $cId1);
    $cId3 = $h->addCustomCategory('Custom Level 3', $cId2);

    $objResponse = $this->showCategoryAction($curr_hid);
    /*
    $current_grouping = $h->getNode($gId);

    $this->view->curr_hierarchy = $h;
    $this->view->g = $current_grouping;
    $this->view->maxCategoryDepth = $h->maxCategoryDepth;
    $Content = $this->view->getContents('groups/hierarchy/hierarchy.grouping.template.tpl');

    $objResponse = new xajaxResponse();
    $objResponse->addCreate('GroupingMainTable', 'tr', 'tr_gr_'.$gId);
    $objResponse->addCreate('tr_gr_'.$gId, 'td', 'td_gr_'.$gId);
    $objResponse->addAssign('td_gr_'.$gId, 'innerHTML', $Content);

    $Script = $h->getJSCategoryTree($current_grouping);

    $objResponse->addScript($Script);
    $objResponse->addScript("document.getElementById('NoCustomCategoryGroupsTR').style.display = 'none';");
    */
