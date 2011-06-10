<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/action.changeConstraints.php.xml');

    //$curr_hid, $level, $value
    $objResponse = new xajaxResponse();

    $h = Warecorp_Group_Hierarchy_Factory::create($curr_hid);
    $c_hierarchy_type = $h->getConstraintsAssoc(1);

    if ( $level == 1 ) {
        $c_category_type = $h->getConstraintsAssoc(2, $value);
        $Level2 = '<select name="category_type" id="category_type" style="width:300px;" onchange="changeConstraints('.$curr_hid.', 2, this.options[this.selectedIndex].value);">';
        foreach ( $c_category_type as $item ) {
            $Level2 .= '<option value="'.$item['value'].'">'.$item['name'].'';
        }
        $Level2 .= '</select>';
        $c_category_focus = $h->getConstraintsAssoc(3, $c_category_type[0]['value']);
        $Level3 = '<select name="category_focus" id="category_focus" style="width:300px;">';
        foreach ( $c_category_focus as $item ) {
            $Level3 .= '<option value="'.$item['value'].'">'.$item['name'].'';
        }
        $Level3 .= '</select>';
        $objResponse->addClear( "ConstraintsLevel2", "innerHTML" );
        $objResponse->addAssign( "ConstraintsLevel2", "innerHTML", $Level2 );
        $objResponse->addClear( "ConstraintsLevel3", "innerHTML" );
        $objResponse->addAssign( "ConstraintsLevel3", "innerHTML", $Level3 );
    } elseif ( $level == 2 ) {
        $c_category_focus = $h->getConstraintsAssoc(3, $value);
        $Level3 = '<select name="category_focus" id="category_focus" style="width:300px;">';
        foreach ( $c_category_focus as $item ) {
            $Level3 .= '<option value="'.$item['value'].'">'.$item['name'].'';
        }
        $Level3 .= '</select>';
        $objResponse->addClear( "ConstraintsLevel3", "innerHTML" );
        $objResponse->addAssign( "ConstraintsLevel3", "innerHTML", $Level3 );
    }


