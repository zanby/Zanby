<?php

class BaseIndexController extends Warecorp_Controller_Action
{
    public $params;
    protected $currentGroup;
    
	/**
	 * Class constructor 
	 */
    public function init()
    {
        Warecorp::addTranslation('/modules/index/index.controller.php.xml');
        
    	parent::init();
        $this->_page->setTitle(Warecorp::t('Homepage'));
        $this->params = $this->_getAllParams();
  
         
        /**
         * Detect global group for 2a-b implementations
         * @author Artem Sukharev
         */
        $esaAllowedActions = array('index', 'sso', 'groups', 'event', 'changelanguage');   
        if ( 'ESA' == IMPLEMENTATION_TYPE ) {
            if ( !in_array(Warecorp::$actionName, $esaAllowedActions) ) $this->_forward('index', 'index');
        } else {
	        if ( Zend_Registry::isRegistered('globalGroup') ) {
	        	require_once(MODULES_DIR.'/GroupsController.php');
	            $this->currentGroup = Zend_Registry::get('globalGroup');   
	        } else $this->_forward('index', 'index');
            
            /* for main family 2a-b implementetion if it isn't family stuff we must apply wide layout */
            if ( !Warecorp::is('Stuff', 'Group') ) {
                $this->view->setLayout('main_wide.tpl');
                //$this->view->isRightBlockHidden = true;
            } else {
                $this->view->setLayout('main.tpl');
            }
            
        }
    }
    public function noRouteAction()		{$this->_redirect('/'); }

    /**
     * +------------------------------------------------------------------------
     * | ESA & EIA Actions
     * | @author Artem Sukharev
     * +------------------------------------------------------------------------
     */
    
    public function indexAction() 
    {                             
    	if ( 'ESA' == IMPLEMENTATION_TYPE ) {
    		include_once(PRODUCT_MODULES_DIR.'/index/action.index.php');
    	} else {
            $this->_forward('summary', 'groups', array('groupid' => $this->currentGroup->getId()));
    	}
    }

	public function changelanguageAction()
	{
		$_SESSION['__currentLocale__'] = $this->getRequest()->getParam('setlocale', 'en');
		$objResponse = new xajaxResponse();
		$objResponse->addScript('document.location.reload();');
        $objResponse->printXml($this->_page->Xajax->sEncoding);
        exit;
	}
	
    /**
     * 
     */
    public function ssoAction() 
    {
        $apiKey = $this->getRequest()->getParam('api_key', null);
        /**
         * 
         */
        if ( null === $apiKey ) {
            $redirectUrl        = $this->getRequest()->getParam('redirect', null);
            $redirectEncodedUrl = ( null !== $redirectUrl ) ? base64_decode($redirectUrl) : null;        
            
            //$redirectUrl = 'http://belarus-on-zanby.groups.zanby.sukharev.buick/en/summary/';
            /**
             * Loggined user
             */
            if ( $this->_page->_user && null !== $this->_page->_user->getId() ) {
                /**
                 * create api_key
                 */
                $apiKey = microtime().'_'.$this->_page->_user->getId().'_'.BASE_HTTP_HOST;
                $apiKey = sha1($apiKey);
                $apiKeyData = array();
                $apiKeyData['uid']          = $this->_page->_user->getId();
                $apiKeyData['redirect']     = $redirectUrl;
                $apiKeyData['mode']         = ( isset($_COOKIE['zanby_username']) && isset($_COOKIE['zanby_password']) ) ? 1 : 0;
                $apiKeyData = serialize($apiKeyData);
                
                $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
                $cache->save($apiKeyData, $apiKey, array('api_keys'), 10);
                /**
                 * redirect to sso
                 */
                $parse  = parse_url($redirectEncodedUrl);
                $url    = $parse['scheme'].'://'.$parse['host'];
                $ssoUrl = $url.'/en/index/sso/api_key/'.$apiKey.'/';
                $this->_redirect($ssoUrl);
            } 
            /**
             * Anonymous user - just redirect to url
             */
            else { $this->_redirect($redirectEncodedUrl); }     
            exit;
        }
        /**
         * 
         */
        else {
            $cache  = $this->getInvokeArg("bootstrap")->getResource("FileCache");
            if ( $apiKeyData = $cache->load($apiKey) ) {                
                $cache->remove($apiKey);
                                
                $apiKeyData         = unserialize($apiKeyData);
                $redirectUrl        = $apiKeyData['redirect'];
                $redirectEncodedUrl = ( null !== $redirectUrl ) ? base64_decode($redirectUrl) : null;        
                                
                $this->_page->_user = new Warecorp_User('id', $apiKeyData['uid']);
                if ( $this->_page->_user && null !== $this->_page->_user->getId() ) {
                    /**
                     * clear old session & cookie data
                     */
                    unset($_SESSION, $_COOKIE);                    
                    
                    $this->_page->_user->authenticate();
                    if ( $apiKeyData['mode'] == 1 ) {
                        setcookie("zanby_username", $this->_page->_user->getLogin(), time()+2592000, "/",'.'.BASE_HTTP_HOST);   //  2592000 = 60*60*24*30
                        setcookie("zanby_password", md5($this->_page->_user->getPass()), time()+2592000, "/",'.'.BASE_HTTP_HOST);
                    }
                }
            } else { $this->_redirect(BASE_URL); }
            /**
             * redirect to requested page
             */
            $request = new Warecorp_Controller_Request_Http($redirectEncodedUrl); 
            $router = new Warecorp_Controller_Router();
            $router->route($request);
            if ( 'groups' == $request->getControllerName() ) {
                $objGroup = null;
                if ( $groupName = $request->getParam('name', null) ) {
                    $objGroup = Warecorp_Group_Factory::loadByPath($groupName);
                } elseif ( $groupId = $request->getParam('groupid', null) ) {
                    $objGroup = Warecorp_Group_Factory::loadById($groupId);
                }
                if ( $objGroup && null !== $objGroup->getId() ) {
                    $query = array();
                    foreach ( $request->getParams() as $_name => $_value ) {
                        if ( $_name !== 'name' && $_name != 'groupId' ) {
                            $query[] = $_name;
                            $query[] = $_value;
                        }
                    }
                    $query = join("/", $query);
                    $query = ( $query ) ? $query.'/' : $query;
                    $redirectEncodedUrl = $objGroup->getGroupPath($request->getActionName()).$query;
                }
            }

            $this->_redirect($redirectEncodedUrl);
        }
    }
    
    /**
     * +------------------------------------------------------------------------
     * | EIA Actions only
     * | if implementatin is ESA it should redirect to Home Page
     * | @author Artem Sukharev
     * +------------------------------------------------------------------------
     */
    public function groupsAction() {
        $this->_forward('members', 'groups', array('groupid' => $this->currentGroup->getId()));
    } 
    public function eventsAction() {
        $this->_forward('calendar.list.view', 'groups', array('groupid' => $this->currentGroup->getId()));
    }    
    public function videosAction() {
        $this->_forward('videos', 'groups', array('groupid' => $this->currentGroup->getId()));
    } 
    public function photosAction() {
        $this->_forward('photos', 'groups', array('groupid' => $this->currentGroup->getId()));
    } 
    public function listsAction() {
        $this->_forward('lists', 'groups', array('groupid' => $this->currentGroup->getId()));
    } 
    public function discussionAction() {
        
        $this->_forward('discussion', 'groups', array('groupid' => $this->currentGroup->getId()));
    } 
    public function blogAction() {     
        $this->_forward('blog', 'groups', array('groupid' => $this->currentGroup->getId()));
    } 
    public function documentsAction() {
        $this->_forward('documents', 'groups', array('groupid' => $this->currentGroup->getId()));
    } 
    public function settingsAction() {
        $this->_forward('settings', 'groups', array('groupid' => $this->currentGroup->getId()));
    } 
}
