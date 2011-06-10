<?php
    Warecorp::addTranslation('/modules/groups/joinfamily/action.joinfamilystep0.php.xml');
    
    if ( $this->_page->_user->getId() === null || $this->currentGroup->getGroupType() != 'family' ) { $this->_redirectToLogin(); }
    
    if (isset($_SESSION['joinfamily'])) $_SESSION['joinfamily'] = array();    
    $_SESSION["tempData"]["lastStep"] = 0;    
    $this->view->group = $this->currentGroup;    
    $this->view->bodyContent = 'groups/joinfamily/step0.tpl';

