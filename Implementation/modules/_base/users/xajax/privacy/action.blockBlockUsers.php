<?php
    Warecorp::addTranslation("/modules/users/xajax/privacy/action.blockBlockUsers.php.xml");

    if ( $this->currentUser->getId() !== $this->_page->_user->getId() ) {
        $objResponse->addRedirect($this->currentUser->getUserPath('profile'));
    } else {
        $objResponse = new xajaxResponse();

        $form = new Warecorp_Form('buForm', 'post', 'javascript:void(0);');
        $privacy = $this->_page->_user->getPrivacy();

        if (isset($params['_wf__buForm'])) {
            $_REQUEST['_wf__buForm'] = $params['_wf__buForm'];
        }

        $login = $params['bu_login'] = isset($params['bu_login']) ? $params['bu_login'] : "";
        $form->addRule('bu_login', 'required', Warecorp::t('Enter please Username'));

        if ($form->validate($params)) {
            $user = new Warecorp_User('login', $params['bu_login']);
            if (!$user->getId()) {
                $params['bu_login'] = "error";
                $form->addRule('bu_login', 'numeric', Warecorp::t('Sorry. Unrecognized username'));
                $form->validate($params);
            } elseif ($privacy->getBlockList()->isExist($user)) {
                $params['bu_login'] = "error";
                $form->addRule('bu_login', 'numeric', Warecorp::t('User already blocked'));
                $form->validate($params);
            } elseif ($user->getId() == $this->_page->_user->getId()){
                $params['bu_login'] = "error";
                $form->addRule('bu_login', 'numeric', Warecorp::t("Sorry. You can't block yourself"));
                $form->validate($params);
            } else {
                $privacy->getBlockList()->add($user);
                $login = "";

            }
        }

        $this->view->privacy = $privacy;
        $this->view->form = $form;
        $this->view->bu_view = 'expanded';
        $this->view->bu_login = $login;

        $output = $this->view->getContents('users/privacy.blockUsers.tpl');
        $objResponse->addClear("blockUsers_Content", "innerHTML");
        $objResponse->addAssign("blockUsers_Content",'innerHTML', $output);

        $objResponse->addScript('var myAutoComp = new YAHOO.widget.AutoComplete("buLogin", "acLogins", myDataSource);');
    }
