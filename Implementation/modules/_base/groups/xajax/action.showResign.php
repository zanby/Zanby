<?php
    Warecorp::addTranslation('/modules/groups/xajax/action.showResign.php.xml');
    
    $this->view->visibility = true;
        
    if($this->currentGroup->getGroupType() == "simple"){
        $Content = $this->view->getContents('groups/settings.resign.tpl');
    } elseif ($this->currentGroup->getGroupType() == "family"){
        $Content = $this->view->getContents('groups/settings.familyresign.tpl');
    }    
    
    $this->view->group = $this->currentGroup;
    $objResponse = new xajaxResponse();
    
    $objResponse->addClear( "GroupSettingsResign_Content", "innerHTML" );
    $objResponse->addAssign( "GroupSettingsResign_Content", "innerHTML", $Content );
    $objResponse->addScript('var resign_myAutoComp = new YAHOO.widget.AutoComplete("newhost", "acMembers", myDataSource);');
