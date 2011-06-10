<?php
Warecorp::addTranslation('/modules/groups/xajax/lists/method.lists.collapse.record.php.xml');

    $AccessManager = Warecorp_List_AccessManager_Factory::create();
    
    $record = $list['records'][$record_id];

    $list['records'][$record_id]['status'] = 'collapsed';

    $this->view->id                = $record_id;
    $this->view->record            = $record;
    $this->view->title             = 'Title';
    $this->view->Warecorp_List_AccessManager = $AccessManager;
    $this->view->recordObj         = new Warecorp_List_Record();
    $this->view->listType          = $list['type'];

    $output = $this->view->getContents('groups/lists/lists.record.tpl');
    $objResponse->addClear("list_items", "div", "item_".($record_id));
    $objResponse->addAssign("item_".($record_id),'innerHTML', $output);
