<?php
class BaseNewfamilygroupController extends Warecorp_Controller_Action
{
    public function init()
    {
        Warecorp::addTranslation('/modules/newfamilygroup/newfamilygroup.controller.php.xml');
        parent::init();

        $this->params = $this->_getAllParams();
        $this->_page->setTitle(Warecorp::t('Create Group Family'));
        if ( !$this->_page->_user->isAuthenticated() ) {
            $this->_redirectToLogin();
        }
        $this->view->setLayout('main_wide.tpl');
        $this->view->isRightBlockHidden = true;
    }

    public function indexAction()  {include_once(PRODUCT_MODULES_DIR.'/newfamilygroup/action.newfamilygroup.index.php');}
    public function step1Action()  {include_once(PRODUCT_MODULES_DIR.'/newfamilygroup/action.newfamilygroup.step1.php');}
    public function step2Action()  {include_once(PRODUCT_MODULES_DIR.'/newfamilygroup/action.newfamilygroup.step2.php');}
    public function step3Action()  {include_once(PRODUCT_MODULES_DIR.'/newfamilygroup/action.newfamilygroup.step3.php');}
    public function step4Action()  {include_once(PRODUCT_MODULES_DIR.'/newfamilygroup/action.newfamilygroup.step4.php');}
    public function step5Action()  {include_once(PRODUCT_MODULES_DIR.'/newfamilygroup/action.newfamilygroup.step5.php');}
    public function successAction(){include_once(PRODUCT_MODULES_DIR.'/newfamilygroup/action.newfamilygroup.success.php');}

    /**
     *  remove this actions
     *  @author Artem Sukharev 
     */
    public function loadUserInfoAction()                        {include_once(PRODUCT_MODULES_DIR.'/newfamilygroup/xajax/action.loadUserInfo.php'); return $objResponse; }
    public function saveTempDataAction($data, $step, $gotoStep) {include_once(PRODUCT_MODULES_DIR.'/newfamilygroup/xajax/action.saveTempData.php'); return $objResponse; }

    public function noRouteAction()		{ $this->_redirect('/'); }
}
