<?php
    Warecorp::addTranslation("/modules/ajax/action.autoCompleteCity.php.xml");
    $objResponse = new xajaxResponse();
    //$objResponse->addAlert('autoCompleteCity Function called : query = '.$query.'');

    $objCountry = Warecorp_Location_Country::create($country);
    $cities = $objCountry->getACCitiesList($query);
    if ( sizeof($cities) != 0 ) {
        foreach ( $cities as &$_c ) {
            $_c[0] = preg_replace("/[\n\r]/", "", $_c[0]);
            $_c[0] = preg_replace("/\s{1,}/", " ", $_c[0]);
        }
    }
    $objResponse->addScriptCall($function, $cities);