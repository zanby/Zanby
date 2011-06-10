<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/action.removeGrouping.php.xml');

    $h = Warecorp_Group_Hierarchy_Factory::create($curr_hid);
    $h->removeGrouping($groupId);

    $objResponse = $this->showCategoryAction($curr_hid);