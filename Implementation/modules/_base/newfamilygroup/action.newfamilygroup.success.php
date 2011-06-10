<?php
    Warecorp::addTranslation('/modules/newfamilygroup/action.newfamilygroup.success.php.xml');

    if ( !isset($_SESSION['newfamilygroup']) || empty($_SESSION['newfamilygroup']) ) $this->_redirect($this->_page->_user->getUserPath('profile'));
    
    $Group = new Warecorp_Group_Simple('id', $_SESSION['newfamilygroup']);
    $this->view->group = $Group;
    $this->view->bodyContent = 'newfamilygroup/success.tpl';
    
    unset($_SESSION['newfamilygroup']);
    