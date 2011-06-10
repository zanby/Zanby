<?php
Warecorp::addTranslation('/modules/groups/hierarchy/action.index.php.xml');

    $membersList        = $this->currentGroup->getGroups();
    $isHostPrivileges   = ($membersList->isCoowner($this->_page->_user)) || ($this->currentGroup->getMembers()->isHost($this->_page->_user));

    if ( !$isHostPrivileges ) {
        $this->_redirect($this->currentGroup->getGroupPath('summary'));
    }
    if ( !($this->currentGroup instanceof Warecorp_Group_Family) ) {
        $this->_redirect($this->currentGroup->getGroupPath('summary'));
    }
    
    //$this->_page->Xajax->registerUriFunction("hierarchy_show_constraints", "/groups/showConstraints/");
    //$this->_page->Xajax->registerUriFunction("hierarchy_show_category", "/groups/showCategory/");
    //$this->_page->Xajax->registerUriFunction("hierarchy_show_options", "/groups/showOptions/");
    //$this->_page->Xajax->registerUriFunction("hierarchy_hide_constraints", "/groups/hideConstraints/");
    //$this->_page->Xajax->registerUriFunction("hierarchy_hide_category", "/groups/hideCategory/");
    //$this->_page->Xajax->registerUriFunction("hierarchy_hide_options", "/groups/hideOptions/");

    $this->_page->Xajax->registerUriFunction("add_hierarchy", "/groups/addHierarchy/");
    $this->_page->Xajax->registerUriFunction("add_hierarchy_handler", "/groups/addHierarchyHandler/");
    //$this->_page->Xajax->registerUriFunction("close_add_hierarchy", "/groups/closeAddHierarchy/");
    $this->_page->Xajax->registerUriFunction("remane_hierarchy", "/groups/remaneHierarchy/");    
    $this->_page->Xajax->registerUriFunction("rename_hierarchy_handler", "/groups/renameHierarchyHandler/");
    //$this->_page->Xajax->registerUriFunction("close_rename_hierarchy", "/groups/closeRemaneHierarchy/");
    $this->_page->Xajax->registerUriFunction("delete_hierarchy", "/groups/deleteHierarchy/");
    $this->_page->Xajax->registerUriFunction("delete_hierarchy_handler", "/groups/deleteHierarchyHandler/");
    
    $this->_page->Xajax->registerUriFunction("change_constraints", "/groups/changeConstraints/");
    $this->_page->Xajax->registerUriFunction("save_constraints", "/groups/saveConstraints/");
    
    
    $this->_page->Xajax->registerUriFunction("change_hierarchy_type", "/groups/changeHierarchyType/");
    $this->_page->Xajax->registerUriFunction("hierarchy_renderer_tree", "/groups/rendererTree/");
    $this->_page->Xajax->registerUriFunction("add_grouping", "/groups/addGrouping/");
    $this->_page->Xajax->registerUriFunction("category_change", "/groups/categoryChangeHandler/");
    $this->_page->Xajax->registerUriFunction("add_category", "/groups/addCategory/");
    $this->_page->Xajax->registerUriFunction("remove_category", "/groups/removeCategory/");
    $this->_page->Xajax->registerUriFunction("remove_grouping", "/groups/removeGrouping/");
    $this->_page->Xajax->registerUriFunction("move_grouping", "/groups/moveGrouping/");    
    $this->_page->Xajax->registerUriFunction("add_item", "/groups/addItem/");
    $this->_page->Xajax->registerUriFunction("remove_item", "/groups/removeItem/");
    $this->_page->Xajax->registerUriFunction("save_tree_items", "/groups/saveTreeItems/");
    $this->_page->Xajax->registerUriFunction("save_options", "/groups/saveOptions/");
    $this->_page->Xajax->registerUriFunction("order_category", "/groups/orderCategory/");    
    

    $h = Warecorp_Group_Hierarchy_Factory::create();
    $h->setGroupId($this->currentGroup->getId());
    
    
    $r = $h->getHierarchyList();
    $curr_hid = (isset($this->params['hid'])) ? $this->params['hid'] : (sizeof($r) != 0 ? $r[0]->getId() : null);
    if ($curr_hid !== null) $h->loadById($curr_hid);

    $c_hierarchy_type   = $h->getConstraintsAssoc(1);
    $c_category_type    = $h->getConstraintsAssoc(2, $h->getHierarchyType());
    $c_category_focus   = $h->getConstraintsAssoc(3, $h->getCategoryType());
	
    $mode = ( isset($this->params['mode']) ) ? floor($this->params['mode']) : '2';
    if ( !in_array($mode, array(1,2)) ) $mode = 2;
    
    if ( $mode == 2 ) {
	    /**
		 * load hierarchy tree
		 */
	    $Script = "";
	    /**
	     * Live Hierarchy
	     */
//	    if ($h->getHierarchyType() == Warecorp_Group_Hierarchy_Enum::TYPE_LIVE) {
//	        $h_grouping[] = array('id' => 0);
//	        $Script = $h->getJSLiveTree($this->currentGroup);
//	        $this->view->h_grouping = $h_grouping;
//	    }
//	    /**
//	     * Custom Hierarchy
//	     */
//	    else {
	        $h_grouping = $h->getGroupingList();
	        $groups = $this->currentGroup->getGroups()
	                       ->setTypes(array('simple','family'))
	                       ->setExcludeIds($h->getHierarchyGroupIds())
	                       ->getList();
	        if (sizeof($h_grouping) != 0) {
	            foreach ($h_grouping as &$grouping) {
	                $Script .= $h->getJSCategoryTree($grouping);
	            }
	        }
	        $this->view->groups = $groups;
	        $this->view->h_grouping = $h_grouping;
//	    }
	    $this->view->TreeScript = $Script;
    } else {
        /**
         * load categories
         */

        /**
         * Live Hierarchy
         */
        if ($h->getHierarchyType() == Warecorp_Group_Hierarchy_Enum::TYPE_LIVE) {
            $h_grouping[] = array('id' => 0);
            $this->view->h_grouping = $h_grouping;
        } 
        /**
         * Custom Hierarchy
         */
        else {
            $h_grouping = $h->getGroupingList();
            $this->view->h_grouping = $h_grouping;
        }
        $this->view->maxCategoryDepth = $h->maxCategoryDepth;
    }
    $this->view->mode = $mode;
       
    $this->view->hierarchyList = $r;
    $this->view->curr_hid = $curr_hid;
    $this->view->current_hierarchy = $h;
    $this->view->menuContent = null;
    
    $this->view->c_hierarchy_type = $c_hierarchy_type;
    $this->view->c_category_type = $c_category_type;
    $this->view->c_category_focus = $c_category_focus;

    $this->_page->setTitle(Warecorp::t('Family Group Hierarchy'));
    $this->view->bodyContent = 'groups/hierarchy/index.tpl';
