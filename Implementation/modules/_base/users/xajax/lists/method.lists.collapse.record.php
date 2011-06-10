<?php
Warecorp::addTranslation("/modules/users/xajax/lists/method.list.collaps.php.xml");
    $record = $list['records'][$record_id];

    $list['records'][$record_id]['status'] = 'collapsed';

    $this->view->id                = $record_id;
    $this->view->record            = $record;
    $this->view->title             = Warecorp::t('Title');
    $this->view->Warecorp_List_AccessManager = Warecorp_List_AccessManager_Factory::create();
    $this->view->recordObj         = new Warecorp_List_Record();
    $this->view->listType          = $list['type'];
    $output = $this->view->getContents('users/lists/lists.record.tpl');
    $objResponse->addClear("list_items", "div", "item_".($record_id));
    $objResponse->addAssign("item_".($record_id),'innerHTML', $output);
