<?php

Warecorp::turnOffDebugInfo();

if ( !Warecorp::isMailServerUsed() ) $this->_redirect($this->admin->getAdminPath('templates'));

if (empty($this->params['uid'])) {
    $this->_redirect($this->admin->getAdminPath('templates/'));
}

$client = Warecorp::getMailServerTemplateClient();

if ($client->isRegisteredForImpl($this->params['uid'], HTTP_CONTEXT) == '' ) {
    throw new Warecorp_Exception('Template is not registered');          
}

$locales = Warecorp::getLocalesListWithoutRss();
           
$templateAlias = $this->params['uid'];
    
$template = $client->getTemplate( $this->params['uid'], HTTP_CONTEXT ); 

$localizations = array();

try {
    foreach ($locales as $locale) {
        $localizations[$locale] = $client->getLocalization( $this->params['uid'], HTTP_CONTEXT, $locale);
        
        $attaches = $client->getLocalizationEmbededImages( $this->params['uid'], HTTP_CONTEXT, $locale);
        if (!empty($attaches)) {
            $localizations[$locale]['attaches'] = $attaches;
        }
        
        $localizations[$locale]['pmb_subject'] = isset( $localizations[$locale]['pmb_subject'] ) ? $localizations[$locale]['pmb_subject'] : null;
        $localizations[$locale]['pmb_message'] = isset( $localizations[$locale]['pmb_message'] ) ? $localizations[$locale]['pmb_message'] : null;
        
    }
} catch ( Exception $e ) { throw $e; }

$this->view->mailTemplate = array('alias' => $templateAlias, 'template' => $template, 'localizations' => $localizations);

$this->view->setLayout('adminarea/template/exportTemplate.tpl');

$this->getResponse()
     ->setHeader('Content-Disposition', "inline; filename={$templateAlias}.xml")
     ->setHeader('Content-type', 'application/download');

