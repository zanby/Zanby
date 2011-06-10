<?php
    Warecorp::addTranslation("/modules/users/addressbook/action.addcontact.php.xml");
    $this->_page->Xajax->registerUriFunction("changeCountry", "/ajax/changeCountry/");
    $this->_page->Xajax->registerUriFunction("changeState",   "/ajax/changeState/");

    $_url = $this->_page->_user->getUserPath('addressbookaddcontact');
    $item = array();
    $addZanbyblock = '';
    $locationText = Warecorp::t('Add New Contact');
    if (isset($this->params['id'])) {
    	$addZanbyblock = 'none';
        $detail = Warecorp_User_Addressbook_Factory::loadById($this->params['id']);
    	//$detail = new Warecorp_User_Addressbook_CustomUser('id', $this->params['id']);
        if ($detail->isExist) {
            if ($detail->getClassName() == Warecorp_User_Addressbook_eType::CUSTOM_USER) {
	        	$item['firstName'] = $detail->getFirstName();
	            $item['lastName'] = $detail->getLastName();
	            $item['email'] = $detail->getEmail();
	            $item['email2'] = $detail->getEmailSecondary();
	            $item['phoneBusiness'] = $detail->getPhoneBusiness();
	            $item['phoneHome'] = $detail->getPhoneHome();
	            $item['phoneMobile'] = $detail->getPhoneMobile();
	            $item['zip'] = $detail->getZipCode();
	            $item['street'] = $detail->getStreet();
	            $item['city'] = $detail->getCity();
	            $item['state'] = $detail->getState();
	            $item['country'] = $detail->getCountry();
	            $item['notes'] = $detail->getNotes();
            } /*elseif ($detail->getClassName() == Warecorp_User_Addressbook_eType::USER) {
            	$item['firstName'] = $detail->getUser()->getFirstname();
	            $item['lastName'] = $detail->getUser()->getLastname();
	            $item['email'] = $detail->getUser()->getEmail();
	            $item['zip'] = $detail->getUser()->getZip();
	            $item['city'] = $detail->getUser()->getCityId();
	            $item['state'] = $detail->getUser()->getState()->id;
	            $item['country'] = $detail->getUser()->getCountry()->id;
            } */else {
            	$this->_redirect($this->currentUser->getUserPath("addressbook"));
            }

            if (!isset($this->params["country"])) $this->params["country"] = $detail->getCountry();
            if (!isset($this->params["state"])) $this->params["state"] = $detail->getState();
            if (!isset($this->params["city"])) $this->params["city"] = $detail->getCity();
            $locationText = $item['firstName'] . ' ' . $item['lastName'];
        } else {
            $this->_redirect($this->currentUser->getUserPath("addressbook"));
        }
        $_url .= 'id/' . $this->params['id'] . '/';
    } 
    if (!isset($this->params["country"])) $this->params["country"] = 0;
    if (!isset($this->params["state"])) $this->params["state"] = 0;
    if (!isset($this->params["city"])) $this->params["city"] = 0;
    
    $location = new Warecorp_Location();
    $countries = $location->getCountriesListAssoc(true);
    $this->view->countries = $countries;
    
    $country = Warecorp_Location_Country::create($this->params["country"]);
    $states = $country->getStatesListAssoc(true);
    $this->view->states = $states;
    
    $state = Warecorp_Location_State::create($this->params["state"]);
    $cities = $state->getCitiesListAssoc(true);
    $this->view->cities = $cities;
    
    $city = Warecorp_Location_City::create($this->params["city"]);
    
    $form = new Warecorp_Form('newContact', 'post', $_url);
    if (!isset($this->params['addZanby'])) {
	    $form->addRule('firstName',    'required',  Warecorp::t('Enter please First Name'));
	    $form->addRule('firstName',    'maxlength', Warecorp::t('First Name too long (max %s)', 50), array('max' => 50));
	    $form->addRule('firstName',    'regexp',    Warecorp::t('First Name must start with letter'), array('regexp' => "/^[a-zA-Z]{1}/"));
	    $form->addRule('firstName',    'regexp',    Warecorp::t('First Name may consist of a-Z, 0-9, \', -, underscores, space, and dot (.)'), array('regexp' => "/^[a-zA-Z]{1}[a-zA-Z0-9_'\s\-\.]{0,}$/"));
	    $form->addRule('lastName',     'required',  Warecorp::t('Enter please Last Name'));
	    $form->addRule('lastName',     'maxlength', Warecorp::t('Last Name too long (max %s)', 50), array('max' => 50));
	    $form->addRule('lastName',     'regexp',    Warecorp::t('Last Name must start with letter'), array('regexp' => "/^[a-zA-Z]{1}/"));
	    $form->addRule('lastName',     'regexp',    Warecorp::t('Last Name may consist of a-Z, 0-9, \', -, underscores, space, and dot (.)'), array('regexp' => "/^[a-zA-Z]{1}[a-zA-Z0-9_'\s\-\.]{0,}$/"));
	    $form->addRule('email',        'required',  Warecorp::t('Enter please Email Address'));
	    if (!empty($this->params['email']))  
        $form->addRule('email',        'email',     Warecorp::t('Enter please correct Email Address'));
        $form->addRule('email',        'maxlength', Warecorp::t('Email too long (max %s)', 255), array('max' => 255));
	    if (!empty($this->params['email2']))
        $form->addRule('email2',        'email',     Warecorp::t('Enter please correct Secondary Email Address'));
        $form->addRule('email2',        'maxlength', Warecorp::t('Secondary Email too long (max %s)', 255), array('max' => 255));
	    if (!empty($this->params['phoneBusiness']))
        $form->addRule('phoneBusiness',     'phone', Warecorp::t('Enter please correct Business phone'));
        $form->addRule('phoneBusiness',     'maxlength', Warecorp::t('Business phone too long (max %s)',30), array('max' => 30));
	    if (!empty($this->params['phoneHome']))
        $form->addRule('phoneHome',     'phone', Warecorp::t('Enter please correct Home phone'));
        $form->addRule('phoneHome',     'maxlength', Warecorp::t('Home phone too long (max %s)', 30), array('max' => 30));
	    if (!empty($this->params['phoneMobile']))
	        $form->addRule('phoneMobile',   'phone', Warecorp::t('Enter please correct Mobile phone'));
	        $form->addRule('phoneMobile',     'maxlength', Warecorp::t('Mobile phone too long (max %s)', 30), array('max' => 30));
	    if (!empty($this->params['zip']))
	        $form->addRule('zip',     'numeric', Warecorp::t('Enter please correct Zip Code'));
	        $form->addRule('zip',     'maxlength', Warecorp::t('Zip Code too long (max %s)', 8), array('max' => 8));
	    if (!empty($this->params['street']))
	        $form->addRule('street',    'maxlength', Warecorp::t('Street too long (max %s)', 50), array('max' => 50));
	    if (!empty($this->params['notes']))
	        $form->addRule('notes',    'maxlength', Warecorp::t('Street too long (max %s)', 65535), array('max' => 65535));
    } else {
    	$form->addRule('username',    'required',  Warecorp::t('Enter please User Name'));
    	if (!empty($this->params['username'])) {
    		$newuser = new Warecorp_User('login', $this->params['username']);
    		if ($newuser->getId() === null) {
    			$form->setValid(false);
    			$form->addCustomErrorMessage(Warecorp::t('Enter please valid User Name'));
    		}
/*        	$userContactInAB = new Warecorp_User_Addressbook_User();
        	$userContactInAB->setContactOwnerId($this->_page->_user->getId());
        	$userContactInAB->setEntityId($newuser->getId());
*/        	
        	if (Warecorp_User_Addressbook_User::loadByEntityId($newuser->getId(), $this->_page->_user->getId())) {
    			$form->setValid(false);
    			$form->addCustomErrorMessage(Warecorp::t('User already in Addressbook'));
        	}
    	}
    }
    if ($form->validate($this->params)) {    	
        if (isset($this->params['id']))
            $contact = new Warecorp_User_Addressbook_CustomUser('id', $this->params['id']);
        elseif (isset($this->params['addZanby'])) {
        	$userContact = new Warecorp_User('login', $this->params['username']);
	        $contact = new Warecorp_User_Addressbook_User();	        
	        $contact->setContactOwnerId($this->_page->_user->getId());
	        $contact->setUserId($userContact->getId());
	        $contact->save();
	        $this->_page->showAjaxAlert(Warecorp::t('Saved'));
	        $this->_redirect($this->currentUser->getUserPath("addressbook"));
        } 
        if (!isset($contact))
        	$contact = new Warecorp_User_Addressbook_CustomUser();
    	$contact->setContactOwnerId($this->_page->_user->getId());
    	$contact->setFirstName($this->params['firstName']);
    	$contact->setLastName($this->params['lastName']);
    	$contact->setEmail($this->params['email']);
    	$contact->setEmailSecondary($this->params['email2']);
    	$contact->setPhoneBusiness($this->params['phoneBusiness']);
    	$contact->setPhoneHome($this->params['phoneHome']);
        $contact->setPhoneMobile($this->params['phoneMobile']);
        $contact->setCity($this->params['city']);
        $contact->setState($this->params['state']);
        $contact->setStreet($this->params['street']);
        $contact->setZipCode($this->params['zip']);
        $contact->setCountry($this->params['country']);
        $contact->setNotes($this->params['notes']);
        $contact->save();
        $this->_page->showAjaxAlert(Warecorp::t('Saved'));
        $this->_redirect($this->currentUser->getUserPath("addressbook"));
    } else {
        $this->view->items = array_merge($item, $this->params);
    }
    
    $contactLists = Warecorp_User_Addressbook_ContactList::getContactLists($this->_page->_user->getId());
    //addressbookList
    $this->view->addZanbyBlock = $addZanbyblock;
    $this->view->locationText = $locationText;
    $this->view->contactLists = $contactLists;
    $this->view->contacts = $this->_page->_user->getAddressbook()->getContacts();
    $this->view->form = $form;
    $this->view->ButtonName = isset($this->params['id'])?Warecorp::t('Change') : Warecorp::t('Add');
    $this->view->bodyContent = 'users/addressbook/addcontact.tpl';
