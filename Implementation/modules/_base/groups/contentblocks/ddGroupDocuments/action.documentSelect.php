<?
Warecorp::addTranslation('/modules/groups/contentblocks/ddGroupDocuments/action.documentSelect.php.xml');
$objResponse = new xajaxResponse();
$blockType = ($blockType === 'narrow') ? 'narrow' : 'wide';


$treeObj = $this->currentGroup->getArtifacts()->createDocumentTree();
$tree_div_id = $div_id . '_tree';
$treeObj->setCallbackFunction('selectDocumentOk_' . $element_id . '_' . $js_array_key);
$treeObj->setShowShared(true);
$treeObj->setShowMainFolder(false);
$tree = $treeObj->startTree('tree_0', $tree_div_id);
$tmpTreeObj = $this->currentGroup->getArtifacts()->createDocumentTree();
$tmpTreeObj->setShowDocuments(true);
$tmpTreeObj->setShowMainFolder(false);
$tmpTreeObj->setShowShared(true);
$tree .= $tmpTreeObj->buildTree('tree_0');
$tree .= $treeObj->endTree('tree_0');
$this->view->tree_div_id = $tree_div_id;



$treeObj_user = $this->_page->_user->getArtifacts()->createDocumentTree();
$tree_div_id_user = $div_id.'_tree_user';
$treeObj_user->setCallbackFunction('selectDocumentOk_user_'.$element_id.'_'.$js_array_key);
$treeObj_user->setShowShared(false);
$treeObj_user->setShowMainFolder(false);
$tree_user = $treeObj->startTree('tree_0_user', $tree_div_id_user);
            $tmpTreeObj = $this->_page->_user->getArtifacts()->createDocumentTree();
            $tmpTreeObj->setShowDocuments(true);
            $tmpTreeObj->setShowMainFolder(false);
            $tmpTreeObj->setShowShared(false);
            $tree_user .= $tmpTreeObj->buildTree('tree_0_user');
$tree_user .= $treeObj->endTree('tree_0_user');
$this->view->tree_div_id_user = $tree_div_id_user;



$this->view->currentUser = $this->_page->_user;
$content = $this->view->getContents('content_objects/ddGroupDocuments/chose_document.tpl');

$popup_window = Warecorp_View_PopupWindow::getInstance();
$popup_window->title(Warecorp::t('Select Document for Display'));
$popup_window->content($content);
$popup_window->width(376)->height(500)->open($objResponse);

$objResponse->addScript($tree);
$objResponse->addScript($tree_user);
//$objResponse->addScript($treeObj_user->getTree($tree_div_id_user));
//$objResponse->addScript('function selectDocumentOk_' . $element_id . '_' . $js_array_key . '(mNode) {if(mNode.data.oType == "document"){var documentsObj = WarecorpDDblockApp.getObjByID("' . $element_id . '"); documentsObj.documents[' . $js_array_key . '] = mNode.data.id; xajax_documents_getcontent("' . $element_id . '",documentsObj.documents, "' . $blockType . '"); popup_window.close();}return false;}');
$objResponse->addScript('function selectDocumentOk_' . $element_id . '_' . $js_array_key . '(mNode) {if(mNode.data.oType == "document"){var documentsObj = WarecorpDDblockApp.getObjByID("' . $element_id . '"); documentsObj.documents[' . $js_array_key . '] = mNode.data.id; xajax_documents_getcontent("' . $element_id . '",documentsObj.documents, "' . $blockType . '"); popup_window.close();}return false;}');
$objResponse->addScript('function selectDocumentOk_user_' . $element_id . '_' . $js_array_key . '(mNode) {if(mNode.data.oType == "document"){var documentsObj = WarecorpDDblockApp.getObjByID("' . $element_id . '"); documentsObj.documents[' . $js_array_key . '] = mNode.data.id; xajax_documents_getcontent("' . $element_id . '",documentsObj.documents, "' . $blockType . '"); popup_window.close();}return false;}');
//$objResponse->addScript('function selectDocumentOk_user_' . $element_id . '_' . $js_array_key . '(doc_id) {var documentsObj = WarecorpDDblockApp.getObjByID("' . $element_id . '"); documentsObj.documents[' . $js_array_key . '] = doc_id; xajax_documents_getcontent("' . $element_id . '",documentsObj.documents, "' . $blockType . '"); popup_window.close();return false;}');
