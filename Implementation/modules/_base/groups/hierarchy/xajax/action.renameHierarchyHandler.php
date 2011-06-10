<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/action.renameHierarchyHandler.php.xml');

    $objResponse = new xajaxResponse();
    $objResponse->addAlert($curr_hid);

    $objResponse = new xajaxResponse();
    if ( trim($name) == "" ) {
        $objResponse->showAjaxAlert(Warecorp::t('Hierarchy name is not defined. Hierarchy was not renamed.'));
    } else {
        $h = Warecorp_Group_Hierarchy_Factory::create();        
        $h->updateNodeName($curr_hid, $name);
        $h->loadById($curr_hid);
        $r = $h->getHierarchyList();
        $this->view->current_hierarchy = $h;
        $this->view->curr_hid = $curr_hid;
        $this->view->hierarchyList = $r;
        $Content = $this->view->getContents("groups/hierarchy/hierarchy.info.template.tpl");
        $objResponse->addAssign("HierarchyInfo", "innerHTML", $Content);
        $objResponse->showAjaxAlert(Warecorp::t('Changes saved'));
    }
