<?php
Warecorp::addTranslation('/modules/groups/xajax/action.addDocMoreFields.php.xml');

	$theme = Zend_Registry::get('AppTheme');
    $objResponse = new xajaxResponse();
    $objResponse->addCreate("DocumentsTable", "tr", "DocumentsTable_tr_".$count);
    $objResponse->addCreate("DocumentsTable_tr_".$count, "td", "DocumentsTable_td1_".$count);
    $objResponse->addCreate("DocumentsTable_tr_".$count, "td", "DocumentsTable_td2_".$count);
    $objResponse->addCreate("DocumentsTable_tr_".$count, "td", "DocumentsTable_td3_".$count);
    $objResponse->addAssign("DocumentsTable_td1_".$count, "innerHTML", $count.".");
    $objResponse->addAssign("DocumentsTable_td2_".$count, "innerHTML", "<img src=\"".$theme->images."/decorators/px.gif\" width='5'>");
    $objResponse->addAssign("DocumentsTable_td3_".$count, "innerHTML", "<input type='file' name='document_5' size='35'>");
    $objResponse->addAssign("docs_count", "value", $count);
