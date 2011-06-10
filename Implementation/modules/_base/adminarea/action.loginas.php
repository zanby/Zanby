<?php
//  
//  Login as selected user
//  
if (isset ( $this->params ['id'] )) {
	
	$user = new Warecorp_User ( 'id', $this->params ['id'] );
	$user->authenticate ();
	// save LOG
    $this->appendLog($user->getLogin(),$this->params['id'],'loginas'); 
	
	$this->_redirect ( $user->getUserPath ( "profile" ) );
} else {
	$this->_redirect('http://'.BASE_HTTP_HOST.'/'.$this->_page->Locale.'/adminarea/index/');
}
    
