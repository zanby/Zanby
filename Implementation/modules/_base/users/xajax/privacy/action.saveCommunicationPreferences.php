<?php
    Warecorp::addTranslation("/modules/users/xajax/privacy/action.saveCommunicationPreferences.php.xml");
    $objResponse = new xajaxResponse();

    if ( $this->currentUser->getId() !== $this->_page->_user->getId() ) {
        $objResponse->addRedirect($this->currentUser->getUserPath('profile'));
    } else {
        $form = new Warecorp_Form('cpForm', 'post', 'javascript:void(0);');
        $form->addRule('cp_any_members', 'required',  '');

        if (isset($params['_wf__cpForm'])) {
            $_REQUEST['_wf__cpForm'] = $params['_wf__cpForm'];
        }

        if ($form->validate($params)){
            $this->view->cp_view = 'collapsed';
            $privacy = $this->_page->_user->getPrivacy();

            $privacy->setCpAnyMembers       ( empty($params['cp_any_members']) ? 0 : 1 )
                    ->setCpGroupOrganizers  ( !empty($params['cp_any_members']) || empty($params['cp_group_organizers']) ? 0 : 1 )
                    ->setCpMyGroupOrganizers( !empty($params['cp_any_members']) || empty($params['cp_my_group_organizers']) ? 0 : 1 )
                    ->setCpMyGroupMembers   ( !empty($params['cp_any_members']) || empty($params['cp_my_group_members']) ? 0 : 1 )
                    ->setCpMyFriends        ( !empty($params['cp_any_members']) || empty($params['cp_my_friends']) ? 0 : 1 )
                    ->setCpMyNetwork        ( !empty($params['cp_any_members']) || empty($params['cp_my_network']) ? 0 : 1 );
                    
            $privacy->save();
            $objResponse->showAjaxAlert(Warecorp::t('Changes saved'));

        } else {
            $this->view->cp_view = 'expanded';
        }

        $this->view->form = $form;
        $this->view->assign($params);
        $this->view->privacy = $privacy;

        $output = $this->view->getContents('users/privacy.communicationPreferences.tpl');
        $objResponse->addClear("communicationPreferences_Content", "innerHTML");
        $objResponse->addAssign("communicationPreferences_Content",'innerHTML', $output);
        $objResponse->addScript('TitltPaneAppcommunicationPreferences.hide();');
        $objResponse->addScriptCall("innerHTMLScript", "cp");		
    }

