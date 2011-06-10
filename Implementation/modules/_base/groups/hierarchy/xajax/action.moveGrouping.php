<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/action.moveGrouping.php.xml');

    $h = Warecorp_Group_Hierarchy_Factory::create($curr_hid);
    $node = $h->getNode($groupid);
    $groupings = $h->getGroupingList($node['level']);
    if ( sizeof($groupings) > 1 ) {
        $ind = null;
        $swap_ind = null;
        for ( $i = 0; $i < sizeof($groupings); $i ++) {
            if ( $groupings[$i]['id'] == $groupid ) {
                if ( $dir == 'up' ) {
                    if ( isset($groupings[$i-1]) ) {
                        $h->swap($groupings[$i]['id'], $groupings[$i-1]['id']);
                    }
                } elseif ( $dir == 'down' ) {
                    if ( isset($groupings[$i+1]) ) {
                        $h->swap($groupings[$i]['id'], $groupings[$i+1]['id']);
                    }
                }
            }
        }
    }
    $objResponse = $this->showCategoryAction($curr_hid);
