<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/action.addItem.php.xml');

    $h = Warecorp_Group_Hierarchy_Factory::create();
    $objResponse = new xajaxResponse();
    if ( $oldcatid !== 0 ) {
        $h->removeItemByCategoryAndGroup($oldcatid, $groupId);
    }
    if ( !$h->checkItemByCategoryAndGroup($oldcatid, $groupId) ) {
        $item_id = $h->addCustomItem($groupId, $catid);
    }


