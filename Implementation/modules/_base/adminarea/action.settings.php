<?php
    Warecorp::addTranslation('/modules/adminarea/action.settings.php.xml');

   $settings = new Warecorp_Settings(HTTP_CONTEXT);


   $form = new Warecorp_Form('settingsForm', 'POST', $this->admin->getAdminPath('settings'));

   if ( $form->isPostback() ) {
        if ( $form->validate($this->params) ) {
            if (isset($this->params['tracer_code'])) {
                $settings->setTracerCode($this->params['tracer_code'])->save();
            }
        }
   }else {
       
   }

   $this->view->tracer_code = $settings->getTracerCode();
   $this->view->form           = $form;
   $this->view->bodyContent    = "adminarea/action.settings.tpl";
   




//    $items_per_page = 10;
//
//    $this->params['page'] = ( !empty($this->params['page']) && 0 + $this->params['page'] > 0 ) ? $this->params['page'] : 1;
//
//    $this->view->groupID = isset( $this->params['id'] ) ? $this->params['id'] : '';
    
    /**
     * +-------------------------------------------------------------------------
     * |    Family Details View/Edit
     * +-------------------------------------------------------------------------
     */
//    if ( isset($this->params['id']) ) {
//
//        $this->_page->Xajax->registerUriFunction("detectCountry", "/ajax/detectCountry/");
//        $this->_page->Xajax->registerUriFunction("autoCompleteCity", "/ajax/autoCompleteCity/");
//        $this->_page->Xajax->registerUriFunction("autoCompleteZip", "/ajax/autoCompleteZip/");
//        $this->_page->Xajax->registerUriFunction("autoCompleteGroupMembers", "/ajax/autoCompleteGroupMembers/");
//
//        $group = new Warecorp_Group_Family('id', $this->params['id']);
//
//
//        /**
//         * Group Details Edit
//         */
//        if ( $form->isPostback() ) {
//            $form->addRule('gname',         'callback',     Warecorp::t('Group with this name already exist'), array('func' => 'Warecorp_Form_Validation::isNewGroupExist', 'params' => array('gname'=>isset($this->params['gname'])?$this->params['gname']:null, 'excludeIds' => $group->getId())));
//            $form->addRule('gname',         'required',     Warecorp::t('Enter Group Name'));
//            $form->addRule('gname',         'regexp',       Warecorp::t('Enter correct Group Name'), array('regexp' => "/^[a-zA-Z0-9]{1}[a-zA-Z0-9_'\s\-\.]{0,}$/"));
//            $form->addRule('gname',         'rangelength',  Warecorp::t('Enter correct Group Name (%s-%s characters)', array(3,100)), array('min' => 3, 'max' => 100));
//            if (!empty($this->params['gemail'])) {
//                $this->params['gemail'] .= '@'.DOMAIN_FOR_GROUP_EMAIL;
//                $form->addRule('gemail',    'email',        Warecorp::t('Enter correct Group Email'));
//            }
//
//            $form->addRule('gemail',        'required',     Warecorp::t('Enter Group Email'));
//            $form->addRule('gemail',        'maxlength',    Warecorp::t('Email too long (max %s)',60), array('max' =>  61 + strlen(DOMAIN_FOR_GROUP_EMAIL)));
//            $form->addRule('gemail',        'regexp',       Warecorp::t('Enter correct Group Address'), array('regexp' => '/^[A-Za-z0-9]{1}[A-Za-z0-9\-]+@'.str_replace('.','\.',DOMAIN_FOR_GROUP_EMAIL).'$/'));
//            $form->addRule('gemail',        'callback',     Warecorp::t('Group Address already exist'),array('func' => 'Warecorp_Form_Validation::isGroupExist', 'params' => isset($this->params['gemail'])?array('key' =>'group_path', 'value'=> $this->params['gemail'], 'exclude'=>$group->getPath()):null));
//            if (!empty($this->params['gname'])) {
//                $name = preg_replace("/\s{1,}/","-", strtolower(trim($this->params['gname'])));
//                $form->addRule('gname',         'callback', Warecorp::t('Group with this name already exist'), array('func' => 'Warecorp_Form_Validation::isNewGroupExist', 'params' => array('gname'=>$this->params['gname'], 'excludeIds' => $group->getId())));
//            }
//
//            $form->addRule('categoryId',    'nonzero',      Warecorp::t('Choose category'));
//
//            $form->addRule('description',   'required',     Warecorp::t('Enter Description'));
//            $form->addRule('description',   'maxlength',    Warecorp::t('Enter correct Description (max %s characters)', 200), array('max' => 2000));
//
//            $form->addRule('company',       'regexp',       Warecorp::t('Enter correct Company Name'), array('regexp' => '/^[A-Za-z0-9\s]*$/'));
//            $form->addRule('company',       'rangelength',  Warecorp::t('Enter correct Company Name (%s-%s characters)', array(1,255)), array('min' => 0, 'max' => 255));
//
//            $form->addRule('position',      'regexp',       Warecorp::t('Enter correct Position'), array('regexp' => '/^[A-Za-z0-9\s]*$/'));
//            $form->addRule('position',      'rangelength',  Warecorp::t('Enter correct Position (max %s characters)', 255), array('min' => 0, 'max' => 255));
//
//            $form->addRule('address1',      'required',     Warecorp::t('Enter Address1'));
//            $form->addRule('address1',      'regexp',       Warecorp::t('Enter correct Address1'), array('regexp' => '/^[A-Za-z0-9\s\.,]*$/'));
//            $form->addRule('address1',      'rangelength',  Warecorp::t('Enter correct Address1 (max %s characters)', 255), array('min' => 0, 'max' => 255));
//
//            $form->addRule('address2',      'regexp',       Warecorp::t('Enter correct Address2'), array('regexp' => '/^[A-Za-z0-9\s\.,]*$/'));
//            $form->addRule('address2',      'rangelength',  Warecorp::t('Enter correct Address2 (max %s characters)', 255), array('min' => 0, 'max' => 255));
//
//            $form->addRule('countryId',     'nonzero', Warecorp::t('Choose country'));
//            $form->addRule('city',          'callback',  Warecorp::t('Enter please City'),
//                array(
//                    'func' => 'Warecorp_Form_Validation::isCityRequired',
//                    'params' => array(
//                        'countryId' => ((isset($this->params['countryId'])) ? $this->params['countryId'] : null),
//                        'city' => ((isset($this->params['city'])) ? $this->params['city'] : null)
//                    )
//                )
//            );
//            $form->addRule('city',          'callback',      Warecorp::t('City name is incorrect. Choose it from autocomplete list.'),
//                array(
//                    'func' => 'Warecorp_Form_Validation::isCityInvalid',
//                    'params' => array(
//                        'countryId' => ((isset($this->params['countryId'])) ? $this->params['countryId'] : null),
//                        'city' => ((isset($this->params['city'])) ? $this->params['city'] : null)
//                    )
//                )
//            );
//            $form->addRule('zipcode',       'callback',      Warecorp::t('Enter please Zip code'),
//                array(
//                    'func' => 'Warecorp_Form_Validation::isZipcodeRequired',
//                    'params' => array(
//                        'countryId' => ((isset($this->params['countryId'])) ? $this->params['countryId'] : null),
//                        'zipcode' => ((isset($this->params['zipcode'])) ? $this->params['zipcode'] : null)
//                    )
//                )
//            );
//            $form->addRule('zipcode',       'callback',      Warecorp::t('Zip code is incorrect. Choose it from autocomplete list.'),
//                array(
//                    'func' => 'Warecorp_Form_Validation::isZipcodeInvalid',
//                    'params' => array(
//                        'countryId' => ((isset($this->params['countryId'])) ? $this->params['countryId'] : null),
//                        'zipcode' => ((isset($this->params['zipcode'])) ? $this->params['zipcode'] : null)
//                    )
//                )
//            );
//
//            if (isset($this->params['hjoin']) && $this->params['hjoin'] == "2") {
//                $form->addRule('jcode',   'required',     Warecorp::t('Enter Invitation Code'));
//                if (!empty($this->params['jcode'])) {
//                    $this->params['jcode'] = trim($this->params['jcode']);
//                }
//            }
//
//            /**
//             * Form Validation
//             */
//            if ( $form->validate($this->params) ) {
//
//
//            } else {
//
//            }
//        }
        /**
         * Group Details View
         */
//        else {
//            $this->params['countryId'] = $group->getCountry()->id;
//            $this->params['city'] = $group->getCity()->name.', '.$group->getState()->name;
//            $this->params['zipcode'] = $group->getZipcode().', '.$group->getCity()->name;
//        }
//
//        $this->view->group = $group;
//        $this->view->editFamilyForm = $form;
//        $this->view->groupID = $group->getId();
//
//        $this->view->countryId = $this->params['countryId'];
//        $this->view->cityStr = $this->params['city'];
//        $this->view->zipStr = $this->params['zipcode'];
//
//        $this->view->coowners = $group->getMembers()->setMembersRole(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_COHOST)->getList();
//
//        $this->view->bodyContent    = "adminarea/action.family.details.tpl";
//
//    }
//    /**
//     * +-------------------------------------------------------------------------
//     * |    Families List View
//     * +-------------------------------------------------------------------------
//     */
//    else {
//        $form = new Warecorp_Form('sForm', 'POST', $this->admin->getAdminPath('families'));
//        $groupsList = new Warecorp_Group_Family_List();
//        $groupsList->setChildTypes(array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE, Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY));
//        $groupsList->setChildStatus(Warecorp_Group_Enum_GroupStatus::GROUP_STATUS_APPROVED, Warecorp_Group_Enum_GroupStatus::GROUP_STATUS_PENDING);
//        $search = "";
//
//        if ( !empty($this->params['keyword']) ) {
//            //FIXME Controller is not best place to put part of SQL query
//            $groupsList->addWhere('zgi.name like "%'.$this->params['keyword'].'%"');
//            $search ='/keyword/'.$this->params['keyword'];
//            $this->view->keyword = $this->params['keyword'];
//        }
//
//        $orders     = array('name', 'creation_date', 'members');
//        $order      = ( !empty($this->params['order']) && in_array($this->params['order'], $orders))     ? $this->params['order'] : 'name';
//        $direction  = ( !empty($this->params['direction']) && $this->params['direction'] == 'desc')      ? 'desc' : 'asc';
//
//        if ( !empty($this->params['order']) ) {
//            if ( $this->params['order'] !== 'members' ) {
//                $groupsList->setOrder('zgi.'.$order.' '.$direction);
//            } else {
//                $groupsList->setOrder('child_groups_cnt '.$direction);
 //           }
  //      }
    
  //      $sort = '/order/'.$order.'/direction/'.$direction;
    
  //      $groupsList->setCurrentPage($this->params['page'])->setListSize($items_per_page);
    
 //       $P = new Warecorp_Common_PagingProduct($groupsList->getCount(), $items_per_page, $this->admin->getAdminPath('families').$search.$sort);
    
  //      $this->view->order          = $order;
  //      $this->view->direction      = $direction;
  //      $this->view->search         = $search;
 //       $this->view->form           = $form;
  //      $this->view->paging         = $P->makePaging(intval($this->params['page']));
   //     $this->view->groupsList     = $groupsList->getList();
 //       $this->view->bodyContent    = "adminarea/action.settings.tpl";
 //   }
