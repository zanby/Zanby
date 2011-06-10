<?php

Warecorp::turnOffDebugInfo();

if ( !Warecorp::isMailServerUsed() ) $this->_redirect($this->admin->getAdminPath('templates'));

$locales = Warecorp::getLocalesListWithoutRss();

$client = Warecorp::getMailServerTemplateClient();

$mailTemplates = array();

$templates = $client->getRegisteredTemplates(HTTP_CONTEXT);

foreach ($templates as $template) {

    $tempalteName = $templateAlias = $template['uid'];
           
    try { $templateItem = $client->getTemplate( $tempalteName, HTTP_CONTEXT ); }
    catch ( Exception $e ) { throw $e; }

    $localizations = array();
    
    try {
        foreach ($locales as $locale) {
            $localizations[$locale] = $client->getLocalization( $tempalteName, HTTP_CONTEXT, $locale);
            
            $attaches = $client->getLocalizationEmbededImages( $tempalteName, HTTP_CONTEXT, $locale);
            if (!empty($attaches)) {
                $localizations[$locale]['attaches'] = $attaches;
            }
            
            $localizations[$locale]['pmb_subject'] = isset( $localizations[$locale]['pmb_subject'] ) ? $localizations[$locale]['pmb_subject'] : null;
            $localizations[$locale]['pmb_message'] = isset( $localizations[$locale]['pmb_message'] ) ? $localizations[$locale]['pmb_message'] : null;
            
        }
    } catch ( Exception $e ) { throw $e; }
    
    $mailTemplates[] = array('alias' => $templateAlias, 'template' => $templateItem, 'localizations' => $localizations);
}

$this->view->mailTemplates = $mailTemplates;

$this->view->setLayout('adminarea/template/exportTemplates.tpl');

$this->getResponse()
     ->setHeader('Content-Disposition', "inline; filename=allTemplates.xml")
     ->setHeader('Content-type', 'application/download');

