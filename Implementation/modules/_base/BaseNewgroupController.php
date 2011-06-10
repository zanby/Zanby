<?php

class BaseNewgroupController extends Warecorp_Controller_Action
{
	public function init()
    {
        Warecorp::addTranslation('/modules/newgroup/newgroup.controller.php.xml');
    	parent::init();

        $this->params = $this->_getAllParams();
    	$this->_page->setTitle(Warecorp::t('Create Group'));
    	if ( !$this->_page->_user->isAuthenticated() ) {
    	   $this->_redirectToLogin();
    	}

        if ( 'EIA' == IMPLEMENTATION_TYPE ) {
            $needRedirect = true;
            $primaryFamily = Zend_Registry::get('globalGroup'); //fix by komarovski

            if ( $primaryFamily != null && $primaryFamily->getId() != null ) {
                $primaryMembersList = $primaryFamily->getMembers();
                if  ( $primaryMembersList->isHost($this->_page->_user->getId()) || $primaryMembersList->isCohost($this->_page->_user->getId()) ) {
                    $needRedirect = false;
                }
                elseif ( (int)$primaryFamily->getPrivileges()->getGroupsCreation() === 1 ) {
                    $userGroups = $this->_page->_user->getGroups()->setMembersRole(array('host', 'cohost'))->setTypes('simple')->returnAsAssoc(true)->getList();
                    $familiesGroups = $primaryFamily->getGroups()->returnAsAssoc(true)->getList();
                    if ( is_array($userGroups) && is_array($familiesGroups) && array_intersect_key($familiesGroups, $userGroups) ) {
                        $needRedirect = false;
                    }
                }
                elseif ( (int)$primaryFamily->getPrivileges()->getGroupsCreation() === 2 ) {
                    if ( $primaryMembersList->isMemberExistsAndApproved($this->_page->_user->getId()) ) {
                        $needRedirect = false;
                    }
                }
                elseif ( (int)$primaryFamily->getPrivileges()->getGroupsCreation() === 3 ) {
                    $aprovedUsers = $primaryFamily->getPrivileges()->getUsersListByTool('gpCreateGroup')->returnAsAssoc(true)->getList();
                    if ( array_key_exists($this->_page->_user->getId(), $aprovedUsers) ) {
                        $needRedirect = false;
                    }
                }
            }
            if ( $needRedirect ) $this->_redirect('/');
        }

    	//@todo author Akexander Komarovski $this->view->menuContent = '_design/menu_content/menu_content.tpl';
    }

    public function indexAction()  {include_once(PRODUCT_MODULES_DIR.'/newgroup/action.newgroup.index.php');}
    public function step1Action()  {include_once(PRODUCT_MODULES_DIR.'/newgroup/action.newgroup.step1.php');}
    public function step2Action()  {include_once(PRODUCT_MODULES_DIR.'/newgroup/action.newgroup.step2.php');}

    public function saveTempDataAction($data, $stepfrom, $stepto)    {include_once(PRODUCT_MODULES_DIR.'/newgroup/xajax/action.saveTempData.php'); return $objResponse; }
    public function addressbookAjaxUtilitiesAction($params=null, $page=null, $pageSize=null, $orderby=null, $direction=null, $filter=null)                       {include_once(PRODUCT_MODULES_DIR.'/newgroup/xajax/action.addressbookAjaxUtilities.php'); return $objResponse;}
	public function addaddressesAction($params) 					{include_once(PRODUCT_MODULES_DIR.'/newgroup/xajax/action.addaddresses.php'); return $objResponse;}
	public function noRouteAction()		{ $this->_redirect('/'); }
}
