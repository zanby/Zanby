<?php
if ($approveResult == null) $this->_redirect(BASE_URL);

Warecorp::addTranslation('/modules/registration/action.processed.php.xml');
    
$this->_page->setTitle(Warecorp::t('Processed!'));
$this->view->accountApproved  = $approveResult;
$this->view->bodyContent       = 'registration/processed.tpl';
