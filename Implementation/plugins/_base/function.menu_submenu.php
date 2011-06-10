<?php
    function smarty_function_menu_submenu($params, Smarty &$smarty)
    {
		Warecorp::addTranslation('/plugins/function.menu_submenu.php.xml');
        $params['output'] = ( !empty($params['output']) ) ? $params['output'] : null;

        $objUser = Zend_Registry::get('User');
        $Warecorp = new Warecorp();
        $objRequest = $smarty->get_template_vars('objRequest');

        $objUser = $objCurrentUser = $objGroup = null;
        $context = Warecorp::isContext($smarty, $objUser, $objCurrentUser, $objGroup);

        switch ( $context ) {
            case 'index' :
                if ( 'EIA' == IMPLEMENTATION_TYPE ) {
                    if ( Zend_Registry::isRegistered('globalGroup') ) {
                        require_once(MODULES_DIR.'/GroupsController.php');
                        $objGlobalGroup = Zend_Registry::get('globalGroup');
                    } else throw new Exception(Warecorp::t('Incorrect global group param'));

                    $membersList        = $objGlobalGroup->getGroups()->setTypes(array('simple', 'family'));
                    $isHostPrivileges   = ($membersList->isCoowner($objUser)) || ($objGlobalGroup->getMembers()->isHost($objUser));
                    $memberPrivileges   = Warecorp_Group_AccessManager::canUseManageMembers($objGlobalGroup, $objUser->getId());
                    $showPending        = ($isHostPrivileges || $memberPrivileges) && ($objGlobalGroup->getJoinMode() == 1);
                    /**
                     * Members
                     */
                    if( 'groups' == Warecorp::$actionName ) {
                        if ( $showPending ) {
                            $mode = $objRequest->getParam('mode', 'active');
                            if ( !$showPending ) $mode = 'active';
                            $isActive       = ( 'active' == $mode )     ? ' class="active"' : '';
                            $isPanding      = ( 'pending' == $mode )    ? ' class="active"' : '';
                            $output = array();
                            $output[] = "<ul>";
                            $output[] = "<li{$isActive}><a href='{$objGlobalGroup->getGroupPath('members/mode/approved')}'>".Warecorp::t('Members')."</a></li>";
                            $output[] = "<li{$isPanding}><a href='{$objGlobalGroup->getGroupPath('members/mode/pending')}'>".Warecorp::t('Pending Members')."</a></li>";
                            $output[] = "</ul>";
                            $output = join('', $output);
                        }
                    }
                    /**
                     * Discussions
                     */
                    elseif( 'discussion' == Warecorp::$actionName ) {
                        $access = $objGlobalGroup->getDiscussionAccessManager();
                        $isView             = ( in_array(Warecorp::$actionName, array('discussion', 'discussionsearch', 'topic', 'replytopic', 'createtopic', 'recenttopic')) ) ? ' class="active"' : '';
                        $isSettings         = ( in_array(Warecorp::$actionName, array('discussionsettings')) ) ? ' class="active"' : '';
                        $isHostSettings     = ( in_array(Warecorp::$actionName, array('discussionhostsettings')) ) ? ' class="active"' : '';
                        $output = array();
                        $output[] = "<ul>";
                        if ( $access->canViewGroupDiscussions($objGlobalGroup->getId(), $objUser->getId()) ) {
                            $output[] = "<li{$isView}><a href='{$objGlobalGroup->getGroupPath('discussion')}'>".Warecorp::t('Discussion')."</a></li>";
                        }
                        if ( $access->canConfigureSettings($objGlobalGroup->getId(), $objUser->getId()) ) {
                            $output[] = "<li{$isSettings}><a href='{$objGlobalGroup->getGroupPath('discussionsettings')}'>".Warecorp::t('Settings')."</a></li>";
                        }
                        if ( $access->canConfigureHostSettings($objGlobalGroup->getId(), $objUser->getId()) ) {
                            if ( $objGlobalGroup->getDiscussionGroupType() == 'simple' ) {
                                $output[] = "<li{$isHostSettings}><a href='{$objGlobalGroup->getGroupPath('discussionhostsettings')}'>".Warecorp::t('Host Settings')."</a></li>";
                            } else {
                                $output[] = "<li{$isHostSettings}><a href='{$objGlobalGroup->getGroupPath('discussionhostsettings')}'>".Warecorp::t('Owner Settings')."</a></li>";
                            }
                        }
                        $output[] = "</ul>";
                        $output = join('', $output);
                    }
                    /**
                     * Events
                     */
                    elseif( 'events' == Warecorp::$actionName ) {
                        $mode = $objRequest->getParam('mode', 'active');
                        $isList         = ( in_array(Warecorp::$actionName, array('events', 'calendar.list.view', 'calendar.event.view', 'calendar.event.create', 'calendar.event.edit', 'calendar.event.copy.do', 'calendar.action.confirm', 'calendar.event.apply.request', 'calendar', 'calendarview', 'calendarviewevent', 'calendaradd', 'calendaredit', 'calendarical', 'calendarconfirm', 'calendar.hierarchy.view', 'calendar.member.view')) && 'active' == $mode ) ? ' class="active"' : '';
                        $isMap          = ( in_array(Warecorp::$actionName, array('calendar.map.view')) && 'active' == $mode ) ? ' class="active"' : '';
                        $isMonth        = ( in_array(Warecorp::$actionName, array('calendar.month.view')) ) ? ' class="active"' : '';
                        $isExpired      = ( in_array(Warecorp::$actionName, array('calendar.list.view')) && 'expired' == $mode ) ? ' class="active"' : '';
                        $isSearch       = ( in_array(Warecorp::$actionName, array('calendarsearchindex', 'calendarsearch')) ) ? ' class="active"' : '';
                        $output = array();
                        $output[] = "<ul>";
                        $output[] = "<li{$isList}><a href='{$objGlobalGroup->getGroupPath('calendar.list.view')}'>".Warecorp::t('List View')."</a></li>";
                        if ( Warecorp_ICal_Calendar_Cfg::isMapViewEnabled() ) {
                            $output[] = "<li{$isMap}><a href='{$objGlobalGroup->getGroupPath('calendar.map.view')}'>".Warecorp::t('Map View')."</a></li>";
                        }                        
                        $output[] = "<li{$isMonth}><a href='{$objGlobalGroup->getGroupPath('calendar.month.view')}'>".Warecorp::t('Calendar View')."</a></li>";
                        if ( Warecorp_ICal_AccessManager_Factory::create()->isHostPrivileges($objGlobalGroup, $objUser) ) {
                            $output[] = "<li{$isExpired}><a href='{$objGlobalGroup->getGroupPath('calendar.list.view/mode/expired')}'>".Warecorp::t('Expired Events')."</a></li>";
                        }
                        $output[] = "</ul>";
                        $output = join('', $output);
                    }
                    /**
                     * Lists
                     */
                    elseif( 'lists' == Warecorp::$actionName ) {
                        $type = $objRequest->getParam('type', 0);
                        $listTypes = Warecorp_List_Item::getListTypesListAssoc();
                        $listTypes = array('0'=> 'All') + $listTypes;

                        $output = array();
                        if ( sizeof($listTypes) != 0 ) {
                            $output[] = "<ul>";
                            foreach ( $listTypes as $_index => $_type ) {
                                $output[] = "<li".( $type == $_index ? ' class="active"' : '' )."><a href='".$objGlobalGroup->getGroupPath('lists/type/'.$_index)."'>{$_type}</a></li>";
                            }
                            $output[] = "</ul>";
                        }
                        $output = join('', $output);
                    }
                    break;
                }
                break;
            case 'search' :
                $URL        = BASE_URL.'/'.LOCALE;
                $output = array();
                $isActive = '';
                $output[] = '<ul>';
                if (Warecorp::$actionName == 'search') {$isActive = "class='active'";} else {$isActive = '';};
                $output[] = '<li '.$isActive.'><a href="'.$URL.'/search/search/preset/new/" class="last">'.Warecorp::t("All results").'</a></li>';

                if (Warecorp::$actionName == 'groups') {$isActive = "class='active'";} else {$isActive = '';};
                $output[] = '<li '.$isActive.'><a href="'.$URL.'/search/groups/preset/new/">'.Warecorp::t("Groups").'</a></li>';

                if (Warecorp::$actionName == 'members') {$isActive = "class='active'";} else {$isActive = '';};
                $output[] = '<li '.$isActive.'><a href="'.$URL.'/search/members/preset/new/">'.Warecorp::t("Members").'</a></li>';

                if (Warecorp::$actionName == 'photos') {$isActive = "class='active'";} else {$isActive = '';};
                $output[] = '<li '.$isActive.'><a href="'.$URL.'/search/photos/preset/new/">'.Warecorp::t("Photos").'</a></li>';

                if (Warecorp::$actionName == 'videos') {$isActive = "class='active'";} else {$isActive = '';};
                $output[] = '<li '.$isActive.'><a href="'.$URL.'/search/videos/preset/new/">'.Warecorp::t("Videos").'</a></li>';

                if (Warecorp::$actionName == 'discussions') {$isActive = "class='active'";} else {$isActive = '';};
                $output[] = '<li '.$isActive.'><a href="'.$URL.'/search/discussions/preset/new/">'.Warecorp::t("Discussions").'</a></li>';

                if (Warecorp::$actionName == 'events') {$isActive = "class='active'";} else {$isActive = '';};
                $output[] = '<li '.$isActive.'><a href="'.$URL.'/search/events/preset/new/">'.Warecorp::t("Events").'</a></li>';

                if (Warecorp::$actionName == 'lists') {$isActive = "class='active'";} else {$isActive = '';};
                $output[] = '<li '.$isActive.'><a href="'.$URL.'/search/lists/preset/new/">'.Warecorp::t("Lists").'</a></li>';

                if (Warecorp::$actionName == 'documents') {$isActive = "class='active'";} else {$isActive = '';};
                $output[] = '<li '.$isActive.'><a href="'.$URL.'/search/documents/preset/new/">'.Warecorp::t("Documents").'</a></li>';
                $output[] = '</ul>';
                $output = join('', $output);
                break;
            /**
             * +-----------------------------------------------------------------
             * |
             * | User Profile
             * |
             * +-----------------------------------------------------------------
             */
            case 'users_index' :
                /**
                 * Members Page
                 */
				if ( $objUser && null != $objUser->getId() && !in_array($objRequest->getParam('view'), array('allcities', 'allstates', 'allcountries')) ) {
					$mode = $objRequest->getParam('view', 'state');
					if ( !in_array($mode, array('city', 'state', 'country', 'world')) ) $mode = 'state';
					$output = array();
					$output[] = "<ul>";
					$output[] = "<li" .(($mode == 'city') ? " class='active'" : '')."><a href='".BASE_URL.'/'.LOCALE.'/'.Warecorp::$controllerName.'/'.Warecorp::$actionName.'/view/city/'."'>".$smarty->_tpl_vars['city']->name."</a></li>";
					$output[] = "<li" .(($mode == 'state') ? " class='active'" : '')."><a href='".BASE_URL.'/'.LOCALE.'/'.Warecorp::$controllerName.'/'.Warecorp::$actionName.'/view/state/'."'>".$smarty->_tpl_vars['state']->name."</a></li>";
					$output[] = "<li" .(($mode == 'country') ? " class='active'" : '')."><a href='".BASE_URL.'/'.LOCALE.'/'.Warecorp::$controllerName.'/'.Warecorp::$actionName.'/view/country/'."'>".$smarty->_tpl_vars['country']->name."</a></li>";
					$output[] = "<li" .(($mode == 'world') ? " class='active'" : '')."><a href='".BASE_URL.'/'.LOCALE.'/'.Warecorp::$controllerName.'/'.Warecorp::$actionName.'/view/world/'."'>".Warecorp::t('World')."</a></li>";
					$output[] = "</ul>";
					$output = join('', $output);
				}
				break;
            /**
             * Groups Index page subnavigation
             */
            case 'group_index' :
                /**
                 * Groups Page
                 */
				if ( $objUser && null != $objUser->getId() &&
						!in_array($objRequest->getParam('view'), array('allcities', 'allstates', 'allcountries')) &&
						!Warecorp::$actionName == 'familylanding') {
					$mode = $objRequest->getParam('view', 'country');
					if ( !in_array($mode, array('city', 'state', 'country', 'world')) ) $mode = 'country';
					$output = array();
					$output[] = "<ul>";
					$output[] = "<li" .(($mode == 'city') ? " class='active'" : '')."><a href='".BASE_URL.'/'.LOCALE.'/'.Warecorp::$controllerName.'/'.Warecorp::$actionName.'/view/city/'."'>".$smarty->_tpl_vars['city']->name."</a></li>";
					$output[] = "<li" .(($mode == 'state') ? " class='active'" : '')."><a href='".BASE_URL.'/'.LOCALE.'/'.Warecorp::$controllerName.'/'.Warecorp::$actionName.'/view/state/'."'>".$smarty->_tpl_vars['state']->name."</a></li>";
					$output[] = "<li" .(($mode == 'country') ? " class='active'" : '')."><a href='".BASE_URL.'/'.LOCALE.'/'.Warecorp::$controllerName.'/'.Warecorp::$actionName.'/view/country/'."'>".$smarty->_tpl_vars['country']->name."</a></li>";
					$output[] = "<li" .(($mode == 'world') ? " class='active'" : '')."><a href='".BASE_URL.'/'.LOCALE.'/'.Warecorp::$controllerName.'/'.Warecorp::$actionName.'/view/world/'."'>".Warecorp::t('World')."</a></li>";
					$output[] = "</ul>";
					$output = join('', $output);
				}
                break;

            /**
             * +-----------------------------------------------------------------
             * |
             * | User Account
             * |
             * +-----------------------------------------------------------------
             */
            case 'user_account' :
                break;
            /**
             * +-----------------------------------------------------------------
             * |
             * | User Profile
             * |
             * +-----------------------------------------------------------------
             */
            case 'user_profile' :
                /**
                 * Template Editor
                 */
                if( in_array(Warecorp::$actionName, array('compose', 'theme')) ) {
                    $output = array();
                    $isContent  = ( in_array(Warecorp::$actionName, array('compose')) ) ? ' class="active"' : '';
                    $isTheme    = ( in_array(Warecorp::$actionName, array('theme')) ) ? ' class="active"' : '';

                    $output[] = "<ul>";
                    $output[] = "<li{$isContent}><a href='{$objCurrentUser->getUserPath('compose')}'>".Warecorp::t('Layout & Content')."</a></li>";
                    $output[] = "<li{$isTheme}><a href='{$objCurrentUser->getUserPath('theme')}'>".Warecorp::t('Theme')."</a></li>";
                    $output[] = "</ul>";
                    $output = join('', $output);
                }
                /**
                 * Discussions
                 */
                elseif ( Warecorp::is('Discussions', 'User') ) {
                    $mode = $objRequest->getParam('mode', 'groups');
                    $isGroups       = ( 'groups' == $mode )     ? ' class="active"' : '';
                    $isFamilies     = ( 'families' == $mode )   ? ' class="active"' : '';
                    $isCommented    = ( 'commented' == $mode )  ? ' class="active"' : '';
                    $output = array();
                    $output[] = "<ul>";
                    $output[] = "<li{$isGroups}><a href='{$objCurrentUser->getUserPath('discussion/mode/groups')}'>".Warecorp::t('My Groups')."</a></li>";
                    $output[] = "<li{$isFamilies}><a href='{$objCurrentUser->getUserPath('discussion/mode/families')}'>".Warecorp::t('My Group Families')."</a></li>";
                    $output[] = "<li{$isCommented}><a href='{$objCurrentUser->getUserPath('discussion/mode/commented')}'>".Warecorp::t('My Comments')."</a></li>";
                    $output[] = "</ul>";
                    $output = join('', $output);
                }
                /**
                 * Events
                 */
                elseif( Warecorp::is('Events', 'User') ) {
                    $mode = $objRequest->getParam('mode', 'active');
                    $isList         = ( in_array(Warecorp::$actionName, array('calendar.list.view', 'calendar.event.view', 'calendar.event.create', 'calendar.event.edit', 'calendar.event.copy.do', 'calendar.action.confirm', 'calendar.event.apply.request', 'calendar', 'calendarview', 'calendarviewevent', 'calendaradd', 'calendaredit', 'calendarical', 'calendarconfirm', 'calendar.event.create.step1', 'calendar.event.create.step2', 'calendar.event.create.step3')) && 'active' == $mode ) ? ' class="active"' : '';
                    $isMap          = ( in_array(Warecorp::$actionName, array('calendar.map.view')) && 'active' == $mode ) ? ' class="active"' : '';
                    $isMonth        = ( in_array(Warecorp::$actionName, array('calendar.month.view')) ) ? ' class="active"' : '';
                    $isExpired      = ( in_array(Warecorp::$actionName, array('calendar.list.view')) && 'expired' == $mode ) ? ' class="active"' : '';
/* No search & browse for 3.0
					$isSearch       = ( in_array(Warecorp::$actionName, array('calendarsearchindex', 'calendarsearch')) ) ? ' class="active"' : ''; */
                    $output = array();
                    $output[] = "<ul>";
                    $output[] = "<li{$isList}><a href='{$objCurrentUser->getUserPath('calendar.list.view')}'>".Warecorp::t('List View')."</a></li>";
                    if ( Warecorp_ICal_Calendar_Cfg::isMapViewEnabled() ) {
                        $output[] = "<li{$isMap}><a href='{$objCurrentUser->getUserPath('calendar.map.view')}'>".Warecorp::t('Map View')."</a></li>";
                    }
                    $output[] = "<li{$isMonth}><a href='{$objCurrentUser->getUserPath('calendar.month.view')}'>".Warecorp::t('Calendar View')."</a></li>";
                    $output[] = "<li{$isExpired}><a href='{$objCurrentUser->getUserPath('calendar.list.view/mode/expired')}'>".Warecorp::t('Expired Events')."</a></li>";
/* No search & browse for 3.0
                    $output[] = "<li{$isSearch}><a href='{$objCurrentUser->getUserPath('calendarsearchindex')}'>Search and Browse Events</a></li>";*/
                    $output[] = "</ul>";
                    $output = join('', $output);
                }
                /**
                 * Friends
                 */
                elseif( Warecorp::is('Friends', 'User') ) {
                    $mode = $objRequest->getParam('requests', '');
                    $isFriends      = ( in_array(Warecorp::$actionName, array('friends')) && $mode == '' ) ? ' class="active"' : '';
                    $isFind         = ( in_array(Warecorp::$actionName, array('findfriends')) ) ? ' class="active"' : '';
                    $isRequests     = ( in_array(Warecorp::$actionName, array('friends')) && $mode != '' ) ? ' class="active"' : '';
                    $output = array();
                    $output[] = "<ul>";
                    $output[] = "<li{$isFriends}><a href='{$objCurrentUser->getUserPath('friends')}'>".Warecorp::t('Friends')."</a></li>";
                    $output[] = "<li{$isFind}><a href='{$objCurrentUser->getUserPath('findfriends/import/1')}'>".Warecorp::t('Find Friends')."</a></li>";
                    $output[] = "<li{$isRequests}><a href='{$objCurrentUser->getUserPath('friends/requests/received')}'>".Warecorp::t('Friends Requests')."</a></li>";
                    $output[] = "</ul>";
                    $output = join('', $output);
                }
                /**
                 * Photos
                 */
                elseif( Warecorp::is('Photos', 'User') ) {
/* nothing for user photos for 3.0
                    $isPhotos       = ( in_array(Warecorp::$actionName, array('photos','gallery','gallerycreate','galleryedit','galleryview')) ) ? ' class="active"' : '';
                    $isSearch       = ( in_array(Warecorp::$actionName, array('photossearch')) ) ? ' class="active"' : '';
                    $output = array();
                    $output[] = "<ul>";
                    $output[] = "<li{$isPhotos}><a href='{$objCurrentUser->getUserPath('photos')}'>Photo Galleries</a></li>";

                    $output[] = "<li{$isSearch}><a href='{$objCurrentUser->getUserPath('photossearch')}'>Search and Browse Photos</a></li>";
                    $output[] = "</ul>";
                    $output = join('', $output); */
                }
                /**
                 * Videos
                 */
                elseif( Warecorp::is('Videos', 'User') ) {
/* nothing for user videos for 3.0
                    $isVideo        = ( in_array(Warecorp::$actionName, array('videos', 'videogallery', 'videogallerycreate', 'videogalleryedit', 'videogalleryview')) ) ? ' class="active"' : '';
                    $isSearch       = ( in_array(Warecorp::$actionName, array('videossearch')) ) ? ' class="active"' : '';
                    $output = array();
                    $output[] = "<ul>";
                    $output[] = "<li{$isVideo}><a href='{$objCurrentUser->getUserPath('videos')}'>Videos</a></li>";

                    $output[] = "<li{$isSearch}><a href='{$objCurrentUser->getUserPath('videossearch')}'>Search and Browse Videos</a></li>";
                    $output[] = "</ul>";
                    $output = join('', $output);*/
                }
                /**
                 * Documents
                 */
                elseif( Warecorp::is('Documents', 'User') ) {
                    $output = '';
                }
                /**
                 * Lists
                 */
                elseif( Warecorp::is('Lists', 'User') ) {
                    $type = $objRequest->getParam('type', 0);
                    $listTypes = Warecorp_List_Item::getListTypesListAssoc();
                    $listTypes = array('0'=> 'All') + $listTypes;

                    $output = array();
                    if ( sizeof($listTypes) != 0 ) {
                        $output[] = "<ul>";
                        foreach ( $listTypes as $_index => $_type ) {
                            $output[] = "<li".( $type == $_index ? ' class="active"' : '' )."><a href='".$objCurrentUser->getUserPath('lists/type/'.$_index)."'>{$_type}</a></li>";
                        }
                        $output[] = "</ul>";
                    }
                    $output = join('', $output);
                }
                /**
                 * Messages
                 */
                elseif( Warecorp::is('Messages', 'User') ) {
                    $isMessages     = ( in_array(Warecorp::$actionName, array('messagelist','messageview','messagecompose','messagedelete')) ) ? ' class="active"' : '';
                    $isAddressbook  = ( in_array(Warecorp::$actionName, array('addressbook','addressbookgroup','addressbookaddmaillist','addressbookaddcontact', 'addressbookmaillist')) ) ? ' class="active"' : '';
                    $isImport       = ( in_array(Warecorp::$actionName, array('importcontacts')) ) ? ' class="active"' : '';
                    $output = array();
                    $output[] = "<ul>";
                    $output[] = "<li{$isMessages}><a href='{$objCurrentUser->getUserPath('messagelist')}'>".Warecorp::t('Inbox')."</a></li>";
                    $output[] = "<li{$isAddressbook}><a href='{$objCurrentUser->getUserPath('addressbook')}'>".Warecorp::t('Addressbook')."</a></li>";
                    $output[] = "<li{$isImport}><a href='{$objCurrentUser->getUserPath('importcontacts/import/1')}'>".Warecorp::t('Import Contacts')."</a></li>";
                    $output[] = "</ul>";
                    $output = join('', $output);
                }
                break;
            /**
             * +-----------------------------------------------------------------
             * |
             * | People Profile
             * |
             * +-----------------------------------------------------------------
             */
            case 'people_profile' :
                /**
                 * Events
                 */
                if( Warecorp::is('Events', 'User') ) {
                    $mode = $objRequest->getParam('mode', 'active');
                    $isList         = ( in_array(Warecorp::$actionName, array('calendar.list.view', 'calendar.event.view', 'calendar.event.create', 'calendar.event.edit', 'calendar.event.copy.do', 'calendar.action.confirm', 'calendar.event.apply.request', 'calendar', 'calendarview', 'calendarviewevent', 'calendaradd', 'calendaredit', 'calendarical', 'calendarconfirm')) && 'active' == $mode ) ? ' class="active"' : '';
                    $isMap          = ( in_array(Warecorp::$actionName, array('calendar.map.view')) && 'active' == $mode ) ? ' class="active"' : '';
                    $isMonth        = ( in_array(Warecorp::$actionName, array('calendar.month.view')) ) ? ' class="active"' : '';
                    $isExpired      = ( in_array(Warecorp::$actionName, array('calendar.list.view')) && 'expired' == $mode ) ? ' class="active"' : '';
                    $isSearch       = ( in_array(Warecorp::$actionName, array('calendarsearchindex', 'calendarsearch')) ) ? ' class="active"' : '';
                    $output = array();
                    $output[] = "<ul>";
                    $output[] = "<li{$isList}><a href='{$objCurrentUser->getUserPath('calendar.list.view')}'>".Warecorp::t('List View')."</a></li>";
                    if ( Warecorp_ICal_Calendar_Cfg::isMapViewEnabled() ) {
                        $output[] = "<li{$isMap}><a href='{$objCurrentUser->getUserPath('calendar.map.view')}'>".Warecorp::t('Map View')."</a></li>";
                    }
                    $output[] = "<li{$isMonth}><a href='{$objCurrentUser->getUserPath('calendar.month.view')}'>".Warecorp::t('Calendar View')."</a></li>";
                    //$output[] = "<li{$isExpired}><a href='{$objCurrentUser->getUserPath('calendar.list.view/mode/expired')}'>".Warecorp::t('Expired Events')."</a></li>";
                    //$output[] = "<li{$isSearch}><a href='{$objCurrentUser->getUserPath('calendarsearchindex')}'>".Warecorp::t('Search and Browse Events')."</a></li>";
                    $output[] = "</ul>";
                    $output = join('', $output);
                }
                /**
                 * Lists
                 */
                elseif( Warecorp::is('Lists', 'User') ) {
                    $type = $objRequest->getParam('type', 0);
                    $listTypes = Warecorp_List_Item::getListTypesListAssoc();
                    $listTypes = array('0'=> 'All') + $listTypes;

                    $output = array();
                    if ( sizeof($listTypes) != 0 ) {
                        $output[] = "<ul>";
                        foreach ( $listTypes as $_index => $_type ) {
                            $output[] = "<li".( $type == $_index ? ' class="active"' : '' )."><a href='".$objCurrentUser->getUserPath('lists/type/'.$_index)."'>{$_type}</a></li>";
                        }
                        $output[] = "</ul>";
                    }
                    $output = join('', $output);
                }
                break;
            /**
             * +-----------------------------------------------------------------
             * |
             * | Group
             * |
             * +-----------------------------------------------------------------
             */
            case 'group' :

                if ( $objGroup->getGroupType() == "family" ) {
                    $membersList        = $objGroup->getGroups()->setTypes(array('simple', 'family'));
                    $isHostPrivileges   = ($membersList->isCoowner($objUser)) || ($objGroup->getMembers()->isHost($objUser));
                    $memberPrivileges   = Warecorp_Group_AccessManager::canUseManageMembers($objGroup, $objUser->getId());
                    $showPending        = ($isHostPrivileges || $memberPrivileges) && ($objGroup->getJoinMode() == 1);
                } else {
                    $membersList = $objGroup->getMembers();
                    $isHostPrivileges = ($membersList->isCohost($objUser)) || ($membersList->isHost($objUser) ) ;
                    $privileges = $objGroup->getPrivileges();
                    $memberPrivileges = false;
                    if ( $objGroup->getMembers()->isMemberExists( $objUser->getId() ) ) {
                        if ( 0 == $privileges->getManageMembers() ) $memberPrivileges = true;
                        elseif ( 2 == $privileges->getManageMembers() ) $memberPrivileges = $privileges->getUsersListByTool( 'gpManageMembers' )->isExist( $objUser );
                    }
                    $showPending = ($isHostPrivileges || $memberPrivileges) && ($objGroup->getJoinMode() == 1);
                }

                /**
                 * Template Editor
                 */
                if( in_array(Warecorp::$actionName, array('edit', 'theme', 'publish')) ) {
                    $output = array();
                    if ( $isHostPrivileges ) {
                        $isContent  = ( in_array(Warecorp::$actionName, array('edit')) ) ? ' class="active"' : '';
                        $isTheme    = ( in_array(Warecorp::$actionName, array('theme')) ) ? ' class="active"' : '';
                        $isPublish  = ( in_array(Warecorp::$actionName, array('publish')) ) ? ' class="active"' : '';

                        $output[] = "<ul>";
                        $output[] = "<li{$isContent}><a href='{$objGroup->getGroupPath('edit')}'>".Warecorp::t('Layout & Content')."</a></li>";
                        $output[] = "<li{$isTheme}><a href='{$objGroup->getGroupPath('theme')}'>".Warecorp::t('Theme')."</a></li>";
                        $output[] = "<li{$isPublish}><a href='{$objGroup->getGroupPath('publish')}'>".Warecorp::t('Publish')."</a></li>";
                        $output[] = "</ul>";
                    }
                    $output = join('', $output);
                }
                /**
                 * Members
                 */
                elseif( Warecorp::is('Members', 'Group') ) {
                    if ( $showPending ) {
                        $mode = $objRequest->getParam('mode', 'active');
                        if ( !$showPending ) $mode = 'active';
                        if ( 'pending' == $mode ) {
                            $isActive  = '';
                            $isPanding = ' class="active"';
                        }
                        else {
                            $isActive  = ' class="active"';
                            $isPanding = '';
                        }
                        $output = array();
                        $output[] = "<ul>";
                        $output[] = "<li{$isActive}><a href='{$objGroup->getGroupPath('members/mode/approved')}'>".Warecorp::t('Members')."</a></li>";
                        $output[] = "<li{$isPanding}><a href='{$objGroup->getGroupPath('members/mode/pending')}'>".Warecorp::t('Pending Members')."</a></li>";
                        $output[] = "</ul>";
                        $output = join('', $output);
                    }
                }
                /**
                 * Discussions
                 */
                elseif( Warecorp::is('Discussions', 'Group') ) {
                    $access = $objGroup->getDiscussionAccessManager();
                    $isView             = ( in_array(Warecorp::$actionName, array('discussion', 'discussionsearch', 'topic', 'replytopic', 'createtopic', 'recenttopic')) ) ? ' class="active"' : '';
                    $isSettings         = ( in_array(Warecorp::$actionName, array('discussionsettings')) ) ? ' class="active"' : '';
                    $isHostSettings     = ( in_array(Warecorp::$actionName, array('discussionhostsettings')) ) ? ' class="active"' : '';
                    $output = array();
                    $output[] = "<ul>";
                    if ( $access->canViewGroupDiscussions($objGroup->getId(), $objUser->getId()) ) {
                        $output[] = "<li{$isView}><a href='{$objGroup->getGroupPath('discussion')}'>".Warecorp::t('Discussion')."</a></li>";
                    }
                    if ( $access->canConfigureSettings($objGroup->getId(), $objUser->getId()) ) {
                        $output[] = "<li{$isSettings}><a href='{$objGroup->getGroupPath('discussionsettings')}'>".Warecorp::t('Settings')."</a></li>";
                    }
                    if ( $access->canConfigureHostSettings($objGroup->getId(), $objUser->getId()) ) {
                        if ( $objGroup->getDiscussionGroupType() == 'simple' ) {
                            $output[] = "<li{$isHostSettings}><a href='{$objGroup->getGroupPath('discussionhostsettings')}'>".Warecorp::t('Host Settings')."</a></li>";
                        } else {
                            $output[] = "<li{$isHostSettings}><a href='{$objGroup->getGroupPath('discussionhostsettings')}'>".Warecorp::t('Owner Settings')."</a></li>";
                        }
                    }
                    $output[] = "</ul>";
                    $output = join('', $output);
                }
                /**
                 * Events
                 */
                elseif( Warecorp::is('Events', 'Group') ) {
                    $mode = $objRequest->getParam('mode', 'active');
                    $isList         = ( in_array(Warecorp::$actionName, array('calendar.list.view', 'calendar.event.view', 'calendar.event.create', 'calendar.event.edit', 'calendar.event.copy.do', 'calendar.action.confirm', 'calendar.event.apply.request', 'calendar', 'calendarview', 'calendarviewevent', 'calendaradd', 'calendaredit', 'calendarical', 'calendarconfirm', 'calendar.hierarchy.view', 'calendar.member.view')) && 'active' == $mode ) ? ' class="active"' : '';
                    $isMap          = ( in_array(Warecorp::$actionName, array('calendar.map.view')) && 'active' == $mode ) ? ' class="active"' : '';
                    $isMonth        = ( in_array(Warecorp::$actionName, array('calendar.month.view')) ) ? ' class="active"' : '';
                    $isExpired      = ( in_array(Warecorp::$actionName, array('calendar.list.view')) && 'expired' == $mode ) ? ' class="active"' : '';
                    $isSearch       = ( in_array(Warecorp::$actionName, array('calendarsearchindex', 'calendarsearch')) ) ? ' class="active"' : '';
                    $output = array();
                    $output[] = "<ul>";
                    $output[] = "<li{$isList}><a href='{$objGroup->getGroupPath('calendar.list.view')}'>".Warecorp::t('List View')."</a></li>";
                    if ( Warecorp_ICal_Calendar_Cfg::isMapViewEnabled() ) {
                        $output[] = "<li{$isMap}><a href='{$objGroup->getGroupPath('calendar.map.view')}'>".Warecorp::t('Map View')."</a></li>";
                    }
                    $output[] = "<li{$isMonth}><a href='{$objGroup->getGroupPath('calendar.month.view')}'>".Warecorp::t('Calendar View')."</a></li>";
                    if ( Warecorp_ICal_AccessManager_Factory::create()->isHostPrivileges($objGroup, $objUser) ) {
                        $output[] = "<li{$isExpired}><a href='{$objGroup->getGroupPath('calendar.list.view/mode/expired')}'>".Warecorp::t('Expired Events')."</a></li>";
                    }
                    $output[] = "</ul>";
                    $output = join('', $output);
                }
                /**
                 * Lists
                 */
                elseif( Warecorp::is('Lists', 'User') ) {
                    $type = $objRequest->getParam('type', 0);
                    $listTypes = Warecorp_List_Item::getListTypesListAssoc();
                    $listTypes = array('0'=> 'All') + $listTypes;

                    $output = array();
                    if ( sizeof($listTypes) != 0 ) {
                        $output[] = "<ul>";
                        foreach ( $listTypes as $_index => $_type ) {
                            $output[] = "<li".( $type == $_index ? ' class="active"' : '' )."><a href='".$objGroup->getGroupPath('lists/type/'.$_index)."'>{$_type}</a></li>";
                        }
                        $output[] = "</ul>";
                    }
                    $output = join('', $output);
                }
                /**
                 * Tools Promotions Invitations
                 */
                elseif( Warecorp::is('Tools', 'Group') ) {
                    if ( in_array(Warecorp::$actionName, array('invite1', 'invitesearch', 'invitelist')) ) {
                        $mode = $objRequest->getParam('folder', '');
                        $isInviteGroup  = ( Warecorp::$actionName == 'invite1' || Warecorp::$actionName == 'invitesearch' ) ? ' class="active"' : '';
                        $isDraft        = ( Warecorp::$actionName == 'invitelist' && $mode == 'draft' ) ? ' class="active"' : '';
                        $isSend         = ( Warecorp::$actionName == 'invitelist' && $mode == 'sent' ) ? ' class="active"' : '';

                        $output = array();
                        $output[] = "<ul>";
                        $output[] = "<li{$isInviteGroup}><a href='{$objGroup->getGroupPath('invite1')}'>".Warecorp::t('Invite Groups')."</a></li>";
                        $output[] = "<li{$isDraft}><a href='{$objGroup->getGroupPath('invitelist/folder/draft')}'>".Warecorp::t('Draft Invitations')."</a></li>";
                        $output[] = "<li{$isSend}><a href='{$objGroup->getGroupPath('invitelist/folder/sent')}'>".Warecorp::t('Sent Invitations')."</a></li>";
                        $output[] = "</ul>";
                        $output = join('', $output);
                    }
                }
                break;
        }

        if ( null !== $params['output'] ) $smarty->assign($params['output'], trim($output));
        else return $output;
    }