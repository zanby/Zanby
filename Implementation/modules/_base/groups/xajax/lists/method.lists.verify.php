<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/method.lists.verify.php.xml');
    
    $_error = false;
    
    if (isset($list['records']) && isset($list['type']) && count($list['records'])) {
        $listObj = new Warecorp_List_Item();
        $listObj->setListType($list['type']);
        foreach ($list['records'] as $id=>&$record) {
            $record['errors'] = $listObj->getErrors($record['data']);
            if (count($record['errors'])) $_error = true;
        }
    }