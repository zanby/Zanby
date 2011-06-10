<?php
Warecorp::addTranslation("/modules/users/contentblocks/ddMyDocuments/action.documentsGetContent.php.xml");
$blockType = ($blockType === 'narrow') ? 'narrow' : 'wide';
$objResponse = new xajaxResponse();
$lvars['documents_ids'] = $Data;
$lvars['cloneId'] = $el_id;
$lvars['documents_objects'] = array();
foreach ($Data as $_k => &$_v) {
    if (! empty($_v)) {
        $_doc = new Warecorp_Document_Item($_v);
        if (Warecorp_Document_AccessManager_Factory::create()->canViewDocument($_doc, $this->currentUser, $this->_page->_user)) {
            $lvars['documents_objects'][$_k] = $_doc;
        } else {
            unset($lvars['documents_ids'][$_k]);
        }
    }
}
$this->view->assign($lvars);
$this->view->currentUser = $this->currentUser;
$Content = $this->view->getContents('content_objects/ddMyDocuments/documents_area_' . $blockType . '.tpl');
$objResponse->addClear("documents_area_" . $lvars['cloneId'], "innerHTML");
$objResponse->addAssign("documents_area_" . $lvars['cloneId'], "innerHTML", $Content);
foreach ($Data as $_k => &$_v) {
    $tooltip_text = Warecorp::t("Click to select document");
    if (! empty($_v)) {
        $string = $lvars['documents_objects'][$_k]->getDescription();
        $length = 50;
        $etc = '...';
        if (strlen($string) > $length) {
            $length -= strlen($etc);
            $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length + 1));
            $string = substr($string, 0, $length) . $etc;
        }
        $tooltip_text = $lvars['documents_objects'][$_k]->getOriginalName() . '<br>' . $lvars['documents_objects'][$_k]->getFileSize() . ' | ' . $lvars['documents_objects'][$_k]->getFileExt() . '<br />' . $string . '<br /><br />'. Warecorp::t('Created by <a href=\"%s/%s/users/profile/userid/%s/\">%s</a> on %s', array(BASE_URL, LOCALE, $lvars['documents_objects'][$_k]->getCreator()->getId(), $lvars['documents_objects'][$_k]->getCreator()->getLogin(), $lvars['documents_objects'][$_k]->getCreationDate()));
    }
    $objResponse->addScript('YAHOO.example.container.ttdocs_' . $lvars['cloneId'] . '_' . $_k . ' = new YAHOO.widget.Tooltip("ttdocs_' . $lvars['cloneId'] . '_' . $_k . '", {hidedelay:100, context:"document_' . $lvars['cloneId'] . '_' . $_k . '", width:"200px", text:"' . $tooltip_text . ' "});');
}
