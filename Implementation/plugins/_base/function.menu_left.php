<?php
    function smarty_function_menu_left($params, &$smarty)
    {
		Warecorp::addTranslation('/plugins/function.menu_left.php.xml');
        $objUser    = Zend_Registry::get('User');
        $Warecorp   = new Warecorp();
        $objRequest = $smarty->get_template_vars('objRequest');
        $URL        = BASE_URL.'/'.LOCALE;

        /**
         * @var string $activeMainCssClass - css class for active item of left main menu
         * first space is required
         */
        $activeMainCssClass   = ' prLeftNavActive';
        /**
         * @var string $activeSubCssClass - css class for active item of left sub menu
         * first space is required
         */
        $activeSubCssClass    = ' class="active"';

        $objUser = $objCurrentUser = $objGroup = null;
        $context = Warecorp::isContext($smarty, $objUser, $objCurrentUser, $objGroup);

        switch ( $context ) {
            case 'index' :
                $output = array();
                /* index actions for EIA implementation */
                $isGlobalGroup = false;
                if ( 'ESA' != IMPLEMENTATION_TYPE ) {
                    if ( Zend_Registry::isRegistered('globalGroup') ) {
                        require_once(MODULES_DIR.'/GroupsController.php');
                        $objGlobalGroup = Zend_Registry::get('globalGroup');
                    } else throw new Exception(Warecorp::t('Incorrect global group param'));

                    if ( in_array(Warecorp::$actionName, array('photos', 'videos', 'lists', 'documents')) ) {
                        $output[] = "<ul id='DOMReadyIE'>";
                        $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Photos', 'Group', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$URL}/index/photos/'><span>".Warecorp::t('Photos')."</span></a></li>";
                        $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Videos', 'Group', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$URL}/index/videos/'><span>".Warecorp::t('Videos')."</span></a></li>";
                        $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Documents', 'Group', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$URL}/index/documents/'><span>".Warecorp::t('Documents')."</span></a></li>";
                        $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Lists', 'Group', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$URL}/index/lists/'><span>".Warecorp::t('Lists')."</span></a></li>";
                        $output[] = "</ul>";
                    }
                    /**
                     * Tools
                     */                    
                    if ( in_array(Warecorp::$actionName, array('settings')) ) {
                        $output[] = "<ul id='DOMReadyIE'>";
                        $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Settings', 'Group', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objGlobalGroup->getGroupPath('settings')}'>".Warecorp::t('Settings')."</a></li>";
                        $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Hierarchy', 'Group', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objGlobalGroup->getGroupPath('hierarchy')}'>".Warecorp::t('Hierarchy')."</a></li>";
                        $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Brandgallery', 'Group', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objGlobalGroup->getGroupPath('brandgallery')}'>".Warecorp::t('Brand Gallery')."</a></li>";
                        $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Invitations', 'Group', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objGlobalGroup->getGroupPath('invite1')}'>".Warecorp::t('Invitations')."</a></li>";
                        $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Avatars', 'Group', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objGlobalGroup->getGroupPath('avatars')}'>".Warecorp::t('Profile Photos')."</a></li>";
                        $output[] = "</ul>";
                    }
                }
                $output = join('', $output);
                break;
            case 'users_index' :
                $output = '';
                break;
            case 'group_index' :
                $output = '';
                break;
            /**
             * +-----------------------------------------------------------------
             * |
             * | User Account
             * |
             * +-----------------------------------------------------------------
             */
            case 'user_account' :
                $output = array();
                $output[] = '<ul id="DOMReadyIE">';
                $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Settings', 'User', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objCurrentUser->getUserPath('settings')}'><span>".Warecorp::t('My Account')."</span></a></li>";
                $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Avatars', 'User', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objCurrentUser->getUserPath('avatars')}'><span>".Warecorp::t('Profile Photos')."</span></a></li>";
                $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Privacy', 'User', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objCurrentUser->getUserPath('privacy')}'><span>".Warecorp::t('Privacy')."</span></a></li>";
                if ( FACEBOOK_USED ) {
                    $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Networks', 'User', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objCurrentUser->getUserPath('networks')}'><span>".Warecorp::t('Facebook')."</span></a></li>";
                }
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
            case 'user_profile' :
                $output = array();
                $output[] = '<ul id="DOMReadyIE">';
                $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Groups', 'User', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objCurrentUser->getUserPath('groups')}'><span>".Warecorp::t('My Groups')."</span></a></li>";
                $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Discussions', 'User', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objCurrentUser->getUserPath('discussion')}'><span>".Warecorp::t('My Discussions')."</span></a></li>";
                $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Events', 'User', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objCurrentUser->getUserPath('calendar.list.view')}'><span>".Warecorp::t('My Events')."</span></a></li>";
                $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Friends', 'User', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objCurrentUser->getUserPath('friends')}'><span>".Warecorp::t('My Friends')."</span></a></li>";
                $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Stuff', 'User', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objCurrentUser->getUserPath('photos')}'><span>".Warecorp::t('My Stuff')."</span></a>";
                if ( $Warecorp->is('Stuff', 'User') ) {
                    $output[] = "<ul class='prLeftSubNav'>";
                    $output[] = "<li{$Warecorp->isActiveClass('Photos', 'User', $activeSubCssClass)}><a href='{$objCurrentUser->getUserPath('photos')}'>".Warecorp::t('Photos')."</a></li>";
                    $output[] = "<li{$Warecorp->isActiveClass('Videos', 'User', $activeSubCssClass)}><a href='{$objCurrentUser->getUserPath('videos')}'>".Warecorp::t('Videos')."</a></li>";
                    $output[] = "<li{$Warecorp->isActiveClass('Documents', 'User', $activeSubCssClass)}><a href='{$objCurrentUser->getUserPath('documents')}'>".Warecorp::t('Documents')."</a></li>";
                    $output[] = "<li{$Warecorp->isActiveClass('Lists', 'User', $activeSubCssClass)}><a href='{$objCurrentUser->getUserPath('lists')}'>".Warecorp::t('Lists')."</a></li>";
                    $output[] = "</ul>";
                }
                $output[] = "</li>";
                $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Messages', 'User', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objCurrentUser->getUserPath('messagelist')}'><span>".Warecorp::t('My Messages')."</span></a>";
                if ( $Warecorp->is('Messages', 'User') ) {
                    $folder = $objRequest->getParam('folder', 'inbox');
                    $isInbox = ( 'inbox' == $folder ) ? $activeSubCssClass : '';
                    $isSent = ( 'sent' == $folder ) ? $activeSubCssClass : '';
                    $isDraft = ( 'draft' == $folder ) ? $activeSubCssClass : '';
                    $isTrash = ( 'trash' == $folder ) ? $activeSubCssClass : '';

                    $messageManager = new Warecorp_Message_List();
                    $folderList = $messageManager->getMessagesFoldersList($objCurrentUser->getId());
                     
                    $output[] = "<ul class='prLeftSubNav'>";
                    $output[] = "<li{$isInbox}><a href='{$objCurrentUser->getUserPath('messagelist')}'>".Warecorp::t('Inbox')." ({$folderList['inbox']['unread']})</a></li>";
                    $output[] = "<li{$isSent}><a href='{$objCurrentUser->getUserPath('messagelist/folder/sent')}'>".Warecorp::t('Sent')."</a></li>";
                    $output[] = "<li{$isDraft}><a href='{$objCurrentUser->getUserPath('messagelist/folder/draft')}'>".Warecorp::t('Draft')." ({$folderList['draft']['all']})</a></li>";
                    $output[] = "<li{$isTrash}><a href='{$objCurrentUser->getUserPath('messagelist/folder/trash')}'>".Warecorp::t('Trash')."</a></li>";
                    $output[] = "</ul>";
                }
                $output[] = '</li></ul>';
                $output = join('', $output);
                break;
            /**
             * +-----------------------------------------------------------------
             * |
             * | People Profile
             * |
             * +-----------------------------------------------------------------
             */
            case 'people_profile' :
                $output = array();
                $output[] = '<ul id="DOMReadyIE">';
                if ( Warecorp_User_AccessManager::canViewFriends($objCurrentUser, $objUser) )
                $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Friends', 'User', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objCurrentUser->getUserPath('friends')}'><span>".Warecorp::t('Friends')."</span></a></li>";
                if ( Warecorp_User_AccessManager::canViewEvents($objCurrentUser, $objUser) )
                $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Events', 'User', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objCurrentUser->getUserPath('calendar.list.view')}'><span>".Warecorp::t('Events')."</span></a></li>";

                $canPhotos      = Warecorp_User_AccessManager::canViewPhotos($objCurrentUser, $objUser);
                $canVideos      = Warecorp_User_AccessManager::canViewVideos($objCurrentUser, $objUser);
                $canDocuments   = Warecorp_User_AccessManager::canViewDocuments($objCurrentUser, $objUser);
                $canLists       = Warecorp_User_AccessManager::canViewLists($objCurrentUser, $objUser);
                if ( $canPhotos || $canVideos || $canDocuments || $canLists ) {
                    $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Stuff', 'User', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objCurrentUser->getUserPath('photos')}'><span>".Warecorp::t('Stuff')."</span></a>";
                    if ( $Warecorp->is('Stuff', 'User') ) {
                        $output[] = "<ul class='prLeftSubNav'>";
                        if ( $canPhotos )       $output[] = "<li{$Warecorp->isActiveClass('Photos', 'User', $activeSubCssClass)}><a href='{$objCurrentUser->getUserPath('photos')}'>".Warecorp::t('Photos')."</a></li>";
                        if ( $canVideos )       $output[] = "<li{$Warecorp->isActiveClass('Videos', 'User', $activeSubCssClass)}><a href='{$objCurrentUser->getUserPath('videos')}'>".Warecorp::t('Videos')."</a></li>";
                        if ( $canDocuments )    $output[] = "<li{$Warecorp->isActiveClass('Documents', 'User', $activeSubCssClass)}><a href='{$objCurrentUser->getUserPath('documents')}'>".Warecorp::t('Documents')."</a></li>";
                        if ( $canLists )        $output[] = "<li{$Warecorp->isActiveClass('Lists', 'User', $activeSubCssClass)}><a href='{$objCurrentUser->getUserPath('lists')}'>".Warecorp::t('Lists')."</a></li>";
                        $output[] = "</ul>";
                    }
                    $output[] = "</li>";
                }
                $output[] = "</ul>";
                $output = join('', $output);
                break;
            /**
             * +-----------------------------------------------------------------
             * |
             * | Group
             * |
             * +-----------------------------------------------------------------
             */
            case 'group' :
                $isGlobalGroup = false;
                if ( 'ESA' != IMPLEMENTATION_TYPE ) {
                    if ( Zend_Registry::isRegistered('globalGroup') ) {
                        require_once(MODULES_DIR.'/GroupsController.php');
                        $objGlobalGroup = Zend_Registry::get('globalGroup');
                    } else throw new Exception('Incorrect global group param');
                    $isGlobalGroup = (boolean) ( $objGlobalGroup->getId() == $objGroup->getId() );
                }

                $output = array();
                if ( 'ESA' == IMPLEMENTATION_TYPE || !$isGlobalGroup ) {
                    $output = array();
                    $output[] = '<ul id="DOMReadyIE">';

                    if (Warecorp_Group_AccessManager::canViewMembers($objGroup, $objUser)) {
                        $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Members', 'Group', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objGroup->getGroupPath('members')}'><span>".Warecorp::t('Members')."</span></a></li>";
                    }
                    if (Warecorp_Group_AccessManager::canViewDiscussions($objGroup, $objUser)) {
                        $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Discussions', 'Group', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objGroup->getGroupPath('discussion')}'><span>".Warecorp::t('Discussions')."</span></a></li>";
                    }
                    if (Warecorp_Group_AccessManager::canViewEvents($objGroup, $objUser)) {
                        $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Events', 'Group', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objGroup->getGroupPath('calendar.list.view')}'><span>".Warecorp::t('Events')."</span></a></li>";
                    }
                    if (Warecorp_Group_AccessManager::canViewPhotos($objGroup, $objUser) ||
                        Warecorp_Group_AccessManager::canViewVideos($objGroup, $objUser) ||
                        Warecorp_Group_AccessManager::canViewDocuments($objGroup, $objUser) ||
                        Warecorp_Group_AccessManager::canViewLists($objGroup, $objUser)) {

                        if ( $objGroup->getGroupType() == 'family' ) {
                            $tr = Warecorp::t('Group Family Stuff');
                        } else {
                            $tr = Warecorp::t('Group Stuff');
                        }
                        $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Stuff', 'Group', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objGroup->getGroupPath('photos')}'><span>{$tr}</span></a>";
                        if ( $Warecorp->is('Stuff', 'Group') ) {
                            $output[] = "<ul class='prLeftSubNav'>";
                            if (Warecorp_Group_AccessManager::canViewPhotos($objGroup, $objUser)) {
                                $output[] = "<li{$Warecorp->isActiveClass('Photos', 'Group', $activeSubCssClass)}><a href='{$objGroup->getGroupPath('photos')}'>".Warecorp::t('Photos')."</a></li>";
                            }
                            if (Warecorp_Group_AccessManager::canViewVideos($objGroup, $objUser)) {
                                $output[] = "<li{$Warecorp->isActiveClass('Videos', 'Group', $activeSubCssClass)}><a href='{$objGroup->getGroupPath('videos')}'>".Warecorp::t('Videos')."</a></li>";
                            }
                            if (Warecorp_Group_AccessManager::canViewDocuments($objGroup, $objUser)) {
                                $output[] = "<li{$Warecorp->isActiveClass('Documents', 'Group', $activeSubCssClass)}><a href='{$objGroup->getGroupPath('documents')}'>".Warecorp::t('Documents')."</a></li>";
                            }
                            if (Warecorp_Group_AccessManager::canViewLists($objGroup, $objUser)) {
                                $output[] = "<li{$Warecorp->isActiveClass('Lists', 'Group', $activeSubCssClass)}><a href='{$objGroup->getGroupPath('lists')}'>".Warecorp::t('Lists')."</a></li>";
                            }
                            $output[] = "</ul>";
                        }
                        $output[] = "</li>";

                    }

                    /**
                     * Tools
                     */
                    //if ($objUser && null !== $objUser && ($objGroup->getMembers()->isHost($objUser->getId()) || $objGroup->getMembers()->isCohost($objUser->getId())) ) {
                    if ($objUser && null !== $objUser && Warecorp_Group_AccessManager::isHostPrivileges($objGroup, $objUser)) {
                        $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Tools', 'Group', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objGroup->getGroupPath('settings')}'><span>".Warecorp::t('Tools')."</span></a>";
                        if ( $Warecorp->is('Tools', 'Group') ) {
                            $output[] = "<ul class='prLeftSubNav'>";
                            $output[] = "<li{$Warecorp->isActiveClass('Settings', 'Group', $activeSubCssClass)}><a href='{$objGroup->getGroupPath('settings')}'>".Warecorp::t('Settings')."</a></li>";
                            if ( $objGroup->getGroupType() == "family" ) {
                                $output[] = "<li{$Warecorp->isActiveClass('Hierarchy', 'Group', $activeSubCssClass)}><a href='{$objGroup->getGroupPath('hierarchy')}'>".Warecorp::t('Hierarchy')."</a></li>";
                                $output[] = "<li{$Warecorp->isActiveClass('Brandgallery', 'Group', $activeSubCssClass)}><a href='{$objGroup->getGroupPath('brandgallery')}'>".Warecorp::t('Brand Gallery')."</a></li>";
                                $output[] = "<li{$Warecorp->isActiveClass('Invitations', 'Group', $activeSubCssClass)}><a href='{$objGroup->getGroupPath('invite1')}'>".Warecorp::t('Invitations')."</a></li>";
                            } else {
                                $output[] = "<li{$Warecorp->isActiveClass('Webbadges', 'Group', $activeSubCssClass)}><a href='{$objGroup->getGroupPath('webbadges')}'>".Warecorp::t('Web Badges')."</a></li>";
                            }
							if ( $objGroup->getGroupType() == 'family' ) {
                            	$output[] = "<li{$Warecorp->isActiveClass('Avatars', 'Group', $activeSubCssClass)}><a href='{$objGroup->getGroupPath('avatars')}'>".Warecorp::t('Group Family Photo')."</a></li>";
							} else {
                            	$output[] = "<li{$Warecorp->isActiveClass('Avatars', 'Group', $activeSubCssClass)}><a href='{$objGroup->getGroupPath('avatars')}'>".Warecorp::t('Group Photo')."</a></li>";
							}
                            $output[] = "</ul>";
                        }
                        $output[] = "</li>";
                    }
                    $output[] = '</ul>';
                } else {

                    //TODO @author komarovski -- implement Group Access Manager to this block (EIA, Global group)
                    if ( $Warecorp->is('Stuff', 'Group') ) {
                        $output[] = "<ul>";
                        $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Photos', 'Group', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objGlobalGroup->getGroupPath('photos')}'><span>".Warecorp::t('Photos')."</span></a></li>";
                        $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Videos', 'Group', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objGlobalGroup->getGroupPath('videos')}'><span>".Warecorp::t('Videos')."</span></a></li>";
                        $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Documents', 'Group', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objGlobalGroup->getGroupPath('documents')}'><span>".Warecorp::t('Documents')."</span></a></li>";
                        $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Lists', 'Group', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objGlobalGroup->getGroupPath('lists')}'><span>".Warecorp::t('Lists')."</span></a></li>";
                        $output[] = "</ul>";
                    }                    
                    /**
                     * Tools
                     */
                    if ( $Warecorp->is('Tools', 'Group') ) {
                        $output[] = "<ul id='DOMReadyIE'>";
                        $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Settings', 'Group', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objGlobalGroup->getGroupPath('settings')}'>".Warecorp::t('Settings')."</a></li>";
                        $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Hierarchy', 'Group', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objGlobalGroup->getGroupPath('hierarchy')}'>".Warecorp::t('Hierarchy')."</a></li>";
                        $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Brandgallery', 'Group', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objGlobalGroup->getGroupPath('brandgallery')}'>".Warecorp::t('Brand Gallery')."</a></li>";
                        if ( 'ESA' == IMPLEMENTATION_TYPE ) {  
                            $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Invitations', 'Group', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objGlobalGroup->getGroupPath('invite1')}'>".Warecorp::t('Invitations')."</a></li>";
                        }
                        $output[] = "<li class='prLeftNav{$Warecorp->isActiveClass('Avatars', 'Group', $activeMainCssClass)}'><a class='prLeftNavInner' href='{$objGlobalGroup->getGroupPath('avatars')}'>".Warecorp::t('Profile Photos')."</a></li>";
                        $output[] = "</ul>";
                    }
                }
                $output = join('', $output);
                break;
        }
        if ( !empty($output) ) $output = '<div class="prLeftNavBlock">'.$output.'</div>';
        return $output;
    }