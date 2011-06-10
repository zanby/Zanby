<?php
Warecorp::addTranslation('/modules/groups/calendar/ajax/action.event.attach.document.php.xml');
    $objResponse = new xajaxResponse();
    
    if ( !$handle ) {
        $tree_div_id = 'event_document';
       
        $groups = array();
        if ($this->currentGroup instanceof Warecorp_Group_Family) $groups = $this->currentGroup->getGroups()->setTypes(array("simple"))->getList();
        array_unshift($groups, $this->currentGroup);

        $treeObj = $this->currentGroup->getArtifacts()->createDocumentTree();
        $treeObj->setCallbackFunction('selectDocumentOk');
        $tree = $treeObj->startTree($tree_div_id, $tree_div_id);
        if ( sizeof($groups) != 0 ) {
            foreach ( $groups as $group ) {
                if ( true == Warecorp_Document_AccessManager_Factory::create()->canViewOwnerDocuments($this->currentGroup, $group, $this->_page->_user->getId()) ) {
                    $tmpTreeObj = $group->getArtifacts()->createDocumentTree();
                    $tmpTreeObj->setShowDocuments(true);
                    $tmpTreeObj->setShowMainFolder(true);
                    $tmpTreeObj->setMainFolderName($group->getName());
                    $tmpTreeObj->setShowShared(true);
                    $tmpTreeObj->setCallbackFunction('selectDocumentOk');
                    $tree .= $tmpTreeObj->buildTree($tree_div_id);
                }
            }
        }
        $tree .= $treeObj->endTree($tree_div_id);
        
        $this->view->tree_div_id = $tree_div_id;
        $objResponse->addScript('function selectDocumentOk(doc_node) {xajax_doAttachDocument(doc_node.data); return false;}');
        
        $content = $this->view->getContents('groups/calendar/ajax/action.event.attach.document.tpl');
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t("Select Document"));
        $popup_window->content($content);
        $popup_window->width(500)->height(450)->open($objResponse);
        
        $objResponse->addScript('var '.$tree_div_id.';');
        $objResponse->addScript($tree);        
    } else {
        if ( $mode == 'ADD' ) {
            if ( $handle['oType'] == 'document' ) {
                $_SESSION['_calendar_']['_documents_'] = ( !isset($_SESSION['_calendar_']) || !isset($_SESSION['_calendar_']['_documents_']) ) ? array() : $_SESSION['_calendar_']['_documents_'];
                $_SESSION['_calendar_']['_documents_'][$handle['id']] = $handle['id'];
                $lstDocuments = array();
                foreach ( $_SESSION['_calendar_']['_documents_'] as $docId ) {
                    $lstDocuments[] = new Warecorp_Document_Item($docId);
                }
                $this->view->lstDocuments = $lstDocuments;
                $content = $this->view->getContents('groups/calendar/action.event.template.documents.tpl');
                $objResponse->addAssign('eventDocumentsContent', 'innerHTML', $content);
                $objResponse->addScript('popup_window.close()');                
            }
        } elseif ( $mode == 'DELETE' ) {
            if ( isset($_SESSION['_calendar_']['_documents_'][$handle]) ) {
                unset($_SESSION['_calendar_']['_documents_'][$handle]);
            }
            $lstDocuments = array();
            foreach ( $_SESSION['_calendar_']['_documents_'] as $docId ) {
                $lstDocuments[] = new Warecorp_Document_Item($docId);
            }
            $this->view->lstDocuments = $lstDocuments;
            $content = $this->view->getContents('groups/calendar/action.event.template.documents.tpl');
            $objResponse->addAssign('eventDocumentsContent', 'innerHTML', $content);
            $objResponse->addScript('popup_window.close()');                    
        }
    } 
