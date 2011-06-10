<?php
$blocksList = new Warecorp_CMS_Block_List();
if (isset($this->params['alias'])) {
    $blocksList->setPageAlias($this->params['alias']);
}

$this->view->blocksList = $blocksList->getList();
$template = 'adminarea/blocks.tpl';	

$this->view->bodyContent = $template;
