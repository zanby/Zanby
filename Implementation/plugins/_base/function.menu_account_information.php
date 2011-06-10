<?php
	Warecorp::addTranslation('/plugins/function.menu_account_information.php.xml');
    function smarty_function_menu_account_information($params, &$smarty)
    {
        $theme              = Zend_Registry::get('AppTheme');
        $objUser            = Zend_Registry::get('User');
        $URL                = BASE_URL.'/'.LOCALE;
		$src = $objUser->getAvatar()->setWidth(28)->setHeight(28)->getImage();
        if ( $objUser && null !== $objUser->getId() ) {
            $output = array();
            $output[] = "<ul class='prTopNav prInnerRight prIndentRight'>";
            $output[] = "<li class='active'><a class='prNoBorder' href='{$objUser->getUserPath('profile')}'>".htmlspecialchars(substr($objUser->getFirstname().' '.$objUser->getLastname(), 0, 20));
			if (strlen($objUser->getFirstname().' '.$objUser->getLastname()) > 20){
				$output[] = "...";
			}
			$output[] = "</a></li>";
            $output[] = "<li class='first'><a href='{$objUser->getUserPath('settings')}'>".Warecorp::t('My Account')."</a></li>";
            if ( FACEBOOK_USED && Warecorp_Facebook_User::isLogined($objUser) ) {                
                $output[] = "<li class='last'><a href='{$URL}/users/logout/' onclick='FBApplication.facebook_logout();'>".Warecorp::t('Sign Out')."</a></li>";
            } else {
                $output[] = "<li class='last'><a href='{$URL}/users/logout/'>".Warecorp::t('Sign Out')."</a></li>";
            }
            $output[] = "</ul>";
			if ( FACEBOOK_USED && Warecorp_Facebook_User::isLogined($objUser) ) {
				$output[] = "<span style='background:url(".$src.") no-repeat center;' class='prSFaceBookAvatar prFloatRight'><img src='{$theme->images}/decorators/icons/icoFB.gif' title='' alt='' width='28' height='28'/></span>";
			} else {
				$output[] = "<img src='{$objUser->getAvatar()->setWidth(28)->setHeight(28)->getImage()}' title='' alt='' width='28' height='28'/>";
			}
        } else {
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
                       throw new Zend_Exception(Warecorp::t('Configuration file')." \'anonymous_allowed.xml\' ".Warecorp::t('was not found.'));
                    }	    
                    $anonymousAccess = new Warecorp_Access();
                    $anonymousAccess->loadXmlConfig($cfg_access_file);
                    if ( !$anonymousAccess->isAllowed('global', '*') ) { return ''; }
                }
			
            $output = array();
            $output[] = "<ul class='prTopNav prInnerRight prIndentRight'>";
            $output[] = "<li class='prText6 prIndentTopSmall'>".Warecorp::t('New User?')."</li>";
            $output[] = "<li class='last'><a class='prNoBorder' href='{$URL}/registration/index/'>".Warecorp::t('Sign Up')."</a></li>";
            $output[] = "<li class='active'><a href='{$URL}/users/login/'>".Warecorp::t('Sign In')."</a></li>";
            $output[] = "</ul>";            
        }
        $output = join('', $output);
        return $output;
    }
