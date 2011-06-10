<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function return_bytes($val)
	{
		$val = trim($val);
		$last = strtolower($val[strlen($val)-1]);
		switch($last) {
			// The 'G' modifier is available since PHP 5.1.0
			case 'g': 	$val *= 1024*1024*1024; break;
			case 'm': 	$val *= 1024*1024;      break;
			case 'k':	$val *= 1024;           break;
		}
		return $val;
	}

    protected function _initErrorReporting()
    {

        $cfgLoader = Warecorp_Config_Loader::getInstance();

        $cfgApp = $cfgLoader->getAppConfig('cfg.application.xml');

        ini_set('display_errors',$cfgApp->phpSettings->display_errors);
        ini_set('display_startup_errors',$cfgApp->phpSettings->display_startup_errors);

        /*
         * Use php.ini settings!
         * if ( $cfgApp->phpSettings->display_errors ) {
            error_reporting(E_ALL);
        }*/
    }

	protected function _initFileCache()
	{
		$cache = Warecorp_Cache::getFileCache();
        return $cache;
	}

	protected function _initDefines()
	{
        $cfgLoader = Warecorp_Config_Loader::getInstance();

        $cfgHome        = $cfgLoader->getAppConfig('cfg.home.xml');
        $cfgSite        = $cfgLoader->getAppConfig('cfg.site.xml');
        $cfgIndexer     = $cfgLoader->getCoreConfig('cfg.indexer.xml');
        $cfgCredentials = $cfgLoader->getAppConfig('cfg.credentials.xml')->{'site'};
        $cfgInstance    = $cfgLoader->getAppConfig('cfg.instance.xml');
        $cfgGMap        = $cfgLoader->getAppConfig('cfg.gmap.xml');

		defined('HTTP_CONTEXT') || define('HTTP_CONTEXT', $cfgSite->http_context ? $cfgSite->http_context : 'zanby');
		//  Web server's document root.
		defined('DOC_ROOT') || define('DOC_ROOT', APPLICATION_PATH.'htdocs'.DIRECTORY_SEPARATOR);
		defined('BASE_HTTP_HOST') || define('BASE_HTTP_HOST', $cfgInstance->base_http_host);
		defined('BASE_URL') || define('BASE_URL', 'http://'.$cfgInstance->base_http_host);
		defined('SERVE_URL') || define('SERVE_URL', 'http://'.$cfgInstance->serve_url);
		defined('BASE_URL_SECURE') || define('BASE_URL_SECURE', 'https://'.$cfgInstance->base_http_host);
		defined('SITE_NAME_AS_STRING') || define('SITE_NAME_AS_STRING', $cfgSite->site_name_as_string);
		defined('GET_IMAGE_WRAPPER_PATH') || define('GET_IMAGE_WRAPPER_PATH', BASE_URL.'/getimage.php');
		//  attachments upload directory.
		defined('ATTACHMENT_DIR') || define('ATTACHMENT_DIR', DOC_ROOT.'/upload/attachments/');
		defined('ADMIN_DIR_NAME') || define('ADMIN_DIR_NAME', 'admin'); // ????
		defined('ADMIN_DIR') || define('ADMIN_DIR', DOC_ROOT.'/'.ADMIN_DIR_NAME.'/');
		defined('SITE_NAME_AS_DOMAIN') || define('SITE_NAME_AS_DOMAIN', $cfgSite->site_name_as_domain);
		defined('SITE_NAME_AS_FULL_DOMAIN') || define('SITE_NAME_AS_FULL_DOMAIN', $cfgSite->site_name_as_full_domain);
		defined('DOMAIN_FOR_EMAIL') || define('DOMAIN_FOR_EMAIL', $cfgInstance->domain_for_email);
		defined('DOMAIN_FOR_GROUP_EMAIL') || define('DOMAIN_FOR_GROUP_EMAIL', $cfgInstance->domain_for_group_email);
		defined('ADMIN_EMAIL') || define('ADMIN_EMAIL', $cfgInstance->admin_email);
		defined('SITE_ENCODING') || define('SITE_ENCODING', $cfgSite->site_encoding);

		defined('GOOGLE_ANALYTICS') || define('GOOGLE_ANALYTICS', $cfgCredentials->google_analytics);
		defined('GOOGLE_ANALYTICS_ID') || define('GOOGLE_ANALYTICS_ID', $cfgCredentials->google_analytics_id);
		defined('GOOGLE_MAP_KEY') || define('GOOGLE_MAP_KEY', $cfgGMap->google_map_key);

		defined('DIRECT_ACTIVATION') || define('DIRECT_ACTIVATION', $cfgInstance->direct_activation);

		defined('EVENTS_LIMIT') || define('EVENTS_LIMIT', $cfgHome->events_limit);
		defined('PHOTOS_LIMIT') || define('PHOTOS_LIMIT', $cfgHome->photos_limit);
		defined('LISTS_LIMIT') || define('LISTS_LIMIT', $cfgHome->lists_limit);
		defined('DISCUSSIONS_LIMIT') || define('DISCUSSIONS_LIMIT', $cfgHome->discussions_limit);
		defined('DISCUSSIONS_CURRENT_PAGE') || define('DISCUSSIONS_CURRENT_PAGE', $cfgHome->discussions_current_page);

		//  Configuration files path
        defined('RESOURCES_DIR') || define('RESOURCES_DIR', APPLICATION_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR);
        defined('ACCESS_RIGHTS_DIR') || define('ACCESS_RIGHTS_DIR', RESOURCES_DIR.'access'.DIRECTORY_SEPARATOR);
		//  Folder for debugger's logs
		defined('DEBUG_LOG_DIR') || define('DEBUG_LOG_DIR', APP_VAR_DIR.'/logs/debug_log/');

        defined('USERS_LOG') || define('USERS_LOG',isset($cfgInstance->users_log) && $cfgInstance->users_log == 'on' ? true: false);

		defined('COPYRIGHT') || define('COPYRIGHT', 'Copyright &copy; 2010, Zanby');
		defined('THROW_EXCEPTIONS') || define('THROW_EXCEPTIONS', true);
		defined('USE_USER_PATH') || define('USE_USER_PATH', true);

		//	processing of EI filter
		defined('EI_FILTER_ENABLED') || define('EI_FILTER_ENABLED', isset($cfgSite->use_EI_filter) && ( $cfgSite->use_EI_filter == 'yes' ) ? true : false );
		//
		defined('WITH_SPHINX') || define('WITH_SPHINX', $cfgIndexer && !empty($cfgSite->with_sphinx) ? ($cfgSite->with_sphinx == 'on') : false);
		defined('WITH_SPHINX_TAGS') || define('WITH_SPHINX_TAGS', WITH_SPHINX && !empty($cfgSite->with_sphinx_tags) ? ($cfgSite->with_sphinx_tags == 'on') : false);
		//
		if ( isset($cfgInstance->ERRORS_DISPLAY_MODE) && !defined('ERRORS_DISPLAY_MODE'))   define('ERRORS_DISPLAY_MODE',   $cfgInstance->ERRORS_DISPLAY_MODE);
		if ( isset($cfgInstance->ERRORS_LOG_MODE) && !defined('ERRORS_LOG_MODE'))       define('ERRORS_LOG_MODE',       $cfgInstance->ERRORS_LOG_MODE);
		if ( isset($cfgInstance->ERRORS_EMAIL_SEND_MODE) && !defined('ERRORS_EMAIL_SEND_MODE'))define('ERRORS_EMAIL_SEND_MODE',$cfgInstance->ERRORS_EMAIL_SEND_MODE);
		if ( isset($cfgInstance->ERRORS_EMAIL_SEND_TO) && !defined('ERRORS_EMAIL_SEND_TO'))  define('ERRORS_EMAIL_SEND_TO',  $cfgInstance->ERRORS_EMAIL_SEND_TO);

        defined('DEFAULT_LOCALE') || define('DEFAULT_LOCALE',!empty($cfgInstance->default_locale) ? $cfgInstance->default_locale:'en');
		defined('S3_BUCKET') || define('S3_BUCKET',!empty($cfgSite->s3_bucket)?$cfgSite->s3_bucket:BASE_HTTP_HOST);
        defined('STORE_ORIGINAL_VIDEO') || define('STORE_ORIGINAL_VIDEO',!empty($cfgSite->store_original_video)?$cfgSite->store_original_video:0);
        defined('USE_VIDEO_SUSPENDED_PROCESSING') || define('USE_VIDEO_SUSPENDED_PROCESSING',!empty($cfgSite->use_video_suspended_processing)?$cfgSite->use_video_suspended_processing:0);
        defined('SINGLEVIDEOMODE') || define('SINGLEVIDEOMODE',!empty($cfgSite->single_video_mode)?$cfgSite->single_video_mode:0);
        defined('VIDEOMODEFOLDER') || define('VIDEOMODEFOLDER',SINGLEVIDEOMODE?'videomode/':'');
        defined('USE_MAIL_QUEUE') || define('USE_MAIL_QUEUE',!empty($cfgInstance->use_mail_queue)?$cfgInstance->use_mail_queue:0);
        defined('AFTER_PARTY_LIVE_TIME') || define('AFTER_PARTY_LIVE_TIME',!empty($cfgSite->after_party_live_time)?$cfgSite->after_party_live_time:7 * 86400);
        defined('MEMBER_REPORT_TERM') || define('MEMBER_REPORT_TERM',!empty($cfgSite->members_report_term)?$cfgSite->members_report_term:86400);
        defined('ALLOW_CHANGE_CHARITY_AFTER_PAYMENT') || define('ALLOW_CHANGE_CHARITY_AFTER_PAYMENT',!empty($cfgSite->allow_change_charity_after_payment)?$cfgSite->allow_change_charity_after_payment:0);
		defined('TOTAL_PHOTOS_LIMIT') || define('TOTAL_PHOTOS_LIMIT', isset($cfgSite->total_photos_limit) && is_numeric($cfgSite->total_photos_limit) && $cfgSite->total_photos_limit != 0 ? $cfgSite->total_photos_limit : 20);
        defined('IMAGES_SIZE_LIMIT') || define('IMAGES_SIZE_LIMIT', isset($cfgSite->images_size_limit) ? $cfgSite->images_size_limit : $this->return_bytes(ini_get('upload_max_filesize')));
        defined('VIDEOS_SIZE_LIMIT') || define('VIDEOS_SIZE_LIMIT', isset($cfgSite->videos_size_limit) ? $cfgSite->videos_size_limit : $this->return_bytes(ini_get('upload_max_filesize')));
        defined('DOCUMENTS_SIZE_LIMIT') || define('DOCUMENTS_SIZE_LIMIT', isset($cfgSite->documents_size_limit) ? $cfgSite->documents_size_limit : $this->return_bytes(ini_get('upload_max_filesize')));
        defined('AVATARS_SIZE_LIMIT') || define('AVATARS_SIZE_LIMIT', isset($cfgSite->avatars_size_limit) ? $cfgSite->avatars_size_limit : $this->return_bytes(ini_get('upload_max_filesize')));
        defined('REGISTRATION_CAPTCHA') || define('REGISTRATION_CAPTCHA', isset($cfgInstance->registration_captcha) ? $cfgInstance->registration_captcha : 'on');
        defined('DISTANCE_OF_SEARCH') || define('DISTANCE_OF_SEARCH', isset($cfgSite->distance_of_search) ? $cfgSite->distance_of_search : 500.0);
        defined('USE_NEW_RESTORE_PASSWORD') || define('USE_NEW_RESTORE_PASSWORD', isset($cfgInstance->use_new_restore_password) ? $cfgInstance->use_new_restore_password : 'off');

		defined('FAMILY_LANDING_SORT') || define('FAMILY_LANDING_SORT', isset($cfgSite->familyLandingSort) ? $cfgSite->familyLandingSort : 'groupsInFamily');

        defined('DISCUSSION_MODE') || define('DISCUSSION_MODE', isset($cfgInstance->discussion->mode) ? $cfgInstance->discussion->mode : 'bbcode');

        /**
         * Wordpress SSO Settings
         */
        if ( isset($cfgInstance->wp_sso->wp_sso_enabled) && ( strtolower($cfgInstance->wp_sso->wp_sso_enabled) == 'true' || $cfgInstance->wp_sso->wp_sso_enabled == 1 ) ) {
            if ( !isset($cfgInstance->wp_sso->wp_sso_url) || empty($cfgInstance->wp_sso->wp_sso_url) ) throw new Exception('wp_sso isn\'t configured. Please specify wp_sso_url in cfg.instance.xml configuration file.');
            defined('WP_SSO_URL') || define('WP_SSO_URL', $cfgInstance->wp_sso->wp_sso_url);

            $cfgCredentialsWPSSO = $cfgLoader->getAppConfig('cfg.credentials.xml')->{'wp_sso'};
            if ( !isset($cfgCredentialsWPSSO->credentials->uid) || empty($cfgCredentialsWPSSO->credentials->uid) ) throw new Exception('wp_sso credentials aren\'t configured. Please specify wp_sso->credentials->uid in cfg.credentials.xml configuration file.');
            defined('WP_SSO_LOGIN') || define('WP_SSO_LOGIN', $cfgCredentialsWPSSO->credentials->uid);

            if ( !isset($cfgCredentialsWPSSO->credentials->pass) || empty($cfgCredentialsWPSSO->credentials->pass) ) throw new Exception('wp_sso credentials aren\'t configured. Please specify wp_sso->credentials->pass in cfg.credentials.xml configuration file.');
            defined('WP_SSO_PASSWORD') || define('WP_SSO_PASSWORD', $cfgCredentialsWPSSO->credentials->pass);

            defined('WP_SSO_ENABLED') || define('WP_SSO_ENABLED', true);
        }
        defined('WP_SSO_ENABLED') || define('WP_SSO_ENABLED', false);
        defined('WP_SSO_URL') || define('WP_SSO_URL', null);
        defined('WP_SSO_LOGIN') || define('WP_SSO_LOGIN', null);
        defined('WP_SSO_PASSWORD') || define('WP_SSO_PASSWORD', null);
	}

	protected function _initDefaultTimezone()
	{
        $cfgSite = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.site.xml');
        
        defined ('DEFAULT_TIMEZONE') || define('DEFAULT_TIMEZONE',$cfgSite->time_zone);
		date_default_timezone_set($cfgSite->time_zone);
	}

	protected function _initMVCPaths()
	{
        $this->bootstrap(array('Defines'));
		//  Base Modules directory.
		defined('PRODUCT_MODULES_DIR')
			|| define('PRODUCT_MODULES_DIR', realpath(APPLICATION_PATH.'modules'.DIRECTORY_SEPARATOR.'_base').DIRECTORY_SEPARATOR);
		//  Base Modules directory for category 3 implementation.
		defined('CORE_MODULES_DIR')
            || define('CORE_MODULES_DIR', realpath(APPLICATION_PATH.'modules'.DIRECTORY_SEPARATOR.'_base3c').DIRECTORY_SEPARATOR);
		defined('MODULES_DIR')
			|| define('MODULES_DIR', realpath(APPLICATION_PATH.'modules'.DIRECTORY_SEPARATOR.HTTP_CONTEXT).DIRECTORY_SEPARATOR);


		//  Base Templates directory
		defined('PRODUCT_TEMPLATES_DIR')
			|| define('PRODUCT_TEMPLATES_DIR', realpath(APPLICATION_PATH.'templates'.DIRECTORY_SEPARATOR.'_base').DIRECTORY_SEPARATOR);
		//  Base Templates directory for category 3 implementation.
		defined('CORE_TEMPLATES_DIR')
            || define('CORE_TEMPLATES_DIR', file_exists(APPLICATION_PATH.'templates'.DIRECTORY_SEPARATOR.'_base3c')
                ? realpath(APPLICATION_PATH.'templates'.DIRECTORY_SEPARATOR.'_base3c').DIRECTORY_SEPARATOR : PRODUCT_TEMPLATES_DIR );
		defined('TEMPLATES_DIR')
			|| define('TEMPLATES_DIR', realpath(APPLICATION_PATH.'templates'.DIRECTORY_SEPARATOR.HTTP_CONTEXT).DIRECTORY_SEPARATOR);

		//  Common Smarty plugins directory
		defined('COMMON_SMARTY_PLUGINS_DIR')
			|| define('COMMON_SMARTY_PLUGINS_DIR', realpath(APPLICATION_PATH.'plugins'.DIRECTORY_SEPARATOR.'_common').DIRECTORY_SEPARATOR);
		//  Base Smarty plugins directory
		defined('PRODUCT_SMARTY_PLUGINS_DIR')
			|| define('PRODUCT_SMARTY_PLUGINS_DIR', realpath(APPLICATION_PATH.'plugins'.DIRECTORY_SEPARATOR.'_base').DIRECTORY_SEPARATOR);
		//  Base Smarty plugins directory for category 3 implementation.
		defined('CORE_SMARTY_PLUGINS_DIR')
            || define('CORE_SMARTY_PLUGINS_DIR', file_exists(APPLICATION_PATH.'plugins'.DIRECTORY_SEPARATOR.'_base3c')
                ? realpath(APPLICATION_PATH.'plugins'.DIRECTORY_SEPARATOR.'_base3c').DIRECTORY_SEPARATOR : PRODUCT_SMARTY_PLUGINS_DIR );
		defined('SMARTY_PLUGINS_DIR')
            || define('SMARTY_PLUGINS_DIR', realpath(APPLICATION_PATH.'plugins'.DIRECTORY_SEPARATOR.HTTP_CONTEXT).DIRECTORY_SEPARATOR);
        defined('EXTENSIONS_DIR') || define('EXTENSIONS_DIR',  realpath(APPLICATION_PATH.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR) );
		/**
		 * Add plugin directories to include path
		 * Create inheritance for plgins by addign paths in correct order
		 * Plugins will be found
		 * 	first in SMARTY_PLUGINS_DIR then CORE_SMARTY_PLUGINS_DIR then PRODUCT_SMARTY_PLUGINS_DIR then COMMON_SMARTY_PLUGINS_DIR
		 */
		set_include_path(
			get_include_path().
			SMARTY_PLUGINS_DIR.PATH_SEPARATOR.				// 1. inplementations level
			CORE_SMARTY_PLUGINS_DIR.PATH_SEPARATOR.			// 2. base3c level
			PRODUCT_SMARTY_PLUGINS_DIR.PATH_SEPARATOR.		// 3. base lavel
			COMMON_SMARTY_PLUGINS_DIR.PATH_SEPARATOR.		// 4. common lavel
            EXTENSIONS_DIR.PATH_SEPARATOR
		);

        //var_dump(get_include_path()); exit();

		//  Base Languages directory.
        
		defined('LANGUAGES_DIR') || define('LANGUAGES_DIR', APPLICATION_PATH.'languages'.DIRECTORY_SEPARATOR.HTTP_CONTEXT.DIRECTORY_SEPARATOR);
        defined('CUSTOM_LANGUAGES_DIR') || define('CUSTOM_LANGUAGES_DIR', APPLICATION_PATH.'languages'.DIRECTORY_SEPARATOR.'custom'.DIRECTORY_SEPARATOR);
        
	}

	protected function _initCssJsImagesPaths()
	{
        $this->bootstrap('Defines');
        $cfgSite = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.site.xml');

		defined('HTTPS_ENABLED') || define('HTTPS_ENABLED', $cfgSite->https == 'on' );
		defined('CSS_VERSION') || define('CSS_VERSION', $cfgSite->css_version);
        defined('CSS_URL') || define('CSS_URL', (HTTPS_ENABLED && isset($_SERVER['HTTPS']) ? BASE_URL_SECURE : BASE_URL).'/'.CSS_VERSION.'/css');
		defined('JS_VERSION') || define('JS_VERSION', $cfgSite->js_version);
        defined('JS_URL') || define('JS_URL', (HTTPS_ENABLED && isset($_SERVER['HTTPS']) ? BASE_URL_SECURE : BASE_URL).'/'.JS_VERSION.'/js');
		defined('UPLOAD_BASE_PATH') || define('UPLOAD_BASE_PATH', DOC_ROOT);
		defined('UPLOAD_BASE_URL') || define('UPLOAD_BASE_URL', BASE_URL);
		defined('SCRIPTING_UPLOAD_PATH') || define('SCRIPTING_UPLOAD_PATH', DOC_ROOT.'upload'.DIRECTORY_SEPARATOR.'scriptCO'.DIRECTORY_SEPARATOR);
		defined('SCRIPTING_UPLOAD_URL') || define('SCRIPTING_UPLOAD_URL', SERVE_URL);
	}

	protected function _initInplementation()
	{
        $this->bootstrap(array('Databases'));
        $cfgSite = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.site.xml');

		/**
		* Define category 3 or 2a-b Implametation CLASS directory
		* include this directiry to include path
		* e.g. HTTP_CONTEXT == 'at', define AT_DIR and add it to include path
		*/
		if ( defined('HTTP_CONTEXT') && HTTP_CONTEXT ) {
			$HTTP_CONTEXT_ENGINE_DIR = ENGINE_DIR.strtoupper(HTTP_CONTEXT).DIRECTORY_SEPARATOR;
			if ( !defined(strtoupper(HTTP_CONTEXT).'_DIR') ) define(strtoupper(HTTP_CONTEXT).'_DIR', $HTTP_CONTEXT_ENGINE_DIR);
			set_include_path(
				get_include_path().
				$HTTP_CONTEXT_ENGINE_DIR.PATH_SEPARATOR
			);
		}

		/**
		* @desc
		* TODO It need to place all 2a implementetions to any conf file and the use it from this file.
		*      If we have muny 2b implamentations we can not control this part of script
		*/
		if ( isset($cfgSite->impl_type) ) {
			if ( 'ESA' != $cfgSite->impl_type ) {
				$globalGroup = Warecorp_Group_Factory::loadByGroupUID($cfgSite->impl_family_group_UID,Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);
				if ( null === $globalGroup ) throw new Zend_Exception('Family whith groupUID \''.$cfgSite->impl_family_group_UID.'\' is not exists');
				Zend_Registry::set("globalGroup", $globalGroup);
			}
			defined('IMPLEMENTATION_TYPE') || define('IMPLEMENTATION_TYPE',               $cfgSite->impl_type);
			defined('IMPLEMENTATION_VERSION') || define('IMPLEMENTATION_VERSION',            $cfgSite->impl_version);
			defined('IMPLEMENTATION_FAMILY_GROUP_UID') || define('IMPLEMENTATION_FAMILY_GROUP_UID',   $cfgSite->impl_family_group_UID);
			defined('IMPLEMENTATION_GROUP_UID') || define('IMPLEMENTATION_GROUP_UID',          $cfgSite->impl_group_UID);
			defined('USE_SSO') || define('USE_SSO',                           ($cfgSite->impl_use_sso && $cfgSite->impl_use_sso == 'on') ? true : false );
		} else throw new Zend_Exception('You use old version of cfg.site.xml. Update it. It should contain \'IMPLEMENTATION CONFIGURATION\' section');


	}

	protected function _initTranslateLocales()
	{
        $this->bootstrap(array('FileCache'));
        $cache = $this->getResource('FileCache');

		$cfgTranslateLocales = $cache->load('cfg_translate_locales_xml');
		$cfgTranslateLocalesNames = $cache->load('cfg_translate_locales_names_xml');
		if ( !$cfgTranslateLocales || !$cfgTranslateLocalesNames ) {
			$cfgTranslateLocales                = array();
			$cfgTranslateLocalesNames           = array();
			$cfgTranslateLocales[]              = 'rss';
			$cfgTranslateLocalesNames['rss']    = 'RSS';
			$dom = new DOMDocument();
			$dom->load(CONFIG_DIR.'cfg.translate.xml');
			$locales = $dom->getElementsByTagName('config')->item(0)->getElementsByTagName('locales')->item(0)->getElementsByTagName('locale');
			if ( 0 != $locales->length ) {
				foreach ( $locales as $_locale ) {
					$cfgTranslateLocales[] = strtolower(trim($_locale->nodeValue));
					if ( $_locale->getAttribute('name') && '' !== $_locale->getAttribute('name')  ) {
						$cfgTranslateLocalesNames[strtolower(trim($_locale->nodeValue))] = $_locale->getAttribute('name');
					} else {
						$cfgTranslateLocalesNames[strtolower(trim($_locale->nodeValue))] = strtoupper(trim($_locale->nodeValue));
					}

					if ( $_locale->getAttribute('default') && 'true' == $_locale->getAttribute('default')  ) {
						Warecorp::$defaultLocale = strtolower(trim($_locale->nodeValue));
					}
				}
			}
			$cache->save($cfgTranslateLocales, 'cfg_translate_locales_xml', array(), Warecorp_Cache::LIFETIME_30DAYS);
			$cache->save($cfgTranslateLocalesNames, 'cfg_translate_locales_names_xml', array(), Warecorp_Cache::LIFETIME_30DAYS);
		}
		Zend_Registry::set('cfg_translate_locales_xml', $cfgTranslateLocales);
		Zend_Registry::set('cfg_translate_locales_names_xml', $cfgTranslateLocalesNames);
	}

	/**
	 *	Define Theme Settings & Assign it to SMARTY engine
	 */
	protected function _initApplicationTheme()
    {
        $this->bootstrap(array('CssJsImagesPaths'));

        $cfgSite    = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.site.xml');

		if ( !isset($cfgSite->impl_use_theme) || $cfgSite->impl_use_theme == '' ) $currentTheme = 'product';
		else $currentTheme = $cfgSite->impl_use_theme;

		$AppTheme = new stdClass();
		$AppTheme->theme				= $currentTheme;
		$AppTheme->base_url             = BASE_URL;
		$AppTheme->common->css          = (HTTPS_ENABLED && isset($_SERVER['HTTPS']) ? BASE_URL_SECURE : BASE_URL).'/'.CSS_VERSION.'/theme/common/css';
		$AppTheme->common->js           = (HTTPS_ENABLED && isset($_SERVER['HTTPS']) ? BASE_URL_SECURE : BASE_URL).'/'.JS_VERSION.'/theme/common/js';
		$AppTheme->common->images       = (HTTPS_ENABLED && isset($_SERVER['HTTPS']) ? BASE_URL_SECURE : BASE_URL).'/'.CSS_VERSION.'/theme/common/images';
		$AppTheme->common->images_path  = DOC_ROOT.'/theme/common/images';
		$AppTheme->common->fonts_path   = DOC_ROOT.'/theme/common/fonts';
		$AppTheme->css                  = (HTTPS_ENABLED && isset($_SERVER['HTTPS']) ? BASE_URL_SECURE : BASE_URL).'/'.CSS_VERSION.'/theme/'.$currentTheme.'/css';
		$AppTheme->js                   = (HTTPS_ENABLED && isset($_SERVER['HTTPS']) ? BASE_URL_SECURE : BASE_URL).'/'.JS_VERSION.'/theme/'.$currentTheme.'/js';
		$AppTheme->images               = (HTTPS_ENABLED && isset($_SERVER['HTTPS']) ? BASE_URL_SECURE : BASE_URL).'/'.CSS_VERSION.'/theme/'.$currentTheme.'/images';
		$AppTheme->images_path          = DOC_ROOT.'/theme/'.$currentTheme.'/images';
		$AppTheme->fonts_path           = DOC_ROOT.'/theme/'.$currentTheme.'/fonts';
		$AppTheme->swfupload            = Warecorp_CO_Theme_Item::getSWFUploadTheme();
        
        Zend_Registry::set('AppTheme', $AppTheme);

        return $AppTheme;
	}

	protected function _initDatabases()
    {
        $cfgLoader      = Warecorp_Config_Loader::getInstance();
        $cfgDb          = $cfgLoader->getAppConfig('cfg.credentials.xml')->{'db'}->{'database'};
        $cfgMessageDb   = $cfgLoader->getAppConfig('cfg.credentials.xml')->{'messagedb'}->{'database'};
        $cfgSite        = $cfgLoader->getAppConfig('cfg.site.xml');
        $cfgInstance    = $cfgLoader->getAppConfig('cfg.instance.xml');

        $options = array(
            Zend_Db::CASE_FOLDING => Zend_Db::CASE_LOWER,
            Zend_Db::AUTO_QUOTE_IDENTIFIERS => FALSE
        );
        $pdo_params = array(
            PDO::NULL_EMPTY_STRING => 1,
            PDO::NULL_NATURAL => 1
        );
		//	Initialize main database
		if ($cfgDb->use == 'true') {
			$params = array (
                'adapterNamespace' => 'Warecorp_Db_Adapter',
				'host'           => $cfgDb->host,
				'username'       => $cfgDb->username,
				'password'       => $cfgDb->password,
				'dbname'         => $cfgDb->name,
                'persistent'     => ( defined('USE_PERSISTENT_CONNECTION') && USE_PERSISTENT_CONNECTION ) ? true : false,
                'options'        => $options,
                'driver_options' => $pdo_params,
                'profiler'       => ( $cfgInstance->debug_mode == 'on' && !defined('TURN_OFF_DEBUG') ) ? true : false
			);
			try {
				$db = Zend_Db::factory($cfgDb->type, $params);
				$db->query('SET NAMES utf8');
				$db->query('SET time_zone = "UTC"');
				Zend_Db_Table::setDefaultAdapter($db);
				Zend_Registry::set('DB', $db);
			} catch (Zend_Db_Adapter_Exception $error) {
			    /**
			     * Log message
			     */
			    $db_connection_log_file = DEBUG_LOG_DIR."db_connection_error.log";
			    $fp = fopen($db_connection_log_file, "a");
                fwrite($fp, '['.date('j/M/Y:H:i:s O').'] SITE_DB '. $error->getMessage() ."\n");
                fclose($fp);
                chmod($db_connection_log_file, 0777);
			    
			    if ( defined("HTTP_CONTEXT") && HTTP_CONTEXT == "zccf" ) {
			        die("<p style='font-size:11px;'>Our apologies, the portal service you requested is unavailable at this time. Please try again in a few moments. Our developers have been informed of this error.</p>");
			    } else {
				    die("<p style='font-size:11px;'>Sorry, the portal is on service. Visit it later.1</p>");
				}
			}
		}

		//	Initialize message database
		if ($cfgMessageDb->use == 'true') {
			$params = array (
                'adapterNamespace' => 'Warecorp_Db_Adapter',
				'host'     => $cfgMessageDb->host,
				'username' => $cfgMessageDb->username,
				'password' => $cfgMessageDb->password,
				'dbname'   => $cfgMessageDb->name,
                'persistent' => ( defined('USE_PERSISTENT_CONNECTION') && USE_PERSISTENT_CONNECTION ) ? true : false,
                'options'   => $options
			);

			try {
				$messageDb = Zend_Db::factory($cfgMessageDb->type, $params);
				$messageDb->query('SET NAMES utf8');
				$messageDb->query('SET time_zone = "UTC"');
				Zend_Registry::set('messageDB', $messageDb);
				$messageDb->getProfiler()->setEnabled(false);
			} catch (Zend_Db_Adapter_Exception $error) {
                /**
                 * Log message
                 */
                $db_connection_log_file = DEBUG_LOG_DIR."db_connection_error.log";
                $fp = fopen($db_connection_log_file, "a");
                fwrite($fp, '['.date('j/M/Y:H:i:s O').'] MESSAGE_DB '. $error->getMessage() ."\n");
                fclose($fp);
                chmod($db_connection_log_file, 0777);
                
			    if ( defined("HTTP_CONTEXT") && HTTP_CONTEXT == "zccf" ) {
			        die("<p style='font-size:11px;'>Our apologies, the portal service you requested is unavailable at this time. Please try again in a few moments. Our developers have been informed of this error.</p>");
			    } else {
				    die("<p style='font-size:11px;'>Sorry, the portal is on service. Visit it later.2</p>");
				}
			}
		}
	}

	protected function _initWhoApproveUserAccount()
	{
        $cfgSite = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.site.xml');
        $cfgInstance = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.instance.xml');

		if ( !empty($cfgInstance->who_approve_user_account) ) {
			if ( Warecorp_User::isUserExists('login', $cfgInstance->who_approve_user_account)) {
				define('WHO_APPROVE_USER_ACCOUNT', $cfgInstance->who_approve_user_account);
			} else {
				//  try get login of Main Group Host
				$mainGroup = Warecorp_Group_Factory::loadByGroupUID($cfgSite->impl_group_UID,Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
				if ( null !== $mainGroup && null !== $mainGroup->getId() && $mainGroup->getHost()->getId() ) {
					defined('WHO_APPROVE_USER_ACCOUNT')
						|| define('WHO_APPROVE_USER_ACCOUNT', $mainGroup->getHost()->getLogin());
				}
				else {
					throw new Exception ("User with such login doesn' exists");
				}
			}
		}
	}

	protected function _initSession()
    {
        $cfgSite = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.site.xml');

		if ( isset( $cfgSite->session_save_handler))    ini_set( "session.save_handler", $cfgSite->session_save_handler);
		if ( isset( $cfgSite->session_save_path))       ini_set( "session.save_path", $cfgSite->session_save_path);
		session_set_cookie_params('', '/', '.'.BASE_HTTP_HOST);

		$SWFUploadID = ( isset($_REQUEST['SWFUploadID']) ) ? $_REQUEST['SWFUploadID'] : null;
		if ( $SWFUploadID !== null ) session_id($SWFUploadID);

		session_start();
	}

    protected function _initFrontController()
    {
        $cfgSite = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.site.xml');

        if ( $cfgSite->use_port_in_URL == '0' && !empty($_SERVER['HTTP_REFERER']) ) {
            $_SERVER['HTTP_REFERER'] = preg_replace('/\:\d+/', '', $_SERVER['HTTP_REFERER']);
        }

        $front = Zend_Controller_Front::getInstance();
        $front->setDispatcher(new Warecorp_Controller_Dispatcher());
        $front->setControllerDirectory(MODULES_DIR);
        $front->setDefaultControllerName('index');
        $front->setDefaultAction('index');
        $front->throwExceptions(true);

        return $front;
    }

    protected function _initRouter()
    {
        $this->bootstrap(array('FrontController'));
        $front = $this->getResource('FrontController');

        $router = $front->getRouter();
        $router->removeDefaultRoutes();
        $router->setGlobalParam('locale', 'en');
        $router->setGlobalParam('module', 'default');



        $router->addRoute(
            'default',
            new Zend_Controller_Router_Route(
                ':locale/:controller/:action/*',
                array(
                    'locale'     => 'en',
                    'controller' => 'index',
                    'action'     => 'index'
                )
            )
        );


        $router->addRoute(
            'generalUsers',
            new Zend_Controller_Router_Route(
                ':locale/users/:action/*',
                array(
                    'controller'    => 'users',
                    'action'        => 'index',
                    'locale'        => 'en'
                )
            )
        );
        $router->addRoute(
            'generalGroups',
            new Zend_Controller_Router_Route(
                ':locale/groups/:action/*',
                array(
                    'controller'    => 'groups',
                    'action'        => 'index',
                    'locale'        => 'en'
                )
            )
        );
        $router->addRoute(
            'directUser',
            new Zend_Controller_Router_Route(
                ':locale/user/:username/:action/*',
                array(
                    'controller'    => 'users',
                    'action'        => 'profile',
                    'locale'        => 'en'
                )
            )
        );
        $router->addRoute(
            'directGroup',
            new Zend_Controller_Router_Route(
                ':locale/group/:groupname/:action/*',
                array(
                    'controller'    => 'groups',
                    'action'        => 'summary',
                    'locale'        => 'en'
                )
            )
        );



        //$usersRouteSpec = new Zend_Controller_Router_Route_Hostname(
            //':username.users.'.BASE_HTTP_HOST,
            //array( 'controller' => 'users' )
        //);
        //$usersRouteDef = new Zend_Controller_Router_Route_Hostname(
            //'users.'.BASE_HTTP_HOST,
            //array( 'controller' => 'users' )
        //);
        //$groupRouteSpec = new Zend_Controller_Router_Route_Hostname(
            //':groupname.groups.'.BASE_HTTP_HOST,
            //array( 'controller' => 'groups' )
        //);
        //$groupRouteDef = new Zend_Controller_Router_Route_Hostname(
            //'groups.'.BASE_HTTP_HOST,
            //array( 'controller' => 'groups' )
        //);
        //$hostRoutePathUsers = new Zend_Controller_Router_Route(
            //':locale/:action/*',
            //array(
                //'locale' => 'en',
                //'action' => 'index'
            //)
        //);
        //$hostRoutePathGroups = new Zend_Controller_Router_Route(
            //':locale/:action/*',
            //array(
                //'locale' => 'en',
                //'action' => 'summary'
            //)
        //);
        //$router->addRoute('usersRouteDef', $usersRouteDef->chain($hostRoutePathUsers));
        //$router->addRoute('usersRouteSpec', $usersRouteSpec->chain($hostRoutePathUsers));
        //$router->addRoute('groupRouteDef', $groupRouteDef->chain($hostRoutePathGroups));
        //$router->addRoute('groupRouteSpec', $groupRouteSpec->chain($hostRoutePathGroups));

        return $router;
    }

	protected function _initView()
	{

        $this->bootstrap(array('Databases','Defines', 'MVCPaths','Inplementation','ApplicationTheme'));

        $cfgLoader   = Warecorp_Config_Loader::getInstance();
        $cfgSite     = $cfgLoader->getAppConfig('cfg.site.xml');
        $cfgInstance = $cfgLoader->getAppConfig('cfg.instance.xml');

		require_once 'Smarty.class.php';

		/**
		 * Set Smarty directories.
		 */
        $view = new Warecorp_View_Smarty();
		$view->setTemplatesDir(TEMPLATES_DIR);
		$view->setCompiledDir(APP_VAR_DIR.'/_compiled/site');

		/**
		 * Add plugin directories to include path
		 * Create inheritance for plgins by addign paths in correct order
		 * Plugins will be found
		 * first in SMARTY_PLUGINS_DIR then CORE_SMARTY_PLUGINS_DIR then PRODUCT_SMARTY_PLUGINS_DIR then COMMON_SMARTY_PLUGINS_DIR
		 */
		if ( defined('CORE_SMARTY_PLUGINS_DIR') ) {
            $view->getSmarty()->plugins_dir = array(
                SMARTY_PLUGINS_DIR,
                CORE_SMARTY_PLUGINS_DIR,
                PRODUCT_SMARTY_PLUGINS_DIR,
                COMMON_SMARTY_PLUGINS_DIR
            );
		} else {
            $view->getSmarty()->plugins_dir = array(
                SMARTY_PLUGINS_DIR,
                PRODUCT_SMARTY_PLUGINS_DIR,
                COMMON_SMARTY_PLUGINS_DIR
            );
		}

		/**
		 * if $cfgSite->debug_force_compile == on templates will be compiled every time
		 */
		if ( isset($cfgInstance->debug_force_compile) && $cfgInstance->debug_force_compile == 'on' ) {
			$view->getSmarty()->force_compile = true;
		}

		/**
		 * Register translate prefilter
		 */
		if ( Warecorp::isTranslateMode() )      $view->getSmarty()->register_prefilter("Warecorp_Translate::translateContentPrefilter");
		if ( Warecorp::isTranslateDebugMode() ) $view->getSmarty()->clear_compiled_tpl();

		$view->getSmarty()->compile_check       =   true;
		$view->SITE_NAME                        =   iconv("UTF-8","Windows-1251", $cfgSite->name);
		$view->IMAGES_SIZE_LIMIT                =   IMAGES_SIZE_LIMIT;
		$view->VIDEOS_SIZE_LIMIT                =   VIDEOS_SIZE_LIMIT;
		$view->DOCUMENTS_SIZE_LIMIT             =   DOCUMENTS_SIZE_LIMIT;
		$view->AVATARS_SIZE_LIMIT               =   AVATARS_SIZE_LIMIT;
		$view->BASE_HTTP_HOST                   =   BASE_HTTP_HOST;
		$view->BASE_URL                         =   BASE_URL;
		$view->BASE_URL_SECURE                  =   BASE_URL_SECURE;
		$view->CSS_VERSION                      =   CSS_VERSION;
		$view->CSS_URL                          =   CSS_URL;
		$view->JS_VERSION                       =   JS_VERSION;
		$view->JS_URL                           =   JS_URL;
		$view->SITE_NAME_AS_STRING              =   SITE_NAME_AS_STRING;
		$view->SITE_NAME_AS_DOMAIN              =   SITE_NAME_AS_DOMAIN;
		$view->SITE_NAME_AS_FULL_DOMAIN         =   SITE_NAME_AS_FULL_DOMAIN;
		$view->DOMAIN_FOR_EMAIL                 =   DOMAIN_FOR_EMAIL;
		$view->DOMAIN_FOR_GROUP_EMAIL           =   DOMAIN_FOR_GROUP_EMAIL;
		$view->ADMIN_EMAIL                      =   ADMIN_EMAIL;
		$view->IMPLEMENTATION_TYPE              =   IMPLEMENTATION_TYPE;
		$view->IMPLEMENTATION_VERSION           =   IMPLEMENTATION_VERSION;
		$view->IMPLEMENTATION_FAMILY_GROUP_UID  =   IMPLEMENTATION_FAMILY_GROUP_UID;
		$view->IMPLEMENTATION_GROUP_UID         =   IMPLEMENTATION_GROUP_UID;
		$view->USE_USER_PATH                    =   USE_USER_PATH;
		$view->GOOGLE_ANALYTICS                 =   GOOGLE_ANALYTICS;
		$view->GOOGLE_ANALYTICS_ID              =   GOOGLE_ANALYTICS_ID;

                $settings = new Warecorp_Settings(HTTP_CONTEXT);
                $view->TRACER_CODE = $settings->getTracerCode();

        $view->Warecorp                         =   new Warecorp();
        $view->IMAGES_EXT                       =   Warecorp_File_Enum_Extensions::getInMaskMode(Warecorp_File_Enum_Extensions::IMAGES);
        $view->VIDEOS_EXT                       =   Warecorp_File_Enum_Extensions::getInMaskMode(Warecorp_File_Enum_Extensions::VIDEOS);
        $view->VIDEOMODEFOLDER                  =   VIDEOMODEFOLDER;
        $view->WP_SSO_ENABLED                   =   WP_SSO_ENABLED;
        $view->WP_SSO_URL                       =   WP_SSO_URL;

		if (!empty($cfgSite->bugTrackingSystem)) {
			$view->BUG_TRACKING_SYSTEM = $cfgSite->bugTrackingSystem;
			if (!empty($cfgSite->bugTrackingProjectCode)) {
				$view->BUG_TRACKING_PROJECT_CODE = $cfgSite->bugTrackingProjectCode;
			}
		}

		/**
		 * Define Theme Settings & Assign it to SMARTY engine
		 */
        $AppTheme = Zend_Registry::get('AppTheme');//$this->getResource('AppTheme');
		$view->AppTheme = $AppTheme;
		$view->AppThemeJson = Zend_Json::encode($AppTheme);

		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
		$viewRenderer->setView($view);
		$viewRenderer->setViewScriptPathSpec($view->layout);

        return $view;
	}

	protected function _initUser()
	{
        $this->bootstrap(array('View'));
        $view = $this->getResource('View');
		/**
		 * Create the User object instance using session data
		 */
		$_SESSION['user_id'] = ( !isset($_SESSION['user_id']) ) ? null : $_SESSION['user_id'];
		$user = new Warecorp_User("id", $_SESSION['user_id']);
		$view->user = $user;
		Zend_Registry::set("User", $user);

	    /**
         * check remember me
         */
        if ( !$user->getId() ){
            if ( isset($_COOKIE["zanby_username"]) && isset($_COOKIE["zanby_password"]) ){
                $user = new Warecorp_User("login", $_COOKIE["zanby_username"]);
                if ( $_COOKIE["zanby_password"] == md5($user->getPass()) && ($user->getStatus() == Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE) ) {
                    $user->authenticate();
                    Zend_Registry::set("User", $user);
                    $view->user = $user;
                }
            }
            //  Wordpress SSO
            elseif ( WP_SSO_ENABLED && isset($_COOKIE["wpsso_username"]) && isset($_COOKIE["wpsso_password"]) ) {
                $user = new Warecorp_User("login", $_COOKIE["wpsso_username"]);
                if ( $_COOKIE["wpsso_password"] == md5($user->getPass()) && ($user->getStatus() == Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE) ) {
                    if ( isset($_COOKIE["wpsso_rememberme"]) && $_COOKIE["wpsso_rememberme"] ) {
                        setcookie("zanby_username", $user->getLogin(), time()+2592000, "/",'.'.BASE_HTTP_HOST); //  60*60*24*30 = 2592000
                        setcookie("zanby_password", md5($user->getPass()), time()+2592000, "/",'.'.BASE_HTTP_HOST);
                    }
                    setcookie("wpsso_username", '', time()+2592000, "/",'.'.BASE_HTTP_HOST); //  60*60*24*30 = 2592000
                    setcookie("wpsso_password", '', time()+2592000, "/",'.'.BASE_HTTP_HOST);
                    setcookie("wpsso_rememberme", '', time()+2592000, "/",'.'.BASE_HTTP_HOST);

                    $user->authenticate();
                    Zend_Registry::set("User", $user);
                    $view->user = $user;
                }
            }
        }

        $user->updateLastAccessDate();

        $locale = new Zend_Locale($user->getLocale()); //set locale (ru_RU, de_AT, fr_FR)
        //$locale->setCache(Warecorp_Cache::getFileCache());
        Zend_Registry::set('Zend_Locale', $locale); //save default locale for application. Yes, ZF store default locale in registry :)
        Zend_Date::setOptions(array('format_type' => 'iso')); // set format type

    }

	protected function _initFacebookAPI()
	{
        $this->bootstrap(array('View','User'));
        $view = $this->getResource('View');

        $cfgCredentials = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.credentials.xml')->{'site'};

		if ( isset($cfgCredentials->facebook_used) && (strtolower($cfgCredentials->facebook_used) == 'on' || $cfgCredentials->facebook_used == 1) ) {
			defined('FACEBOOK_USED') || define('FACEBOOK_USED', true);
			defined('FACEBOOK_API_KEY') || define('FACEBOOK_API_KEY', $cfgCredentials->facebook_api_key); //DEPRECATED
            defined('FACEBOOK_APP_ID') || define('FACEBOOK_APP_ID', $cfgCredentials->facebook_app_id);
			defined('FACEBOOK_API_SECRET') || define('FACEBOOK_API_SECRET', $cfgCredentials->facebook_api_secret);
			$view->FACEBOOK_USED        = FACEBOOK_USED;
            $view->FACEBOOK_APP_ID      = FACEBOOK_APP_ID;
			$view->FACEBOOK_API_KEY     = FACEBOOK_API_KEY;
			$view->FACEBOOK_API_SECRET  = FACEBOOK_API_SECRET;

			$_SESSION['FACEBOOK_SESSION_STATE'] = ( isset($_SESSION['FACEBOOK_SESSION_STATE']) ) ? $_SESSION['FACEBOOK_SESSION_STATE'] : 0;
			defined ('FACEBOOK_SESSION_STATE') || define('FACEBOOK_SESSION_STATE', $_SESSION['FACEBOOK_SESSION_STATE']);
			$view->FACEBOOK_SESSION_STATE = $_SESSION['FACEBOOK_SESSION_STATE'];

			$facebookId = Warecorp_Facebook_Api::getFacebookId();
			$_SESSION['FACEBOOK_SESSION_STATE'] = ($facebookId) ? 1 : 0;

			/**
			 * Don't remove this block
			 * @author Artem Sukharev
			 */
            //   Facebook SSO
            /*
	        if ( ! Zend_Registry::get('User')->getId() ) {
                $fbuser = Warecorp_Facebook_User::login();
                if ( $fbuser && $fbuser instanceof Warecorp_User ) {
                    $fbuser->authenticate();
                    Zend_Registry::set("User", $fbuser);
                    $view->user = $fbuser;
                }
            }
            */
		} else {
			define('FACEBOOK_USED', false);
			$view->FACEBOOK_USED = FACEBOOK_USED;
		}
	}

	protected function _initAppNotifications()
	{
        $this->bootstrap(array('View'));
        $view = $this->getResource('View');
        /**
         * Facebook Post Feed Dialog
         */
        if ( FACEBOOK_USED ) {
            if (!defined('FB_JS_ASSIGNED')) {
               // echo "asdasdasd";
                $fbJsInit = Warecorp_Facebook_Feed::onPageInit();
                $view->fbJsInit = $fbJsInit;
                if ( isset($fbJsInit) && !empty($fbJsInit) ) {$view->denyAutoRedirect = true;}
                else  { $view->denyAutoRedirect = false;}
                define('FB_JS_ASSIGNED', true);
            }

        }
	}
	
	/*
	protected function _initEnvchecks()
	{
	    $this->_initDefines();
	    $cache = $this->_initFileCache();
	    
	    
	    if ($cache->load('EnvChecks_completed') === false) {
	        $client = Warecorp::getMailServerTemplateClient();
	        try {
            $version = $client->getVersion();
                if ((int)$version < 20110120) {
                    throw new Exception('Wrong version');
                }
            } catch (Exception $e) {
                print "ERROR. Old Mailsrv version.\n";
                exit;
            }
	    }
	    $cache->save(1, 'EnvChecks_completed');
	}*/
}

