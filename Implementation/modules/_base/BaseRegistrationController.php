<?php

class BaseRegistrationController extends Warecorp_Controller_Action
{
    public $params;
	public function init()
    {
        Warecorp::addTranslation('/modules/registration/registration.controller.php.xml');
        
    	parent::init();
        $this->params = $this->_getAllParams();
    	$this->_page->setTitle(Warecorp::t('Registration'));
		$this->view->isRightBlockHidden = true;
    	//@todo author Akexander Komarovski $this->view->menuContent = '_design/menu_content/menu_content.tpl';
		
		$this->view->setLayout('main_wide.tpl');
    }

    public static function addRequestAction($mailObj, $returnedEmailParams, $params)
    {        
        $request = $params['request'];
        $request->addRelation($mailObj->message);
    }
    
    public function indexAction()                             {include(PRODUCT_MODULES_DIR.'/registration/action.index.php');}
    public function completedAction()                         {include(PRODUCT_MODULES_DIR.'/registration/action.completed.php');}
    public function sentforapproveAction()                    {include(PRODUCT_MODULES_DIR.'/registration/action.sentforapprove.php');}
    protected function processedAction($approveResult = null) {include(PRODUCT_MODULES_DIR.'/registration/action.processed.php');}
    public function registrationcompletedAction()             {include(PRODUCT_MODULES_DIR.'/registration/action.registrationcompleted.php');}
    public function confirmAction()                           {include(PRODUCT_MODULES_DIR.'/registration/action.confirm.php');}
    public function confirmcompletedAction()                  {include(PRODUCT_MODULES_DIR.'/registration/action.confirmcompleted.php');}

	public function noRouteAction()
	{
        $this->_redirect('/');
	}
}
