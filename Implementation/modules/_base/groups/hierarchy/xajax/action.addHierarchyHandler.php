<?php
Warecorp::addTranslation('/modules/groups/hierarchy/xajax/action.addHierarchyHandler.php.xml');

    $objResponse = new xajaxResponse();
    if ( trim($name) == "" ) {
        $objResponse->showAjaxAlert(Warecorp::t('Hierarchy name is not defined. Hierarchy was not aded.'));
    } else {
        $h = Warecorp_Group_Hierarchy_Factory::create();
        $h->setGroupId($this->currentGroup->getId());
        $count = $h->getHierarchyCount();
        
        //if ( $count < $h->maxHierarchyCount ) {
            $r = $h->getHierarchyList();
            $h->setName($name);
            $h->setDefault(false);
            $h->save();
            
            $url = $this->currentGroup->getGroupPath('hierarchy').'hid/'.$h->getId().'/';
            $objResponse->addScript("document.location.replace('".$url."');");
        //} else {
			//$objResponse->showAjaxAlert(Warecorp::t('You can create only %s hierarchies', array($h->getHierarchyCount())));
        //}
    }
