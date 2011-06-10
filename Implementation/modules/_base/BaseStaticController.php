<?php
/**
 * @author Vasili Ovchinnikov, Serge Rybakov
 *
 */
class BaseStaticController extends Warecorp_Controller_Action
{
    public $params;
	public function init()
    {
        Warecorp::addTranslation('/modules/static/static.controller.php.xml');
        
    	parent::init();
        $this->_page->setTitle(Warecorp::t('Page'));
        $this->params = $this->_getAllParams();
    }
    public function noRouteAction()		{$this->_redirect('/'); }
    public function renderAction()       {include_once(PRODUCT_MODULES_DIR.'/static/action.render.php');}
}
