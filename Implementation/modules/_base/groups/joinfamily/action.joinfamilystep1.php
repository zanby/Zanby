<?php
    if ( isset($_SERVER['HTTPS'])) { $this->_redirect($this->currentGroup->getGroupPath('joinfamilystep1')); }

    Warecorp::addTranslation('/modules/groups/joinfamily/action.joinfamilystep1.php.xml');
    
    $this->_page->Xajax->registerUriFunction("changeListGroups", "/groups/changeListGroups/");
    $this->_page->Xajax->registerUriFunction("formulaPopup", "/groups/formulaPopup/");
    $this->_page->Xajax->registerUriFunction("pricesPopup", "/groups/pricesPopup/");
    
    $form = new Warecorp_Form('join_form', 'POST', $this->currentGroup->getGroupPath('joinfamilystep1'));
    
    if ( $form->isPostback() ) {
        /* Validate Data */
        if ( !isset($this->params['groupId']) || !is_array($this->params['groupId']) ){
            $form->setValid(false);
            $form->addCustomErrorMessage(Warecorp::t("Select at least one group"));
        }    
        if ($this->currentGroup->getJoinMode() == "2"){
            if ( !isset($this->params['join_code']) || !$this->currentGroup->checkJoinCode($this->params['join_code']) ) {
                $form->setValid(false);
                $form->addCustomErrorMessage(Warecorp::t("Code Incorrect"));
            }
        }
    
        /* Handle Data */
        if ( $form->isValid() ) {
            $groupId = isset($this->params['groupId'])?$this->params['groupId']:0;    
            $subject = isset($this->params['subject']) ? trim($this->params['subject']) : "";
            $text = isset($this->params['text']) ? trim($this->params['text']) : "";
    
            $_SESSION['joinfamily']['group_id'] = $groupId;
            $_SESSION['joinfamily']['subject'] = $subject;
            $_SESSION['joinfamily']['text'] = $text;
            $_SESSION['joinfamily']['step1'] = true;   
    
            if (!isset($_SESSION["tempData"]["lastStep"]) || $_SESSION["tempData"]["lastStep"] <1) $_SESSION["tempData"]["lastStep"] = 1;
            if (($_SESSION["tempData"]["lastStep"]+1) === 2) $this->currentGroup->setUsePathParamsMode();
            $this->_redirect($this->currentGroup->getGroupPath('joinfamilystep'.($_SESSION["tempData"]["lastStep"]+1), true, HTTPS_ENABLED) );
        }
    }
    
    
    $family =Warecorp_Group_Factory::loadById($this->currentGroup->getId(),Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);
    $currentGroups = $family->getGroups()->setChildren()->setTypes(array('simple'))->returnAsAssoc()->setStatus(Warecorp_Group_Enum_GroupStatus::GROUP_STATUS_BOTH)->getList();
    $currentGroups = array_keys($currentGroups);
    $currentGroups[] = $family->getId();
    
    //changed by Komarovski in according with bug#7020
    $groups = $this->_page->_user->getGroups()->returnAsAssoc()->setMembersRole(array('host', 'cohost'))->setTypes('simple')->setExcludeIds($currentGroups)->getList();
    
    $steps['step1'] = (isset($_SESSION['joinfamily']['step1'])) ? true : false;
    $steps['step2'] = (isset($_SESSION['joinfamily']['step2'])) ? true : false;
    $steps['step3'] = (isset($_SESSION['joinfamily']['step3'])) ? true : false;
    
    $groupId = isset($_SESSION['joinfamily']['group_id']) ? $_SESSION['joinfamily']['group_id'] : (isset($this->params['groupId']) ? $this->params['groupId'] : array());
    $subject = isset($_SESSION['joinfamily']['subject']) ? $_SESSION['joinfamily']['subject'] : (isset($this->params['subject']) ? $this->params['subject'] : "");
    $text = isset($_SESSION['joinfamily']['text']) ? $_SESSION['joinfamily']['text'] : (isset($this->params['text']) ? $this->params['text'] : "");
    
    $groupsId = is_array($groupId)?array_values($groupId):array();    
    foreach ($groups as $gid=>&$g) { $g = Warecorp_Group_Factory::loadById($gid,Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE); }
    
    $this->view->form = $form;
    $this->view->steps = $steps;
    $this->view->groupId = $groupId;
    $this->view->groupsId = $groupsId;
    $this->view->subject = $subject;
    $this->view->text = $text;
    $this->view->CurrentGroup = $this->currentGroup;    
    $this->view->groups = $groups;
    $this->view->join_code = (isset($this->params['join_code'])) ? $this->params['join_code'] : null;
    $this->view->bodyContent = 'groups/joinfamily/step1.tpl';
