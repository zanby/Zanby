<?php
    $user = Zend_Registry::get("User");
    $user = new Warecorp_User();
    $user->logout();
    
    if (isset($_SESSION['login_return_page'])) {
        unset($_SESSION['login_return_page']);
    }
    
    if ( WP_SSO_ENABLED && Warecorp_Wordpress_SSO::isWordpressSiteEnabled() ) {
        if (defined('HTTP_CONTEXT') && HTTP_CONTEXT == 'zccf') {
            $this->_redirect(WP_SSO_URL.'?zssodoaction=signout&ret='.urlencode(WP_SSO_URL));
        } else {
            $this->_redirect(WP_SSO_URL.'?zssodoaction=signout&ret='.urlencode(BASE_URL.'/'.$this->_page->Locale.'/'));
        }
    } else {
        $this->_redirect(BASE_URL.'/'.$this->_page->Locale.'/');
    }
    
    
