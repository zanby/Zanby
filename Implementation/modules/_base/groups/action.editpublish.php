<?php
Warecorp::addTranslation('/modules/groups/action.editpublish.php.xml');

if ($this->_page->_user->getMembershipPlan() == 'free'){//free account cant use publishing
     $this->_redirectToLogin();
}


$this->view->menuContent = '';

$form = new Warecorp_Form('editpublish', 'POST', $this->currentGroup->getGroupPath('editpublish'));

$form->addRule('ftp_server',   'required', Warecorp::t('Input ftp server address'));
$form->addRule('ftp_username', 'required', Warecorp::t('Input ftp username'));
//$form->addRule('ftp_password', 'required', 'Input ftp password');
$form->addRule('desturl1',     'required', Warecorp::t('Input Destination Url1'));
$form->addRule('filename',     'required', Warecorp::t('Input Filename'));

if ( $form->isPostback()) {

    if ( !$form->validate($this->params) ) $form->setValid(false);
    if ( $form->isValid() ) {

        $settings = new Warecorp_Group_Publish("group_id", $this->currentGroup->getId());   
        $settings->setGroupId($this->currentGroup->getId());
        $settings->setFtpServer($this->params['ftp_server']);
        $settings->setFtpMode(isset($this->params['ftp_mode'])?$this->params['ftp_mode']:0);
        $settings->setFtpUsername($this->params['ftp_username']);
        $settings->setFtpPassword($this->params['ftp_password']);
        $settings->setFtpFolder($this->params['ftp_folder']);
        $settings->setDesturl($this->params['desturl1']);
        $settings->setFilename($this->params['filename']);
        $settings->save();

        if (isset($this->params['publishnow'])){
            $this->_redirect('/'.LOCALE.'/editpublishstatus/update/yes/');
        }
    }
}

$settings = $this->currentGroup->getPublishSettings();

$values = array();
$values['ftp_server']       = (isset($this->params['ftp_server']))   ? $this->params['ftp_server']   : $settings->getFtpServer();
$values['ftp_mode']         = (isset($this->params['ftp_mode']))     ? $this->params['ftp_mode']     : 0;
$values['ftp_username']     = (isset($this->params['ftp_username'])) ? $this->params['ftp_username'] : $settings->getFtpUsername();
$values['ftp_password']     = (isset($this->params['ftp_password'])) ? $this->params['ftp_password'] : $settings->getFtpPassword();
$values['ftp_folder']       = (isset($this->params['ftp_folder']))   ? $this->params['ftp_folder']   : $settings->getFtpFolder();
$values['desturl1']         = (isset($this->params['desturl1']))     ? $this->params['desturl1']     : $settings->getDesturl();
$values['filename']         = (isset($this->params['filename']))     ? $this->params['filename']     : $settings->getFilename();
$values['publishnow']       = (isset($this->params['publishnow']))   ? $this->params['publishnow']   : "";

$this->view->setLayout('main_wide.tpl');


$this->view->form = $form;
$this->view->values = $values;
$this->view->action = 'editpublish';
$this->view->bodyContent = 'groups/editpublish.tpl';
$this->view->isRightBlockHidden = true;
/**/

