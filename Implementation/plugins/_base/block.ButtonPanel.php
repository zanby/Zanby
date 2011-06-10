<?php
    function smarty_block_ButtonPanel($params, $content, &$smarty)
    {
		Warecorp::addTranslation('/plugins/block.ButtonPanel.php.xml');
		$theme = Zend_Registry::get('AppTheme');
        if ( $content !== null ) {
			if ( trim($content) != '' ) $content = '<div class="prButtonPanel">'.$content.'</div>';
            return $content;
        } else {
            $output = array();
            $objRequest = $smarty->get_template_vars('objRequest');

            $objButtonPanel         = new ButtonPanel();
            $objButtonIcons         = $objButtonPanel->addGroup('Icons');
            $objButtonGroupInfo     = $objButtonPanel->addGroup('GroupInfo');
            $objButtonGroupLinks    = $objButtonPanel->addGroup('GroupLinks');

            $objButtonIcons->addIcon('bookmark');
            $objButtonIcons->addIcon('print');
            $objButtonIcons->addIcon('rss');

            $objUser = $objCurrentUser = $objGroup = null;
            $context = Warecorp::isContext($smarty, $objUser, $objCurrentUser, $objGroup);
			$isAuthenticated = (boolean) ($objUser->getId());

            switch ( $context ) {
                /**
                 * Index Page
                 */
                case 'index' :
                    if ( 'EIA' == IMPLEMENTATION_TYPE ) {
                        if ( Zend_Registry::isRegistered('globalGroup') ) {
                            require_once(MODULES_DIR.'/GroupsController.php');
                            $objGlobalGroup = Zend_Registry::get('globalGroup');
                        } else throw new Exception(Warecorp::t('Incorrect global group param'));

                        /**
                         * Check Permissions
                         */
                        $isHost     = (boolean) $objGlobalGroup->getMembers()->isHost($objUser->getId());
                        $isCohost   = (boolean) $objGlobalGroup->getMembers()->isCohost($objUser->getId());
                        $isMember   = $isHost || $isCohost || $objGlobalGroup->getMembers()->isMemberExistsAndApproved($objUser->getId());
                        $isPending  = (boolean) $objGlobalGroup->getMembers()->isMemberExistsAndPending($objUser->getId());
                        /**
                         * Group Summary
                         */
                        if ( 'index' == Warecorp::$actionName ) {
                            if ( $isHost || $isCohost ) {
                                $objButtonPanel->addLink(Warecorp::t('Open template editor'), $objGlobalGroup->getGroupPath('edit'));
                            }
                            if ( !$isHost ) {
                                $iconSendMessage = $objButtonIcons->addIcon('send_message_group');
                                $iconSendMessage->setObjCurrentGroup($objGlobalGroup);
                            }
                            /**
                             * User status
                             */
                            if ( $isHost ) {
                                if ( $objGlobalGroup->getGroupType() == "family" ) $objButtonGroupInfo->addText('<div class="prBgIcon"><img class="pngFixIE" src="'.$theme->images.'/decorators/bkgOwner.png" /></div>');
                                else $objButtonGroupInfo->addText('<div class="prBgIcon"><img class="pngFixIE" src="'.$theme->images.'/decorators/bkgHost.png" /></div>');
                            } elseif ( $isCohost ) {
                                if ( $objGlobalGroup->getGroupType() == "family" ) $objButtonGroupInfo->addText('<div class="prBgIcon"><img class="pngFixIE" src="'.$theme->images.'/decorators/bkgCoOwner.png" /></div>');
                                else $objButtonGroupInfo->addText('<div class="prBgIcon"><img class="pngFixIE" src="'.$theme->images.'/decorators/bkgCoHost.png" /></div>');
                            } elseif ( $isMember ) {
                                $objButtonGroupInfo->addText('<div class="prBgIcon"><img class="pngFixIE" src="'.$theme->images.'/decorators/bkgMember.png" /></div>');
                            } elseif ( $isPending ) {
                                $objButtonGroupInfo->addText('<div class="prMembership"><img class="pngFixIE" src="'.$theme->images.'/decorators/bkgPendingMember.png" /></div>');
                            }
                            /**
                             * Join Link
                             */                            
                            if ( $objGlobalGroup->getGroupType() == 'family' ) {
                                if ( $objUser->getGroups()->setMembersRole('host')->setTypes('simple')->setExcludeIds($objGlobalGroup->getGroups()->setAssocValue('zgi.id')->returnAsAssoc()->getList())->getCount() > 0 ) {
                                    $objButtonGroupLinks->addLink(Warecorp::t('Join Family'), $objGlobalGroup->getGroupPath('joinfamilygroup'));
                                }
                            } else {
                                if ( !$isHost && !$isCohost && !$isMember && !$isPending && $isAuthenticated ) {
                                    $objButtonGroupLinks->addLink(Warecorp::t('Join Group'), $objGlobalGroup->getGroupPath('joingroup'));
                                }
                            }
                        }
                        /**
                         * Group Members Page
                         */
                        elseif ( 'groups' == Warecorp::$actionName ) {
                            if ( $objGlobalGroup->getGroupType() == 'family' ) {
                                if ( $isHost || $isCohost ) {
                                    $objButtonPanel->addLink(Warecorp::t('+ Add family Member'), $objGlobalGroup->getGroupPath('membersAddStep1'));
                                }
                            } else {
                                if ( $objGlobalGroup->getGroupType() != 'family' && $acl['ManageMembers'] ) {
                                    $objButtonPanel->addLink(Warecorp::t('Invite Members'), null, "xajax_invitemembers(".$objGlobalGroup->getId()."); return false;");
                                }
                            }
                        }
                        /**
                         * Group events
                         */
                        elseif ( 'events' == Warecorp::$actionName ) {
                            /*@todo we should avoid custom implementatioon code in _base dir.*/
                            if ( HTTP_CONTEXT == 'zccf' && ($isHost || $isCohost) ) {
                                $objButtonPanel->addLink(Warecorp::t('Export all events RSVP'), $objGlobalGroup->getGroupPath('calendar.export.rsvp'));
                            }
                            /**
                             * Add or Edit page
                             */
                            if ( Warecorp_ICal_AccessManager_Factory::create()->canCreateEvent($objGlobalGroup, $objUser) ) {
                                if ( in_array(Warecorp::$actionName, array('calendar.event.create', 'calendar.event.edit', 'calendar.event.copy.do')) ) {
                                    $objButtonPanel->addLink('Save Event', null, "document.forms['form_add_event'].submit(); return false;");
                                    $objButtonPanel->addText(' or ');
                                    $objButtonPanel->addTextLink(Warecorp::t('Cancel'), $objGlobalGroup->getGroupPath('calendar.list.view'));
                                } else {
                                    $objButtonPanel->addLink(Warecorp::t('Create Event'), $objGlobalGroup->getGroupPath('calendar.event.create'));
                                }
                            }
                        }
                        /**
                         * Group Lists
                         */
                        elseif ( 'lists' == Warecorp::$actionName ) {
                            if ( Warecorp_List_AccessManager_Factory::create()->canCreateLists($objGlobalGroup, $objUser) ) {
                                if ( !in_array(Warecorp::$actionName, array('listsadd', 'listsedit') ) ) {
                                    // Not Add or Edit page
                                    $objButtonPanel->addLink(Warecorp::t('Make List'), $objGlobalGroup->getGroupPath('listsadd'));
                                } else {
                                    // Add/edit pages
                                    if ( Warecorp::$actionName == 'listsedit' ) {
                                        $objButtonPanel->addLink(Warecorp::t('Save List'), null, "lock_content(); xajax_list_edit_publish(xajax.getFormValues('list_edit_form'));  return false;");
                                    } else {
                                        $objButtonPanel->addLink(Warecorp::t('Save List'), null, "lock_content(); xajax_list_add_publish(xajax.getFormValues('list_add_form')); return false;");
                                    }
                                    $objButtonPanel->addText(Warecorp::t(' or '));
                                    $objButtonPanel->addTextLink(Warecorp::t('Cancel'), $objGlobalGroup->getGroupPath('lists'));
                                }
                            }
                        }
                        /**
                         * Group Photos
                         */
                        elseif ( 'photos' == Warecorp::$actionName ) {
                            if ( Warecorp_Photo_AccessManager_Factory::create()->canCreateGallery($objGlobalGroup, $objUser) ) {
                                if ( !in_array(Warecorp::$actionName, array('gallerycreate', 'galleryedit') ) ) {
                                        // Not Add or Edit page
                                        $objButtonPanel->addLink(Warecorp::t('Upload Photos'), $objGlobalGroup->getGroupPath('gallerycreate/step/1'));
                                    }
                            }
                        }
                        /**
                         * Group Videos
                         */
                        elseif ( 'videos' == Warecorp::$actionName ) {
                            if ( Warecorp_Video_AccessManager_Factory::create()->canUploadVideos($objGlobalGroup, $objUser) ) {
                                if ( !in_array(Warecorp::$actionName, array('videogallerycreate', 'videogalleryedit', 'videogalleryView') ) ) {
                                        // Not Add or Edit page
                                        $objButtonPanel->addLink(Warecorp::t('Upload Video'), $objGlobalGroup->getGroupPath('videogallerycreate/step/1'));
                                    }
                            }
                        }
                        /**
                         * Tools
                         */
                        elseif ( 'settings' == Warecorp::$actionName ) {
                            if ( $objGlobalGroup->getGroupType() == 'family' ) {
                                if ( $isHost || $isCohost ) {
                                    if ( !Warecorp::is('Rounds', 'Group') ) {
                                        $objButtonPanel->addLink(Warecorp::t('Export members list'), $objGlobalGroup->getGroupPath('exportmembers'));
                                    }
                                }
                            }
                        }

                    }
                    break;

                /**
                 * Members Page
                 */
                case 'users_index' :
                    break;
                /**
                 * Account of current user, account pages
                 */
                case 'user_account' :
                    break;
                /**
                 * Profile of current user, profile pages
                 */
                case 'user_profile' :
                    /**
                     * User summary page
                     */
                    if ( Warecorp::is('Profile', 'User') ) {
                        $objButtonPanel->addLink(Warecorp::t('Open template editor'), $objCurrentUser->getUserPath('compose'));                        
                    }
                    /**
                     * User Template Editor
                     */
                    elseif ( in_array(Warecorp::$actionName, array('compose', 'theme')) ) {
                        $objButtonPanel->addLink(Warecorp::t('Save and exit'), $objCurrentUser->getUserPath('profile'));
                    }
                    /**
                     * User events
                     */
                    elseif ( Warecorp::is('Events', 'User') ) {
                        /**
                         * Add or Edit page
                         */
                        if ( Warecorp_ICal_AccessManager_Factory::create()->canCreateEvent($objCurrentUser, $objUser) ) {
                            if ( in_array(Warecorp::$actionName, array('calendar.event.create', 'calendar.event.edit', 'calendar.event.copy.do')) ) {
                                $objButtonPanel->addLink(Warecorp::t('Save Event'), null, "document.forms['form_add_event'].submit(); return false;");
                                $objButtonPanel->addText(Warecorp::t(' or '));
                                $objButtonPanel->addTextLink(Warecorp::t('Cancel'), $objCurrentUser->getUserPath('calendar.list.view'));
                            } else {
                                $objButtonPanel->addLink(Warecorp::t('Create Event'), $objCurrentUser->getUserPath('calendar.event.create'));
                            }
                        }
                    }
                    /**
                     * User Lists
                     */
                    elseif ( Warecorp::is('Lists', 'User') ) {
                        /**
                         * Add or Edit page
                         */
                        if ( in_array(Warecorp::$actionName, array('listsadd', 'listsedit')) ) {
                            //$objButtonPanel->addLink('Back to My Lists', $objCurrentUser->getUserPath('lists'));
                        } else {
                            $objButtonPanel->addLink(Warecorp::t('Make List'), $objCurrentUser->getUserPath('listsadd'));
                        }
                    }
                    /**
                     * User Photos
                     */
                    elseif ( Warecorp::is('Photos', 'User') ) {
                        /**
                         * Add or Edit page
                         */
                        if ( in_array(Warecorp::$actionName, array('gallerycreate', 'galleryedit')) ) {
                            //$objButtonPanel->addTextLink('Back to Photo Galleries', $objCurrentUser->getUserPath('photos'));
                        } else {
                            $objButtonPanel->addLink(Warecorp::t('Upload Photos'), $objCurrentUser->getUserPath('gallerycreate/step/1'));
                        }
                    }
                    /**
                     * User Videos
                     */
                    elseif ( Warecorp::is('Videos', 'User') ) {
                        /**
                         * Add or Edit page
                         */
                        if ( in_array(Warecorp::$actionName, array('videogallerycreate', 'videogalleryedit')) ) {
                            //$objButtonPanel->addTextLink('Back to Videos', $objCurrentUser->getUserPath('videos'));
                        } else {
                            $objButtonPanel->addLink(Warecorp::t('Upload Video'), $objCurrentUser->getUserPath('videogallerycreate/step/1'));
                        }
                    }
					/**
                     * Address book
                     */
                    elseif ( Warecorp::is('Messages', 'User') ) {
                        if ( in_array(Warecorp::$actionName, array('addressbook','addressbookgroup','addressbookaddmaillist','addressbookaddcontact', 'addressbookmaillist')) ) {
                            $objButtonPanel->addLink(Warecorp::t('Add Mailing List'), $objCurrentUser->getUserPath('addressbookaddmaillist'));
							$objButtonPanel->addLink(Warecorp::t('Add Contact'), $objCurrentUser->getUserPath('addressbookaddcontact'));
                        }
                    }
                    elseif ( Warecorp::is('Groups', 'User') ) {
                        if ( 'EIA' == IMPLEMENTATION_TYPE ) {
                            if ( Zend_Registry::isRegistered('globalGroup') ) {
                                require_once(MODULES_DIR.'/GroupsController.php');
                                $primaryFamily = Zend_Registry::get('globalGroup');
                            } else throw new Exception(Warecorp::t('Incorrect global group param'));
                            if ( $primaryFamily != null && $primaryFamily->getId() != null ) {
                                $primaryMembersList = $primaryFamily->getMembers();
                                if  ( $primaryMembersList->isHost($objCurrentUser->getId()) || $primaryMembersList->isCohost($objCurrentUser->getId()) ) {
                                        $objButtonPanel->addLink(Warecorp::t('Create a Group'), 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/newgroup/index/');
                                }
                                elseif ( (int)$primaryFamily->getPrivileges()->getGroupsCreation() === 1 ) {
                                    $userGroups = $objCurrentUser->getGroups()->setMembersRole(array('host', 'cohost'))->setTypes('simple')->returnAsAssoc(true)->getList();
                                    $familiesGroups = $primaryFamily->getGroups()->returnAsAssoc(true)->getList();
                                    if ( is_array($userGroups) && is_array($familiesGroups) && array_intersect_key($familiesGroups, $userGroups) )
                                        $objButtonPanel->addLink(Warecorp::t('Create a Group'), 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/newgroup/index/');
                                }
                                elseif ( (int)$primaryFamily->getPrivileges()->getGroupsCreation() === 2 ) {
                                    if ( $primaryMembersList->isMemberExistsAndApproved($objCurrentUser->getId()) )
                                        $objButtonPanel->addLink(Warecorp::t('Create a Group'), 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/newgroup/index/');
                                }
                                elseif ( (int)$primaryFamily->getPrivileges()->getGroupsCreation() === 3 ) {
                                    $aprovedUsers = $primaryFamily->getPrivileges()->getUsersListByTool('gpCreateGroup')->returnAsAssoc(true)->getList();
                                    if ( array_key_exists($objCurrentUser->getId(), $aprovedUsers) )
                                        $objButtonPanel->addLink(Warecorp::t('Create a Group'), 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/newgroup/index/');
                                }
                            }
                        }
                        else{
                            $objButtonPanel->addLink(Warecorp::t('Create a Group'), 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/newgroup/index/');
                        }

                    }
                    break;
                /**
                 * Profile of other user, profile pages
                 */
                case 'people_profile' :
                    /**
                     * Other user summary page
                     */
                    if ( Warecorp::is('Profile', 'User') ) {
                        if ( $objUser->getPrivacy()->getSrViewAddToFriend() ) {
                            /**
                             * TODO remove $friendsAssoc from other actions in modules
                             */
                            $friendsAssoc = $objCurrentUser->getFriendsList()->returnAsAssoc()->getList();
                            if ( !in_array($objUser->getId(), $friendsAssoc) ) {
                                $objButtonPanel->addLink(Warecorp::t('+ Add to Friends'), null, 'xajax_addToFriends('.$objCurrentUser->getId().'); return false;');
                            }
                        }
                        $objButtonIcons = $objButtonPanel->addGroup('Icons');
                        if ( Warecorp_User_AccessManager::getInstance()->canContact($objCurrentUser, $objUser) ) {
                            $iconSendMessage = $objButtonIcons->addIcon('send_message_user');
                            $iconSendMessage->setObjCurrentUser($objCurrentUser);
                        }
                        $objButtonIcons->addIcon('print');
                        $objButtonIcons->addIcon('bookmark');
                        $objButtonIcons->addIcon('rss');
                    }
                    break;
                /**
                 * Groups/Groups Search Page
                 */
                case 'group_index' :
                    break;
                /**
                 * Current Group Pages
                 */
                case 'group' :
                    /**
                     * Check Permissions
                     */
                    $isHostPrivileges = (boolean) Warecorp_Group_AccessManager::isHostPrivileges($objGroup, $objUser); //komarovski

                    $isHost     = (boolean) $objGroup->getMembers()->isHost($objUser->getId());
                    $isCohost   = (boolean) $objGroup->getMembers()->isCohost($objUser->getId());
                    $isMember   = (boolean) $isHost || $isCohost || $objGroup->getMembers()->isMemberExistsAndApproved($objUser->getId());
                    $isPending  = (boolean) $objGroup->getMembers()->isMemberExistsAndPending($objUser->getId());

                    /*Zend_Debug::Dump($isHost);
                    Zend_Debug::Dump($isCohost);
                    Zend_Debug::Dump($isMember);
                    Zend_Debug::Dump($isPending);exit;*/

                    $acl = array(
                        'Calendar'              =>(int)Warecorp_Group_AccessManager::canUseCalendar($objGroup, $objUser),
                        'Email'                 =>(int)Warecorp_Group_AccessManager::canUseEmail($objGroup, $objUser),
                        'Photos'                =>(int)Warecorp_Group_AccessManager::canUsePhotos($objGroup, $objUser),
                        'Documents'             =>(int)Warecorp_Group_AccessManager::canUseDocuments($objGroup, $objUser),
                        'Lists'                 =>(int)Warecorp_Group_AccessManager::canUseLists($objGroup, $objUser),
                        'Polls'                 =>(int)Warecorp_Group_AccessManager::canUsePolls($objGroup, $objUser),
                        'ManageMembers'         =>(int)Warecorp_Group_AccessManager::canUseManageMembers($objGroup, $objUser),
                        'ManageGroupFamilies'   =>(int)Warecorp_Group_AccessManager::canUseManageGroupFamilies($objGroup, $objUser),
                        'ModifyLayout'          =>(int)Warecorp_Group_AccessManager::canUseModifyLayout($objGroup, $objUser),
                    );
                    /**
                     * Group Summary
                     */
                    if ( Warecorp::is('Summary', 'Group') ) {
                        if ($isHostPrivileges/*$isHost || $isCohost*//*komarovski*/ ) {
                            $objButtonPanel->addLink(Warecorp::t('Open template editor'), $objGroup->getGroupPath('edit'));
                        }
                        if ( !$isHost ) {
                            $iconSendMessage = $objButtonIcons->addIcon('send_message_group');
                            $iconSendMessage->setObjCurrentGroup($objGroup);
                        }
                        /**
                         * User status
                         */
                        if ( $isHost ) {
                            if ( $objGroup->getGroupType() == "family" ) $objButtonGroupInfo->addText('<div class="prBgIcon"><img class="pngFixIE" src="'.$theme->images.'/decorators/bkgOwner.png" /></div>');
                            else $objButtonGroupInfo->addText('<div class="prBgIcon"><img class="pngFixIE" src="'.$theme->images.'/decorators/bkgHost.png" /></div>');
                        } elseif ( $isCohost ) {
                            if ( $objGroup->getGroupType() == "family" ) $objButtonGroupInfo->addText('<div class="prBgIcon"><img class="pngFixIE" src="'.$theme->images.'/decorators/bkgCoOwner.png" /></div>');
                            else $objButtonGroupInfo->addText('<div class="prBgIcon"><img class="pngFixIE" src="'.$theme->images.'/decorators/bkgCoHost.png" /></div>');
                        } elseif ( $isMember ) {
                            $objButtonGroupInfo->addText('<div class="prBgIcon"><img class="pngFixIE" src="'.$theme->images.'/decorators/bkgMember.png" /></div>');
                        } elseif ( $isPending ) {
                            $objButtonGroupInfo->addText('<div class="prMembership"><img class="pngFixIE" src="'.$theme->images.'/decorators/bkgPendingMember.png" /></div>');
                        } /* elseif ($isHostPrivileges) { //komarovski
                            //$objButtonGroupInfo->addText('<div class="prBgIcon"><img class="pngFixIE" src="'.$theme->images.'/decorators/bkgOwner.png" /></div>');
                            //@TODO make differences between owner and coowner (only coowner reflects in any way)
                            $objButtonGroupInfo->addText('<div class="prBgIcon"><img class="pngFixIE" src="'.$theme->images.'/decorators/bkgCoOwner.png" /></div>');
                            //approved by Sergei Gusev
                        } */
                        /**
                         * Join Link
                         */
                        if ( $objGroup->getGroupType() == 'family' ) {
                            if ( $objUser->getGroups()->setMembersRole('host')->setTypes('simple')->setExcludeIds($objGroup->getGroups()->setAssocValue('zgi.id')->setStatus(Warecorp_Group_Enum_GroupStatus::GROUP_STATUS_BOTH)->returnAsAssoc()->getList())->getCount() > 0 ) {
                                $objButtonGroupLinks->addLink(Warecorp::t('Join Family'), $objGroup->getGroupPath('joinfamilygroup'));
                            }
                        } else {
                            /**
                             * @see https://secure.warecorp.com/redmine/issues/12510
                             * @author Artem Sukharev
                             */
                            //if ( !$isHost && !$isCohost && !$isMember && !$isPending && $isAuthenticated && !$objGroup->isPrivate()) {
                            if ( !$isHost && !$isCohost && !$isMember && !$isPending && $isAuthenticated) {
                                $objButtonGroupLinks->addLink(Warecorp::t('Join Group'), $objGroup->getGroupPath('joingroup'));
                            }
                        }
                    }
                    /**
                     * Group Template Editor
                     */
                    elseif ( in_array(Warecorp::$actionName, array('edit', 'theme', 'publish')) ) {
                        if ($isHostPrivileges/*$isHost || $isCohost*//*komarovski*/ ) {
                            $objButtonPanel->addLink(Warecorp::t('Save and exit'), $objGroup->getGroupPath('summary'));
                        }
                    }
                    /**
                     * Group Members Page
                     */
                    elseif ( Warecorp::is('Members', 'Group') ) {
                        if ( $objGroup->getGroupType() == 'family' ) {
                            if ( $isHost || $isCohost ) {
                                $objButtonPanel->addLink(Warecorp::t('+ Add family Member'), $objGroup->getGroupPath('membersAddStep1'));
                            }
                        } else {
                            if ( $objGroup->getGroupType() != 'family' && $acl['ManageMembers'] ) {
                                $objButtonPanel->addLink(Warecorp::t('Invite Members'), null, "xajax_invitemembers(".$objGroup->getId()."); return false;");
                            }
                        }
                    }
                    /**
                     * Group events
                     */
                    elseif ( Warecorp::is('Events', 'Group') ) {
                        /*@todo we should avoid implementation specific code in _base dir*/
                        if ( HTTP_CONTEXT == 'zccf' && ($isHost || $isCohost) ) {
                            $objButtonPanel->addLink(Warecorp::t('Export all events RSVP'), $objGroup->getGroupPath('calendar.export.rsvp'));
                        }

                        /**
                         * Add or Edit page
                         */
						if ( Warecorp_ICal_AccessManager_Factory::create()->canCreateEvent($objGroup, $objUser) ) {
							if ( in_array(Warecorp::$actionName, array('calendar.event.create', 'calendar.event.edit', 'calendar.event.copy.do')) ) {
								$objButtonPanel->addLink(Warecorp::t('Save Event'), null, "document.forms['form_add_event'].submit(); return false;");
								$objButtonPanel->addText(Warecorp::t(' or '));
								$objButtonPanel->addTextLink(Warecorp::t('Cancel'), $objGroup->getGroupPath('calendar.list.view'));
							} else {
								$objButtonPanel->addLink(Warecorp::t('Create Event'), $objGroup->getGroupPath('calendar.event.create'));
							}
						}
                    }
                    /**
                     * Group Lists
                     */
                    elseif ( Warecorp::is('Lists', 'Group') ) {
						if ( Warecorp_List_AccessManager_Factory::create()->canCreateLists($objGroup, $objUser) ) {
							if ( !in_array(Warecorp::$actionName, array('listsadd', 'listsedit') ) ) {
								// Not Add or Edit page
								$objButtonPanel->addLink(Warecorp::t('Make List'), $objGroup->getGroupPath('listsadd'));
							} else {
								// Add/edit pages
								if ( Warecorp::$actionName == 'listsedit' ) {
									$objButtonPanel->addLink(Warecorp::t('Save List'), null, "lock_content(); xajax_list_edit_publish(xajax.getFormValues('list_edit_form'));  return false;");
								} else {
									$objButtonPanel->addLink(Warecorp::t('Save List'), null, "lock_content(); xajax_list_add_publish(xajax.getFormValues('list_add_form')); return false;");
								}
								$objButtonPanel->addText(Warecorp::t(' or '));
								$objButtonPanel->addTextLink(Warecorp::t('Cancel'), $objGroup->getGroupPath('lists'));
							}
						}
                    }
                    /**
                     * Group Photos
                     */
                    elseif ( Warecorp::is('Photos', 'Group') ) {
						if ( Warecorp_Photo_AccessManager_Factory::create()->canCreateGallery($objGroup, $objUser) ) {
							if ( !in_array(Warecorp::$actionName, array('gallerycreate', 'galleryedit') ) ) {
									// Not Add or Edit page
									$objButtonPanel->addLink(Warecorp::t('Upload Photos'), $objGroup->getGroupPath('gallerycreate/step/1'));
								}
						}
                    }
                    /**
                     * Group Videos
                     */
                    elseif ( Warecorp::is('Videos', 'User') ) {
						if ( Warecorp_Video_AccessManager_Factory::create()->canUploadVideos($objGroup, $objUser) ) {
							if ( !in_array(Warecorp::$actionName, array('videogallerycreate', 'videogalleryedit', 'videogalleryView') ) ) {
									// Not Add or Edit page
									$objButtonPanel->addLink(Warecorp::t('Upload Video'), $objGroup->getGroupPath('videogallerycreate/step/1'));
								}
						}
                    }
                    /**
					 * Tools
                     */
                    elseif ( Warecorp::is('Tools', 'Group') ) {
                        if ( $objGroup->getGroupType() == 'family' ) {
                            if ( $isHost || $isCohost ) {
                                if ( !Warecorp::is('Rounds', 'Group') ) {
                                    $objButtonPanel->addLink(Warecorp::t('Export members list'), $objGroup->getGroupPath('exportmembers'));
                                }
                            }
                        }
                    }
                    break;
            }

            if ( !$lstWidgetsButtonPanel = $smarty->get_template_vars('__Widgets_ButtonPanel__') ) {
                $lstWidgetsButtonPanel = array();
            }
            array_push($lstWidgetsButtonPanel, $objButtonPanel);
            $smarty->assign('__Widgets_ButtonPanel__', $lstWidgetsButtonPanel);

            //return $objButtonPanel->render();
        }
    }

    /**
     * +-------------------------------------------------
     * |
     * |
     * }
     * +-------------------------------------------------
     */

    /**
     * Enter description here...
     *
     */
    class ButtonPanel
    {
        protected $isRendered;
        private $groups;
        protected $defaultGroup = 'Default';

        public function __construct()
        {
            $this->groups = array();
            $this->addGroup($this->defaultGroup);
            $this->isRendered = false;
        }
        public function addGroup($id)
        {
            $objElement = new ButtonPanelGroup($id);
            $this->groups[$id] = $objElement;
            return $objElement;
        }
        public function getGroup($id = null)
        {
            if ( null === $id ) return $this->groups[$this->defaultGroup];
            if ( $this->groups[$id] ) return $this->groups[$id];
            else throw new Zend_Exception(Warecorp::t('Undefined group'));
        }
        /**
         * Enter description here...
         *
         * @param unknown_type $label
         * @param unknown_type $url
         * @param unknown_type $onclick
         * @return unknown
         */
        public function addLink($label, $url, $onclick = null, $id = null)
        {
            $objElement = new ButtonPanelLink();
            $objElement->setLabel($label);
            $objElement->setUrl($url);
            $objElement->setOnclik($onclick);
            $objElement->setId($id);
            $this->getGroup()->addElement($objElement);
            return $objElement;
        }
        public function addIcon($type)
        {
            $objElement = new ButtonPanelIcon();
            $objElement->setType($type);
            $this->getGroup()->addElement($objElement);
            return $objElement;
        }
        public function addTextLink($label, $url, $onclick = null)
        {
            $objElement = new ButtonPanelTextLink();
            $objElement->setLabel($label);
            $objElement->setUrl($url);
            $objElement->setOnclik($onclick);
            $this->getGroup()->addElement($objElement);
            return $objElement;
        }
        public function addText($text)
        {
            $objElement = new ButtonPanelText();
            $objElement->setText($text);
            $this->getGroup()->addElement($objElement);
            return $objElement;
        }
        public function addButton($label)
        {
            $objElement = new ButtonPanelButton();
            $objElement->setText($text);
            $this->getGroup()->addElement($objElement);
            return $objElement;
        }

        /**
         * Enter description here...
         *
         * @return strign
         */
        public function render($groupId = null)
        {
            $output = array();
            if ( null === $groupId ) {
                if ( sizeof($this->groups) ) {
                    foreach ( $this->groups as $_group ) {
                        $output[] = $_group->render();
                    }
                }
            } else {
                if ( is_array($groupId) ) {
                } else $groupId = explode(";", $groupId);
                foreach ( $groupId as $_group ) {
                    $output[] = $this->getGroup($_group)->render();
                }
            }
            $output = trim(join(' ', $output));
            if ( $output ) $output = '<div class="prIndentLeftSmall prFloatRight">'.$output.'</div>';
            $this->isRendered = true;
            return $output;
        }
    }



    /**
     * Enter description here...
     *
     */
    class ButtonPanelGroup extends ButtonPanel
    {
        protected $elements;
        private $groupId;

        public function __construct($groupId)
        {
            $this->defaultGroup = $groupId;
            $this->groupId = $groupId;
            $this->isRendered = false;
        }
        public function getGroupId()
        {
            return $this->groupId;
        }
        public function getGroup($id = null)
        {
            return $this;
        }
        public function addElement($objElement)
        {
            $this->elements[] = $objElement;
        }
        public function addGroup($id) { throw new Zend_Exception(Warecorp::t('You can not add group to group')); }

        /**
         * Enter description here...
         *
         * @return strign
         */
        public function render()
        {
            $output = array();
            if ( sizeof($this->elements) ) {
                foreach ( $this->elements as $_element ) {
                    $output[] = $_element->render();
                }
            }
            $output = join(' ', $output);
            $this->isRendered = true;
            return $output;
        }
    }



    /**
     * Enter description here...
     *
     */
    class ButtonPanelButton
    {
        private $label;

        public function getLabel()
        {
            return $this->label;
        }
        public function setLabel( $label )
        {
            $this->label = $label;
        }

        /**
         * Enter description here...
         *
         * @return strign
         */
        public function render()
        {
            return '<input type="button" name="Button" value="'.$this->getLabel().'">';
        }
    }



    /**
     * Enter description here...
     *
     */
    class ButtonPanelLink
    {
        private $id;
        private $label;
        private $url;
        private $onclik;

        public function getOnclik()
        {
            return $this->onclik;
        }
        public function setOnclik( $onclik )
        {
            $this->onclik = $onclik;
        }
        public function getLabel()
        {
            return $this->label;
        }
        public function getUrl()
        {
            if ( null === $this->url ) return 'javascript:return void();';
            return $this->url;
        }
        public function setLabel( $label )
        {
            $this->label = $label;
        }
        public function setUrl( $url )
        {
            $this->url = $url;
        }
        public function setId( $id ) 
        {
            $this->id = $id;
        }
        public function getId() 
        {
            return $this->id;
        }
        
        /**
         * Enter description here...
         *
         * @return unknown
         */
        public function render()
        {
            $output = array();
            $output[] = '<a class="prButton" href="'.$this->getUrl().'"';
            if ( null !== $this->getId() ) {
                $output[] = ' id="'.$this->getId().'"';
            }
            if ( null !== $this->getOnclik() ) {
                $output[] = ' onclick="'.str_replace('"', '\"', $this->getOnclik()).'"';
            }
            $output[] = '><span>'.$this->getLabel();
            $output[] = '</span></a>';
            return join('', $output);
        }
    }



    /**
     * Enter description here...
     *
     */
    class ButtonPanelTextLink extends ButtonPanelLink
    {
        /**
         * Enter description here...
         *
         * @return unknown
         */
        public function render()
        {
            $output = array();
            $output[] = '<a href="'.$this->getUrl().'"';
            if ( null !== $this->getOnclik() ) {
                $output[] = ' onclick="'.str_replace('"', '\"', $this->getOnclik()).'"';
            }
            $output[] = '>'.$this->getLabel();
            $output[] = '</a>';
            return join('', $output);
        }
    }



    /**
     * Enter description here...
     *
     */
    class ButtonPanelIcon
    {
        private $type;
        private $objCurrentUser;
        private $objCurrentGroup;

        public function getObjCurrentGroup()
        {
            if ( null === $this->objCurrentGroup ) throw new Zend_Exception(Warecorp::t('Set group object first'));
            return $this->objCurrentGroup;
        }
        public function setObjCurrentGroup( $objCurrentGroup )
        {
            $this->objCurrentGroup = $objCurrentGroup;
        }
        public function getType()
        {
            return $this->type;
        }
        public function setType( $type )
        {
            $this->type = $type;
        }
        public function getObjCurrentUser()
        {
            if ( null === $this->objCurrentUser ) throw new Zend_Exception(Warecorp::t('Set user object first'));
            return $this->objCurrentUser;
        }
        public function setObjCurrentUser( $objCurrentUser )
        {
            $this->objCurrentUser = $objCurrentUser;
        }

        /**
         * Enter description here...
         *
         */
        public function render()
        {
            $output = '';
            switch ( $this->getType() ) {
                case 'print' :
                    $title = 'Print this page';
                    $output = '<a href="#null" title="'.$title.'" onclick="window.print(); return false;"><img src="'.$theme->images.'/buttons/print.gif"/></a>';
                    break;
                case 'bookmark' :
                    $title = 'Bookmark this page';
                    $output = '<a href="#null" title="'.$title.'" onClick="xajax_bookmarkit()">Bookmark</a>';
                    break;
                case 'send_message_user' :
                    $title = 'Send message to member';
                    $output = '<a href="#null" title="'.$title.'" onClick="xajax_sendMessage('.$this->getObjCurrentUser()->getId().'); return false;">'.Warecorp::t('Send message').'</a>';
                    break;
                case 'send_message_group' :
                    $title = ( $this->getObjCurrentGroup()->getGroupType() == 'family' ) ? Warecorp::t('Send message to owner') : Warecorp::t('Send message to host');
                    $output = '<a href="#null" title="'.$title.'" onClick="xajax_sendMessage('.$this->getObjCurrentGroup()->getHost()->getId().'); return false;">'.Warecorp::t('Send message').'</a>';
                    break;
                case 'rss' :
                    /**
                     * TODO
                     */
                    $output = '<img src="'.$theme->images.'/buttons/rss.gif"/>';
                    break;
            }
            return $output;
        }
    }



    /**
     * Enter description here...
     *
     */
    class ButtonPanelText
    {
        private $text;

        public function getText()
        {
            return $this->text;
        }
        public function setText( $text )
        {
            $this->text = $text;
        }

        /**
         * Enter description here...
         *
         * @return unknown
         */
        public function render()
        {
            return $this->getText();
        }
    }
?>
