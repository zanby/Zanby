<?php
    Warecorp::addTranslation('/modules/groups/xajax/action.doTransfer.php.xml');

    $objResponse = new xajaxResponse();
    
    if ( $this->currentGroup->getId() === null || $this->_page->_user->getId() === null ) {
        $objResponse->addRedirect("/");
        return $objResponse;
    }
    
    if (isset($params['_wf__transferForm'])) $_REQUEST['_wf__transferForm'] = $params['_wf__transferForm'];
    
    $form = new Warecorp_Form('transferForm', 'POST', 'javasript:void(0);');
    $form->addRule('new_owner', 'required', Warecorp::t('Please, enter Username or email address of the person who will own the family'));
    $form->addRule('message_subject', 'maxlength', Warecorp::t('Message Subject is too long (max %s)', array(255)), array('max' => 255));
    $form->addRule('message_body', 'maxlength', Warecorp::t('Message Body is too long (max %s)', array(2000)), array('max' => 2000));
    
    
    if ( $form->validate($params) ) {
        $flLogin = $flEmail = false;
        if ( !$flLogin = Warecorp_User::isUserExists('login', $params['new_owner']) && !$flEmail = Warecorp_User::isUserExists('email', $params['new_owner']) ) {
            $form->addCustomErrorMessage( Warecorp::t("Sorry, '%s' is not a valid %s username.",array ($params['new_owner'], SITE_NAME_AS_STRING)) );
            $this->view->assign($params);
            $objResponse = $this->showTransferAction( $form );
            return $objResponse;
        }
        
        $objUser = $flLogin ? new Warecorp_User('login', $params['new_owner']) : new Warecorp_User('email', $params['new_owner']);
        if ( $objUser->getId() == $this->_page->_user->getId() ) {
            $form->addCustomErrorMessage( Warecorp::t("You are Host already." ) );
            $this->view->assign($params);
            $objResponse = $this->showTransferAction( $form );
            return $objResponse;
            
        }
        
        $req = new Warecorp_Group_Resign_Requests();
        $req->setGroupId($this->currentGroup->getId());
        $req->setUserId($objUser->getId());
        $req->save();
        
        $this->currentGroup->sendResignRequestNewHost( $this->currentGroup, $objUser, md5($req->getId()), $params['message_subject'], $params['message_body'] );
        
        $this->view->visibility = true;
        $this->view->objUser = $objUser;
        $Content = $this->view->getContents('groups/settings.transferaccount.confirm.tpl');
        $objResponse->addClear( 'GroupSettingsTransfer_Content', "innerHTML" );
        $objResponse->addAssign( 'GroupSettingsTransfer_Content', "innerHTML", $Content );

    } else {
        $this->view->assign($params);
        $objResponse = $this->showTransferAction( $form );
    }
