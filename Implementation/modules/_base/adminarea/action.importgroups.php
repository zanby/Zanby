<?php
Warecorp::addTranslation('/modules/adminarea/action.importgroups.php.xml');
$this->_page->Xajax->registerUriFunction("changeCountry", "/ajax/changeCountry/");
$this->_page->Xajax->registerUriFunction("changeState",   "/ajax/changeState/");

// set place where uploads import file
$doc_path = BASE_URL."/upload/import/import_groups_".$this->admin->getLogin().'_';
//	if ($form->isPostback())

if (!isset($this->params['result'])) {
// form 1  	
	$form = new Warecorp_Form('iuForm', 'POST', $this->admin->getAdminPath('importgroups/result/1'));
	$template = 'adminarea/importgroups.tpl';	
	$defaultSet = new Warecorp_User('id', $this->admin->getId());
	
	if ($form->isPostback()) {
		
	} else {
		$this->params['country'] = $defaultSet->getCountry()->id;		
		$this->params['state'] = $defaultSet->getState()->id;
		$this->params['city'] = $defaultSet->getCity()->id;		
	}
	$country = $this->params['country'];
	$state = $this->params['state'];
	$city = $this->params['city'];		
    
    $allCategoriesObj = new Warecorp_Group_Category_List();
    $allCategories = $allCategoriesObj->returnAsAssoc()->getList();    

	$countries = Warecorp_Location::getCountriesListAssoc(true);
	$country = Warecorp_Location_Country::create($country);
	$states = $country->getStatesListAssoc(true);
	$state = Warecorp_Location_State::create($state);
	$cities = $state->getCitiesListAssoc(true);
	$city = Warecorp_Location_City::create($city);
//	$timezones = new Warecorp_Location_Timezone();
	
	$this->view->countries = $countries;
	$this->view->states = $states;
	$this->view->cities = $cities;
    $this->view->categories = $allCategories;

	$this->view->country = $this->params['country'];
	$this->view->state =   $this->params['state'];
	$this->view->city =    $this->params['city'];	

	$this->view->form = $form;
	$this->view->defaultSet = $defaultSet;
	$this->view->group_names = '';
	$this->view->is_show_warnings = false;
	$this->view->is_join_groups = false;
	
} elseif($this->params['result']==1) {
// form 2 import accept 	

	$form = new Warecorp_Form('iuForm', 'POST', $this->admin->getAdminPath('importgroups/result/2'));
	$template = 'adminarea/importgroupsres.tpl';	
	require_once (WARECORP_DIR.'Group/Import/ImportGroups.php');
	$imp = new Warecorp_Import_Groups();
	$imp->setAdminName($this->admin->getLogin());
	// uploading	
	//*****************	
    if($imp->uploadCSV()) {
	// Upload successfully
    ////////////////// 
	// PARSE header	
	//////////////////
	$imp->open5();
	$imp->getHead();

		while (!$imp->inEOF()){
          	$loadstr = $imp->readNext();
        	$onestr = $imp->getOneStr();
           	if(strlen($onestr)<2) continue;
           	$imp->incRowNum();
           	$imp->parseStr(true);
				//					
//
// undefined fields filled by default values					
			if(!$imp->getStrField('city_id')){
				$imp->setStrField('city_id',$this->params['city']);
			}
			
			if(!$imp->getStrField('country_id')){
				$imp->setStrField('country_id',$this->params['country']);
			}
			if(!$imp->getStrField('zip')){
				$imp->setStrField('zip',isset($this->params['zipcode'])?$this->params['zipcode']:'');
			}
// group_name is important !
//			$email = $imp->getStrField('email');
            $group_name = $imp->getStrField('group_name');
            $name_err = true;
			if(!$group_name || empty($group_name)) {
				$imp->addErr('group_name');
			} elseif (strlen($group_name)>255) {
				$imp->addErr('group_name');
			} elseif($imp->isGroupExist($group_name)){
                $imp->addErr('group_name',Warecorp::t('Group name already exist'));
            } else {
                $name_err = false;
                if(!$imp->getStrField('group_email_prefix')) {
                    $imp->setStrField('group_email_prefix', str_replace(' ','-',$imp->getStrField('group_name')));
                }
                if(!$imp->getStrField('headline')) {
                    $imp->setStrField('headline', $imp->getStrField('group_name'));
                }
                if(!$imp->getStrField('members_name')) {
                    $imp->setStrField('members_name', $imp->getStrField('group_name'));
                }
                if(!$imp->getStrField('description')) {
                    $imp->setStrField('description', $imp->getStrField('group_name'));
                }
            }

            if(!$imp->getStrField('tags')) {
                $imp->setStrField('tags', '');
            }
            // set default join mode
            if(($imp->getStrField('join')!=='anyone') && ($imp->getStrField('join')!=='approve') && ((strtolower(substr($imp->getStrField('join'),0,5))!=='code:'))) {
               if($this->params['hjoin']==0) {
                   $imp->setStrField('join','anyone');
               } elseif($this->params['hjoin']==1) {
                   $imp->setStrField('join','approve');
               } else {
                   $imp->setStrField('join','code:'.$this->params['jcode']);
               }
            }
            
            if(!$imp->isLoginExist($imp->getStrField('host_login'))) {
                $imp->addErr('host_login',Warecorp::t('Host login not found'));
            }          
            if($imp->isEmailPrefixExist($imp->getStrField('group_email_prefix'))) {
                $imp->addErr('group_email_prefix',Warecorp::t('Group email prefix name already exist'));
            }  
            
            $category_id = false;
            if($imp->getStrField('category')) {
                $category_id = $imp->getCategoryId($category = $imp->getStrField('category'));
            } 
            if($category_id===false) {
                $category_id = isset($this->params['categoryId'])?$this->params['categoryId']:0;
            }
            $imp->setStrField('category_id', $category_id);
            
            $imp->setStrField('is_private',isset($this->params['is_private'])?'1':'0');

				
			$imp->writeRes();
        }
		// NEXT
		
        if(isset($this->params['is_join_groups']) && isset($this->params['group_names'])) {
        	$families = explode(',',$this->params['group_names']);
        	$families_ok = array();
        	foreach($families as $value) {
        		$value = trim($value);
        		if($imp->isFamily($value)) {
        			$families_ok[] = $value; 		
        		}
        	}
        	if(count($families_ok)>0) {
        		$group_names = implode(',',$families_ok);
        	} else {
        		$group_names = '';
        	}
        } else {
        	$group_names = '';
        }
	
		$rec_succ = $imp->getRowNum()-$imp->getRecErr();
		
        $this->view->group_names = $group_names;
		
		$this->view->path_err = $doc_path.'err.csv';
		$this->view->path_res = $doc_path.'res.csv';
		$this->view->path_rej = $doc_path.'rej.csv';
		$this->view->path_warn = $doc_path.'warn.csv';
		
		$this->view->rec_total = $imp->getRowNum();
		$this->view->rec_err = $imp->getRecErr();
		$this->view->rec_succ = $rec_succ;
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
//	$this->params['send_notifications'] = isset($this->params['send_notifications'])?1:0;
	
	$template = 'adminarea/importgroupsok.tpl';
	require_once (WARECORP_DIR.'Group/Import/ImportGroups.php');
	$imp = new Warecorp_Import_Groups();
	$imp->setAdminName($this->admin->getLogin());
	$imp->openRes();
	$imp->getHeadRes();
		while (!$imp->resEOF()){
          	$loadstr = $imp->readNextRes();
        	$onestr = $imp->getOneStr();
           	if(strlen($onestr)<2) continue;
			$imp->incRowNum();
			$imp->parseStr();
            if(!$imp->isGroupExist($imp->getStrField('group_name'))) {
                $Group = new Warecorp_Group_Simple();
                $Group->setGroupType('simple');
                $Group->setCategoryId( $imp->getStrField('category_id'));
                $Group->setName($imp->getStrField('group_name'));
                $Group->setPath($imp->getStrField('group_email_prefix'));
                $Group->setMembersName($imp->getStrField('members_name'));
                //$Group->setHeadline($imp->getStrField('headline'));                     
                $Group->setDescription($imp->getStrField('description'));                     
                // todo ADD default value is_private
                $Group->setIsPrivate($imp->getStrField('is_private'));
                //
                if($imp->getStrField('join')==='anyone') {
                    $Group->setJoinMode(0); 
                } elseif($imp->getStrField('join')==='approve') {
                    $Group->setJoinMode(1); 
                } elseif(strtolower(substr($imp->getStrField('join'),0,5))==='code:') {
                    $Group->setJoinMode(2); 
                    $join_code = trim(substr($imp->getStrField('join'),5));
                    $Group->setJoinCode($join_code);
                }
                $Group->setGroupPaymentType( 'business' );
                $Group->setJoinNotifyMode(1);
                $Group->setImportedGroup(1);
                
	        	$country = Warecorp_Location_Country::create($imp->getStrField('country_id'));
		        if ( $imp->getStrField('country_id') == 1 || $imp->getStrField('country_id') == 38 ) {
		            $locationInfo = $country->getZipcodeByACInfo($imp->getStrField('zip'));
		            $Group->setZipcode($locationInfo['zipcode']);
		            $Group->setCityId($locationInfo['city_id']);
		        } else {
		            $Group->setCityId($imp->getStrField('city_id'));
		            $Group->setZipcode('');
		        }
                
                $city = Warecorp_Location_City::create($Group->getCityId());
                $Group->setLatitude( $city->getLatitude() );
                $Group->setLongitude( $city->getLongitude() );
                $Group->save();
                
                $user = new Warecorp_User("login",$imp->getStrField('host_login'));
                $Group->getMembers()->addMember($user->getId(),'host');                            
                
	            // GROUP save 
		        

                
				//JOIN TO GROUPS	
                
                
                
                	        
				if(isset($this->params['group_names'])) {
				//
//				Zend_Debug::dump($this->params['group_names']);
					$families = explode(',',$this->params['group_names']);
		        	$families_ok = array();
		        	foreach($families as $value) {
		        		$value = trim($value);
		        		if($imp->isFamily($value)) {
//		        			$families_ok[] = $value;
		        			$family = Warecorp_Group_Factory::loadByName($value,Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);
                            $family->getGroups()->addGroup($Group->getId(), "active");
		        		}
		        	}
		        }
                
            // exist group    
			}
            
		}
	$imp->closeRes();
    
    $this->appendLog('groups',0,'import'); 
    
	$rec_succ = $imp->getRowNum();
	$this->view->path_res = $doc_path.'res.csv';
	$this->view->rec_succ = $rec_succ;

	
}elseif($this->params['result']==4) {
	/*
	 * ***************************
	 * todo:
	 * !!! delete users !!! only in test version !!!
	 * *************************** 
 	*/
	$form = new Warecorp_Form('iuForm', 'POST', $this->admin->getAdminPath('importgroups/result/2'));
	$template = 'adminarea/index.tpl';
	$db = Zend_Registry::get("DB");
    $query = $db->select();
    $query->from('zanby_groups__items g', 'g.id');
    $query->where('g.imported_group = ?',1);
    $res = $db->fetchCol($query);
    foreach($res as $_g) {
		$del_group = new Warecorp_Group_Simple('id',$_g);
		$del_group->loadByPk($_g);
		$del_group->delete();
	}
    $db->delete('zanby_groups__items', $db->quoteInto('imported_group = ?',1));
	Zend_Debug::dump(count($res).' DELETED');
}

$this->view->bodyContent = $template;
