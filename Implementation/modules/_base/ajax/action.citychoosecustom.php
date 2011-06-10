<?php
    Warecorp::addTranslation("/modules/ajax/action.citychoosecustom.php.xml");
    $objResponse = new xajaxResponse();
    
    $objCountry = Warecorp_Location_Country::create($country);
    if ( null === $objCountry->id ) return;
    
    $lstCityAliasIds = Warecorp_Location_Alias_List::detectAliasByQueryString($query, $objCountry->id);
    $lstCities = $objCountry->findByCityNameOrIds($query, $lstCityAliasIds);
    $lstCitiesSize = sizeof($lstCities);
    
    if ( $checked == 'false' ) {
        $isRECOGNIZED   = false;
        // recognization status (left block)
        $text = '<label style="color:red;">'.Warecorp::t('UNRECOGNIZED').'</label>';
        $objResponse->addAssign("cityavailable", "innerHTML", $text);
    } else {
	    $isRECOGNIZED   = true;
	    $outRECOGNIZED  = $query.', '.$objCountry->name;
	    // recognization status (left block)
	    $text = '<label style="color:green;">'.Warecorp::t('RECOGNIZED').( ($outRECOGNIZED) ? ' : '.$outRECOGNIZED : '' ).'</label>';
	    $objResponse->addAssign("cityavailable", "innerHTML", $text);
    }
    $objResponse->addAssign("cityAliasSelected", "value", '');
    
    /**
     * One variant
     */
    if ( $lstCitiesSize != 0 ) {
        $out = '';
        foreach ( $lstCities as $_city ) {
            $out .= '<label><a id="cityAlias'.$_city->id.'" href="#null" onclick="chooseAlias('.$_city->id.', \''.$query.'\')">'.( $_city->name.', '.$_city->getState()->name.', '.$_city->getState()->getCountry()->name ).'</a></label><br>';
        }     
        // variat choose box
        $objResponse->addAssign('cityavailableResults', 'style.display', '');
        $objResponse->addAssign('cityavailableResultsMessage', 'innerHTML', $out);
        //  custom variant choose box
        $objResponse->addAssign('cityavailableChoose', 'style.display', ''); 
        //$objResponse->addAssign('city_correct', 'checked', false);        
        // upate query string selected
        $objResponse->addAssign("cityQuerySelected", "value", $query);
    } 
    /**
     * No variants
     */
    else {
        // variat choose box
        $objResponse->addAssign('cityavailableResults', 'style.display', 'none');
        $objResponse->addAssign('cityavailableResultsMessage', 'innerHTML', '');
        //  custom variant choose box
        $objResponse->addAssign('cityavailableChoose', 'style.display', ''); 
        //$objResponse->addAssign('city_correct', 'checked', false);        
        // upate query string selected
        $objResponse->addAssign("cityQuerySelected", "value", $query);          
    } 
    return;