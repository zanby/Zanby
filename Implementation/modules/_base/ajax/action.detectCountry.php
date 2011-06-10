<?php
    Warecorp::addTranslation("/modules/ajax/action.detectCountry.php.xml");
    $objResponse = new xajaxResponse();

    /**
     * No country selected
     */
    if ( !$countryId ) {
        $objResponse->addAssign('LocationTrCity', 'style.display', 'none');
        $objResponse->addAssign('LocationTrZip', 'style.display', 'none'); 
        $objResponse->addAssign('zipId', 'value', '');
        $objResponse->addAssign('city', 'value', '');
        $objResponse->addAssign('cityQuerySelected', 'value', '');
        $objResponse->addAssign('cityAliasSelected', 'value', '');        
        $objResponse->addAssign('cityavailable', 'innerHTML', '');
        $objResponse->addAssign('cityavailableResultsMessage', 'innerHTML', '');
        $objResponse->addAssign('cityavailableResults', 'style.display', 'none');
        $objResponse->addAssign('cityavailableChoose', 'style.display', 'none');
        $objResponse->addAssign('city_correct', 'checked', false);
        $Script = 'var tmpInput = document.getElementById("LocationTrCity").getElementsByTagName("INPUT");';
        $Script .= 'tmpInput[0].style.display = "none";';
        $Script .= 'var tmpInput = document.getElementById("LocationTrZip").getElementsByTagName("INPUT");';
        $Script .= 'tmpInput[0].style.display = "none";';
        $objResponse->addScript($Script);
    } 
    /**
     * United States OR Canada selected
     */
    elseif ( $countryId == 1 || $countryId == 38 ) {
        $objResponse->addAssign('LocationTrCity', 'style.display', 'none');
        $objResponse->addAssign('zipId', 'value', '');
        $objResponse->addAssign('city', 'value', '');
        $objResponse->addAssign('cityQuerySelected', 'value', '');
        $objResponse->addAssign('cityAliasSelected', 'value', '');
        $objResponse->addAssign('LocationTrZip', 'style.display', '');
        $objResponse->addAssign('cityavailable', 'innerHTML', '');
        $objResponse->addAssign('cityavailableResultsMessage', 'innerHTML', '');
        $objResponse->addAssign('cityavailableResults', 'style.display', 'none');   
        $objResponse->addAssign('cityavailableChoose', 'style.display', 'none');   
        $objResponse->addAssign('city_correct', 'checked', false);  
        $Script = 'var tmpInput = document.getElementById("LocationTrCity").getElementsByTagName("INPUT");';
        $Script .= 'tmpInput[0].style.display = "none";';
        $Script .= 'var tmpInput = document.getElementById("LocationTrZip").getElementsByTagName("INPUT");';
        $Script .= 'tmpInput[0].style.display = "";';        
        $Script .= 'var zipcodeavailable = document.getElementById("zipcodeavailable");';
        $Script .= 'if (zipcodeavailable) { zipcodeavailable.innerHTML = ""; };';
        $objResponse->addScript($Script);
    }
    /**
     * Other Country selected
     */
    else {
        $objResponse->addAssign('LocationTrZip', 'style.display', 'none');
        $objResponse->addAssign('zipId', 'value', '');
        $objResponse->addAssign('city', 'value', '');
        $objResponse->addAssign('cityQuerySelected', 'value', '');
        $objResponse->addAssign('cityAliasSelected', 'value', '');
        $objResponse->addAssign('LocationTrCity', 'style.display', '');
        $objResponse->addAssign('cityavailable', 'innerHTML', '');
        $objResponse->addAssign('cityavailableResultsMessage', 'innerHTML', '');
        $objResponse->addAssign('cityavailableResults', 'style.display', 'none');
        $objResponse->addAssign('cityavailableChoose', 'style.display', 'none');
        $objResponse->addAssign('city_correct', 'checked', false);
        $Script = 'var tmpInput = document.getElementById("LocationTrZip").getElementsByTagName("INPUT");';
        $Script .= 'tmpInput[0].style.display = "none";';
        $Script .= 'var tmpInput = document.getElementById("LocationTrCity").getElementsByTagName("INPUT");';
        $Script .= 'tmpInput[0].style.display = "";';
        $objResponse->addScript($Script);
    }    
    