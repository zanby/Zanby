<?php
Warecorp::addTranslation('/modules/groups/discussion/action.discussion.settings.host.php.xml');
    /**
     * check access
     */
    if ( !$this->_page->_user->isAuthenticated() ) {
        $this->_redirectToLogin();
    }
    if ( !$this->currentGroup->getDiscussionAccessManager()->canConfigureHostSettings($this->currentGroup->getId(), $this->_page->_user->getId()) ) {
        $this->_redirect($this->currentGroup->getGroupPath('discussion'));
    }
    /**
     * register ajax methods
     */
    $this->_page->Xajax->registerUriFunction("choose_discussion_edit", "/groups/chooseDiscussionForEdit/");
    /**
     * create form
     */
    $form = new Warecorp_Form("settingsForm", "post", $this->currentGroup->getGroupPath("discussionhostsettings"));

    /**
     * load settings for current group
     */
    $settings = $this->currentGroup->getDiscussionGroupSettings();
    $main_discussion = $this->currentGroup->getDiscussionGroupDiscussions()->findMainByGroupId($this->currentGroup->getId());

    /**
     * opened tabs
     */
    $ContentOpen = array();
    if ( isset($_SESSION['CommunicationPrivilegesContent']) ) {
        unset($_SESSION['CommunicationPrivilegesContent']);
        $ContentOpen['CommunicationPrivilegesContent'] = 1;
    }
    /*
    $ContentOpen['CommunicationPrivilegesContent'] = 1;
    $ContentOpen['EmailSettingsContent'] = 1;
    $ContentOpen['DiscussionSetupContent'] = 1;
    $ContentOpen['GroupFamiliesContent'] = 1;
    */
    
    if ( $form->validate($this->params) ) {
    	/**
    	 * Save Communication Privileges
    	 */
        if ( isset($this->params['SaveCommunicationPrivileges']) ) {
            /**
             * check discussion email
             */
        	$this->params['main_discussion_email'] = trim($this->params['main_discussion_email']);
            if ( $this->params['main_discussion_email'] == '' ) {
                $form->addCustomErrorMessage(Warecorp::t("Enter discussion email address"));
            }
            if ( $form->isValid() ) {
                if ( !Warecorp_DiscussionServer_Discussion::checkUniqMainEmail($this->params['main_discussion_email'], $main_discussion->getId()) ) {
                    $form->addCustomErrorMessage(Warecorp::t("Discussion email already exists"));
                }
            }
            /**
             * Save params if input is valid
             */
            if ( $form->isValid() ) {
                $settings->setPostMode($this->params['post_mode']);
                $settings->setAllowDeleteOwn((isset($this->params['allow_delete_own']))?1:0);
                $settings->setAllowEditOwn((isset($this->params['allow_edit_own']))?1:0);
                $settings->update();

                $main_discussion->setEmail($this->params['main_discussion_email']);
                //$main_discussion->updateEmail();
                /**
                 * update path of group
                 * main discussion will be updated automaticly
                 */
                $group = Warecorp_Group_Factory::loadById($main_discussion->getGroupId());
                $group->setPath($this->params['main_discussion_email']);
                $group->save();
                $this->currentGroup = Warecorp_Group_Factory::loadById($this->currentGroup->getId());
                
                $this->_page->showAjaxAlert(Warecorp::t('Changes saved'));
                $_SESSION['CommunicationPrivilegesContent'] = 1;
                $this->_redirect($this->currentGroup->getGroupPath('discussionhostsettings'));
            } else {
                $main_discussion->setEmail($this->params['main_discussion_email']);
            }
            $ContentOpen['CommunicationPrivilegesContent'] = 1;
        } 
        /**
         * Save Email Settings
         */
        elseif ( isset($this->params['SaveEmailSettings']) ) {
            $settings->setDiscussionStyle($this->params['discussion_style']);
            //$settings->setEmailedRepliesMode($this->params['emailed_replies_mode']);
            $settings->setEmailedRepliesMode(1);
            $settings->setMessageFooterMode($this->params['message_footer_mode']);
            $settings->setMessageFooterContent(trim($this->params['message_footer_content']));
            $settings->setEmailSubjectPrefix(trim($this->params['email_subject_prefix']));
            $settings->update();
            $this->_page->showAjaxAlert(Warecorp::t('Changes saved'));
            $ContentOpen['EmailSettingsContent'] = 1;
        } 
        /**
         * Add new moderator
         */
        elseif ( isset($this->params['SaveNewModerator']) && $this->params['SaveNewModerator'] == 1 ) {
            /**
             * Check new moderator
             */
        	$this->params['new_moderator_name'] = trim($this->params['new_moderator_name']);
            if ( $this->params['new_moderator_name'] == '' ) {
                $form->addCustomErrorMessage(Warecorp::t("Enter Moderator Name"), "NewModerator");
            }
            if ( $form->isValid() ) {
                $newModerator = new Warecorp_User("login", $this->params['new_moderator_name']);
                if ( $newModerator->getId() === null ) {
                    $form->addCustomErrorMessage(Warecorp::t("User not exists"), "NewModerator");
                } elseif(!($this->currentGroup->getMembers()->isMemberExists($newModerator->getId()))) {
                    $form->addCustomErrorMessage(Warecorp::t("User is not member of this group"), "NewModerator");
                }
            }
            /**
             * Add new moderator if input is valid
             */
            if ( $form->isValid() ) {
                //FIXME надо сделать проверку, является ли пользователь членом данной группы
                $moderatorsList = new Warecorp_DiscussionServer_ModeratorList();
                $moderatorsList->addGroupModerator($this->currentGroup->getId(), $newModerator->getId());
                $this->_page->showAjaxAlert(Warecorp::t('Changes saved'));
            } else {
                $this->view->new_moderator_name = $this->params['new_moderator_name'];
            }
            $ContentOpen['CommunicationPrivilegesContent'] = 1;
        }
        /**
         * Create new discussion for current group
         */ 
        elseif ( isset($this->params['SaveCreateNewDiscussion']) ) {
            /**
             * Check input data
             */
        	$this->params['new_discussion_name']        = trim($this->params['new_discussion_name']);
            $this->params['new_discussion_description'] = trim($this->params['new_discussion_description']);
            $this->params['new_discussion_email']       = trim($this->params['new_discussion_email']);

            if ( $this->params['new_discussion_name'] == '' )           $form->addCustomErrorMessage(Warecorp::t("Enter Discussion Name"), "CreateNewDiscussion");
            if ( $this->params['new_discussion_description'] == '' )    $form->addCustomErrorMessage(Warecorp::t("Enter Discussion Description"), "CreateNewDiscussion");
            if ( $this->params['new_discussion_email'] == '' )          $form->addCustomErrorMessage(Warecorp::t("Enter Discussion Email"), "CreateNewDiscussion");
            if ( $form->isValid() ) {
                if ( !preg_match('/^[a-zA-Z0-9\-]{1,}$/i', $this->params['new_discussion_email']) ) {
                    $form->addCustomErrorMessage(Warecorp::t("Discussion Email  may consist of a-Z, 0-9, -"), "CreateNewDiscussion");
                } elseif ( !Warecorp_DiscussionServer_Discussion::checkUniqEmail($this->currentGroup->getId(), $this->params['new_discussion_email']) ) {
                    $form->addCustomErrorMessage(Warecorp::t("Discussion Email already exists"), "CreateNewDiscussion");
                }
            }
            /**
             * Add new discussion if input is valid
             */
            if ( !$form->isValid() ) {
                $this->view->new_discussion_name = $this->params['new_discussion_name'];
                $this->view->new_discussion_description = $this->params['new_discussion_description'];
                $this->view->new_discussion_email = $this->params['new_discussion_email'];
                $jsCode = "YAHOO.util.Event.onDOMReady(set_focus);function set_focus(){document.getElementById('DiscussionSetupAnchor').focus();}";
                $this->view->jsCode = $jsCode;
            } else {
                $newDiscussion = new Warecorp_DiscussionServer_Discussion();
                $newDiscussion->setGroupId($this->currentGroup->getId());
                $newDiscussion->setAuthorId($this->_page->_user->getId());
                $newDiscussion->setTitle(trim($this->params['new_discussion_name']));
                $newDiscussion->setEmail(trim($this->params['new_discussion_email']));
                $newDiscussion->setDescription(trim($this->params['new_discussion_description']));
                $newDiscussion->setMain(0);
                $newDiscussion->save();
                $this->_page->showAjaxAlert(Warecorp::t('Changes saved'));
            }
            $ContentOpen['DiscussionSetupContent'] = 1;
        } 
        /**
         * Delete discussion
         */
        elseif ( isset($this->params['SaveDeleteDiscussion']) ) {
            if ( isset($this->params['delete_discussion_id']) && floor($this->params['delete_discussion_id']) != 0 ) {
                $discussion = new Warecorp_DiscussionServer_Discussion($this->params['delete_discussion_id']);
                $discussion->delete();
                $this->_page->showAjaxAlert(Warecorp::t('Changes saved'));
            } else {
                $form->addCustomErrorMessage(Warecorp::t("Choose Discussion"), "DeleteDiscussion");
                $jsCode = "YAHOO.util.Event.onDOMReady(set_focus);function set_focus(){document.getElementById('DiscussionSetupAnchor').focus();}";
                $this->view->jsCode = $jsCode;
            }
            $ContentOpen['DiscussionSetupContent'] = 1;
        } 
        /**
         * Update discussion
         */
        elseif ( isset($this->params['SaveEditDiscussion']) ) {
            $editDiscussion = null;
            if ( isset($this->params['edit_discussion_id']) && floor($this->params['edit_discussion_id']) != 0 ) {
                $this->params['edit_discussion_name'] = trim($this->params['edit_discussion_name']);
                $this->params['edit_discussion_description'] = trim($this->params['edit_discussion_description']);

                $editDiscussion = new Warecorp_DiscussionServer_Discussion($this->params['edit_discussion_id']);
                $editDiscussion->setTitle($this->params['edit_discussion_name']);
                $editDiscussion->setDescription($this->params['edit_discussion_description']);

                if ( $this->params['edit_discussion_name'] == '' ) $form->addCustomErrorMessage(Warecorp::t("Enter Discussion Name"), "EditDiscussion");
                if ( $this->params['edit_discussion_description'] == '' ) $form->addCustomErrorMessage(Warecorp::t("Enter Discussion Discription"), "EditDiscussion");
                if ( $form->isValid() ) {
                    $editDiscussion->update();
                    $this->_page->showAjaxAlert(Warecorp::t('Changes saved'));
                }
            } else {
                $form->addCustomErrorMessage(Warecorp::t("Choose Discussion"), "EditDiscussion");
            }
            if ( !$form->isValid() ) {
                $jsCode = "YAHOO.util.Event.onDOMReady(set_focus);function set_focus(){document.getElementById('DiscussionSetupAnchor').focus();}";
                $this->view->jsCode = $jsCode;
                $this->view->editDiscussion = $editDiscussion;
            }
            $ContentOpen['DiscussionSetupContent'] = 1;
        } 
        /**
         * Update order of discussions
         */
        elseif ( isset($this->params['SaveOrderDescussion']) && $this->params['SaveOrderDescussion'] == 1 ) {
            $split = preg_explode("/;/mi", $this->params['OrderString']);
            if ( sizeof($split) != 0 ) {
                foreach ( $split as $_ind => $dis_id ) {
                    $dis_id = str_replace("OrderedDiv", "", trim($dis_id));
                    if ( $dis_id != '' ) {
                        $tmpDis = new Warecorp_DiscussionServer_Discussion($dis_id);
                        $tmpDis->setPosition($_ind + 1);
                        $tmpDis->updatePosition();
                    }
                }
                $this->_page->showAjaxAlert(Warecorp::t('Changes saved'));
            }
            $ContentOpen['DiscussionSetupContent'] = 1;
        } 
        /**
         * Save settings for publishing of families
         */
        elseif ( isset($this->params['SaveFamilyPublishSettings']) ) {
            if ( isset( $this->params['publishGroupOntoFamily'] ) ) {
                if ( sizeof($this->params['publishGroupOntoFamily']) != 0 ) {
                    foreach ( $this->params['publishGroupOntoFamily'] as $_groupId => $_val ) {
                        $mode = ( isset($this->params['publishGroupOntoFamilyCh']) && isset($this->params['publishGroupOntoFamilyCh'][$_groupId]) ) ? 1 : 2;
                        Warecorp_DiscussionServer_Settings::setGroupPublish($this->currentGroup->getId(), $_groupId, $mode);
                    }
                }
                $this->_page->showAjaxAlert(Warecorp::t('Changes saved'));
            }
            $ContentOpen['GroupFamiliesContent'] = 1;
        }
    }
    /**
     * remove moderator if need
     */
    if ( isset($this->params['remove']) && floor($this->params['remove']) != 0 ) {
        $moderatorsList = new Warecorp_DiscussionServer_ModeratorList();
        $moderatorsList->removeGroupModerator($this->currentGroup->getId(), $this->params['remove']);
        $ContentOpen['CommunicationPrivilegesContent'] = 1;
        $this->_page->showAjaxAlert(Warecorp::t('Changes saved'));
    }
    /**
     * get discussions list for current group
     */
    $blogAllow = Warecorp_DiscussionServer_DiscussionList::isIncludeBlog();
    Warecorp_DiscussionServer_DiscussionList::setIncludeBlog(false);
    $discussions = $this->currentGroup->getDiscussionGroupDiscussions()->setIncludeMain(false)->findByGroupId($this->currentGroup->getId());
    Warecorp_DiscussionServer_DiscussionList::setIncludeBlog($blogAllow);
    $discussionsAll = $this->currentGroup->getDiscussionGroupDiscussions()->setIncludeMain(true)->findByGroupId($this->currentGroup->getId());
    /**
     * get moderators list
     */
    $moderatorsList = new Warecorp_DiscussionServer_ModeratorList();
    $moderators = $moderatorsList->findByGroupId($this->currentGroup->getId());
    if ( sizeof($moderators) != 0 ) {
        foreach ($moderators as &$moderator) $moderator = new Warecorp_User("id", $moderator);
    }
    /**
     * get family groups list for simple group
     */
    if ( $this->currentGroup->getGroupType() == 'simple' ) {
        $familyGroups = $this->currentGroup->getFamilyGroups()->getList();
        $this->view->familyGroups = $familyGroups;
    }
    /**
     * assign template vars
     */
    $this->view->form = $form;
    $this->view->main_discussion = $main_discussion;
    $this->view->settings = $settings;
    $this->view->moderators = $moderators;
    $this->view->discussions = $discussions;
    $this->view->discussionsAll = $discussionsAll;
    $this->view->ContentOpen = $ContentOpen;
    
    /**
     * build breadcrumb
     * @todo remove block
     */
//    if($this->currentGroup->getGroupType() == "family") {
//	    $this->_page->breadcrumb = array_merge($this->_page->breadcrumb, array("Group families" => "/" .$this->_page->Locale. "/summary/", $this->currentGroup->getName() => ""));
//    } else {
//        $this->_page->breadcrumb = array_merge(
//            $this->_page->breadcrumb,
//            array($this->currentGroup->getCategory($this->currentGroup->getCategoryId())->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/world/1/",
//                $this->currentGroup->getCountry()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/country/" .$this->currentGroup->getCountry()->id. "/",
//                $this->currentGroup->getState()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/state/" .$this->currentGroup->getState()->id. "/",
//                $this->currentGroup->getCity()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/city/" .$this->currentGroup->getCity()->id. "/",
//                $this->currentGroup->getName() => "")
//            ); 
//    } 
    $this->view->bodyContent = 'groups/discussion/settings.host.tpl';
