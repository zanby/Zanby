<?php
Warecorp::addTranslation('/modules/groups/xajax/action.searchOnChangeState.php.xml');

    $objResponse = new xajaxResponse();
    $objResponse->addScript('var cities = document.getElementById("cityId");');
    $objResponse->addScript('cities.length = 0;');
    
    $objResponse->addScript('var zipes = document.getElementById("zipId");');
    $objResponse->addScript('zipes.length = 0;');
    $objResponse->addScript('zipes.options.add(new Option("", "0"));');
    
    if ( $stateId != 0 ) {
        $state = Warecorp_Location_State::create($stateId);
        $cities = $state->getCitiesListAssoc();
        $text_info = Warecorp::t('All Cities');
        $objResponse->addScript('cities.options.add(new Option("'.$text_info.'", "0"));');
        foreach ( $cities as $_id => $_name ) {
    
            $objResponse->addScript('cities.options.add(new Option("'.$_name.'","'.$_id.'"));');
        }
    } else {
        $text_info = Warecorp::t('All Cities');
        $objResponse->addScript('cities.options.add(new Option("'.$text_info.'", "0"));');
    }