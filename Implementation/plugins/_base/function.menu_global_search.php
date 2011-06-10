<?php
    function smarty_function_menu_global_search($params, &$smarty)
    {
		Warecorp::addTranslation('/plugins/function.menu_global_search.php.xml');
        $theme = Zend_Registry::get('AppTheme');
        $objUser = Zend_Registry::get('User');

        /* if all action are disabled for anon user - turn off block account information */
            if ( !$objUser || null == $objUser->getId()  ) {
                /**
                 * Choose configuration file
                 * if file exits in root access folder get it else
                 * get configuration file from ESA|EIA folder
                 */
                if ( file_exists(ACCESS_RIGHTS_DIR.'anonymous_allowed.xml') ) {
                    $cfg_access_file = ACCESS_RIGHTS_DIR.'anonymous_allowed.xml';
                } elseif ( file_exists(ACCESS_RIGHTS_DIR.IMPLEMENTATION_TYPE.'/anonymous_allowed.xml') ) {
                    $cfg_access_file = ACCESS_RIGHTS_DIR.IMPLEMENTATION_TYPE.'/anonymous_allowed.xml';
                } else {
                   throw new Zend_Exception(Warecorp::t('Configuration file').' \'anonymous_allowed.xml\' '.Warecorp::t('was not found.'));
                }
                $anonymousAccess = new Warecorp_Access();
                $anonymousAccess->loadXmlConfig($cfg_access_file);
                if ( !$anonymousAccess->isAllowed('global', '*') ) { return ''; }
            }

        if ($smarty->get_template_vars('keywords_gs') != ''){
            $value = htmlspecialchars($smarty->get_template_vars('keywords_gs'));
        } else {
            $value = Warecorp::t('Search');
        }
        $prepared_value = Warecorp::t('Search');
        $prepared_value = str_replace("'", "\'", $prepared_value);
        $onblur = "if (this.value == '') {this.value = '".$prepared_value."';}";
        $onfocus = "if (this.value == '".$prepared_value."') {this.value = '';}";

        if ('search' == Warecorp::$controllerName){
             $formAction = BASE_URL."/".LOCALE."/search/".Warecorp::$actionName."/";
        } else {
             $formAction = BASE_URL."/".LOCALE."/search/search/";
        }

        $output = array();
        $output[] = "<form method='POST' action='".$formAction."'>";
        $output[] = "<input type='hidden' name='preset' value='new' />";
        $output[] = "<input src='{$theme->images}/buttons/searchTopButton.gif' type='image' name='' alt='".Warecorp::t('Search')."' class='prSearchButton' />";
        $output[] = "<input type='text' name='keywords' class='prSearchField' value=\"{$value}\" onblur=\"{$onblur}\" onfocus=\"{$onfocus}\" />";
        $output[] = "</form>";
        $output = join('', $output);

        return $output;
    }
