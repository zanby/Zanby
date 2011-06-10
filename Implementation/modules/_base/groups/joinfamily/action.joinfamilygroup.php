<?php
    Warecorp::addTranslation('/modules/groups/joinfamily/action.joinfamilygroup.php.xml');
    unset($_SESSION['joinfamily']);
    if ( $this->_page->_user->getId() === null || $this->currentGroup->getGroupType() != 'family' ) { $this->_redirectToLogin();}
    
    $this->_redirect($this->currentGroup->getGroupPath('joinfamilystep0'));