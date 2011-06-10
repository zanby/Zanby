<?php
    Warecorp::addTranslation("/modules/cms/xajax/action.blockEditSave.php.xml");
$block = new Warecorp_CMS_Block_Item($frmData["edit_block_id"]);
$block->setContent($frmData["edit_block_content_area"]);
$block->setPageId($frmData["edit_block_page_id"]);
$block->setOrder($frmData["edit_block_order"]);
$block->save();

$objResponse = new xajaxResponse();
$objResponse->addAssign($frmData["container"], "innerHTML", $frmData["edit_block_content_area"]);
$objResponse->addScript("popup_window.close();");
