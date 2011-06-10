<?php
$form = new Warecorp_Form('pdForm', 'POST', $this->admin->getAdminPath('newpage/'));	
$page = new Warecorp_CMS_Page_Item();
$page->template = "cms/templates/default.tpl";
if ($form->isPostback())
{
	$page->alias = $this->params['alias'];
	$page->title = $this->params['title'];
	$page->template = $this->params['template'];
	$page->save();
	
	$this->_redirect($this->admin->getAdminPath('pages/'));
}
$this->view->page = $page;
$this->view->form = $form;
$template = 'adminarea/cms/page.tpl';	

$this->view->bodyContent = $template;
