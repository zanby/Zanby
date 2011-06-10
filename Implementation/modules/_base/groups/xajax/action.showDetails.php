<?php
	
	Warecorp::addTranslation('/modules/groups/xajax/action.showDetails.php.xml');

    $this->view->visibility = true;
    
    if ($this->currentGroup->getGroupType() == "simple"){
        $form = new Warecorp_Form('dForm', 'POST', 'javasript:void(0);');
        $currentGroup = new Warecorp_Group_Simple("id", $this->currentGroup->getId());
    } elseif ($this->currentGroup->getGroupType() == "family"){
        $form = new Warecorp_Form('fdForm', 'POST', 'javasript:void(0);');
        $currentGroup = new Warecorp_Group_Family("id", $this->currentGroup->getId());
    }

    $currentGroup->setForceDbTags(true); 
    $group = array();
    $group['countryId'] = $currentGroup->getCountry()->id;

    /**
     * create countries list
     */
    $countries = Warecorp_Location::getCountriesListAssoc(true);

    /**
     * create tags list for group
     */
    $tagsList = $currentGroup->getTagsList();
    $tags = "";
    foreach ($tagsList as $tag){
        $tags .= $tag->getPreparedTagName()." ";
    }
    $tags = trim($tags);
    /**
     * create categories list
     */
    $allCategoriesObj = new Warecorp_Group_Category_List();
    $allCategoriesObj->setRelation($this->currentGroup->getGroupType());
    $allCategories = $allCategoriesObj->returnAsAssoc()->getList();

    $this->view->cityStr = $currentGroup->getCity()->name.', '.$currentGroup->getState()->name;
    $this->view->zipStr = $currentGroup->getZipcode().', '.$currentGroup->getCity()->name;
    
    $this->view->countries = $countries;
    $this->view->countryId = $group['countryId'];
    $this->view->categories = $allCategories;
    $this->view->form = $form;
    $this->view->tags = $tags;
    $this->view->currentGroup = $currentGroup;

    if ($this->currentGroup->getGroupType() == "simple"){
        $Content = $this->view->getContents('groups/settings.details.tpl');
    } elseif ($this->currentGroup->getGroupType() == "family"){
        $Content = $this->view->getContents('groups/settings.familydetails.tpl');
    }
	
    $objResponse = new xajaxResponse();
    $objResponse->addClear( "GroupSettingsGroupDetails_Content", "innerHTML" );
    $objResponse->addAssign( "GroupSettingsGroupDetails_Content", "innerHTML", $Content );
    
    $objResponse->addScript('initCityAutocomplete();initZipAutocomplete();');

