<?php
$form = new Warecorp_Form('tdForm', 'POST', $this->admin->getAdminPath('newtemplate/'));	
$template = new Warecorp_Mail_Template();
if ($form->isPostback()) {
	$template->templateKey = $this->params['template_key'];
	$template->description = $this->params['description'];
	$template->createDate = date('Y-m-d H:i:s');
	$template->changeDate = date('Y-m-d H:i:s');
	$template->content = $this->params['content'];
	$template->context = '';
	$template->isFixed = 0;
	$template->isHidden = 0;
	$template->save();
	// save LOG
    $this->appendLog('templates',0,'new'); 
	
	$this->_redirect($this->admin->getAdminPath('templates/'));
}
$this->view->template = $template;
$this->view->form = $form;
$template = 'adminarea/template.tpl';	

$this->view->bodyContent = $template;
