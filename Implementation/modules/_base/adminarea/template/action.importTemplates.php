<?php

Warecorp::addTranslation('/modules/adminarea/action.importgroups.php.xml');

if ( !Warecorp::isMailServerUsed() ) $this->_redirect($this->admin->getAdminPath('templates'));

// set place where uploads import file
$doc_path = BASE_URL."/upload/import/import_mails_".$this->admin->getLogin().'_';

$form = new Warecorp_Form('iuForm', 'POST', $this->admin->getAdminPath('importTemplates/'));

$template = 'adminarea/template/importTemplates.tpl';

if (isset($this->params['_wf__iuForm'])) { /* form submitted */
    //check form
    if (isset($_FILES['import_file']) && is_uploaded_file($_FILES['import_file']['tmp_name'])) {
        // make import
        
        $importedCount = 0;
        
        try {
            $import = new Warecorp_Mail_Template_Import($_FILES['import_file']['tmp_name'] );

            try {
                $importedCount = $import->import();
            } catch (Warecorp_Mail_Template_Mailserver_Exception $exc) {
                $this->view->mailsrvDown = 1;
            }
        
            $this->view->messages = $import->getImportMessages();
            $this->view->errors = $import->getImportErrors();
        
        } catch (Warecorp_Mail_Template_WrongFile_Exception $exc) {
            // import xml format error
            $this->view->errors = array($exc->getMessage());
        } 
        
        $this->view->importedCount = $importedCount;
                
        $this->view->importStart = 1;
        
        $cache = Warecorp_Cache::getCache('memory');
        // TODO Alex Che Add remove by tag (mailtemplates) functinality
        $cache->remove('registeredTemplateUids');
    }
	
}

$this->view->form = $form;

$this->view->bodyContent = $template;
