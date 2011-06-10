<?php
    Warecorp::addTranslation("/modules/users/xajax/action.saveAccountCancel.php.xml");

if ( $this->currentUser->getId() !== $this->_page->_user->getId() ) {
    $this->_redirect($this->currentUser->getUserPath('profile'));
}

$form = new Warecorp_Form('acForm', 'post', 'javasript:void(0);');
if (isset($params['_wf__acForm'])) {
    $_REQUEST['_wf__acForm'] = $params['_wf__acForm'];
}
$form->addRule('confirm',        'required',  Warecorp::t('Confirm account deleting'));

$objResponse = new xajaxResponse();

$redirect = false;
if ($form->validate($params)){

    /**
     * we cant update login field in user object, created as new User('login', 'blah').
     * so create user object by UserID
     */
    $user = new Warecorp_User('id', $this->currentUser->getId());
	$user->delete();
	
/*    $user->setStatus('deleted');
    $user->save();*/
    $user->logout();
    //problem with setcookie in xajax response
    $objResponse->addRedirect(BASE_URL.'/'.$this->_page->Locale.'/');
    return;

} else {
    $this->view->visibility = true;
}

$this->view->edituser = $this->currentUser;
$this->view->form = $form;
$Content = $this->view->getContents('users/settings.accountCancel.tpl');

$objResponse->addClear( "AccountCancel_Content", "innerHTML" );
$objResponse->addAssign( "AccountCancel_Content", "innerHTML", $Content );
if ($redirect === true)
$objResponse->addRedirect(BASE_URL);
