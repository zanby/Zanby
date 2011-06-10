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


require_once 'Zend/Controller/Dispatcher/Standard.php';

class BaseWarecorp_Controller_Dispatcher extends Zend_Controller_Dispatcher_Standard
{
    /**
     * dispatch 
     * 
     * @param Zend_Controller_Request_Abstract $request 
     * @param Zend_Controller_Response_Abstract $response 
     * @access public
     * @return void
     */
    public function dispatch(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response)
    {
        $this->setResponse($response);

        /**
         * Get controller class
         */
        if (!$this->isDispatchable($request)) {
            $controller = $request->getControllerName();
            //var_dump($controller);
            if (!$this->getParam('useDefaultControllerAlways') && !empty($controller)) {
                if (ERRORS_DISPLAY_MODE) {
                    require_once 'Zend/Controller/Dispatcher/Exception.php';    
                    throw new Zend_Controller_Dispatcher_Exception('Invalid controller specified (' . $request->getControllerName() . ')');
                }
            }
            $className = $this->getDefaultControllerClass($request);
        } else {
            $className = $this->getControllerClass($request);
            if (!$className) {
                $className = $this->getDefaultControllerClass($request);
            }
        }

        /**
         * Load the controller class file
         */
        $className = $this->loadClass($className);

        //  XAJAX CALL
        //__________________________________________________________________
        if ( isset($_GET["xajax"]) || isset($_POST["xajax"]) ) {
            $function_name = ( isset($_GET["xajax"]) ) ? $_GET["xajax"] : $_POST["xajax"];            
            if ( $function_name == "" ) {
                $className = ucfirst($request->getParam('xajaxcontext', 'ajax')).'Controller';      
                //@Zend_Loader::loadClass($className, array(MODULES_DIR.'/'.$request->getParam('xajaxcontext', 'ajax')));
				@Zend_Loader::loadClass($className, array(MODULES_DIR));
                $controller = new $className($request, $this->getResponse(), $this->getParams()); 
                if (!$controller instanceof Zend_Controller_Action) {
                    throw new Zend_Controller_Dispatcher_Exception("Controller '$className' is not an instance of Zend_Controller_Action");
                }
                $controller->isAjaxAction(true);
                $action = $this->getActionMethod($request);
                $doCall = !method_exists($controller, $action);
                $request->setDispatched(true);
                $controller->preDispatch();
                if ($request->isDispatched()) {
                    if ( !$doCall ) {
                		$sContentHeader = "Content-type: text/xml;";
                		if ($controller->_page->Xajax->sEncoding && strlen(trim($controller->_page->Xajax->sEncoding)) > 0) {
                			$sContentHeader .= " charset=".$controller->_page->Xajax->sEncoding;
                	    }
                		$xajaxargs = $controller->_page->Xajax->getRequestParams();
                        $sResponse = call_user_func_array(array(&$controller, $action), $xajaxargs);
                        if (is_a($sResponse, "xajaxResponse")) {
        					$sResponse = $sResponse->getXML();
        				}
                        Warecorp_Debug::analyse('undefined', false);
        				header($sContentHeader);
        				print $sResponse; exit;
                    }
                }
                exit;
            } else {
                $controller = new $className($request, $this->getResponse(), $this->getParams());
                $controller->_page->needXajaxInit = true;
            }
        }
        //End xajax call
        //----------------------------------------------------------------------------------------------------
        else {
            /**
             * Instantiate controller with request, response, and invocation
             * arguments; throw exception if it's not an action controller
             */
            $controller = new $className($request, $this->getResponse(), $this->getParams());
        }

        if (!($controller instanceof Zend_Controller_Action_Interface) &&
            !($controller instanceof Zend_Controller_Action)) {
            require_once 'Zend/Controller/Dispatcher/Exception.php';
            throw new Zend_Controller_Dispatcher_Exception(
                'Controller "' . $className . '" is not an instance of Zend_Controller_Action_Interface'
            );
        }

        /**
         * Retrieve the action name
         */
        $action = $this->getActionMethod($request);

        /**
         * Dispatch the method call
         */
        $request->setDispatched(true);

        // by default, buffer output
        $disableOb = $this->getParam('disableOutputBuffering');
        $obLevel   = ob_get_level();
        if (empty($disableOb)) {
            ob_start();
        }

        try {
            $controller->dispatch($action);
        } catch (Exception $e) {
            // Clean output buffer on error
            $curObLevel = ob_get_level();
            if ($curObLevel > $obLevel) {
                do {
                    ob_get_clean();
                    $curObLevel = ob_get_level();
                } while ($curObLevel > $obLevel);
            }
            throw $e;
        }

        if (empty($disableOb)) {
            $content = ob_get_clean();
            $response->appendBody($content);
        }

        // Destroy the page controller instance and reflection objects
        $controller = null;
    }
}
