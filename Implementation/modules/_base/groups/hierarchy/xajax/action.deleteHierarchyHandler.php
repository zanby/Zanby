<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/action.deleteHierarchyHandler.php.xml');

    $h = Warecorp_Group_Hierarchy_Factory::create($curr_hid);
    $h->removeHierarchy($curr_hid);

    $objResponse = new xajaxResponse();
    $objResponse->addScript("document.location.replace('".$this->currentGroup->getGroupPath('hierarchy')."');");