<?php
    Warecorp::addTranslation("/modules/users/xajax/action.OnChangeCountry.php.xml");

    $objResponse = new xajaxResponse();

    $objResponse->addScript('var states = document.getElementById("stateId");');
    $objResponse->addScript('states.length = 0;');

    $objResponse->addScript('var cities = document.getElementById("cityId");');
    $objResponse->addScript('cities.length = 0;');
    $objResponse->addScript('cities.options.add(new Option("' . Warecorp::t("All Cities") .'", "0"));');
    
    if ( $countryId > 0 ) {
        $country = Warecorp_Location_Country::create($countryId);
        $states = $country->getStatesListAssoc();
        $objResponse->addScript('states.options.add(new Option("' . Warecorp::t("All States") .'", "0"));');
        foreach ( $states as $_id => $_name ) {
            $objResponse->addScript('states.options.add(new Option("'.$_name.'","'.$_id.'"));');
        }
    } else {
        $objResponse->addScript('states.options.add(new Option("' . Warecorp::t("All States") .'", "0"));');
    }
