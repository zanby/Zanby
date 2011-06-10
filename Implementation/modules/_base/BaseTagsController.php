<?php

class BaseTagsController extends Warecorp_Controller_Action
{
	public function init()
    {
        Warecorp::addTranslation('/modules/tags/tags.controller.php.xml');
    	parent::init();

        $this->params = $this->_getAllParams();
    	$this->_page->setTitle(Warecorp::t('Tags'));
    	//@todo author Akexander Komarovski $this->view->menuContent = '_design/menu_content/menu_content.tpl';
    }

    public function indexAction()       {include_once(PRODUCT_MODULES_DIR.'/tags/action.index.php');}
    public function viewAction()        {include_once(PRODUCT_MODULES_DIR.'/tags/action.index.php');}
	public function noRouteAction()		{ $this->_redirect('/'); }
}
