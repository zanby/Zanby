<?php
    function smarty_function_menu_bottom($params, &$smarty)
    {
		Warecorp::addTranslation('/plugins/function.menu_bottom.php.xml');
        $theme      = Zend_Registry::get('AppTheme');
        $objUser    = Zend_Registry::get('User');
        $URL        = BASE_URL.'/'.LOCALE;
        
        if ( $Overide = $smarty->get_template_vars('__OveridePluginContent__') && !empty($Overide['function.menu_bottom']) ) {
            return $Overide['function.menu_bottom'];
        }
            
        /**
         * +------------------------------------------------------------------------------------
         * |
         * |    ESA 
         * |
         * +------------------------------------------------------------------------------------
         */
        if ( 'ESA' == IMPLEMENTATION_TYPE ) {
            $output[] = "<ul>";
            if ( $objUser && null !== $objUser->getId() ) {
                $output[] = "<li><a href='{$objUser->getUserPath('profile')}'>".Warecorp::t('Profile')."</a></li>";
            }
            $output[] = "<li><a href='{$URL}/groups/'>".Warecorp::t('Groups')."</a></li>";
            $output[] = "<li><a href='{$URL}/groups/familylanding/'>".Warecorp::t('Group Families')."</a></li>";
            $output[] = "<li class='last'><a href='{$URL}/users/'>".Warecorp::t('People')."</a></li>";
            //$output[] = "<li class='last'><a href='{$URL}/info/tour/'>".Warecorp::t('Tours')."</a></li>";
            $output[] = "</ul>";
            
            $output[] = "<ul>";
            $output[] = "<li><a href='{$URL}/info/contactus/'>".Warecorp::t('Contact us')."</a></li>";
            $output[] = "<li><a href='{$URL}/info/about/'>".Warecorp::t('About Us')."</a></li>";
            $output[] = "<li><a href='{$URL}/info/copyright/'>".Warecorp::t('Copyright')."</a></li>";
            $output[] = "<li class='last'><a href='{$URL}/info/privacy/'>".Warecorp::t('Privacy Policy')."</a></li>";
            //$output[] = "<li class='last'><a href='{$URL}/info/support/'>".Warecorp::t('Help')."</a></li>";
            $output[] = "</ul>";
        }
        /**
         * +------------------------------------------------------------------------------------
         * |
         * |    EIA 
         * |
         * +------------------------------------------------------------------------------------
         */
        else {
	        if ( Zend_Registry::isRegistered('globalGroup') ) {
	        	require_once(MODULES_DIR.'/GroupsController.php');
	            $objGlobalGroup = Zend_Registry::get('globalGroup');       
	        } else throw new Exception('Incorrect global group param');

            if ( $objUser && null !== $objUser->getId() ) {
                $output[] = "<ul>";
                $output[] = "<li><a href='{$URL}/index/'>".Warecorp::t('Home')."</a></li>";
                $output[] = "<li><a href='{$objUser->getUserPath('profile')}'>".Warecorp::t('Profile')."</a></li>";
                $output[] = "<li><a href='{$objGlobalGroup->getGroupPath('documents')}'>".Warecorp::t('Documents')."</a></li>";
                $output[] = "<li class='last'><a href='{$URL}/users/'>".Warecorp::t('People')."</a></li>";
                //$output[] = "<li class='last'><a href='{$URL}/info/tour/'>".Warecorp::t('Tours')."</a></li>";
                $output[] = "</ul>";
                
                $output[] = "<ul>";
                $output[] = "<li><a href='{$URL}/info/contactus/'>".Warecorp::t('Contact us')."</a></li>";
                $output[] = "<li><a href='{$URL}/info/about/'>".Warecorp::t('About Us')."</a></li>";
                $output[] = "<li><a href='{$URL}/info/copyright/'>".Warecorp::t('Copyright')."</a></li>";
                $output[] = "<li class='last'><a href='{$URL}/info/privacy/'>".Warecorp::t('Privacy Policy')."</a></li>";
                //$output[] = "<li class='last'><a href='{$URL}/info/support/'>".Warecorp::t('Help')."</a></li>";
                $output[] = "</ul>";
            } else {
                if ( 'zays' == $objGlobalGroup->getGroupUID() ) {
                    $output[] = "<p class='prText5 prIndentTopSmall prTCenter'>".Warecorp::t('Responsible Cotton Network')." </p>";
                    $output[] = "<p class='prText5 prIndentTopSmall prTCenter'>".Warecorp::t('311 California Street, Suite 510  San Francisco, CA 94104  +1.415.391.3212')."</p>";
                    $output[] = "<p class='prIndentTopSmall prTCenter'><a href='{$URL}/info/privacy/' class='prLink3'>".Warecorp::t('Privacy Policy')."</a>  |  <a href='{$URL}/info/terms/' class='prLink3'>".Warecorp::t('Confidentiality and Terms of Service')."</a></p>";
                    $output = join('', $output);
                    return $output;
                }
                
                $output[] = "<ul>";
                $output[] = "<li><a href='{$URL}/index/'>".Warecorp::t('Home')."</a></li>";
                $output[] = "<li><a href='{$URL}/registration/index/'>".Warecorp::t('Register')."</a></li>";
                $output[] = "<li><a href='{$URL}/users/login/'>".Warecorp::t('Login')."</a></li>";
                $output[] = "<li class='last'><a href='{$URL}/users/'>".Warecorp::t('People')."</a></li>";
               // $output[] = "<li class='last'><a href='{$URL}/info/tour/'>".Warecorp::t('Tours')."</a></li>";
                $output[] = "</ul>";
                
                $output[] = "<ul>";
                $output[] = "<li><a href='{$URL}/info/contactus/'>".Warecorp::t('Contact us')."</a></li>";
                $output[] = "<li><a href='{$URL}/info/about/'>".Warecorp::t('About Us')."</a></li>";
                $output[] = "<li><a href='{$URL}/info/copyright/'>".Warecorp::t('Copyright')."</a></li>";
                $output[] = "<li class='last'><a href='{$URL}/info/privacy/'>".Warecorp::t('Privacy Policy')."</a></li>";
                //$output[] = "<li class='last'><a href='{$URL}/info/support/'>".Warecorp::t('Help')."</a></li>";
                $output[] = "</ul>";            
            }
        }
        
        $output = join('', $output);
        return $output;
    }