<?php
    Warecorp::addTranslation('/modules/users/documents/xajax/action.documentRevisions.php.xml');    
    $objResponse = new xajaxResponse();

    if ( null === $this->_page->_user->getId() ) {
        $_SESSION['login_return_page'] = $this->currentUser->getUserPath('documents');
        $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;                       
    }
    
    if ( !isset($this->params['groups']) || !$this->params['groups'] ) {
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title(Warecorp::t('Information'));
        $popup_window->content('<p>' . Warecorp::t('Error') . '</p>');
        $popup_window->width(350)->height(100)->reload($objResponse);  
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;   
    }

    $objDocument = new Warecorp_Document_Item($this->params['groups']);
    if ( null === $objDocument->getId() ) {
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->close($objResponse);
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;       
    }
    
    /* get revisions list */
        $this->params['page'] = isset($this->params['page']) ? (int)$this->params['page'] : 1;
        $size = 15;
        $link = $this->currentUser->getUserPath('documentRevisions', false);
        
        $objRevisionsList = new Warecorp_Document_Revision_List();
        $objRevisionsList->setDocumentId($objDocument->getId());            
        $listRevisions = $objRevisionsList->setCurrentPage($this->params['page'])->setListSize($size)->getList();
        $countRevisions = $objRevisionsList->getCount();
    /* last revision */
        $currentRevision = $objDocument->getRevisionId();
        if ( !$currentRevision ) {
            $lastRevision = $objRevisionsList->getLastRevision();
            $currentRevision = ($lastRevision) ? $lastRevision->getRevisionId() : null;
        }        
    /* open window */        
        $P = new Warecorp_Common_PagingProduct($countRevisions, $size, $link);
        //$paging = $P->makePaging($this->params['page']);
        $paging = $P->makeAjaxLinkPaging($this->params['page'], 'DocumentApplication.revisionHistory(',');');
                
        $this->view->currentRevision = $currentRevision;
        $this->view->listRevisions = $listRevisions;
        $this->view->paging = $paging;
        $this->view->page = $this->params['page'];
        $content = $this->view->getContents('users/documents/documents.revisions.template.tpl');        
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();        
        $popup_window->title(Warecorp::t('Revision History'));
        $popup_window->content($content);
        $popup_window->width(700)->height(500)->reload($objResponse);
    
    $objResponse->printXml($this->_page->Xajax->sEncoding);
    exit;       
