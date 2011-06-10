<?php
    Warecorp::addTranslation("/modules/ajax/action.loginavailable.php.xml");
    $objResponse = new xajaxResponse();
    $text = '';
    if (!empty($login)) {
        $form = new Warecorp_Form('loginFormValidation');
        $_REQUEST['_wf__' . 'loginFormValidation'] = 1;
        $params['login'] = $login;
        $form->addRule('login',        'required',      '');
        $form->addRule('login',        'maxlength',     '', array('max' => 50));
        $form->addRule('login',        'callback',      '', array('func' => 'Warecorp_Form_Validation::isLoginExist', 'params' => $login));
        $form->addRule('login',        'alphanumeric',  '');
        $form->addRule('login',        'minlength',     '', array('min' => 3));

        $text = (!$form->validate($params))?'<label style="color:red;">'.Warecorp::t('UNAVAILABLE').'</label>':'<label style="color:green;">'.Warecorp::t('AVAILABLE').'</label>';
    }
    $objResponse->addAssign("loginavailable", "innerHTML", $text);