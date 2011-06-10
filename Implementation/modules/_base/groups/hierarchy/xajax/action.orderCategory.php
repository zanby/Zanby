<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/action.orderCategory.php.xml');

    $objResponse = new xajaxResponse();
    $h = Warecorp_Group_Hierarchy_Factory::create();
    //$h->removeChildren($catid);
    //var_dump($children);
    foreach ( $children as $item) {
        if ($item['groupID'] == 0 || $item['groupID'] == 'undefined' || $item['groupID']== null) continue;
        
        if ( $oldCatId !== 0 ) {
            $h->removeItemByCategoryAndGroup($oldCatId, $item['groupID']);
        }
        if ( !$h->checkItemByCategoryAndGroup($catid, $item['groupID']) ) {
            $item_id = $h->addCustomItem($item['groupID'], $catid);
        }
    }