<?php
Warecorp::addTranslation('/modules/groups/hierarchy/action.preview.php.xml');
    $membersList        = $this->currentGroup->getGroups();
    $isHostPrivileges   = ($membersList->isCoowner($this->_page->_user)) || ($this->currentGroup->getMembers()->isHost($this->_page->_user));

    if ( !$isHostPrivileges ) {
        $this->_redirect($this->currentGroup->getGroupPath('summary'));
    }
    if ( !($this->currentGroup instanceof Warecorp_Group_Family) ) {
        $this->_redirect($this->currentGroup->getGroupPath('summary'));
    }
    
    $h = Warecorp_Group_Hierarchy_Factory::create();
    $h->setGroupId($this->currentGroup->getId());

    $r = $h->getHierarchyList();
    $curr_hid = (isset($this->params['hid'])) ? $this->params['hid'] : (sizeof($r) != 0 ? $r[0]->getId() : null);
    if ($curr_hid !== null) $h->loadById($curr_hid);

    $tree = $h->getHierarchyTree();
    $this->view->globalCategories = Warecorp_Group_Hierarchy::prepareTreeToPreview($h, $tree);
    
    $this->view->cols = 2;
    $this->view->minGrgoupInSection = 2;
    $this->view->tree = $tree;
    $this->view->hierarchyList = $r;
    $this->view->curr_hid = $curr_hid;
    $this->view->current_hierarchy = $h;
    $this->view->menuContent = null;
    
    $this->_page->setTitle(Warecorp::t('Family Group Hierarchy'));
    $this->view->bodyContent = 'groups/hierarchy/previewhierarchy.tpl';
    
    
