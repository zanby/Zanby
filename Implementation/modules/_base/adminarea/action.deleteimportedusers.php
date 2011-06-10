<?php
$this->_page->Xajax->registerUriFunction("changeCountry", "/ajax/changeCountry/");
$this->_page->Xajax->registerUriFunction("changeState",   "/ajax/changeState/");
// set place where uploads import file
$doc_path = BASE_URL."/upload/import/import_members_".$this->admin->getLogin().'_';

//	if ($form->isPostback())
require_once (WARECORP_DIR.'User/Import/ImportMembers.php');

if (isset($this->params['id']) ) {
	/*
	 * ***************************
	 * !!! delete users !!! 
	 * *************************** 
 	*/
    $date_imp = trim($this->params['id']);
    if(strlen($date_imp)>10) {

        $del_path = $this->admin->getAdminPath('deleteimportedusers/id/').$date_imp;
        $cancel_path = $this->admin->getAdminPath('importmembers/');
        $form = new Warecorp_Form('iuForm', 'POST', $del_path);
        
	    $template = 'adminarea/deleteimportedusers.tpl';
	        $db = Zend_Registry::get("DB");
            $query = $db->select()
                        ->from('zanby_users__accounts u', 'u.id')
                        ->where('u.imported_user = ?',$date_imp);
            $res = $db->fetchCol($query);
            $rec_count = count($res);
        if($form->isPostback()) {
            foreach($res as $_u) {
		        $del_user = new Warecorp_User('id',$_u);
    //		    $del_user->loadByPk($_u);
		        $del_user->delete();
	        }
            $db->delete('zanby_users__accounts', $db->quoteInto('imported_user = ?',$date_imp));
                $this->view->deleted = $rec_count;
                
            $this->appendLog('members',0,'delete'); 
        } else {
            $this->view->deleted = 0;
        }
        $this->view->rec_count = $rec_count;
        $this->view->cancel_path = $cancel_path;
        $this->view->form = $form;
    }
}

$this->view->bodyContent = $template;
