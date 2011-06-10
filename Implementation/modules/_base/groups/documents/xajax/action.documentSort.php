<?php
    Warecorp::addTranslation('/modules/groups/documents/xajax/action.documentSort.php.xml');
    $AccessManager = Warecorp_Document_AccessManager_Factory::create(); 
    $objResponse = new xajaxResponse();
    
    //  Check folder
    //*******************************************
    $curr_folder_id = ( floor($curr_folder_id) == 0 ) ? null : floor($curr_folder_id);
    $curr_owner_id  = ( floor($curr_owner_id) == 0 )  ? null : floor($curr_owner_id);
    $owner = Warecorp_Group_Factory::loadById($curr_owner_id);

    $folder = new Warecorp_Document_FolderItem($curr_folder_id);
    if ( $curr_folder_id !== null && !$folder->getId()) {
        $text_info = Warecorp::t("Incorrect folder!");
        $objResponse->addScript("DocumentApplication.showInfo('".$text_info."');");
//        $objResponse->addScript("DocumentApplication.showInfo('Incorrect folder!');");
        return;
    } elseif ( empty($this->currentGroup) || !$this->currentGroup->getId() ) {
        $text_info = Warecorp::t("Incorrect group!");
        $objResponse->addScript("DocumentApplication.showInfo('".$text_info."');");
//        $objResponse->addScript("DocumentApplication.showInfo('Incorrect group!');");
        return;
    }
   
    //  Check permissions        
    //*******************************************
    if (!$AccessManager->canViewOwnerDocuments($this->currentGroup, $owner, $this->_page->_user->getId())) {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('summary'));
        return;
    }
    //*******************************************

    //*******************************************
    $s = &$_SESSION['documents']['group']['order'][$this->currentGroup->getId()];
    $s              = array();
    $_directions    = array('asc'=>'desc', 'desc'=>'asc');
    $_files_orders  = array('title'=>'original_name', 'update'=>'update_date');
    $_dirs_orders   = array('title'=>'name', 'update'=>'update_date');
    $_fields        = array('title'=>'sortTitle', 'update'=>'sortUpdate');
    $_styles        = array('asc'=>'prbSortUp', 'desc'=>'prbSortDown');

    $objResponse->addAssign("sortTitle", "className", "");
    $objResponse->addAssign("sortUpdate", "className", "");
    
    if (isset($curr_order) && isset($_files_orders[$curr_order]) &&
        isset($curr_direction) && in_array($curr_direction, $_directions) && 
        isset($order) && isset($_dirs_orders[$order]) 
       ) {
        if ($curr_order == $order) {
        	$curr_direction = $_directions[$curr_direction];
        } else {
        	$curr_order        = $order;
        	$curr_direction    = 'asc';
        }
    } else {
    	$curr_order        = 'title';
    	$curr_direction    = 'asc';
    }
    
    $s['files'] = $_files_orders[$curr_order] ." " .$curr_direction;
    $s['dirs']  = $_dirs_orders[$curr_order] ." " .$curr_direction;
    
    $objResponse->addAssign("sortTitle", "className", "");
    $objResponse->addAssign("sortUpdate", "className", "");
    $objResponse->addAssign($_fields[$curr_order], "className", "prbActive {$_styles[$curr_direction]}");
    
    $objResponse->addScript("YAHOO.util.Dom.get('current_order').value = '{$curr_order}'");
    $objResponse->addScript("YAHOO.util.Dom.get('current_direction').value = '{$curr_direction}'");            
    
    
    $this->documentChangeContent($objResponse, $owner, $curr_folder_id);