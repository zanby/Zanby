<?php
/**
*   Zanby Enterprise Group Family System
*
*    Copyright (C) 2005-2011 Zanby LLC. (http://www.zanby.com)
*
*    This program is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
*    To contact Zanby LLC, send email to info@zanby.com.  Our mailing
*    address is:
*
*            Zanby LLC
*            3611 Farmington Road
*            Minnetonka, MN 55305
*
* @category   Zanby
* @package    Zanby
* @copyright  Copyright (c) 2005-2011 Zanby LLC. (http://www.zanby.com)
* @license    http://zanby.com/license/     GPL License
* @version    <this will be auto generated>
*/

abstract class BaseWarecorp_Controller_Action extends Zend_Controller_Action {

    public $_page;
    //protected $_db;
    protected $_session;
    protected $_isAjaxAction;

    public function init()
    {
        $request = $this->getRequest();

		if ( !empty($_SESSION['__currentLocale__']) && in_array($_SESSION['__currentLocale__'], Warecorp::getLocalesList()) ) {
			$request->setParam('locale', $_SESSION['__currentLocale__']);
			unset($_SESSION['__currentLocale__']);
		} elseif ( !in_array($request->getParam('locale', NULL), Warecorp::getLocalesList()) ) {
            $request->setParam('locale', Warecorp::getDefaultLocale());
		}

        defined('LOCALE')
            || define('LOCALE', $request->getParam('locale'));

        Warecorp::$actionName           =   $request->getActionName();
        Warecorp::$controllerName       =   $request->getControllerName();
        Warecorp::$locale               =   $request->getParam('locale');

        $this->_page                    =   new Warecorp_Common_Page();
        $this->_page->setTemplate($this->view);
        $this->_page->_user             =&  Zend_Registry::get('User');
		$this->_page->Locale            =   $request->getParam('locale');
        $this->view->objRequest         =   $request;
        $this->view->MOD_NAME           =   $request->getControllerName();
        $this->view->ACTION_NAME        =   $request->getActionName();
        $this->view->MOD_ACTION_NAME    =   $request->getControllerName().'_'.$request->getActionName();
        $this->view->LOCALE             =   $request->getParam('locale');
        //$this->_db                      =   Zend_Registry::get("DB");
        $this->_isAjaxAction            =   false;

        if ( isset($_SESSION['AjaxAlertProperty']) && $_SESSION['AjaxAlertProperty'] !== null ) {
            $this->_page->showAjaxAlert($_SESSION['AjaxAlertProperty']->content, $_SESSION['AjaxAlertProperty']);
            unset($_SESSION['AjaxAlertProperty']);
        }
        if ( null !== $ajaxJsCode = $this->_page->getAjaxAlertJsCode() ) {
            $this->view->AjaxAlertJsCode =  $ajaxJsCode;
        }
        
        $this->_page->Xajax->registerUriFunction('cms_showBlockEditPopupJS', '/cms/blockeditpopupjs/');
        $this->_page->Xajax->registerUriFunction('cms_showBlockEditSave', '/cms/blockeditsave/');
        $this->_page->Xajax->registerUriFunction('setInviteProperties', '/ajax/setInviteProperties/');
        
        if ( Warecorp::isTranslateOnlineDebugMode() ) {
            $this->_page->Xajax->registerUriFunction ('showTranslatePopup', '/ajax/showTranslatePopup/') ;
        }
    }

    public function preDispatch()
    {
        $user           = Zend_Registry::get('User');
        $request        = $this->getRequest();
        $controllerName = $request->getControllerName();
        $actionName     = $request->getActionName();

        if ( !$user->getId() ) {      
            //$this->view->setLayout('main_wide.tpl');
            /**
             * Choose configuration file
             * if file exits in root access folder get it else
             * get configuration file from ESA|EIA folder
             */
            if ( file_exists( ACCESS_RIGHTS_DIR.'anonymous_allowed.xml' ) ) {
                $cfg_access_file = ACCESS_RIGHTS_DIR.'anonymous_allowed.xml';
            } elseif ( file_exists( ACCESS_RIGHTS_DIR.IMPLEMENTATION_TYPE.DIRECTORY_SEPARATOR.'anonymous_allowed.xml' ) ) {
                $cfg_access_file = ACCESS_RIGHTS_DIR.IMPLEMENTATION_TYPE.DIRECTORY_SEPARATOR.'anonymous_allowed.xml';
            } else {
                throw new Zend_Exception( 'Configuration file \'anonymous_allowed.xml\' was not found.' );
            }
            $anonymousAccess    = new Warecorp_Access();
            $anonymousAccess->loadXmlConfig( $cfg_access_file );

            $allowAction        = 
                ( $controllerName === 'widget' )          || 
                ( $controllerName === 'adminarea' )       ||
                ( $actionName     === 'loginAjax' )       ||
                ( $controllerName === 'registration' )    ||
                ( $controllerName === 'users' && in_array( strtolower($actionName), array('login', 'restore', 'restorepassword') ))      ||
                ( $controllerName === 'info'  && !in_array( strtolower($actionName), array('listsviewadd', 'listsranking', 'version') )) ||
                ( $controllerName === 'ajax'  && in_array( strtolower($actionName ), array('loginavailable', 'detectcountry', 'zipcodeavailable', 'cityavailable', 'citychoosealias', 'citychoosecustom') ));
            
            if ( !$allowAction ) {
                if ( !$anonymousAccess->isAllowed( 'global', '*' ) ) {
                    if ( $this->_page->Xajax->getRequestMode() == -1 ) {
                            $anonymousAccess->redirectToLogin();
                    } else {
                        $anonymousAccess->redirectToLoginXajax( $this->_page->Xajax );
                    }
                } elseif ( !$anonymousAccess->isAllowed( $controllerName, $actionName ) ) {
                    if ( $this->_page->Xajax->getRequestMode() == -1 ) {
                            $anonymousAccess->redirectToLogin();
                    } else {
                        $anonymousAccess->redirectToLoginXajax( $this->_page->Xajax );
                    }
                }
            }
        }

        if ( $request->getControllerName() === 'groups' ) {
            if ( $this->_hasParam('groupname') ) {
                /** Param 'groupname' comes from Route variable ':groupname' in Bootstrap.php **/
                $this->_setParam('name', $this->_getParam('groupname'));
            }
            /**
             * Find current group by request params
             * by name or by groupId
             */
            /*
            if ( $request->getParam('name', false) || $request->getParam('groupid', false) ) {
                if ( $request->getParam('name', false) && Warecorp_Group_Simple::isGroupExists('group_path', $request->getParam('name')) ) {
                    if ( in_array($request->getActionName(), array('index', 'default', 'main')) )
                       $this->_forward('summary', 'groups', null, $this->getParams()); 
                } elseif ( $request->getParam('groupid', false) && Warecorp_Group_Simple::isGroupExists('id', $request->getParam('groupid')) ) {
                    if ( in_array($request->getActionName(), array('index', 'default', 'main')) )
                       $this->_forward('summary', 'groups', null, $this->getParams());
                } else
                    $this->_forward('page404', 'groups');
            } else {
                if ( !in_array(
                    strtolower($request->getActionName()), 
                    array('index','browse','search','searchonchangecountry','searchonchangestate','updatepostsposition','events','familylanding')
                ) )
                    $this->_redirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/groups/summary/');
            }
            */
        } else if ( $request->getControllerName() === 'adminarea' ) {
            $this->view->setLayout('main_admin.tpl');
        }
    }

    /**
     * Dispatch the requested action
     *
     * @param string $action Method name of action
     * @return void
     */
    public function dispatch($action)
    {
        // Notify helpers of action preDispatch state
        $this->_helper->notifyPreDispatch();

        $this->preDispatch();
        if ($this->getRequest()->isDispatched()) {
            if (null === $this->_classMethods) {
                $this->_classMethods = get_class_methods($this);
            }

            // preDispatch() didn't change the action, so we can continue
            if ($this->getInvokeArg('useCaseSensitiveActions') || in_array($action, $this->_classMethods)) {
                if ($this->getInvokeArg('useCaseSensitiveActions')) {
                    trigger_error('Using case sensitive actions without word separators is deprecated; please do not rely on this "feature"');
                }
                $this->$action();
            } else {
                $classMethods = $this->_classMethods;
                foreach ( $classMethods as &$val )
                    $val = strtolower($val);
                if ( in_array(strtolower($action), $classMethods ) ) {
                    $this->$action();
                } else {
                    $this->__call($action, array());
                }
            }
            $this->postDispatch();
        }

        // whats actually important here is that this action controller is
        // shutting down, regardless of dispatching; notify the helpers of this
        // state
        $this->_helper->notifyPostDispatch();
    }
    /**
     *
     */
    public function postDispatch()
    {
        $this->_page->initAjax();

        //add breadcrumb
        if ($this->_page->hideBreadcrumb === false){
            $this->view->breadcrumb = $this->_page->breadcrumb;
        }

        // ajax alert
        if ( isset($_SESSION['AjaxAlertProperty']) && $_SESSION['AjaxAlertProperty'] !== null ) {
            $this->_page->showAjaxAlert($_SESSION['AjaxAlertProperty']->content, $_SESSION['AjaxAlertProperty']);
            unset($_SESSION['AjaxAlertProperty']);
        }
        if ( null !== $ajaxJsCode = $this->_page->getAjaxAlertJsCode() ) {
            $this->view->AjaxAlertJsCode = $ajaxJsCode;
        }

        /**
         * Facebook Post Feed Dialog
         *
         */
        if ( FACEBOOK_USED ) {
            if (!defined('FB_JS_ASSIGNED')) {
                $fbJsInit = Warecorp_Facebook_Feed::onPageInit();
                $this->view->fbJsInit = $fbJsInit;
                
                if ( !empty($fbJsInit) )    $this->view->denyAutoRedirect = true;
                else                        $this->view->denyAutoRedirect = false;

                define('FB_JS_ASSIGNED', true);
            }
        }
        
        $this->view->strWP_ZSSO_IFrame = Warecorp_Wordpress_SSO::onControllerPostDispatch();
    }
    
    /**
     * Works as setter or getter
     * @param boolean|null $value
     * @return boolean|Warecorp_Controller_Action
     */
    public function isAjaxAction( $value = null )
    {
        if ( null === $value ) {
            return (boolean) $this->_isAjaxAction;
        } else {
            $this->_isAjaxAction = (boolean) $value;
            return $this;
        }
    }
    
    /**
     * redirect to error page
     * @author Artem Sukharev
     */
    public function _redirectError($message = "Error Occured")
    {
        $this->view->message = $message;
        $this->view->bodyContent = 'customerror.tpl';
        $this->view->render($this->_page->Template->layout); exit;
    }
    
    /**
     * redirect to login page
     * @author Artem Sukharev
     */
    public function _redirectToLogin($return_page_url = null)
    {

    	if ( $this->_page->issetAjaxAlert() ) {
           $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
        }    	
        if ( $return_page_url !== null ) $_SESSION['login_return_page'] = $return_page_url;
        else $_SESSION['login_return_page'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

        $this->_redirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
    }
    
    /**
     * Redirect to another URL
     *
     * By default, emits a 302 HTTP status header, prepends base URL as defined 
     * in request object if url is relative, and halts script execution by 
     * calling exit().
     *
     * $options is an optional associative array that can be used to control 
     * redirect behaviour. The available option keys are:
     * - exit: boolean flag indicating whether or not to halt script execution when done
     * - prependBase: boolean flag indicating whether or not to prepend the base URL when a relative URL is provided
     * - code: integer HTTP status code to use with redirect. Should be between 300 and 307.
     *
     * _redirect() sets the Location header in the response object. If you set 
     * the exit flag to false, you can override this header later in code 
     * execution.
     *
     * If the exit flag is true (true by default), _redirect() will write and 
     * close the current session, if any.
     *
     * @param string $url
     * @param array $options Options to be used when redirecting
     * @return void
     */
    protected function _redirect($url, array $options = array())
    {
    	if ( $this->_page->issetAjaxAlert() ) {
    	   $_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
    	}
    	
    	parent::_redirect($url, $options);
    }

    /**
     * Proxy for undefined methods.  Default behavior is to throw an
     * exception on undefined methods, however this function can be
     * overridden to implement magic (dynamic) actions, or provide run-time 
     * dispatching.
     *
     * @param string $methodName
     * @param array $args
     */
    public function __call($methodName, $args)
    {
        $this->view->bodyContent = 'index/404.tpl';
        /*
        if (empty($methodName)) {
            $msg = 'No action specified and no default action has been defined in __call() for '
                 . get_class($this);
        } else {
            $msg = get_class($this) . '::' . $methodName
                 .'() does not exist and was not trapped in __call()';
        }

        throw new Zend_Controller_Exception($msg);
        */
    }

    public function page404Action()
    {
        $this->view->bodyContent = 'index/404.tpl';
    }
}

