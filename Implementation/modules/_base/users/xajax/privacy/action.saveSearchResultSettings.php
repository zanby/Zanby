<?php
    Warecorp::addTranslation("/modules/users/xajax/privacy/action.saveSearchResultSettings.php.xml");
    $objResponse = new xajaxResponse();

    if ( $this->currentUser->getId() !== $this->_page->_user->getId() ) {
        $objResponse->addRedirect($this->currentUser->getUserPath('profile'));
    } else {
        $form = new Warecorp_Form('srForm', 'post', 'javascript:void(0);');

        if (isset($params['_wf__srForm'])) {
            $_REQUEST['_wf__srForm'] = $params['_wf__srForm'];
        }
        $params['sr_radio'] = isset($params['sr_radio']) ? floor($params['sr_radio']) : 0;
        $params['sr_radio'] = in_array($params['sr_radio'], array(0,1,2)) ? $params['sr_radio'] : 0;

        $privacy = $this->_page->_user->getPrivacy();

        $privacy->setSrAnyone           ( $params['sr_radio'] == 2 ? 1 : 0 )
                ->setSrAnyMembers       ( $params['sr_radio'] == 1 ? 1 : 0 )
                ->setSrGroupOrganizers  ( !empty($params['sr_radio']) || empty($params['sr_group_organizers']) ? 0 : 1 )
                ->setSrMyGroupOrganizers( !empty($params['sr_radio']) || empty($params['sr_my_group_organizers']) ? 0 : 1 )
                ->setSrMyGroupMembers   ( !empty($params['sr_radio']) || empty($params['sr_my_group_members']) ? 0 : 1 )
                ->setSrMyFriends        ( !empty($params['sr_radio']) || empty($params['sr_my_friends']) ? 0 : 1 )
                ->setSrMyNetwork        ( !empty($params['sr_radio']) || empty($params['sr_my_network']) ? 0 : 1 )
                ->setSrMyAddressBook    ( !empty($params['sr_radio']) || empty($params['sr_my_address_book']) ? 0 : 1 )
                ->setSrViewProfilePhoto ( !empty($params['sr_view_profile_photo']) ? 1 : 0 )
                ->setSrViewSendMessage  ( !empty($params['sr_view_send_message']) ? 1 : 0 )
                ->setSrViewAddToFriend  ( !empty($params['sr_view_add_to_friend']) ? 1 : 0 )
                ->setSrViewMyFriends    ( !empty($params['sr_view_my_friends']) ? 1 : 0 );

        if ($form->validate($params)){
            $this->view->sr_view = 'collapsed';
            $privacy->save();
            $objResponse->showAjaxAlert(Warecorp::t('Changes saved'));
        } else {
            $this->view->sr_view = 'expanded';
        }

        $this->view->form = $form;
        $this->view->assign($params);
        $this->view->privacy = $privacy;

        $output = $this->view->getContents('users/privacy.searchResultSettings.tpl');
        $objResponse->addClear("searchResultSettings_Content", "innerHTML");
        $objResponse->addAssign("searchResultSettings_Content",'innerHTML', $output);
        $objResponse->addScript('TitltPaneAppsearchResultSettings.hide();');
        $objResponse->addScriptCall("innerHTMLScript","sr");		
    }

