<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/action.Options.php.xml');

    $objResponse = new xajaxResponse();

    $isdefault = $options['isdefault'];
    unset($options['isdefault']);
    $h = Warecorp_Group_Hierarchy_Factory::create($curr_hid);
    $h->setHierarchyAsDefault($isdefault);
    $h->updateHierarchyOptions($options);

    $objResponse = new xajaxResponse();
    $objResponse->addScript('document.location.reload();');
