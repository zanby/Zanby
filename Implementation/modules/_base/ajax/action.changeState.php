<?php
    Warecorp::addTranslation("/modules/ajax/action.changeState.php.xml");
    $objResponse = new xajaxResponse();
    
    $objResponse->addScript('var cities = document.getElementById("cityId");');
    $objResponse->addScript('cities.length = 0;');
    $objResponse->addScript('cities.options.add(new Option("[Select City]", "0"));');
    
    if ( $stateId != 0 ) {
        $state = Warecorp_Location_State::create($stateId);
        $cities = $state->getCitiesListAssoc();
        foreach ( $cities as $_id => $_name ) {
            $objResponse->addScript('cities.options.add(new Option("'.$_name.'","'.$_id.'"));');
        }
    }