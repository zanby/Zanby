<?php
/**
 * Warecorp Frameworkа
 *
 * @package    Warecorp
 * @copyright  Copyright (c) 2006-2007
 * @author
 */

include_once('Defines.php');

final class Warecorp
{
    static public $locale         = 'en';
    static public $controllerName = 'index';
    static public $actionName     = 'index';
    static public $defaultLocale  = 'en';

    /**
    * Zend_Translate object
    * @var
    */
    static private $translate;
    /**
    * if this param is true application allow trunslation
    * @var boolean
    */
    static private $translateMode;
    /**
    * if this param is true application clean all compiled templates per each request
    * @var boolean
    */
    static private $translateDebugMode;

    static private $translateOnlineDebugMode;
    /**
    * if this param is true application will generate automaticly missing xml file for templates
    * @var boolean
    */
    static private $translateAutoGenerateMode;
    /**
    * Current file for translation
    * @var string
    */
    static private $defaultTranslationFile;
    /**
    * @var array
    */
    static private $translationStack;
    /**
     * @var array
     */
    static private $isActions;

    /**
     * +-----------------------------------------------------------------------
     * |
     * |    TINY URL
     * |
     * +-----------------------------------------------------------------------
     */

    /**
     * @param string $url for encode to tiny format
     * @param string $context of implemenatation
     * @return string
     */
    static public function getTinyUrl($url, $context = null)
    {
        if ( null === $context ) {
            if ( defined('HTTP_CONTEXT') ) {
                $context = HTTP_CONTEXT;
            } else {
                $context = 'generalcontext';
            }
        }

        ini_set("soap.wsdl_cache_enabled", "0");

        $cfgTinyService = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.instance.xml')->{'tinyservice'};
        $cfgCredentials = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.credentials.xml')->{'tinyservice'};

        $wsu = 'http://schemas.xmlsoap.org/ws/2002/07/utility';
        $usernameToken = new stdClass();
        $usernameToken->Username = ( empty($cfgCredentials->credentials->uid) )  ? 'default' : $cfgCredentials->credentials->uid;
        $usernameToken->Password = ( empty($cfgCredentials->credentials->pass) ) ? 'default' : md5($cfgCredentials->credentials->pass);
        $soapHeaders   = new SoapHeader($wsu, 'UsernameToken', $usernameToken);

        try {
            $client = new SoapClient($cfgTinyService->wsdl, array('timeout'  => $cfgTinyService->timeout, 'trace' => true));
        } catch ( Exception $e ) {
            return $url;
        }

        try {
            $client->__setSoapHeaders($soapHeaders);
            return $client->getTinyUrl($url, BASE_URL, $context);
        }
        catch ( SoapFault $e ) {
            //print_r($e);/* do nothing */
            $resp = $client->__getLastResponse();
            print_r($resp);

        }
        catch ( Exception $e ) {/* do nothing */}
        return $url;
    }

    /**
     * @param string $tinyurl
     * @param string|null $context
     * @return string
     */
    static public function getFullUrl($tinyurl, $context = null)
    {
        $_404url = BASE_URL;

        if ( null === $context ) {
            if ( defined('HTTP_CONTEXT') ) {
                $context = HTTP_CONTEXT;
            } else {
                $context = 'generalcontext';
            }
        }

        $cfgTinyService = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.instance.xml')->{'tinyservice'};
        $cfgCredentials = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.credentials.xml')->{'tinyservice'};

        $wsu = 'http://schemas.xmlsoap.org/ws/2002/07/utility';
        $usernameToken = new stdClass();
        $usernameToken->Username = ( empty($cfgCredentials->credentials->uid) )  ? 'default' : $cfgCredentials->credentials->uid;
        $usernameToken->Password = ( empty($cfgCredentials->credentials->pass) ) ? 'default' : md5($cfgCredentials->credentials->pass);
        $soapHeaders   = new SoapHeader($wsu, 'UsernameToken', $usernameToken);

        try {
            $client = new SoapClient($cfgTinyService->wsdl, array('timeout' => $cfgTinyService->timeout));
        } catch ( Exception $e ) {
            return $_404url;
        }

        try {
            $client->__setSoapHeaders($soapHeaders);
            return $client->getFullUrl($tinyurl, $context);
        }
        catch ( SoapFault $e ) {/* do nothing */}
        catch ( Exception $e ) {/* do nothing */}

        return $_404url;
    }

    /**
     * +-----------------------------------------------------------------------
     * |
     * |    SOAP: MAIL SERVER
     * |
     * +-----------------------------------------------------------------------
     */

    /**
     * return true if application use SOAP Mailsrv
     * @return boolean
     */
    static public function isMailServerUsed()
    {
        $cfgMailSrvService = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.instance.xml')->{'mailsrv'};
        return $cfgMailSrvService->use && ( $cfgMailSrvService->use == 1 || $cfgMailSrvService->use == 'true' );
    }

    /**
     * return soap client to use Mailsrv
     * @return SoapClient
     */
    static public function getMailServerClient()
    {
        if ( !Warecorp::isMailServerUsed() ) return null;

        ini_set("soap.wsdl_cache_enabled", "0");
        //ini_set("soap.wsdl_cache_ttl", "0");
        $cfgMailSrvService  = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.instance.xml')->{'mailsrv'};
        $cfgCredentials     = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.credentials.xml')->{'mailsrv_service'};

        try {
            $client = new Warecorp_SOAP_Client_Mailsrv($cfgMailSrvService->wsdl, array('timeout' => $cfgMailSrvService->timeout));
            $client->setUsername( $cfgCredentials->credentials->uid  ? $cfgCredentials->credentials->uid  : 'username' );
            $client->setPassword( $cfgCredentials->credentials->pass ? $cfgCredentials->credentials->pass : 'password' );
        } catch ( Exception $e ) { throw $e; }

        return $client;
    }

    
    /**
     * Get template Uid by template name (use $templateKey value)
     * 
     * @param string $templateKey
     * @throws Exception
     * @deprecated
     */
    
    static public function getMailServerTemplate($templateKey)
    {
        return $templateKey;
    }

    
    /**
     * Check that template is registered
     * 
     * @param string $templateKey Template name
     * @throws Warecorp_Exception
     * @return boolean
     */
    static public function isMailServerTemplateRegistered($templateKey)
    {
        ini_set("soap.wsdl_cache_enabled", "0");
        if (!defined('HTTP_CONTEXT')) {
            throw new Warecorp_Exception('HTTP_CONTEXT is not defined');
        }
        
        $registeredTemplateUids = self::getMailServerRegisteredTemplateUids(); 
        
        if (is_array($registeredTemplateUids) && in_array($templateKey, $registeredTemplateUids)) {
            return true;
        } else {
            return false;
        }
    }
    
    
    /**
     * Get list template Uids of registered templates
     * 
     * @throws Warecorp_Exception
     * @return array
     */
    static public function getMailServerRegisteredTemplateUids()
    {
        if (!defined('HTTP_CONTEXT')) {
            throw new Warecorp_Exception('HTTP_CONTEXT is not defined');
        }
        
        $cache = Warecorp_Cache::getCache('memory');
        
        if ( ($registeredTemplateUids = $cache->load('registeredTemplateUids') ) == false) {
            // create list of registered templates
            
            // may throws exception
            $client = Warecorp::getMailServerTemplateClient();
            
            $registeredTemplateUids = array();
            $registeredTemplates = $client->getRegisteredTemplates(HTTP_CONTEXT);
            foreach ($registeredTemplates as $template) {
                $registeredTemplateUids[] = $template['uid'];
            }
            
            // save to cache
            $cache->save($registeredTemplateUids, 'registeredTemplateUids', array('mailtemplates') );
        }
        
        return $registeredTemplateUids;
    }

    
    /**
     * return soap client to use Mailsrv
     * @return SoapClient
     */
    static public function getMailServerTemplateClient()
    {
        if ( !Warecorp::isMailServerUsed() ) return null;

        ini_set("soap.wsdl_cache_enabled", "0");
        //ini_set("soap.wsdl_cache_ttl", "0");
        $cfgMailSrvService  = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.instance.xml')->{'mailsrv'};
        $cfgCredentials     = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.credentials.xml')->{'mailsrv_service'};
        $wsdl = str_replace('wsdl.php?t=service', 'wsdl.php?t=template', $cfgMailSrvService->wsdl);
        try {
            $client = new Warecorp_SOAP_Client_Mailsrv($wsdl, array('timeout' => $cfgMailSrvService->timeout));
            $client->setUsername( $cfgCredentials->credentials->uid  ? $cfgCredentials->credentials->uid.''  : 'username' );
            $client->setPassword( $cfgCredentials->credentials->pass ? $cfgCredentials->credentials->pass : 'password' );
        } catch ( Exception $e ) { throw $e; }

        return $client;
    }

    /**
     * +-----------------------------------------------------------------------
     * |
     * |    ACTIONS TOOLS
     * |
     * +-----------------------------------------------------------------------
     */

    /**
     * Parse the scheme-specific portion of the URI and place its parts into instance variables.
     *
     */
    static public function selfURL()
    {
        //global $cfgSite;
        $cfgSite = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.site.xml');
        $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
        $protocol = self::strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
        if ($cfgSite->use_port_in_URL == '1') {
            $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
        } else {$port = '';}
        return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
    }

    /**
    * @desc
    *
    * @deprecated since Product R4
    */
    static function parseURI()
    {
        throw new Zend_Exception('Function '.__METHOD__.' is depricated since Product R4');

        //$params = explode('/', $_SERVER['REQUEST_URI']);
        //$params = explode('/', $_SERVER['QUERY_STRING']);


        //if ( sizeof($params) != 0 ) {
            //$lastParam = $params[sizeof($params) - 1];
            //if ( preg_match('/\.(ico|pdf|bmp|flv|jpg|jpeg|png|gif|js|css|swf)(\?|&){0,1}(.*?)$/i', $lastParam) ) {
                //ob_start();
                //header("HTTP/1.1 404 Not Found");
                //header('Status: 404 Not Found');
                //ob_end_flush();
                //exit;
            //}
        //}

        //if ( isset($params[1]) && $params[1] != ''){                                            // проверяем, есть ли параметры в урле
            //if ($params[1] != ADMIN_DIR_NAME){
                //if ( !empty($_SESSION['__currentLocale__']) && in_array($_SESSION['__currentLocale__'], self::getLocalesList()) ) {
                    //self::$locale = $_SESSION['__currentLocale__'];
                    //unset($_SESSION['__currentLocale__']);
                //} else {
                    //if (in_array($params[1], self::getLocalesList())) self::$locale = $params[1];
                    //else self::$locale = self::getDefaultLocale();
                //}
            //} else {
                //define('ADMIN_MODE', true);
            //}
            //if ( isset($params[2]) && $params[2]!='' ) self::$controllerName = $params[2];
            //if (isset($params[3]) && $params[3]!='') self::$actionName = strtolower($params[3]);
        //}
        //define("LOCALE", self::$locale);
    }

    /**
     * Generate Cross Domain URL to use it in ajax calls
     * @param mixed $options
     * @return string
     */
    static public function getCrossDomainUrl($options)
    {
        if ( is_array($options) ) {
            if ( $_SERVER['HTTP_HOST'] != BASE_HTTP_HOST ) {
                $url = ( HTTPS_ENABLED && isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].'/'.LOCALE.'/'.$options['action'].'/xajaxcontext/'.$options['controller'].'/?xajax';
            } else {
                $url = ( HTTPS_ENABLED && isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].'/'.LOCALE.'/'.$options['controller'].'/'.$options['action'].'/xajaxcontext/'.$options['controller'].'/?xajax';
            }
            return $url;
        } else {
            $parse = parse_url($options);
            $params = split("/", $parse['path']);
            $options = array();
            if ( $parse['host'] == BASE_HTTP_HOST ) {
                array_shift($params);
                $options['controller'] = array_shift($params);
                $options['action'] = array_shift($params);
                $options['add_params'] = join("/",$params);
            } else {
                array_shift($params);
                $hostParts = split("\.", $parse['host']);
                array_shift($params);
                $options['name'] = array_shift($hostParts);
                $options['controller'] = array_shift($hostParts);;
                $options['action'] = array_shift($params);
                $options['add_params'] = join("/",$params);
            }
            if ( $_SERVER['HTTP_HOST'] != BASE_HTTP_HOST ) {
                $url = ( HTTPS_ENABLED && isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].'/'.LOCALE.'/'.$options['action'].'/xajaxcontext/'.$options['controller'].'/';
                if ( !empty($options['name']) ) $url .= 'name/'.$options['name'].'/';
                if ( !empty($options['add_params']) ) $url .= $options['add_params'].'/';
                $url .= '?xajax';
            } else {
                $url = ( HTTPS_ENABLED && isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].'/'.LOCALE.'/'.$options['controller'].'/'.$options['action'].'/xajaxcontext/'.$options['controller'].'/';
                if ( !empty($options['name']) ) $url .= 'name/'.$options['name'].'/';
                if ( !empty($options['add_params']) ) $url .= $options['add_params'].'/';
                $url .= '?xajax';
            }
            return $url;
        }
    }

    /**
     * Enter description here...
     *
     * @param string $actionName - name of actions section
     * @param string $for - User|Group
     * @return string
     */
    static public function is($actionName, $for)
    {
        if ( null == self::$isActions || self::$isActions['controller'] != Warecorp::$controllerName ) {

            if ( !Zend_Registry::isRegistered('User') ) throw new Zend_Exception('User object must be initialized');
            $objUser = Zend_Registry::get('User');

            /**
             *
             */
            $userActions = array(
                'index'     => array('index', 'search'),
                'account'   => array('settings','avatars','avatarupload','privacy','bookmarks','networks','rounds'),
                'profile'   => array(
                    'profile'       => array('profile'),
                    'groups'        => array('groups'),
                    'discussions'   => array('discussion'),
                    'events'        => array('calendar.list.view','calendar.map.view','calendar.month.view','calendar.event.view','calendar.event.create','calendar.event.edit','calendar.event.copy.do','calendar.action.confirm','calendar.event.apply.request', 'calendar','calendarview','calendarviewevent','calendarexpired','calendaradd','calendaredit','calendarical','calendarconfirm','calendarsearch','calendarsearchindex', 'calendar.event.create.step1', 'calendar.event.create.step2', 'calendar.event.create.step3'),
                    'friends'       => array('friends','findfriends'),
                    'messages'      => array('messagelist','messageview','messagecompose','messagedelete','addressbook','importcontacts','addressbookgroup','addressbookaddmaillist','addressbookaddcontact', 'addressbookmaillist'),
                    'stuff' => array(
                        'photos'    => array('photos','gallery','gallerycreate','galleryedit','galleryview', 'galleryView', 'photossearch'),
                        'videos'    => array('videos','videogallery','videogallerycreate','videogalleryedit','videogalleryview', 'videogalleryView', 'videossearch'),
                        'documents' => array('documents'),
                        'lists'     => array('lists','listsdelete','listsedit','listsadd','listsview','listssearch')
                    )
                )
            );
            $userActions['profile']['stuff']['_all_'] = array_merge(
                $userActions['profile']['stuff']['photos'],
                $userActions['profile']['stuff']['videos'],
                $userActions['profile']['stuff']['documents'],
                $userActions['profile']['stuff']['lists']);

            /**
             *
             */
            $groupActions = array(
                'index'         => array('index', 'search', 'familylanding'),
                'summary'       => array('summary'),
                'members'       => array('members', 'groups'),
                'discussions'   => array('discussion', 'discussionsettings', 'discussionhostsettings', 'topic', 'replytopic', 'discussionsearch', 'recenttopic', 'createtopic'),
                'events'        => array('events','calendar.list.view','calendar.map.view','calendar.month.view', 'calendar.hierarchy.view', 'calendar.member.view', 'calendar.event.view','get.embedded.map','calendar.event.create','calendar.event.edit','calendar.event.copy.do','calendar.action.confirm','calendar.event.apply.request', 'calendar','calendarview','calendarviewevent','calendarexpired','calendaradd','calendaredit','calendarical','calendarconfirm','calendarsearch','calendarsearchindex', 'calendar.member.view', 'calendar.hierarchy.view', 'calendar.event.create.step2', 'calendar.event.create.step1', 'calendar.event.create.step3'),
                'stuff' => array(
                    'photos'    => array('photos','gallery','gallerycreate','galleryedit','galleryview', 'photossearch'),
                    'videos'    => array('videos','videogallery','videogallerycreate','videogalleryedit','videogalleryview', 'videossearch'),
                    'documents' => array('documents'),
                    'lists'     => array('lists','listsdelete','listsedit','listsadd','listsview','listssearch')
                ),
                'tools' => array(
                    'settings'      => array('settings'),
                    'hierarchy'     => array('hierarchy', 'previewhierarchy'),
                    'brandgallery'  => array('brandgallery', 'webbadges', 'mapmarker'),
                    'webbadges'     => array('webbadges'),
                    'invitations'   => array('invite1', 'invitesearch', 'invitelist'),
                    'avatars'       => array('avatars', 'avatarupload'),
                    'rounds'        => array('rounds'),
                ),
                'blog'          => array('blog', 'blog.details', 'blog.create', 'blog.edit')
            );
            $groupActions['stuff']['_all_'] = array_merge(
                $groupActions['stuff']['photos'],
                $groupActions['stuff']['videos'],
                $groupActions['stuff']['documents'],
                $groupActions['stuff']['lists']);

            $groupActions['tools']['_all_'] = array_merge(
                $groupActions['tools']['settings'],
                $groupActions['tools']['hierarchy'],
                $groupActions['tools']['brandgallery'],
                $groupActions['tools']['webbadges'],
                $groupActions['tools']['invitations'],
                $groupActions['tools']['avatars'],
                $groupActions['tools']['rounds']);


            /**
             * User Actions
             */
            self::$isActions['User']['Index']           = ( in_array(Warecorp::$actionName, $userActions['index'] ) )                           ? true : false;
            self::$isActions['User']['Account']         = ( in_array(Warecorp::$actionName, $userActions['account'] ) )                         ? true : false;
            self::$isActions['User']['Settings']        = ( 'settings' == Warecorp::$actionName )                                               ? true : false;
            self::$isActions['User']['Rounds']          = ( 'rounds' == Warecorp::$actionName )                                                 ? true : false;
            self::$isActions['User']['Avatars']         = ( in_array(Warecorp::$actionName, array('avatars', 'avatarupload')) )                 ? true : false;
            self::$isActions['User']['Privacy']         = ( 'privacy' == Warecorp::$actionName )                                                ? true : false;
            self::$isActions['User']['Networks']        = ( 'networks' == Warecorp::$actionName )                                               ? true : false;
            self::$isActions['User']['Bookmarks']       = ( 'bookmarks' == Warecorp::$actionName )                                              ? true : false;
            self::$isActions['User']['Profile']         = ( in_array(Warecorp::$actionName, $userActions['profile']['profile'] ) )              ? true : false;
            self::$isActions['User']['Groups']          = ( in_array(Warecorp::$actionName, $userActions['profile']['groups'] ) )               ? true : false;
            self::$isActions['User']['Discussions']     = ( in_array(Warecorp::$actionName, $userActions['profile']['discussions'] ) )          ? true : false;
            self::$isActions['User']['Events']          = ( in_array(Warecorp::$actionName, $userActions['profile']['events'] ) )               ? true : false;
            self::$isActions['User']['Friends']         = ( in_array(Warecorp::$actionName, $userActions['profile']['friends'] ) )              ? true : false;
            self::$isActions['User']['Messages']        = ( in_array(Warecorp::$actionName, $userActions['profile']['messages'] ) )             ? true : false;
            self::$isActions['User']['Stuff']           = ( in_array(Warecorp::$actionName, $userActions['profile']['stuff']['_all_'] ) )       ? true : false;

            /* Sub Menu */
            self::$isActions['User']['Photos']          = ( in_array(Warecorp::$actionName, $userActions['profile']['stuff']['photos'] ) )      ? true : false;
            self::$isActions['User']['Videos']          = ( in_array(Warecorp::$actionName, $userActions['profile']['stuff']['videos'] ) )      ? true : false;
            self::$isActions['User']['Documents']       = ( in_array(Warecorp::$actionName, $userActions['profile']['stuff']['documents'] ) )   ? true : false;
            self::$isActions['User']['Lists']           = ( in_array(Warecorp::$actionName, $userActions['profile']['stuff']['lists'] ) )       ? true : false;
            /**
             * Group Actions
             */
            self::$isActions['Group']['Index']          = ( in_array(Warecorp::$actionName, $groupActions['index']) )                           ? true : false;
            self::$isActions['Group']['Summary']        = ( in_array(Warecorp::$actionName, $groupActions['summary']) )                         ? true : false;
            self::$isActions['Group']['Members']        = ( in_array(Warecorp::$actionName, $groupActions['members']) )                         ? true : false;
            self::$isActions['Group']['Discussions']    = ( in_array(Warecorp::$actionName, $groupActions['discussions']) )                     ? true : false;
            self::$isActions['Group']['Events']         = ( in_array(Warecorp::$actionName, $groupActions['events']) )                          ? true : false;
            self::$isActions['Group']['Stuff']          = ( in_array(Warecorp::$actionName, $groupActions['stuff']['_all_']) )                  ? true : false;
            self::$isActions['Group']['Tools']          = ( in_array(Warecorp::$actionName, $groupActions['tools']['_all_']) )                  ? true : false;

            /* Sub Menu */
            self::$isActions['Group']['Photos']         = ( in_array(Warecorp::$actionName, $groupActions['stuff']['photos'] ) )                ? true : false;
            self::$isActions['Group']['Videos']         = ( in_array(Warecorp::$actionName, $groupActions['stuff']['videos'] ) )                ? true : false;
            self::$isActions['Group']['Documents']      = ( in_array(Warecorp::$actionName, $groupActions['stuff']['documents'] ) )             ? true : false;
            self::$isActions['Group']['Lists']          = ( in_array(Warecorp::$actionName, $groupActions['stuff']['lists'] ) )                 ? true : false;

            /* Sub Menu */
            self::$isActions['Group']['Settings']       = ( in_array(Warecorp::$actionName, $groupActions['tools']['settings'] ) )              ? true : false;
            self::$isActions['Group']['Hierarchy']      = ( in_array(Warecorp::$actionName, $groupActions['tools']['hierarchy'] ) )             ? true : false;
            self::$isActions['Group']['Brandgallery']   = ( in_array(Warecorp::$actionName, $groupActions['tools']['brandgallery'] ) )          ? true : false;
            self::$isActions['Group']['Webbadges']      = ( in_array(Warecorp::$actionName, $groupActions['tools']['webbadges'] ) )             ? true : false;
            self::$isActions['Group']['Invitations']    = ( in_array(Warecorp::$actionName, $groupActions['tools']['invitations'] ) )           ? true : false;
            self::$isActions['Group']['Avatars']        = ( in_array(Warecorp::$actionName, $groupActions['tools']['avatars'] ) )               ? true : false;
            self::$isActions['Group']['Rounds']         = ( in_array(Warecorp::$actionName, $groupActions['tools']['rounds'] ) )                ? true : false;

            self::$isActions['controller'] = Warecorp::$controllerName;
        }
        if ( isset(self::$isActions[$for]) && isset(self::$isActions[$for][$actionName]) ) {
            return self::$isActions[$for][$actionName];
        } else {
            return '';
        }
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $actionName
     * @param unknown_type $for
     * @param unknown_type $cssClass
     * @return unknown
     */
    static public function isActiveClass($actionName, $for, $cssClass )
    {
        if ( Warecorp::is($actionName, $for) ) return $cssClass;
        else return '';
    }


    /**
     * check if any given context(s) match current one.
     * @param string|array $context
     * @return boolean
     */
    static public function checkHttpContext($context) {


        if (!defined('HTTP_CONTEXT') || $context === null)
            return false;
        
        if (is_string($context)) $context = array($context);

        foreach ($context as $c) {
            if ((strpos(HTTP_CONTEXT,$c) !== false)) return true;
        }

        return false;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $smarty
     * @param unknown_type $objUser
     * @param unknown_type $objCurrentUser
     * @param unknown_type $objGroup
     * @return unknown
     */
    static public function isContext($smarty, &$objUser, &$objCurrentUser, &$objGroup)
    {
        $objUser = Zend_Registry::get('User');
        $objCurrentUser = $objGroup = null;

        if ( 'users' == Warecorp::$controllerName ) {
            if ( Warecorp::is('Index', 'User') ) $context = 'users_index';
            else {
                $objCurrentUser = $smarty->get_template_vars('currentUser');
                if ( $objUser && null !== $objUser->getId() && $objCurrentUser && null !== $objCurrentUser->getId() && $objUser->getId() == $objCurrentUser->getId() ) {
                    if ( Warecorp::is('Account', 'User') ) $context = 'user_account';
                    else $context = 'user_profile';
                } elseif ($objCurrentUser && null !== $objCurrentUser->getId()) {
                    $context = 'people_profile';
                } else {
                    $context = 'unknown';
                }
            }
        } elseif( 'groups' == Warecorp::$controllerName ) {
            if ( Warecorp::is('Index', 'Group') ) $context = 'group_index';
            else {
                $context = 'group';
                $objGroup = $smarty->get_template_vars('currentGroup');
                if ( !$objGroup || null === $objGroup->getId() ) $context = 'unknown';
            }
        } elseif ( 'search' == Warecorp::$controllerName ) {
            $context = 'search';
        } elseif ( 'event' == Warecorp::$controllerName ) {
            $context = 'event';
        } elseif ( 'index' == Warecorp::$controllerName ) {
            $context = 'index';
        } elseif ( 'info' == Warecorp::$controllerName ) {
            $context = 'info';
        } elseif ( 'registration' == Warecorp::$controllerName ) {
            $context = 'registration';
        }

        return $context;
    }

    /**
     * +-----------------------------------------------------------------------
     * |
     * |    TRANSLATION TOOLS
     * |
     * +-----------------------------------------------------------------------
     */

    /**
    * Return Translation object
    * @return Zend_Translate
    * @author Artem Sukharev
    * @deprecated
    */
    static public function getTranslate()
    {
        return Warecorp_Translate::getTranslate();
    }
    /**
    * Add language file
    * @param string $data - relative path to language file
    * @param string $locale
    * @param array $options
    * @param array $matches
    * @return Zend_Translate
    * @author Artem Sukharev
    * @deprecated
    */
    static public function addTranslation($data, $locale = null, array $options = array(), $matches = null)
    {
        Warecorp_Translate::add_translation_file($data, $locale);
        return true;
    }

    /**
    * return true if translate mode is on
    * @return boolean
    * @author Artem Sulharev
    */
    static public function isTranslateMode()
    {
        if ( null === self::$translateMode ) {
            /**
            * TODO Load settings from config file
            */
            self::$translateMode = ( 'on' == Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.translate.xml')->translate->TranslateMode ) ? true : false;
        }
        return (bool) self::$translateMode;
    }

    static public function isNewTranslateVersion()
    {
        if (isset(Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.translate.xml')->translate->TranslateVersion) && Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.translate.xml')->translate->TranslateVersion == 1)
        {
            return true;
        }
        return false;
    }

    /**
    * @desc
    */
    static public function setTranslateMode($mode)
    {
        self::$translateMode = (boolean) $mode;
    }
    /**
    * return true if translate debug mode is on or false
    * @return boolean
    * @author Artem Sukharev
    */
    static public function isTranslateDebugMode()
    {
        if ( null === self::$translateDebugMode ) {
            /**
            * TODO Load settings from config file
            */
            self::$translateDebugMode = ( 'on' == Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.translate.xml')->translate->TranslateDebugMode ) ? true : false;
        }
        return (bool) self::$translateDebugMode;
    }
    /**
    * @desc
    */
    static public function setTranslateDebugMode($mode)
    {
        self::$translateDebugMode = (boolean) $mode;
    }
    /**
    * return true if translate aout generate mode is on or false
    * @return boolean
    * @author Artem Sukharev
    */
    static public function isTranslateAutoGenerateMode()
    {
        if ( null === self::$translateAutoGenerateMode ) {
            /**
            * TODO Load settings from config file
            */
            self::$translateAutoGenerateMode = ( 'on' == Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.translate.xml')->translate->TranslateAutoGenerateMode ) ? true : false;
        }
        return (bool) self::$translateAutoGenerateMode;
    }
    /**
    * @desc
    */
    static public function setTranslateAutoGenerateMode($mode)
    {
        self::$translateAutoGenerateMode = (boolean) $mode;
    }

    /**
    * return true if translate online debug mode is on or false
    * @return boolean
    * @author Artem Sukharev
    */
    static public function isTranslateOnlineDebugMode()
    {
        if ( null === self::$translateOnlineDebugMode ) {
            self::$translateOnlineDebugMode = false;
        }
        return (bool) self::$translateOnlineDebugMode;
    }
    /**
    * @desc
    */
    static public function setTranslateOnlineDebugMode($mode)
    {
        self::$translateOnlineDebugMode = (boolean) $mode;
    }
    /**
    * translate text to language
    * @author Artem Sukharev
    * @deprecated
    */
    public static function t($messageKey, $defaultMessage = null, $params = null, $editable = false)
    {
        //if (self::isNewTranslateVersion()){
        if (true){
            $params = $defaultMessage;
            $defaultMessage = $messageKey;
            return Warecorp_Translate::translate($defaultMessage, $params);
        }else{
            if ( null === $defaultMessage && null === $params ) {   //  Warecorp::t('Message');
                $defaultMessage = $messageKey;
            } elseif ( null !== $defaultMessage && is_array($defaultMessage) ) { //  Warecorp::t('Message %s', array(1));
                $params = $defaultMessage;
                $defaultMessage = $messageKey;
            } else { //  OLD : Warecorp::t('key', 'Message'); or Warecorp::t('key', 'Message %s', array(1));

            }
            return Warecorp_Translate::translate($defaultMessage, $params);
        }
    }

    /**
     * remove all smarty complied cache
     */
    public static function cleanTemplatesCache()
    {
        $path = APP_VAR_DIR.'/_compiled/site/*';
        exec('rm -rf '.$path);
        return true;
    }

    /**
     * +-----------------------------------------------------------------------
     * |
     * |    LOCALES TOOLS
     * |
     * +-----------------------------------------------------------------------
     */

    /**
     * return array of available locations
     * 
     * RSS walue will be removed from Locales list, and this function removed. 
     * 
     * @return array
     * @author Alex Che
     */
    static public function getLocalesListWithoutRss()
    {
        $locales = self::getLocalesList();
        return array_diff($locales, array('rss'));
    }
    /**
     * return array of available locations
     * @return array
     * @author Halauniou
     * @author Artem Sukharev
     */
    static public function getLocalesList()
    {
        /**
        * TODO It need to remove hard-coded language params and place them to config file for different implementations because
        *      each implamentation can have different language settings
        */
        return Zend_Registry::get('cfg_translate_locales_xml');
    }
    /**
     *
     */
    static public function getLocalesNamesList()
    {
        return Zend_Registry::get('cfg_translate_locales_names_xml');
    }
    /**
    * return default locale for application
    * @return string
    * @author Artem Sukharev
    */
    static function getDefaultLocale()
    {
        return self::$defaultLocale;
    }

    /**
     * +-----------------------------------------------------------------------
     * |
     * |    LOCALES TOOLS
     * |
     * +-----------------------------------------------------------------------
     */

    /**
     * try to load CCFID from user profile
     * it is used for zccf, zccf-alt, zccf-base only
     * @param Warecorp_User $objUser
     * @return int
     * @author Artem Sukharev
     */
    static public function getCCFID( $objUser )
    {
        if ( $objUser instanceof Warecorp_User ) {
            if ( null !== $objProfile = $objUser->getProfile() ) {
                if ( $objProfile instanceof ZCCF_User_Profile ) return $objProfile->getCCFID();
            }
        } elseif ( $objUser instanceof Warecorp_User_Addressbook_CustomUser ) {
            $tmpUser = null;
            if ( $userID = $objUser->getCustomUserId() ) {
                $tmpUser = new Warecorp_User('id', $userID);
            } elseif ( $userEmail = $objUser->getEmail() ) {
                $tmpUser = new Warecorp_User('email', $userEmail);
            }
            if ( $tmpUser && $tmpUser->getId() && $tmpUser->isExist ) {
                if ( null !== $objProfile = $tmpUser->getProfile() ) {
                    if ( $objProfile instanceof ZCCF_User_Profile ) return $objProfile->getCCFID();
                }
            }
        } elseif ( is_string($objUser) || is_numeric($objUser) ) {
            $tmpUser = null;
            $tmpUser = new Warecorp_User('id', $objUser);
            if ( $tmpUser && $tmpUser->getId() && $tmpUser->isExist ) {
                if ( null !== $objProfile = $tmpUser->getProfile() ) {
                    if ( $objProfile instanceof ZCCF_User_Profile ) return $objProfile->getCCFID();
                }
            }

            $tmpUser = null;
            $tmpUser = new Warecorp_User('email', $objUser);
            if ( $tmpUser && $tmpUser->getId() && $tmpUser->isExist ) {
                if ( null !== $objProfile = $tmpUser->getProfile() ) {
                    if ( $objProfile instanceof ZCCF_User_Profile ) return $objProfile->getCCFID();
                }
            }

            $tmpUser = null;
            $tmpUser = new Warecorp_User('login', $objUser);
            if ( $tmpUser && $tmpUser->getId() && $tmpUser->isExist ) {
                if ( null !== $objProfile = $tmpUser->getProfile() ) {
                    if ( $objProfile instanceof ZCCF_User_Profile ) return $objProfile->getCCFID();
                }
            }
        }

        return null;
    }

    /**
     * Enter description here...
     *
     */
    static function finally()
    {
        //Zend_Debug::dump(self::$translationStack);
        $db = Zend_Registry::get("DB");
        $db->closeConnection();
        session_write_close();
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $url
     * @param unknown_type $contentType
     * @return unknown
     */
    public static function url_exists($url, $contentType = null)
    {
        if (null === $url || '' === trim($url)) return false;
        $handle   = curl_init($url);
        if (false == $handle) return false;

        curl_setopt($handle, CURLOPT_HEADER, true);
        curl_setopt($handle, CURLOPT_NOBODY, true);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_TIMEOUT, 1);

        // grab Url
        $timerStart = self::getmicrotime();
        $connectable = curl_exec($handle);
        $timerEnd = self::getmicrotime();
        $timerDelta = $timerEnd - $timerStart;
        /*
         * Logging
         */
        if ( false == strpos(BASE_HTTP_HOST, '.buick') ) { // use only for test and production servers
            if ( !file_exists(APP_VAR_DIR.'/logs/url_exists.log') ) {
                $fp = fopen(APP_VAR_DIR.'/logs/url_exists.log', 'w');
                fclose($fp);
                chmod(APP_VAR_DIR.'/logs/url_exists.log', 0777);
            }
            $logFileSize = 0;
            if ( file_exists(APP_VAR_DIR.'/logs/url_exists.log') && is_writable(APP_VAR_DIR.'/logs/url_exists.log') ) {
                $logFileSize = filesize(APP_VAR_DIR.'/logs/url_exists.log');
                $fp = fopen(APP_VAR_DIR.'/logs/url_exists.log', 'a+');
                $message = ( $timerDelta > 0.5 ) ? '* ' : '  ';
                $message .= $timerDelta . ' : ' . $url . "\n";
                fwrite($fp, $message);
                fclose($fp);

            }
            if ( $logFileSize > 1024 * 1024 ) {
                require_once(ENGINE_DIR.'/htmlMimeMail5/htmlMimeMail5.php');
                $mail = new htmlMimeMail5();
                $mail->setTextCharset("UTF-8");
                $mail->setHTMLCharset("UTF-8");
                $mail->setHeadCharset("UTF-8");
                $cfgSite = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.site.xml');
                $cfgInstance = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.instance.xml');
                if ( isset($cfgInstance->smtp_method) && $cfgInstance->smtp_method == 'smtp' ) {
                    $timeout                = ( isset($cfgInstance->smtp_timeout) ) ? $cfgInstance->smtp_timeout : 5;
                    $socket_set_timeout     = ( isset($cfgInstance->socket_set_timeout) ) ? $cfgInstance->socket_set_timeout : 5;
                    $mail->setSMTPParams($cfgInstance->smtp_host, $cfgInstance->smtp_port, null, null, null, null, $timeout, $socket_set_timeout);
                    $send_method = 'smtp';
                } else {
                    $send_method = 'mail';
                }
                $mail->setText('Log file url_exists');
                $mail->setFrom('admin@warecorp.com');
                $mail->setSubject('Log file url_exists');
                $mail->addAttachment(new stringAttachment(file_get_contents(APP_VAR_DIR.'/logs/url_exists.log'), 'url_exists.txt') );
                if ($cfgInstance->smtp_method == 'smtp') {
                    $mail->send(array('artem.sukharev@warecorp.com'), $cfgInstance->smtp_method, true);
                } else {
                    $mail->send(array('artem.sukharev@warecorp.com'), $cfgInstance->smtp_method, true);
                }
                $fp = fopen(APP_VAR_DIR.'/logs/url_exists.log', 'w');
                fclose($fp);
            }
        }
        /**
         *
         */
        $result = (strpos($connectable, '200 OK') !== false)?true:false;
        if ($contentType && $result) $result = $result && (strpos($connectable, 'Content-Type: '.$contentType) !== false)?true:false;
        // close Curl resource, and free up system resources

        //print_r(curl_getinfo($handle));
        //echo "\n\ncURL error number:" .curl_errno($handle);
        //echo "\n\ncURL error:" . curl_error($handle);

        curl_close($handle);
        return $result;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $fileName
     */
    static public function loadSmartyPlugin($fileName)
    {
        if ( defined('CORE_SMARTY_PLUGINS_DIR') ) {
            if ( file_exists(SMARTY_PLUGINS_DIR.$fileName) ) require_once(SMARTY_PLUGINS_DIR.$fileName);
            elseif ( file_exists(CORE_SMARTY_PLUGINS_DIR.$fileName) ) require_once(CORE_SMARTY_PLUGINS_DIR.$fileName);
            elseif ( file_exists(PRODUCT_SMARTY_PLUGINS_DIR.$fileName) ) require_once(PRODUCT_SMARTY_PLUGINS_DIR.$fileName);
            elseif ( file_exists(COMMON_SMARTY_PLUGINS_DIR.$fileName) ) require_once(COMMON_SMARTY_PLUGINS_DIR.$fileName);
        } else {
            if ( file_exists(SMARTY_PLUGINS_DIR.$fileName) ) require_once(SMARTY_PLUGINS_DIR.$fileName);
            elseif ( file_exists(PRODUCT_SMARTY_PLUGINS_DIR.$fileName) ) require_once(PRODUCT_SMARTY_PLUGINS_DIR.$fileName);
            elseif ( file_exists(COMMON_SMARTY_PLUGINS_DIR.$fileName) ) require_once(COMMON_SMARTY_PLUGINS_DIR.$fileName);
        }
    }

    /**
     * Enter description here...
     *
     * @return unknown
     */
    private function getmicrotime()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $s1
     * @param unknown_type $s2
     * @return unknown
     */
    static private function strleft($s1, $s2)
    {
        return substr($s1, 0, strpos($s1, $s2));
    }

    static public function user_date_format($string, $timeZone = null, $format = null, $timeZoneFrom = 'UTC')
    {
        //get(Zend_Date::DATE_SHORT)
        date_default_timezone_set($timeZoneFrom);          

        if ( $string instanceof Zend_Date ) {
            $created = clone $string;
        } else {
            $created = new Zend_Date($string, Zend_Date::ISO_8601);
        }
        if ($timeZone) $created->setTimezone($timeZone);
        if ( $format !== null ) {
            switch ( $format ) {
            case 'MAIL_SHORT' :
                return $created->toString('MM/dd/yyyy');
                break;
            default:
                return $created->toString($format);
            }
        } else {
            return $created->toString(Warecorp_Date::DATETIME);
        }
    }
    
    static public function sendContactUs( $arrParams )
    {
        $arrParams['email'] = isset($arrParams['email']) ? $arrParams['email'] : '';
        $arrParams['first_name'] = isset($arrParams['first_name']) ? $arrParams['first_name'] : '';
        $arrParams['last_name'] = isset($arrParams['last_name']) ? $arrParams['last_name'] : '';
        $arrParams['company'] = isset($arrParams['company']) ? $arrParams['company'] : '';
        $arrParams['phone'] = isset($arrParams['phone']) ? $arrParams['phone'] : '';
        $arrParams['message'] = isset($arrParams['message']) ? $arrParams['message'] : 'Message';
        $arrParams['topic'] = isset($arrParams['topic']) ? $arrParams['topic'] : 'Subject';
        $arrParams['city'] = isset($arrParams['city']) ? $arrParams['city'] : '';
        
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered('CONTACT_US_ACCEPTED') ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {                
                $recipient = new Warecorp_SOAP_Type_Recipient();
                $recipient->setEmail( ADMIN_EMAIL );
                $recipient->setName( null );
                $recipient->setLocale( null );
                $recipient->addParam( 'sender_email', $arrParams['email'] );
                $recipient->addParam( 'sender_first_name', $arrParams['first_name'] );
                $recipient->addParam( 'sender_last_name', $arrParams['last_name'] );
                $recipient->addParam( 'company', $arrParams['company'] );
                $recipient->addParam( 'phone', $arrParams['phone'] );
                $recipient->addParam( 'message_body_plain', $arrParams['message'] );
                $recipient->addParam( 'message_body_html', nl2br(htmlspecialchars($arrParams['message'])) );
                $msrvRecipients->addRecipient($recipient);
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    $request = $client->setSender($campaignUID, $arrParams['email'], $arrParams['first_name'].' '.$arrParams['last_name']);
                    $request = $client->setTemplate($campaignUID, 'CONTACT_US_ACCEPTED', HTTP_CONTEXT); /* CONTACT_US_ACCEPTED */
                    
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $params->addParam( 'message_subject', !empty($arrParams['topic']) ? $arrParams['topic'] : 'Contact Us'  );
                    $request = $client->addParams($campaignUID, $params);
                                       
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                    
                } catch ( Exception $e ) { $msrvSended = false;  }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            //  Send message
            $mail = new Warecorp_Mail_Template('template_key', 'CONTACT_US_ACCEPTED');

            $sender_object = new Warecorp_User();
            $sender_object->setCityId($arrParams['city']);
            $sender_object->setFirstname(html_entity_decode($arrParams['first_name']));
            $sender_object->setLastname(html_entity_decode($arrParams['last_name']));
            $sender_object->setEmail($arrParams['email']);
            $sender_object->setRegisterDate(date('Y-m-d H:i:s'));
        
            $mail->setSender($sender_object);
            /*  strange part     */
        
            $mail->addUserRecipientsFormString( ADMIN_EMAIL );
            //*=- smarty variables for template of data base
            $mail->addParam('subject', $arrParams['topic']);
            $mail->addParam('original_message', html_entity_decode($arrParams['message']));
            $mail->addParam('company', html_entity_decode($arrParams['company']));
            $mail->addParam('phone', $arrParams['phone']);
            //*=-
            $mail->send();
        }
    }
    
    static public function r_implode( $glue, $pieces )
    {
        $retVal = array();
        foreach( $pieces as $r_pieces ){
            if( is_array( $r_pieces ) ){
                $retVal[] = self::r_implode( $glue, $r_pieces );
            }
            else {
                $retVal[] = $r_pieces;
            }
        }
        return implode( $glue, $retVal );
    } 
    
    
    
    static public function turnOffDebugInfo()
    {
        if ( !defined('TURN_OFF_DEBUG') ) {
            define('TURN_OFF_DEBUG', true);
        }
    }
}
