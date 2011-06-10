<?php
    Warecorp::addTranslation("/modules/ajax/action.autoCompleteZip.php.xml");
    $objResponse = new xajaxResponse();
    //$objResponse->addAlert('autoCompleteCity Function called : query = '.$query.'');

    $objCountry = Warecorp_Location_Country::create($country);
    $zipcodes = $objCountry->getACZipcodesList($query);
    if ( sizeof($zipcodes) != 0 ) {
        foreach ( $zipcodes as &$_z ) {
            $_z[0] = preg_replace("/[\n\r]/", "", $_z[0]);
            $_z[0] = preg_replace("/\s{1,}/", " ", $_z[0]);
        }
    }
    $objResponse->addScriptCall($function, $zipcodes);
