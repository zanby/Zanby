<?php
if (isset($this->params['id'])) {
    
    $template = $this->admin->getTemplate($this->params['id']);
    
    if (!empty($template->id) && empty($template->context)){
        $template->id = null;
    	$template->context = HTTP_CONTEXT;
    	$template->save();
	}
}

$this->_redirect($this->admin->getAdminPath('templates'));
