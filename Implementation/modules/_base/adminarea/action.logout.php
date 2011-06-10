<?php
// save LOG
$this->appendLog('',0,'logout'); 
$this->admin->logout();
$this->_redirect('http://'.BASE_HTTP_HOST.'/'.$this->_page->Locale.'/adminarea/login/');