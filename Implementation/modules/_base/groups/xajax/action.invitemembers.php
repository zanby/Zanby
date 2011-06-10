<?php
Warecorp::addTranslation('/modules/groups/xajax/action.invitemembers.php.xml');

    $objResponse = new xajaxResponse();  
    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->width(450)->height(400);
    
    $form = new Warecorp_Form('form_step5', 'POST', $this->currentGroup->getGroupPath('invitemembers'));
    if (isset($params['_wf__form_step5'])) $_REQUEST['_wf__form_step5'] = $params['_wf__form_step5'];
    if ($form->isPostback())
    {
        $params['emails'] = trim($params['emails']);
        $params['message'] = trim($params['message']);
        $params['subject'] = trim($params['subject']);
        $emails = explode(',',$params['emails']);                
        
        foreach($emails as $key=>&$value) {
            $value = trim($value);
            if (!empty($value))
                if (!Warecorp_User::isUserExists('login', $value))
                    $form->addRule($key,    'email', Warecorp::t("%s is not correct recipient",$value));
        }
        $paramstovalidate = array_merge($params, $emails);
        $form->addRule('emails',    'required', Warecorp::t("Please enter addresses to send invitation"));
        $form->addRule('message',   'required', Warecorp::t("Please fill message area"));
        $form->addRule('subject',   'required', Warecorp::t("Please enter subject"));
        if ( $form->validate($paramstovalidate)) {            
            $this->currentGroup->sendInviteMembers( $params['mail'] == 1 ? $this->_page->_user : $this->currentGroup, $this->_page->_user, $params['emails'], $params['subject'], $params['message'] );                        
            $objResponse->addScript('popup_window.close();');
        } else $popup_window->width(450)->height(500);        
    }
    $this->view->form = $form; 
    $this->view->group = $params; 
    $this->view->currentUser = $this->_page->_user;  
    $template = 'groups/invitemembers.tpl';
    $Content = $this->view->getContents ( $template ) ;
      
    $popup_window->title(Warecorp::t("Invite new members to join your group"));
    $popup_window->content($Content);
    $popup_window->open($objResponse);
