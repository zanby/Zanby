<?php

class BaseSearchController extends Warecorp_Controller_Action
{
	public function init()
    {
        Warecorp::addTranslation('/modules/search/search.controller.php.xml');

        parent::init();
    	$this->_page->setTitle(Warecorp::t('Search'));
        $this->params = $this->_getAllParams();
        if (!isset($this->params['keywords'])){
            if (isset($_SESSION['gs_keyword'])){
                $this->params['keywords'] = strip_tags($_SESSION['gs_keyword']);
            }
            else{
                $this->params['keywords'] = '';
            } 
        }
        else{
            $_SESSION['gs_keyword'] = strip_tags($this->params['keywords']);
        }
        $this->view->keywords_gs = strip_tags($this->params['keywords']);
		$this->view->setLayout('main_wide.tpl');		
    }

    public function indexAction()		    { include_once(PRODUCT_MODULES_DIR.'/search/action.search.php'); }
    public function searchAction()          { include_once(PRODUCT_MODULES_DIR.'/search/action.search.php'); } 
    public function groupsAction()          { include_once(PRODUCT_MODULES_DIR.'/search/action.groups.php'); } 
    public function membersAction()         { include_once(PRODUCT_MODULES_DIR.'/search/action.members.php');} 
    public function photosAction()          { include_once(PRODUCT_MODULES_DIR.'/search/action.photos.php'); } 
    public function videosAction()          { include_once(PRODUCT_MODULES_DIR.'/search/action.videos.php'); } 
    public function discussionsAction()     { include_once(PRODUCT_MODULES_DIR.'/search/action.discussions.php'); } 
    public function eventsAction()          { include_once(PRODUCT_MODULES_DIR.'/search/action.events.php'); } 
    public function listsAction()           { include_once(PRODUCT_MODULES_DIR.'/search/action.lists.php'); } 
    public function documentsAction()       { include_once(PRODUCT_MODULES_DIR.'/search/action.documents.php'); } 
    public function photoAddToMyAction()    { include_once(PRODUCT_MODULES_DIR.'/search/xajax/photo.add.to.my.php'); }
    public function videoAddToMyAction()    { include_once(PRODUCT_MODULES_DIR.'/search/xajax/video.add.to.my.php'); }
    public function eventAddToMyAction()    { include_once(PRODUCT_MODULES_DIR.'/search/xajax/event.add.to.my.php'); }
    public function documentAddToMyAction() { include_once(PRODUCT_MODULES_DIR.'/search/xajax/document.add.to.my.php'); }
    public function listAddToMyAction()     { include_once(PRODUCT_MODULES_DIR.'/search/xajax/list.add.to.my.php'); }
	public function noRouteAction()		    { $this->_redirect('/'); }
}
