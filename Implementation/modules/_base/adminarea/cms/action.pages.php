<?php
if (isset($this->params['id'])) {
	$page = new Warecorp_CMS_Page_Item($this->params['id']);
	$form = new Warecorp_Form('pdForm', 'POST', $this->admin->getAdminPath('pages/id/').$this->params['id']);	
	if ($form->isPostback()) {

		$this->changeField('Alias',$page->alias,$this->params['alias']);
		$page->alias = $this->params['alias'];
		
		$this->changeField('Title',$page->title,$this->params['title']);
		$page->title = $this->params['title'];

		$this->changeField('Template',$page->template,$this->params['template']);
		$page->template = $this->params['template'];

		$page->save();
		
        // save LOG
		$this->appendLog('pages', $this->params['id'], 'edit'); 
	}
	$this->view->page = $page;
	$this->view->form = $form;
	$template = 'adminarea/cms/page.tpl';	
} else {
	$pagesList = $this->admin->getAllStaticPages();
	$this->view->pagesList = $pagesList;
	$template = 'adminarea/cms/pages.tpl';	
}
$this->view->bodyContent = $template;
