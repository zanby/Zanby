<?php
    Warecorp::addTranslation("/modules/ajax/action.zipcodeavailable.php.xml");
    $objResponse = new xajaxResponse();
    $text = '';
    if ( !empty($zipcode) && !empty($country) ) {
        $objCountry = Warecorp_Location_Country::create($country);

        $text = ( !$objCountry->checkZipcode($zipcode) ) 
            ? '<label style="color:red;">'.Warecorp::t('UNRECOGNIZED').'</label>'
            : '<label style="color:green;">'.Warecorp::t('RECOGNIZED').'</label>';
    }
    $objResponse->addAssign("zipcodeavailable", "innerHTML", $text);