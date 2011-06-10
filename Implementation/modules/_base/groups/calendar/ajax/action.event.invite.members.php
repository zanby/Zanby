<?php
Warecorp::addTranslation('/modules/groups/calendar/ajax/action.event.invite.members.php.xml');
    $objResponse = new xajaxResponse();
    
    if ( null === $this->_page->_user->getId() ) {
        $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('calendar.month.view');
        $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
        return $objResponse;
    }

    $objGroup = Warecorp_Group_Factory::loadById($groupId);
    if ( null === $objGroup->getId() ) return;
    
    $membersList = $objGroup->getMembers();
    $membersList->setMembersStatus('approved');
    $membersList->setOrder('zua.login ASC');
    $lstMembers = $membersList->getList();
    
    $form = new Warecorp_Form('form_invite_members', 'POST');
    
    if ( null === $handle ) {
        $arrEmails = explode(',',$strEmails);
        if ( sizeof($arrEmails) != 0 ) {
            foreach ( $arrEmails as &$item ) $item = trim($item);
        }
        $strCheckScript = '';
        $formCheckedItems = array();
        if ( sizeof($lstMembers) != 0 ) {
            foreach ( $lstMembers as &$member ) {
                if ( in_array($member->getLogin(), $arrEmails) || in_array($member->getEmail(), $arrEmails) ) {
                    $formCheckedItems[$member->getId()] = $member->getId();
                    $strCheckScript .= 'YAHOO.util.Dom.get("event_invite_members_'.$member->getId().'").checked = true;';
                } else {
                    $formCheckedItems[$member->getId()] = 0;
                    $strCheckScript .= 'YAHOO.util.Dom.get("event_invite_members_'.$member->getId().'").checked = false;';
                }
            }
        }
        
        $linkUrl = "xajax_doInviteMembers('".$groupId."', document.getElementById('inv_emails').value, xajax.getFormValues('form_invite_members')); return false;";
        
        $this->view->form = $form;
        $this->view->linkUrl = $linkUrl;
        $this->view->lstMembers = $lstMembers;
        $this->view->formCheckedItems = $formCheckedItems;
    
        $Content = $this->view->getContents('groups/calendar/ajax/action.event.invite.members.tpl');
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t("Invite Members"));
        $popup_window->content($Content);
        $popup_window->width(500)->height(450)->open($objResponse);
        
        $objResponse->addScript($strCheckScript);
    } else {
        //$objResponse->addAlert(var_export($handle, true));
        $_REQUEST['_wf__form_invite_members'] = 1;
        /**
        * +-----------------------------------------------------------------------
        * | Handle Form Callback
        * +-----------------------------------------------------------------------
        */
        if ( $form->validate($handle) ) {
            $event_invite_members = ( isset($handle['event_invite_members']) && sizeof($handle['event_invite_members']) != 0 ) ? $handle['event_invite_members'] : array();
            
            //if ( sizeof($event_invite_members) != 0 ) {
                $arrTmpEmails = explode(',',$strEmails);
                $arrEmails = array();
                if ( sizeof($arrTmpEmails) != 0 ) {
                    foreach ( $arrTmpEmails as $item ) {
                        if ( trim($item) != '' ) $arrEmails[] = trim($item);
                    }
                }
                
                foreach ( $event_invite_members as $memberId ) {
                    $tmpUser = new Warecorp_User('id', $memberId);
                    if ( null !== $tmpUser->getId() ) {
                        if ( !in_array($tmpUser->getLogin(), $arrEmails) && !in_array($tmpUser->getEmail(), $arrEmails) ) {
                            $arrEmails[] = $tmpUser->getLogin();
                        }
                    }
                }
                
                if ( sizeof($lstMembers) != 0 ) {
                    foreach ( $lstMembers as &$member ) {
                        if ( !in_array($member->getId(), $event_invite_members) ) {
                            $arrNewEmails = array();
                            foreach ( $arrEmails as $item ) {
                                if ( $item != $member->getLogin() && $item != $member->getEmail() ) {
                                    $arrNewEmails[] = $item;
                                }
                            }
                            $arrEmails = $arrNewEmails;
                        }
                    }
                }
                
                $strEmails = join(', ',$arrEmails);            
                $objResponse->addAssign('inv_emails', 'value', $strEmails);
            //}
            $objResponse->addScript('popup_window.close();');
        }
    }
