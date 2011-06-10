<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/action.changeHierarchyType.php.xml');

    $objResponse = new xajaxResponse();
    //$objResponse->addAlert('YOOOPS!!!'.$type);
    $display = ( $type == Warecorp_Group_Hierarchy_Enum::TYPE_LIVE ) ? 'none' : '';
    $objResponse->addAssign('hierarchy_focus', 'style.display', $display);