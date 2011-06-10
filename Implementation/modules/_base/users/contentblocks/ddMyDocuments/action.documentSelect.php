<?
Warecorp::addTranslation("/modules/users/contentblocks/ddMyDocuments/action.documentSelect.php.xml");
$objResponse = new xajaxResponse();
$blockType = ($blockType === 'narrow') ? 'narrow' : 'wide';
$tree_div_id = $div_id . '_tree';
$treeObj = $this->_page->_user->getArtifacts()->createDocumentTree();
$treeObj->setCallbackFunction('selectDocumentOk_' . $element_id . '_' . $js_array_key);
$treeObj->setShowMainFolder(false);
$tree = $treeObj->startTree('tree_0', $tree_div_id);
$tmpTreeObj = $this->currentUser->getArtifacts()->createDocumentTree();
$tmpTreeObj->setShowDocuments(true);
$tmpTreeObj->setShowMainFolder(false);
$tmpTreeObj->setShowShared(false);
$tree .= $tmpTreeObj->buildTree('tree_0');
$tree .= $treeObj->endTree('tree_0');
//$treeObj->setShowShared(false);
$this->view->tree_div_id = $tree_div_id;

$content = $this->view->getContents('content_objects/ddMyDocuments/chose_document.tpl');

$popup_window = Warecorp_View_PopupWindow::getInstance();
$popup_window->title(Warecorp::t('Select Document for Display'));
$popup_window->content($content);
$popup_window->width(376)->height(300)->open($objResponse);


$objResponse->addScript($tree);
$objResponse->addScript('function selectDocumentOk_' . $element_id . '_' . $js_array_key . '(mNode) {if(mNode.data.oType == "document"){var documentsObj = WarecorpDDblockApp.getObjByID("' . $element_id . '"); documentsObj.documents[' . $js_array_key . '] = mNode.data.id; xajax_documents_getcontent("' . $element_id . '",documentsObj.documents, "' . $blockType . '"); popup_window.close();}return false;}');
