<?php

//	if ($form->isPostback())
require_once (WARECORP_DIR.'User/Import/ImportMembers.php');
/* temporary. should be in the core soon. */
require_once (PRODUCT_MODULES_DIR.'adminarea/_import_users_helpers.php');

// set place where uploads import file
$tmname = time();
//Zend_Debug::dump($tmname);
$doc_path = BASE_URL."/upload/import/import_members_".$this->admin->getLogin();

$this->_page->Xajax->registerUriFunction("changeCountry", "/ajax/changeCountry/");
$this->_page->Xajax->registerUriFunction("changeState",   "/ajax/changeState/");

if (!isset($this->params['result'])) {
    // form 1
    $form = new Warecorp_Form('iuForm', 'POST', $this->admin->getAdminPath('importmembers/result/1'));
    $template = 'adminarea/importmembers.tpl';
    $defaultSet = new Warecorp_User('id', $this->admin->getId());
    $imp = new Warecorp_Import_Members();
    //    $imp->setTmName($tmname);
    $transactions = $imp->getImportedTransactions();
    $trans_count = count($transactions);
    if ($form->isPostback()) {

    } else {
        $this->params['country'] = $defaultSet->getCountry()->id;
        $this->params['state'] = $defaultSet->getState()->id;
        $this->params['city'] = $defaultSet->getCity()->id;
    }
    $country = $this->params['country'];
    $state = $this->params['state'];
    $city = $this->params['city'];

    $countries = Warecorp_Location::getCountriesListAssoc(true);
    $country = Warecorp_Location_Country::create($country);
    $states = $country->getStatesListAssoc(true);
    $state = Warecorp_Location_State::create($state);
    $cities = $state->getCitiesListAssoc(true);
    $city = Warecorp_Location_City::create($city);
    $timezones = new Warecorp_Location_Timezone();

    $this->view->countries = $countries;
    $this->view->states = $states;
    $this->view->cities = $cities;
    $this->view->time_zones = $timezones->getZanbyTimezonesNamesAssoc();
    $this->view->country = $this->params['country'];
    $this->view->state =   $this->params['state'];
    $this->view->city =    $this->params['city'];
    $this->view->transactions = $transactions;
    $this->view->trans_count = $trans_count;

    $this->view->form = $form;
    $this->view->defaultSet = $defaultSet;
    $this->view->group_names = '';
    $this->view->is_show_warnings = false;
    $this->view->is_join_groups = false;

} elseif($this->params['result']==1) {
    // form 2 import accept

    $form = new Warecorp_Form('iuForm', 'POST', $this->admin->getAdminPath('importmembers/result/2'));
    $template = 'adminarea/importmembersres.tpl';
    $imp = new Warecorp_Import_Members();
    $imp->setTmName($tmname);
    $imp->setAdminName($this->admin->getLogin());
    // uploading
    //*****************
    if($imp->uploadCSV()) {
        // Upload successfully
        //////////////////
        // PARSE header
        //////////////////
        importMembersParseCSVForErrors( $imp, $this->params);

        if(isset($this->params['is_join_groups']) && isset($this->params['group_names'])) {
            $groups = explode(',',$this->params['group_names']);
            $groups_ok = array();
            foreach($groups as $value) {
                $value = trim($value);
                if($imp->isGroupSimple($value)) {
                    $groups_ok[] = $value;
                }
            }
            if(count($groups_ok)>0) {
                $group_names = implode(',',$groups_ok);
            } else {
                $group_names = '';
            }
        } else {
            $group_names = '';
        }

        $doc_path = $doc_path.$tmname.'_';
        $rec_succ = $imp->getRowNum()-$imp->getRecRej();
        $rec_create = $imp->getRowNum()-$imp->getRecJoin()-$imp->getRecRej();

        $this->view->group_names = $group_names;

        $this->view->tmname = $tmname;

        $this->view->path_err = $doc_path.'err.csv';
        $this->view->path_res = $doc_path.'res.csv';
        $this->view->path_rej = $doc_path.'rej.csv';
        $this->view->path_warn = $doc_path.'warn.csv';
        $this->view->path_mx = $doc_path.'mx.csv';

        $this->view->rec_total = $imp->getRowNum();
        $this->view->rec_err = $imp->getRecErr();
        $this->view->rec_join = $imp->getRecJoin();
        $this->view->rec_join = $imp->getRecRej();
        $this->view->rec_succ = $rec_succ;
        $this->view->rec_create = $rec_create;
        $this->view->is_show_warn = isset($this->params['is_show_warnings']);
        $this->view->rec_warn = $imp->getRecWarn();
        $this->view->allstr = $imp->getAllStr();
        $this->view->allerr = $imp->getAllErr();
        $this->view->allwarn = $imp->getAllWarn();
        $this->view->hdn = $imp->getHdn();
        $this->view->form = $form;

        $imp->close5();
    } else {
        // Upload ERROR
    }
}elseif($this->params['result']==2) {
    /*
     * CREATE USERS
     */
    $this->params['send_notifications'] = isset($this->params['send_notifications'])?1:0;

/*     	Zend_Debug::dump($this->params['activate_now']); */
/*     	Zend_Debug::dump($this->params['send_notifications']); */

    $template = 'adminarea/importmembersok.tpl';
    require_once (WARECORP_DIR.'User/Import/ImportMembers.php');
    $imp = new Warecorp_Import_Members();
    $tmname = $this->params['tmname'];
    $imp->setTmName($tmname);
    $imp->setAdminName($this->admin->getLogin());
    importMembersAddUsers( $imp, $this->params);
    $all_added = importMembersJoinGroups( $imp, $this->params);
//die;

    $this->appendLog('members',0,'import');

    $rec_succ = $imp->getRowNum();
    $doc_path = $doc_path.$tmname.'_';

    $this->view->path_res = $doc_path.'res.csv';
    $this->view->rec_succ = $rec_succ;
    $this->view->rec_added = ($imp->getRecAdded()>$all_added)?$imp->getRecAdded():$all_added;

}

$this->view->bodyContent = $template;
