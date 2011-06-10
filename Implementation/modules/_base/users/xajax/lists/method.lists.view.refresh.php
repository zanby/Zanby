<?php

    $list = new Warecorp_List_Item($list_id);

    $XSLTProcessor = new XSLTProcessor();
    $XSLTProcessor->registerPHPFunctions();
    $XSLTProcessor->importStyleSheet($list->getXslTitleExtra());

    $order =  isset($_SESSION['list_view'][$list_id]['order']) ? $_SESSION['list_view'][$list_id]['order'] : null;

    $records = $list->getRecordsList($order);
    $_index = 1;

    $dateObj = new Zend_Date();
    $dateObj->setTimezone($this->_page->_user->getTimezone());

    $this->view->list          = $list;
    $this->view->XSLTProcessor = $XSLTProcessor;
    $this->view->Warecorp_List_AccessManager = Warecorp_List_AccessManager_Factory::create();
    $this->view->TIMEZONE      = $dateObj->get(Zend_Date::TIMEZONE);

    switch ($list->isSpecialView()) {
        case 1 :
            $template = 'users/lists/lists.view.record.special.tpl';
            break;
        default:
            $template = 'users/lists/lists.view.record.tpl';
            break;
    }
    $objResponse ->addClear('list_items', 'innerHTML');
    foreach ($records as &$r) {
        $r->domXml = DOMDocument::loadXML($r->getXml());
        $r->displayIndex = $_index++;
        $this->view->record = $r;
        $output = $this->view->getContents($template);
        $objResponse->addCreate("list_items", "div", "item_".($r->getId()));
        $objResponse->addAssign("item_".($r->getId()),'innerHTML', $output);
    }
