<?php
    function smarty_function_menu_global($params, &$smarty)
    {
        Warecorp::addTranslation('/plugins/function.menu_global.php.xml');
        $objUser    = Zend_Registry::get('User');
        $Warecorp   = new Warecorp();
        $URL        = BASE_URL.'/'.LOCALE;

        $output = array();
        /**
         * +------------------------------------------------------------------------------------
         * |
         * |    ESA
         * |
         * +------------------------------------------------------------------------------------
         */
        if ( 'ESA' == IMPLEMENTATION_TYPE ) {
            $isProfile  = '';
            $isGroups   = '';
            $isFamilies = '';
            $isPeople   = '';
            if ( 'users' == Warecorp::$controllerName && 'index' == Warecorp::$actionName ) {
                $context = 'user';
                $objCurrentUser = $smarty->get_template_vars('currentUser');
                $isPeople = ' class="active"';
            }
            if ( 'users' == Warecorp::$controllerName && 'settings' != Warecorp::$actionName && 'avatars' != Warecorp::$actionName && 'privacy' != Warecorp::$actionName && 'networks' != Warecorp::$actionName) {
                $context = 'user';
                $objCurrentUser = $smarty->get_template_vars('currentUser');
                if ( $objUser && null !== $objUser->getId() && $objCurrentUser && null !== $objCurrentUser->getId() && $objUser->getId() == $objCurrentUser->getId() && 'settings' !== Warecorp::$actionName ) {
                    $isProfile = ' class="active"';
                }
            } elseif( 'groups' == Warecorp::$controllerName ) {
                $context = 'group';
                $objGroup = $smarty->get_template_vars('currentGroup');
                if ( $objGroup && null !== $objGroup->getId() && 'family' != $objGroup->getGroupType() ) {
                    $isGroups = ' class="active"';
                } elseif ( $objGroup && null !== $objGroup->getId() && 'family' == $objGroup->getGroupType() ) {
                    $isFamilies = ' class="active"';
                } elseif ( 'familylanding' == Warecorp::$actionName )  {
                    $isFamilies = ' class="active"';
                } elseif ( Warecorp::is('Index', 'Group') ) {
                    $isGroups = ' class="active"';
                }
            }
            $output[] = "<ul>";
            $output[] = "<li{$isPeople} id ='tab4'><a href='{$URL}/users/' class='last'><span>".Warecorp::t('People')."</span></a></li>";
            $output[] = "<li{$isFamilies} id ='tab3'><a href='{$URL}/groups/familylanding/'><span>".Warecorp::t('Group Families')."</span></a></li>";
            $output[] = "<li{$isGroups} id ='tab2'><a href='{$URL}/groups/'><span>".Warecorp::t('Groups')."</span></a></li>";
            if ( $objUser && null !== $objUser->getId() ) {
                $output[] = "<li{$isProfile} id ='tab1'><a href='{$objUser->getUserPath('profile')}'><span>".Warecorp::t('Profile')."</span></a></li>";
            }
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
            } else throw new Exception(Warecorp::t('Incorrect global group param'));

            /* if all action are disabled for anon user - turn off gloabal navigation */
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

            $showProfile = (boolean) $objUser && null !== $objUser->getId();

            $isHost     = (boolean) $objGlobalGroup->getMembers()->isHost($objUser->getId());
            $isCohost   = (boolean) $objGlobalGroup->getMembers()->isCohost($objUser->getId());
            $showTools  = $isHost || $isCohost;

            $isProfile      = '';
            $isTools        = '';
            $isFamilyFiles  = '';
            $isEvents       = '';
            $isDiscussions  = '';
            $isPeople       = '';
            $isGroups       = '';
            $isHome         = '';
            if ( 'users' == Warecorp::$controllerName && 'settings' != Warecorp::$actionName && 'avatars' != Warecorp::$actionName && 'privacy' != Warecorp::$actionName && 'networks' != Warecorp::$actionName ) {
                $context = 'user';
                $objCurrentUser = $smarty->get_template_vars('currentUser');
                if ( $objUser && null !== $objUser->getId() && $objCurrentUser && null !== $objCurrentUser->getId() && $objUser->getId() == $objCurrentUser->getId() && 'settings' !== Warecorp::$actionName ) {
                    $isProfile = ' class="active"';
                } elseif (!$objCurrentUser || !$objCurrentUser->getId() || !$objUser || !$objUser->getId() || $objUser->getId() != $objCurrentUser->getId()) {
                    $isPeople = ' class="active"';
                }
                if ( 'index' == Warecorp::$actionName ) $isPeople = ' class="active"';
            } elseif( 'groups' == Warecorp::$controllerName ) {
                $context = 'group';
                $objGroup = $smarty->get_template_vars('currentGroup');
                if ( $objGlobalGroup && $objGroup && $objGlobalGroup->getId() == $objGroup->getId() ) {
                    if ( 'summary' == Warecorp::$actionName || 'index' == Warecorp::$actionName ) $isHome = ' class="active"';
                    if ( Warecorp::is('Summary', 'Group') ) $isHome = ' class="active"';
                    if ( Warecorp::is('Members', 'Group') ) $isGroups = ' class="active"';
                    if ( Warecorp::is('Discussions', 'Group') ) $isDiscussions = ' class="active"';
                    if ( Warecorp::is('Events', 'Group') ) $isEvents = ' class="active"';
                    if ( Warecorp::is('Stuff', 'Group') ) $isFamilyFiles = ' class="active"';
                    if ( Warecorp::is('Tools', 'Group') ) $isTools = ' class="active"';
                    if ( Warecorp::is('Index', 'Group') ) $isHome = ' class="active"';
                } else {
                    $isGroups = ' class="active"';
                }
            } elseif ( 'index' == Warecorp::$controllerName ) {
                $context = 'family_index';
                if ( 'index' == Warecorp::$actionName ) $isHome = ' class="active"';
                elseif ( 'groups' == Warecorp::$actionName ) $isGroups = ' class="active"';
                elseif ( 'people' == Warecorp::$actionName ) $isPeople = ' class="active"';
                elseif ( 'discussion' == Warecorp::$actionName ) $isDiscussions = ' class="active"';
                elseif ( 'events' == Warecorp::$actionName ) $isEvents = ' class="active"';
                elseif ( in_array(Warecorp::$actionName, array('photos', 'videos', 'lists', 'documents')) ) $isFamilyFiles = ' class="active"';
                elseif ( 'settings' == Warecorp::$actionName ) $isTools = ' class="active"';
            }

            $output[] = "<ul>";
            $output[] = "<li{$isPeople} id ='tab0'><a href='{$URL}/users/' class='last'><span>".Warecorp::t('People')."</span></a></li>";
            if ( $showProfile ) $output[] = "<li{$isProfile} id ='tab1'><a href='{$objUser->getUserPath('profile')}'><span>".Warecorp::t('Profile')."</span></a></li>";
            if ( $showTools ) $output[] = "<li{$isTools} id ='tab9'><a href='{$URL}/index/settings/'".(!$showProfile ? " class='last'" : "")."><span>".Warecorp::t('Tools')."</span></a></li>";
            $output[] = "<li{$isFamilyFiles} id ='tab8'><a href='{$URL}/index/photos/'".(!$showProfile && !$showTools ? " class='last'" : "")."><span>".Warecorp::t('Family Files')."</span></a></li>";
            $output[] = "<li{$isEvents} id ='tab7'><a href='{$URL}/index/events/'><span>".Warecorp::t('Events')."</span></a></li>";
            $output[] = "<li{$isDiscussions} id ='tab6'><a href='{$URL}/index/discussion/'><span>".Warecorp::t('Discussions')."</span></a></li>";
            //$output[] = "<li{$isPeople} id ='tab4'><a href='{$URL}/index/people/'>".Warecorp::t('People')."<span></span></a></li>";
            $output[] = "<li{$isGroups} id ='tab2'><a href='{$URL}/index/groups/'><span>".Warecorp::t('Groups')."</span></a></li>";
            $output[] = "<li{$isHome} id ='tab5'><a href='{$URL}/index/'><span>".Warecorp::t('Home')."</span></a></li>";
            $output[] = "</ul>";
        }
        $output = join('', $output);
        return $output;
    }
