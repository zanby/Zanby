<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/action.removeItem.php.xml');

    $h = Warecorp_Group_Hierarchy_Factory::create();
    $objResponse = new xajaxResponse();
    $h->removeItemByCategoryAndGroup($catid, $groupId);


