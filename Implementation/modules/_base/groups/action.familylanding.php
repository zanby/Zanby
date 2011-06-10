<?php
    $familyList = new Warecorp_Group_Family_List();
    $familyList->setChildTypes(array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE, Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY));
    $familyList->setChildStatus(Warecorp_Group_Enum_GroupStatus::GROUP_STATUS_APPROVED);
    /**
     *  @author Roman Gabrusenok
     *  according to the bug #6600
     */
    /* $familyList->addWhere('zgi.payment_type="business"'); */

    switch (FAMILY_LANDING_SORT) {
        case 'groupsInFamily':
            $familyList->setOrder('child_groups_cnt DESC');
            break;
        case 'name':
            $familyList->setorder('name');
            break;
	}

    $size = 10;
    $count = $familyList->getCount();

    $this->params['page'] = isset($this->params['page']) ? (int)$this->params['page'] : 1;
    $P = new Warecorp_Common_PagingProduct($count, $size, "http://".BASE_HTTP_HOST."/".LOCALE."/groups/familylanding");
    $paging = $P->makePaging($this->params['page']);

    $families = $familyList->setListSize($size)->setCurrentPage($this->params['page'])->getList();

   $this->view->bodyContent = 'groups/familylanding.tpl';
   $this->view->paging = $paging;
   $this->view->families = $families;
   $this->view->SSO = $this->getSSOConfig();

    $this->_page->breadcrumb = array("Home" => "http://".BASE_HTTP_HOST."/".LOCALE."/", "Groups" => "http://".BASE_HTTP_HOST."/".LOCALE."/groups/index/", "Group Families" => "");

    /**/
    $this->view->setLayout('main_wide.tpl');
    $this->view->isRightBlockHidden = true;
