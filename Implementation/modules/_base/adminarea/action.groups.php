<?php
    Warecorp::addTranslation('/modules/adminarea/action.groups.php.xml');
    $this->_page->Xajax->registerUriFunction("changeCountry", "/ajax/changeCountry/");
    $this->_page->Xajax->registerUriFunction("changeState",   "/ajax/changeState/");
    $items_per_page = 50;
    $this->params['page'] = isset($this->params['page'])?$this->params['page']:1;
    
    if ( isset($this->params['ajax_mode']) ) {
        $objResponse = new xajaxResponse();
        switch ( $this->params['ajax_mode'] ) {
            /**
             * Remove Group : Group List View
             */
            case 'delete' :
                if ( isset($this->params['groups']) && trim($this->params['groups']) ) {
                    $groups = explode(',', $this->params['groups']);
                    if ( sizeof($groups) ) {
                        foreach ( $groups as $groupID ) {
                            $objGroup = Warecorp_Group_Factory::loadById($groupID,Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
                            if ( $objGroup && $objGroup->getId() ) {
                                /** If group have any value in groupUID then group is special and can't be empty **/
                                /** According to the bug #6543 **/
                                if ( !trim($objGroup->getGroupUID()) ) {
                                    $objGroup->delete();
                                } else {
                                    
                                }
                            }
                        }
                    }
                }
                $popup_window = Warecorp_View_PopupWindow::getInstance();
                $popup_window->close($objResponse);
                $objResponse->addScript('document.location.reload();');
                break;
            /**
             * Join Group to family : Group List View
             */
            case 'joinfamily' :
                if ( isset($this->params['family']) && trim($this->params['family']) ) {
                    $objFamily = Warecorp_Group_Factory::loadById($this->params['family'],Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);
                    if ( $objFamily && $objFamily->getId() && $objFamily instanceof Warecorp_Group_Family ) {                         
                        if ( isset($this->params['groups']) && trim($this->params['groups']) ) {
                            $groups = explode(',', $this->params['groups']);
                            if ( sizeof($groups) ) {
                                foreach ( $groups as $groupID ) {
                                    $objGroup = Warecorp_Group_Factory::loadById($groupID,Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
                                    if ( $objGroup && $objGroup->getId() && $objGroup instanceof Warecorp_Group_Simple ) {
                                        $objFamily->getGroups()->addGroup($objGroup->getId(), 'active');
                                    }
                                }
                            }
                        }
                    }
                }
                $popup_window = Warecorp_View_PopupWindow::getInstance();
                $popup_window->close($objResponse);
                break;
            /**
             * Change Host of Group : Group Details View
             */
            case 'change_host' : 
                $this->params['newHost'] = ( isset($this->params['newHost']) && trim($this->params['newHost']) ) ? $this->params['newHost'] : null;
                if ( null !== $this->params['newHost'] ) {
                    $objUser = new Warecorp_User('login', $this->params['newHost']);
                    if ( $objUser && $objUser->getId() && $objUser->getStatus() == 'active' ) {
                        if ( isset($this->params['group']) && trim($this->params['group']) ) {
                            $objGroup = Warecorp_Group_Factory::loadById($this->params['group'],Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
                            if ( $objGroup && $objGroup->getId() ) {
                                if ( $objGroup->getMembers()->isMemberExistsAndApproved($objUser->getId()) ) {
                                    $objGroup->getMembers()->changeHost($objUser->getId());
                                    $objResponse->addScript('GDApplication.onChangeHostCancel();');
                                    $objResponse->addScript('document.location.reload();');
                                } else {
                                    $objResponse->addScript("$('#plhChangeHostErrorsMessage').html('".Warecorp::t('User is not member of current group. Please use autocomplete to choose user.')."');");
                                    $objResponse->addScript("$('#newHost').addClass('prFormErrors');");
                                    $objResponse->addScript("$('#plhChangeHostErrors').show();");                                        
                                }                                             
                            }
                        }
                    } else {
                        $objResponse->addScript("$('#plhChangeHostErrorsMessage').html('".Warecorp::t('Username you entered is incorrect.')."');");
                        $objResponse->addScript("$('#newHost').addClass('prFormErrors');");
                        $objResponse->addScript("$('#plhChangeHostErrors').show();");    
                    }
                } else {
                    $objResponse->addScript("$('#plhChangeHostErrorsMessage').html('".Warecorp::t('Please enter username.')."');");
                    $objResponse->addScript("$('#newHost').addClass('prFormErrors');");
                    $objResponse->addScript("$('#plhChangeHostErrors').show();");                        
                }
                break;
            /**
             * Add Co-Host of Group : Group Details View
             */
            case 'add_co_host' : 
                $this->params['newCoHost'] = ( isset($this->params['newCoHost']) && trim($this->params['newCoHost']) ) ? $this->params['newCoHost'] : null;
                if ( null !== $this->params['newCoHost'] ) {
                    $objUser = new Warecorp_User('login', $this->params['newCoHost']);
                    if ( $objUser && $objUser->getId() && $objUser->getStatus() == 'active' ) {
                        if ( isset($this->params['group']) && trim($this->params['group']) ) {
                            $objGroup = Warecorp_Group_Factory::loadById($this->params['group'],Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
                            if ( $objGroup && $objGroup->getId() ) {
                                if ( $objGroup->getMembers()->isMemberExistsAndApproved($objUser->getId()) ) {
                                    if ( !$objGroup->getMembers()->isHost($objUser->getId()) ) {
                                        if ( !$objGroup->getMembers()->isCoHost($objUser->getId()) ) {
                                            $objGroup->getMembers()->setAsCohost($objUser->getId());
                                            $objResponse->addScript('document.location.reload();');                                        
                                        } else {
                                            $objResponse->addScript("$('#plhAddCoHostErrorsMessage').html('".Warecorp::t('User you entered is co-host of current group. Please use autocomplete to choose user.')."');");
                                            $objResponse->addScript("$('#newCoHost').addClass('prFormErrors');");
                                            $objResponse->addScript("$('#plhAddCoHostErrors').show();");
                                        }                                        
                                    } else {
                                        $objResponse->addScript("$('#plhAddCoHostErrorsMessage').html('".Warecorp::t('User you entered is host of current group. Please use autocomplete to choose user.')."');");
                                        $objResponse->addScript("$('#newCoHost').addClass('prFormErrors');");
                                        $objResponse->addScript("$('#plhAddCoHostErrors').show();");                                        
                                    }
                                } else {
                                    $objResponse->addScript("$('#plhAddCoHostErrorsMessage').html('".Warecorp::t('User is not member of current group. Please use autocomplete to choose user.')."');");
                                    $objResponse->addScript("$('#newCoHost').addClass('prFormErrors');");
                                    $objResponse->addScript("$('#plhAddCoHostErrors').show();");                                        
                                }                                             
                            }
                        }
                    } else {
                        $objResponse->addScript("$('#plhAddCoHostErrorsMessage').html('".Warecorp::t('Username you entered is incorrect.')."');");
                        $objResponse->addScript("$('#newCoHost').addClass('prFormErrors');");
                        $objResponse->addScript("$('#plhAddCoHostErrors').show();");    
                    }
                } else {
                    $objResponse->addScript("$('#plhAddCoHostErrorsMessage').html('".Warecorp::t('Please enter username.')."');");
                    $objResponse->addScript("$('#newCoHost').addClass('prFormErrors');");
                    $objResponse->addScript("$('#plhAddCoHostErrors').show();");                        
                }
                break;
            /**
             * Remove CoHost of Group : Group Details View
             */
            case 'remove_cohost' : 
                $this->params['cohost'] = ( isset($this->params['cohost']) && trim($this->params['cohost']) ) ? $this->params['cohost'] : null;
                if ( null !== $this->params['cohost'] ) {
                    $objUser = new Warecorp_User('id', $this->params['cohost']);
                    if ( $objUser && $objUser->getId() && $objUser->getStatus() == 'active' ) {
                        if ( isset($this->params['group']) && trim($this->params['group']) ) {
                            $objGroup = Warecorp_Group_Factory::loadById($this->params['group'],Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
                            if ( $objGroup && $objGroup->getId() ) {
                                if ( $objGroup->getMembers()->isCoHost($objUser->getId()) ) {
                                    $objGroup->getMembers()->setAsMember($objUser->getId()); 
                                }                                             
                            }
                        }
                    }
                }
                break;
            /**
             * Delete Group : Group Details View
             */
            case 'delete_group' : 
                if ( isset($this->params['group']) && trim($this->params['group']) ) {
                    $objGroup = Warecorp_Group_Factory::loadById($this->params['group'],Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
                    if ( $objGroup && $objGroup->getId() ) {
                        /** If group have any value in groupUID then group is special and can't be empty **/
                        /** According to the bug #6543 **/
                        if ( !trim($objGroup->getGroupUID()) ) {
                            $objGroup->delete();
                        } else {
                            
                        }
                    }
                }
                $popup_window = Warecorp_View_PopupWindow::getInstance();
                $popup_window->close($objResponse);
                $objResponse->addRedirect(BASE_URL.'/'.LOCALE.'/adminarea/groups/');
                break;
        }
        $objResponse->printXml($this->_page->Xajax->sEncoding); exit();
    }
    
    /**
     * +-------------------------------------------------------------------------
     * |    Group Details View/Edit
     * +-------------------------------------------------------------------------
     */
    if ( isset($this->params['id']) ) {
        
        $this->_page->Xajax->registerUriFunction("detectCountry", "/ajax/detectCountry/");
        $this->_page->Xajax->registerUriFunction("autoCompleteCity", "/ajax/autoCompleteCity/");
        $this->_page->Xajax->registerUriFunction("autoCompleteZip", "/ajax/autoCompleteZip/");
        $this->_page->Xajax->registerUriFunction("autoCompleteGroupMembers", "/ajax/autoCompleteGroupMembers/");
                
        $group = Warecorp_Group_Factory::loadById($this->params['id'],Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
        if ( $group->getGroupType() == "family" ) {
            $this->_redirect(BASE_URL.'/'.LOCALE.'/adminarea/families/id/'.$this->params['id'].'/');
            exit;
        }
        
        $form = new Warecorp_Form('gdForm', 'POST', $this->admin->getAdminPath('groups').'/id/'.$group->getId());
        $approveAllMembers = false;
        /**
         * Group Details Edit
         */
        if ( $form->isPostback() ) {   
            $form->addRule('gname',         'required',     Warecorp::t('Enter Group Name'));
            $form->addRule('gname',         'regexp',       Warecorp::t('Enter correct Group Name'), array('regexp' => "/^[a-zA-Z]{1}[a-zA-Z0-9_'\s\-\.]{0,}$/"));
            $form->addRule('gname',         'rangelength',  Warecorp::t('Enter correct Group Name'), array('min' => 3, 'max' => 255));
            $form->addRule('gname',         'callback',     Warecorp::t('Group with this name already exist'), array('func' => 'Warecorp_Form_Validation::isNewGroupExist', 'params' => array('gname'=>isset($this->params['gname'])?$this->params['gname']:null, 'excludeIds' => $group->getId())));
            
            $form->addRule('gemail',        'required',     Warecorp::t('Enter Group Address'));
            $form->addRule('gemail',        'maxlength',    Warecorp::t('Group Address too long (max 255)'), array('max' => 272));
            $form->addRule('gemail',        'regexp',       Warecorp::t('Enter correct Group Address'), array('regexp' => '/^[A-Za-z0-9]{1}[A-Za-z0-9\-]+@'.str_replace('.','\.',DOMAIN_FOR_GROUP_EMAIL).'$/'));
            $form->addRule('gemail',        'callback',     Warecorp::t('Group Address already exist'),array('func' => 'Warecorp_Form_Validation::isGroupExist', 'params' => isset($this->params['gemail'])?array('key' =>'group_path', 'value'=> $this->params['gemail'], 'exclude'=>$group->getPath()):null));
            
            $form->addRule('categoryId',    'nonzero',      Warecorp::t('Choose category'));
            
            $form->addRule('countryId',     'nonzero', Warecorp::t('Choose country'));
            $form->addRule('city',          'callback',  Warecorp::t('Enter please City'),
                array(
                    'func' => 'Warecorp_Form_Validation::isCityRequired',
                    'params' => array(
                        'countryId' => ((isset($this->params['countryId'])) ? $this->params['countryId'] : null),
                        'city' => ((isset($this->params['city'])) ? $this->params['city'] : null)
                    )
                )
            );
            $form->addRule('city',          'callback',      Warecorp::t('City name is incorrect. Choose it from autocomplete list.'),
                array(
                    'func' => 'Warecorp_Form_Validation::isCityInvalid',
                    'params' => array(
                        'countryId' => ((isset($this->params['countryId'])) ? $this->params['countryId'] : null),
                        'city' => ((isset($this->params['city'])) ? $this->params['city'] : null)
                    )
                )
            );
            $form->addRule('zipcode',       'callback',      Warecorp::t('Enter please Zip code'),
                array(
                    'func' => 'Warecorp_Form_Validation::isZipcodeRequired',
                    'params' => array(
                        'countryId' => ((isset($this->params['countryId'])) ? $this->params['countryId'] : null),
                        'zipcode' => ((isset($this->params['zipcode'])) ? $this->params['zipcode'] : null)
                    )
                )
            );
            $form->addRule('zipcode',       'callback',      Warecorp::t('Zip code is incorrect. Choose it from autocomplete list.'),
                array(
                    'func' => 'Warecorp_Form_Validation::isZipcodeInvalid',
                    'params' => array(
                        'countryId' => ((isset($this->params['countryId'])) ? $this->params['countryId'] : null),
                        'zipcode' => ((isset($this->params['zipcode'])) ? $this->params['zipcode'] : null)
                    )
                )
            );
            
            $form->addRule('description',   'required',     Warecorp::t('Enter Description'));
            $form->addRule('description',   'maxlength',    Warecorp::t('Enter correct Description'), array('max' => 2000));
    
            $this->changeField('Members_called',$group->getMembersName(),$this->params['membersName']);
            $group->setMembersName( $this->params['membersName'] );

            $this->changeField('Is_Private',$group->getIsPrivate()=='1'?'Yes':'No',($this->params['gtype']=='1') ? 'Yes' : 'No');
            $group->setIsPrivate( $this->params['gtype'] );

            $approveAllMembers = ($this->params['hjoin'] != 1 && $group->getJoinMode() == 1)?true:false;

            $city = Warecorp_Location_City::create($this->params['city']);
            $group->setLatitude( $city->getLatitude() );
            $group->setLongitude( $city->getLongitude() );
    
            $oldcatname = $group->getCategory()->name;
            $group->getCategory()->loadByPk($this->params['categoryId']);
            $group->setCategoryId( $this->params['categoryId'] );
            $this->changeField('Category', $oldcatname, $group->getCategory()->name);
    
            //$this->changeField('Zipcode',$group->getZipcode(),$this->params['zipId']);
            //$group->setZipcode( $this->params['zipId'] );
    
            /* check change City */
            //$oldcityname = $group->getCity()->name;
            //$group->getCity()->loadByPk($this->params['city']);
            //$group->setCityId( $this->params['city'] );
            //$this->changeField('City', $oldcityname, $group->getCity()->name);
    
            $this->changeField('Name',$group->getName(),$this->params['gname']);
            $group->setName( $this->params['gname'] );
    
            $this->changeField('Email',$group->getPath(),$this->params['gemail']);
            $group->setPath( $this->params['gemail']);
    
            $this->changeField('Description',$group->getDescription(),$this->params['description']);
            $group->setDescription( $this->params['description'] );
    
            $this->changeField('Join_Mode',$group->getJoinMode(),$this->params['hjoin']);
            $group->setJoinMode( $this->params['hjoin'] );
            $group->setJoinCode( ($this->params['hjoin'] == 2) ? $this->params['jcode'] : null );
    
            if (!empty($this->params['gemail'])) {
                $this->params['gemail'] .= '@'.DOMAIN_FOR_GROUP_EMAIL;
                $form->addRule('gemail', 'email', Warecorp::t('Enter correct Group Email'));
            }
    
            if (!empty($this->params['gname'])) {
                $name = preg_replace("/\s{1,}/","-", strtolower(trim($this->params['gname'])));
                $form->addRule('gname', 'callback', Warecorp::t('Group with this name already exist'), array('func' => 'Warecorp_Form_Validation::isNewGroupExist', 'params' => array('gname'=>$this->params['gname'], 'excludeIds' => $group->getId())));
            }
    
            if (isset($this->params['hjoin']) && $this->params['hjoin'] == "2") {
                $form->addRule('jcode', 'required', Warecorp::t('Enter Invitation Code'));
                if (!empty($this->params['jcode'])) {
                    $this->params['jcode'] = trim($this->params['jcode']);
                }
            }
    
            /**
             * Form Validation
             */            
            if ( $form->validate($this->params) ) {
                //  Prepare location to save
                $country = Warecorp_Location_Country::create($this->params['countryId']);
                if ( $this->params['countryId'] == 1 || $this->params['countryId'] == 38 ) {
                    if ( strpos($this->params['zipcode'], ",") ) $locationInfo = $country->getZipcodeByACFullInfo($this->params['zipcode']);
                    else $locationInfo = $country->getZipcodeByACInfo($this->params['zipcode']);
                    $group->setZipcode  ( $locationInfo['zipcode'] );
                    $group->setCityId   ( $locationInfo['city_id'] );
                } else {
                    $locationInfo = $country->getCityByACInfo($this->params['city']);
                    $group->setZipcode  ( '' );
                    $group->setCityId   ( $locationInfo['city_id'] );
                }
                $city = Warecorp_Location_City::create($locationInfo['city_id']);
                $group->setLatitude( $city->getLatitude() );
                $group->setLongitude( $city->getLongitude() );
        
                if ( $approveAllMembers ) {
                    $groupMembers = $group->getMembers();
                    $pendingMembers = $groupMembers->setMembersStatus(Warecorp_Group_Enum_MemberStatus::MEMBER_STATUS_PENDING)->getList();
                    foreach($pendingMembers as $member) {
                        $groupMembers->approveMember($member);
                    }
                }
                $group->save();
                $group->deleteTags();
                $group->addTags($this->params['tags']);
                // save LOG
                $this->appendLog('groups',$this->params['id'],'edit');
            }
        } 
        /**
         * Group Details View
         */
        else {
            $this->params['countryId'] = $group->getCountry()->id;
            $this->params['city'] = $group->getCity()->name.', '.$group->getState()->name;
            $this->params['zipcode'] = $group->getZipcode().', '.$group->getCity()->name;
        }

        $allCategoriesObj = new Warecorp_Group_Category_List();
        $allCategories = $allCategoriesObj->returnAsAssoc()->getList();
        $this->view->categories = $allCategories;
        
        $tags = ""; 
        $tagsList = $group->getTagsList();
        foreach ( $tagsList as $tag ) $tags .= $tag->getPreparedTagName()." ";
        $tags = trim($tags);
        $this->view->tags = $tags;
        
        $countries = Warecorp_Location::getCountriesListAssoc(true);
        $this->view->countries = $countries;
        
        //$timezones = new Warecorp_Location_Timezone();
        
        $this->view->group = $group;
        $this->view->form = $form;
        $this->view->groupID = $group->getId();

        $this->view->countryId = $this->params['countryId'];
        $this->view->cityStr = $this->params['city'];
        $this->view->zipStr = $this->params['zipcode'];
        
        /**
         * 
         */
        $this->view->coowners = $group->getMembers()->setMembersRole(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST)->getList();
        
        $template = 'adminarea/simplegroup.tpl';
    } 
    /**
     * +-------------------------------------------------------------------------
     * |    Groups List View
     * +-------------------------------------------------------------------------
     */    
    else {
        $form = new Warecorp_Form('sForm', 'POST', $this->admin->getAdminPath('groups'));
        $groupsList = new Warecorp_Group_List();
        $search = "";$order="";
        if (!empty($this->params['keyword'])) {
            $groupsList->addWhere('zgi.name like "%'.$this->params['keyword'].'%"');
            $search ='/keyword/'.$this->params['keyword'];
            $this->view->keyword = $this->params['keyword'];
        }
        $order = isset($this->params['order'])?$this->params['order']:'name';
        $direction = isset($this->params['direction'])?$this->params['direction']:'asc';
        $groupsList->setOrder('zgi.'.$order.' '.$direction);
    
        $this->view->order = $order;
        $this->view->direction = $direction;
        $this->view->search = $search;
    
        $sort = '/order/'.$order.'/direction/'.$direction;
    
        $groupsList->setTypes(array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE));
        $groupsList->setCurrentPage($this->params['page'])->setListSize($items_per_page);
        $url = $this->admin->getAdminPath('groups').$search.$sort;
        $P = new Warecorp_Common_PagingProduct($groupsList->getCount(), $items_per_page, $url);
        $this->view->form = $form;
        $this->view->paging = $P->makePaging(intval($this->params['page']));
        $this->view->groupsList = $groupsList->getList();
        $template = 'adminarea/groups.tpl';
        
        $groupsList = new Warecorp_Group_Family_List();
        $groupsList->setOrder('zgi.name ASC');
        $families = $groupsList->returnAsAssoc()->getList();
        $this->view->families = $families;
    }
    
    
    $this->view->bodyContent = $template;
