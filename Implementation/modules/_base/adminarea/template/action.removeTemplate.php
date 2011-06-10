<?php

if (!empty($this->params['uid']) ) {
    $client = Warecorp::getMailServerTemplateClient();
    $client->unregisterTemplate($this->params['uid'], HTTP_CONTEXT);          
}

$this->_redirect($this->admin->getAdminPath('templates/'));
