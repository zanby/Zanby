<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/action.categoryChangeHandler.php.xml');
    $h = Warecorp_Group_Hierarchy_Factory::create($hid);
    $h->updateNodeName($catid, $name);
    $current_grouping = $h->getNode($groupid);
    $current_category = $h->getNode($catid);
    
    $objResponse = new xajaxResponse();
