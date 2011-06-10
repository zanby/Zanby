<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/__action.showCategory.php.xml');

    $objResponse = new xajaxResponse();

    $this->view->visibility = true;
    $this->view->curr_hid = $curr_hid;

    if ( $this->currentGroup->getGroupType() == 'family' ) {
        //  явно приводим группу к типу family для использования ее метода
        $this->currentGroup = new Warecorp_Group_Family('id', $this->currentGroup->getId());

        $h = Warecorp_Group_Hierarchy_Factory::create($curr_hid);
        $Script = "";
        if ( $h->getHierarchyType() == Warecorp_Group_Hierarchy_Enum::TYPE_LIVE ) {    //  Live Hierarchy
            $h_grouping[] = array('id' => 0);
            $Script = $h->getJSLiveTree($this->currentGroup);
            $this->view->h_grouping = $h_grouping;
        } else {                                //  Custom Hierarchy
            $h_grouping = $h->getGroupingList();
            $groups = $this->currentGroup->getGroups()
                           ->setTypes('simple')
                           ->setExcludeIds($h->getHierarchyGroupIds())
                           ->getList();
            if ( sizeof($h_grouping) != 0 ) {
                foreach ( $h_grouping as &$grouping ) {
                    $Script .= $h->getJSCategoryTree($grouping);
                }
            }
            $this->view->groups = $groups;
            $this->view->h_grouping = $h_grouping;
        }

        $this->view->curr_hierarchy = $h;
        $this->view->maxCategoryDepth = $h->maxCategoryDepth;
        $Content = $this->view->getContents('groups/hierarchy/hierarchy.category.tpl');

        $objResponse->addClear( "GroupHierarchyCategory", "innerHTML" );
        $objResponse->addAssign( "GroupHierarchyCategory", "innerHTML", $Content );
        $objResponse->addScript($Script);
        $objResponse->addScript("initDragedObjects();");
        $objResponse->addScript('categoryTabIsOpen = true;');
    }

