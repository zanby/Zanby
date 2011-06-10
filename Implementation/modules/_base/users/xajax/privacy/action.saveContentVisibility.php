<?php
    Warecorp::addTranslation("/modules/users/xajax/privacy/action.saveContentVisibility.php.xml");
    $objResponse = new xajaxResponse();
    if ( $this->currentUser->getId() !== $this->_page->_user->getId() ) {
        $objResponse->addRedirect($this->currentUser->getUserPath('profile'));
    } else {
        $form = new Warecorp_Form('cvForm', 'post', 'javascript:void(0);');

        if (isset($params['_wf__cvForm'])) {
            $_REQUEST['_wf__cvForm'] = $params['_wf__cvForm'];
        }
        $params['cv_radio'] = isset($params['cv_radio']) ? floor($params['cv_radio']) : 0;
        $params['cv_radio'] = in_array($params['cv_radio'], array(0,1,2)) ? $params['cv_radio'] : 0;

        $privacy =  $this->_page->_user->getPrivacy();
        $cvSelectOptions = Warecorp_User_Privacy_Enum_PublicMeans::getPublicMeansAssoc();
        
        $privacy->setCvAnyone           ( $params['cv_radio'] == 2 ? 1 : 0 )
                ->setCvAnyMembers       ( $params['cv_radio'] == 1 ? 1 : 0 )
                ->setCvGroupOrganizers  ( !empty($params['cv_radio']) || empty($params['cv_group_organizers']) ? 0 : 1 )
                ->setCvMyGroupOrganizers( !empty($params['cv_radio']) || empty($params['cv_my_group_organizers']) ? 0 : 1 )
                ->setCvMyGroupMembers   ( !empty($params['cv_radio']) || empty($params['cv_my_group_members']) ? 0 : 1 )
                ->setCvMyFriends        ( !empty($params['cv_radio']) || empty($params['cv_my_friends']) ? 0 : 1 )
                ->setCvMyNetwork        ( !empty($params['cv_radio']) || empty($params['cv_my_network']) ? 0 : 1 )
                ->setCvMyAddressBook    ( !empty($params['cv_radio']) || empty($params['cv_my_address_book']) ? 0 : 1 )
                ->setCvPublicPhotos     ( isset($cvSelectOptions[$params['cv_public_photos']]) ? floor($params['cv_public_photos']) : 1 )
                ->setCvPublicLists      ( isset($cvSelectOptions[$params['cv_public_lists']]) ? floor($params['cv_public_lists']) : 1 )
                ->setCvPublicDocuments  ( isset($cvSelectOptions[$params['cv_public_documents']]) ? floor($params['cv_public_documents']) : 1 )
                ->setCvPublicEvents     ( isset($cvSelectOptions[$params['cv_public_events']]) ? floor($params['cv_public_events']) : 1 )
                ->setCvPublicTags       ( isset($cvSelectOptions[$params['cv_public_tags']]) ? floor($params['cv_public_tags']) : 1 )
                ->setCvPublicFriends    ( isset($cvSelectOptions[$params['cv_public_friends']]) ? floor($params['cv_public_friends']) : 1 )
                ->setCvPublicVideos     ( isset($cvSelectOptions[$params['cv_public_videos']]) ? floor($params['cv_public_videos']) : 1 );

        if ($form->validate($params)){
            $this->view->cv_view = 'collapsed';
            $privacy->save();
            $objResponse->showAjaxAlert(Warecorp::t('Changes saved'));
        } else {
            $this->view->cv_view = 'expanded';
        }

        $this->view->form = $form;
        $this->view->assign($params);
        $this->view->privacy = $privacy;
        $this->view->cvSelectOptions = $cvSelectOptions;


        $output = $this->view->getContents('users/privacy.contentVisibility.tpl');
        $objResponse->addClear("contentVisibility_Content", "innerHTML");
        $objResponse->addAssign("contentVisibility_Content",'innerHTML', $output);
        $objResponse->addScript('TitltPaneAppcontentVisibility.hide();');
        $objResponse->addScriptCall("innerHTMLScript", "cv");

    }

