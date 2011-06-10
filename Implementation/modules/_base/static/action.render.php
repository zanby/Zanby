<?php
$this->_page->Xajax->registerUriFunction("save_block",  "/block/save/");

$arrBlocks = array();

if (isset($this->params['alias'])) {
    $objPage = new Warecorp_CMS_Page_Item();
    $objPage->pkColName = "alias";
    $objPage->loadByPk($this->params['alias']);
    
    $objBlocks = new Warecorp_CMS_Block_List($this->params['alias']);
    $arrBlocks = $objBlocks->getList();
    $this->_page->setTitle($objPage->title);
}

$this->view->arrBlocks = $arrBlocks;
$this->view->objPage = $objPage;
$this->view->bodyContent = $objPage->template;
