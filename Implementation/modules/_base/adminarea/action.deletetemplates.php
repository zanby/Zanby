<?php
if (isset($this->params['id'])) {
    
    $template = $this->admin->getTemplate($this->params['id']);
    if ($template->context == HTTP_CONTEXT && !$template->isFixed)	$template->delete();	
}

$this->_redirect($this->admin->getAdminPath('templates'));
