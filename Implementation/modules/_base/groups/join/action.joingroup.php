<?php
Warecorp::addTranslation('/modules/groups/join/action.joingroup.php.xml');

    if ( $this->_page->_user->getId() === null || $this->currentGroup->getGroupType() != 'simple' ) {
        $this->_redirectToLogin();
    }

    $this->_page->Xajax->registerUriFunction ("sendMessage", "/groups/joinSendMessageToHost/");
    $this->_page->Xajax->registerUriFunction ("sendMessageDo", "/groups/joinSendMessageToHost/");

    $_template = 'groups/join/joingroup.tpl';
    $form = new Warecorp_Form('join_form', 'POST', $this->currentGroup->getGroupPath('joingroup'));

    $attempt = $this->currentGroup->getJoinAttempt($this->_page->_user);
    $attemptLimit = 5; // attempts join with code
    $attemptPause = 10; // min group locked for user

    $lockGroup = false;
    if ( isset($attempt['attempts']) && $attempt['attempts']%($attemptLimit-1) == 0
    && $attempt['seconds']<$attemptPause*60 && $this->currentGroup->getJoinMode() == 2) {
        $lockGroup = true;
    }
    if ( $form->isPostback() && !$lockGroup) {
        //  Validate Data
        //________________________________________
        switch ( $this->currentGroup->getJoinMode() ) {
            case 0 :    //  Anyone
                if ( isset($this->params['SendAndJoin']) ) {
                    $form->addRule('subject', 'required',  Warecorp::t('Enter please subject'));
                    $form->addRule('text', 'required',  Warecorp::t('Enter please text'));
                }
                break;
            case 1 :    //  Only those I approve
                $form->addRule('subject', 'required',  Warecorp::t('Enter please subject'));
                $form->addRule('text', 'required',  Warecorp::t('Enter please text'));
                break;
            case 2 :    //  Only those with a following code
                $_params = array('group_id'=>$this->currentGroup->getId(),
                                 'join_code'=>isset($this->params['join_code']) ? $this->params['join_code'] : '');
                $form->addRule('join_code', 'callback',  Warecorp::t('Enter please valid join code'),
                               array('func' => 'Warecorp_Form_Validation::isJoinCodeValid', 'params' => $_params));
                $this->currentGroup->saveJoinAttempt($this->_page->_user->getId());
                break;
        }

        //  Handle Data
        //________________________________________
        if ( $form->validate($this->params) ) {
            if ( !$this->currentGroup->getMembers()->isMemberExists($this->_page->_user->getId()) ) {
                switch ( $this->currentGroup->getJoinMode() ) {
                    case 0 : //так надо  без брейка
                    case 2 :
                        $this->currentGroup->getMembers()->addMember($this->_page->_user->getId(), 'member', 'approved');
                        $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
                        $cache->remove('all_mygroups_menu_account_tools_'.$this->_page->_user->getId());
                        $this->currentGroup->sendGroupJoinNewMember( $this->currentGroup, $this->_page->_user, $this->params['subject'], $this->params['text'], isset($this->params['SendAndJoin']) ? true : false );
                        break;
                    case 1 :
                        $this->currentGroup->getMembers()->addMember($this->_page->_user->getId(), 'member', 'pending');
                        $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
                        $cache->remove('all_mygroups_menu_account_tools_'.$this->_page->_user->getId());
                        $this->currentGroup->sendGroupJoinRequest( $this->currentGroup, $this->_page->_user, $this->params['subject'], $this->params['text'] );
                        break;
                }
                $_SESSION['join_group'] = true;

                /**
                 * Discussion settings
                 */
                $subscription = Warecorp_DiscussionServer_GroupSubscription::findByGroupAndUserId($this->currentGroup->getId(), $this->_page->_user->getId());
                $subscription->setSubscriptionMode($this->params['digest_type']);
                $this->params['digest_type_value_all'] = ( !isset($this->params['digest_type_value_all']) ) ? 1 : $this->params['digest_type_value_all'];
                $subscription->setSubscriptionType($this->params['digest_type_value_all']);
                $subscription->setGroupAsOne((isset($this->params['group_as_one'])) ? 1 : 0);
                $subscription->update();

                $this->currentGroup->resetJoinAttempt($this->_page->_user->getId());
                $this->_redirect($this->currentGroup->getGroupPath('joinsuccess'));
            } else {
                $this->_redirect($this->currentGroup->getGroupPath('summary'));
            }

        }
    } elseif ($lockGroup) {
        $_template = 'groups/join/attempts.exceeded.tpl';
    }

    $this->view->subscribeContentOptions = Warecorp_DiscussionServer_Enum_SubscriptionType::getAsOptions();


// @todo - remove this block
//    $_url = BASE_URL."/".LOCALE."/groups/search/";
//    $this->_page->breadcrumb = empty($this->_page->breadcrumb) ? array() : $this->_page->breadcrumb;
//    $this->_page->breadcrumb[$this->currentGroup->getCountry()->name]   = $_url."preset/country/id/{$this->currentGroup->getCountry()->id}/";
//    $this->_page->breadcrumb[$this->currentGroup->getState()->name]     = $_url."preset/state/id/{$this->currentGroup->getState()->id}/";
//    $this->_page->breadcrumb[$this->currentGroup->getCity()->name]      = $_url."preset/city/id/{$this->currentGroup->getCity()->id}/";
//    $this->_page->breadcrumb[$this->currentGroup->getCategory()->name]  = $_url."preset/category/id/{$this->currentGroup->getCategory()->id}/city/{$this->currentGroup->getCity()->id}/";
//    $this->_page->breadcrumb[$this->currentGroup->getName()]            = $this->currentGroup->getGroupPath('summary');
//    $this->_page->breadcrumb['Join'] = null;

    $this->view->form = $form;
    $this->view->CurrentGroup = $this->currentGroup;
    $this->view->attemptLimit = $attemptLimit;
    $this->view->attemptPause = $attemptPause;

    $this->view->subject = (isset($this->params['subject'])) ? $this->params['subject'] : null;
    $this->view->text = (isset($this->params['text'])) ? $this->params['text'] : null;
    $this->view->join_code = (isset($this->params['join_code'])) ? $this->params['join_code'] : null;

    $this->view->digest_type = (isset($this->params['digest_type'])) ? $this->params['digest_type'] : 2;
    $this->view->digest_type_value_all = (isset($this->params['digest_type_value_all'])) ? $this->params['digest_type_value_all'] : 5;

    $this->view->bodyContent = $_template;


