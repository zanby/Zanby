<?php
Warecorp::addTranslation('/modules/groups/join/action.joinsuccess.php.xml');

    if ( !isset($_SESSION['join_group']) ) {
        $this->_redirect($this->currentGroup->getGroupPath('summary'));
    }
    unset($_SESSION['join_group']);

    if ( FACEBOOK_USED ) {
        $params = array(
            'title' => htmlspecialchars($this->currentGroup->getName()), 
            'orgname' => htmlspecialchars(SITE_NAME_AS_STRING)
        );
        $action_links[] = array('text' => 'View Group', 'href' => $this->currentGroup->getGroupPath('summary/'));
        $objMessage = Warecorp_Facebook_Feed::getStreamActionMessage(Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_JOIN_GROUP, $params);    
        Warecorp_Facebook_Feed::postStream($objMessage, null, $action_links);
        
    }

    
// @todo - remove this block    
//    $_url = BASE_URL."/".LOCALE."/groups/search/";
//    $this->_page->breadcrumb = empty($this->_page->breadcrumb) ? array() : $this->_page->breadcrumb;
//    $this->_page->breadcrumb[$this->currentGroup->getCountry()->name] = $_url."preset/country/id/{$this->currentGroup->getCountry()->id}/";  
//    $this->_page->breadcrumb[$this->currentGroup->getState()->name] = $_url."preset/state/id/{$this->currentGroup->getState()->id}/";  
//    $this->_page->breadcrumb[$this->currentGroup->getCity()->name] = $_url."preset/city/id/{$this->currentGroup->getCity()->id}/";  
//    $this->_page->breadcrumb[$this->currentGroup->getCategory()->name] = $_url."preset/category/id/{$this->currentGroup->getCategory()->id}/city/{$this->currentGroup->getCity()->id}/";  
//    $this->_page->breadcrumb[$this->currentGroup->getName()] = $this->currentGroup->getGroupPath('summary');
//    $this->_page->breadcrumb['Join'] = null;

    $this->view->currentUser = $this->_page->_user;
    $this->view->bodyContent = 'groups/join/joinsuccess.tpl';
