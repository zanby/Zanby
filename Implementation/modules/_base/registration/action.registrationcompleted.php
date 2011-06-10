<?php

    Warecorp::addTranslation('/modules/registration/action.registrationcompleted.php.xml');

   /**
    * Redmine bug #2991
    * @author Roman Gabrusenok
    */
    if (isset($_SESSION['login_return_page'])) {
        $url = $_SESSION['login_return_page'];
        $parsed_url = parse_url($url);
        $condition=(strstr($parsed_url['host'], BASE_HTTP_HOST) === BASE_HTTP_HOST);
        if ($condition) {
            $_SESSION['login_return_page'] = null;
            unset($_SESSION['login_return_page']);
            $this->_redirect($url);
        }
    }

    $this->_page->setTitle(Warecorp::t('Registration Completed'));
    $this->view->bodyContent = 'registration/registration_completed.tpl';
