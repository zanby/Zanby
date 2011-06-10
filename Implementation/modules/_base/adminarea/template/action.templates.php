<?php

if ( isset($this->params['uid']) ) { 
    $form = new Warecorp_Form('tdForm', 'POST', $this->admin->getAdminPath('templates/uid/'.$this->params['uid'].'/'));
           
    if ( !Warecorp::isMailServerUsed() ) $this->_redirect($this->admin->getAdminPath('templates'));
    try { $client = Warecorp::getMailServerTemplateClient(); }
    catch ( Exception $e ) { throw $e; }
    
    if ( $form->isPostback() ) {
        try { 
            $client->setDescription( $this->params['uid'], HTTP_CONTEXT, $this->params['description']);
            $client->updateLocalization( $this->params['uid'], HTTP_CONTEXT, $client->getDefaultLocale(), $this->params['body_html'], $this->params['body_plain'], $this->params['subject'] );
            $this->params['pmb_subject'] = trim($this->params['pmb_subject']);
            $this->params['pmb_message'] = trim($this->params['pmb_message']);
            if ( $this->params['pmb_subject'] != '' || $this->params['pmb_message'] != '' ) {
                $client->addPMBMessage( $this->params['uid'], HTTP_CONTEXT, $client->getDefaultLocale(), $this->params['pmb_subject'], $this->params['pmb_message'] );
            } 
        } catch ( Exception $e ) { throw $e; } 

        $cache = Warecorp_Cache::getCache('memory');
        // TODO Alex Che Add remove by tag (mailtemplates) functinality
        $cache->remove('registeredTemplateUids');
        
        // save LOG
        $this->appendLog('templates',$this->params['id'],'edit');
        
        $this->_redirect($this->admin->getAdminPath('templates/'));
    } else {
        try { $template = $client->getTemplate( $this->params['uid'], HTTP_CONTEXT ); }
        catch ( Exception $e ) { throw $e; }
        
        try { $localization = $client->getLocalization( $this->params['uid'], HTTP_CONTEXT, $client->getDefaultLocale() ); }
        catch ( Exception $e ) { throw $e; }

        $localization['pmb_subject'] = isset( $localization['pmb_subject'] ) ? $localization['pmb_subject'] : null;
        $localization['pmb_message'] = isset( $localization['pmb_message'] ) ? $localization['pmb_message'] : null;
        
        $this->view->template = $template;
        $this->view->localization = $localization;
    }
    
    $this->view->form = $form;    
    $template = 'adminarea/template/edit.tpl';       
} else {
    
    // Mailserver is not configured
    if ( !Warecorp::isMailServerUsed() ) {
        $template = 'adminarea/template/no-config.tpl';    
    } else {

        try { $client = Warecorp::getMailServerTemplateClient(); }
        catch ( Exception $e ) { throw $e; }
            
        $cfgMailSrvServiceTemplates = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.mailsrv.service.templates.xml')->{'templates'};
        $lstTemplates = array();

        $templates = $client->getRegisteredTemplates(HTTP_CONTEXT);

        $this->view->templatesList = $templates;
        $template = 'adminarea/template/list.tpl';  
    }
}

$this->view->bodyContent = $template;
