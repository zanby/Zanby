<?php
Warecorp::addTranslation('/modules/groups/xajax/action.searchOnChangeCountry.php.xml');

    $objResponse = new xajaxResponse();
    
    $objResponse->addScript('var states = document.getElementById("stateId");');
    $objResponse->addScript('states.length = 0;');

    $objResponse->addScript('var cities = document.getElementById("cityId");');
    $objResponse->addScript('cities.length = 0;');
    $text_info = Warecorp::t('All Cities');
    $objResponse->addScript('cities.options.add(new Option("'.$text_info.'", "0"));');
    
    if ( $countryId > 0 ) {
        $country = Warecorp_Location_Country::create($countryId);
        $states = $country->getStatesListAssoc();
        $text_info = Warecorp::t("All States");
        $objResponse->addScript('states.options.add(new Option("'.$text_info.'", "0"));');
        foreach ( $states as $_id => $_name ) {
            $objResponse->addScript('states.options.add(new Option("'.$_name.'","'.$_id.'"));');
        }
    } else {
        $text_info = Warecorp::t("All States");
        $objResponse->addScript('states.options.add(new Option("'.$text_info.'", "0"));');
    }
