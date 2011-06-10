<?php
    Warecorp::addTranslation("/modules/ajax/action.cityavailable.php.xml");
    $objResponse = new xajaxResponse();
    if ( !empty($city) && !empty($country) ) {
        $objCountry = Warecorp_Location_Country::create($country);
        if ( null === $objCountry->id ) return;
        
        $lstCityAliasIds = Warecorp_Location_Alias_List::detectAliasByQueryString($city, $objCountry->id);
        $lstCities = $objCountry->findByCityNameOrIds($city, $lstCityAliasIds);
        $lstCitiesSize = sizeof($lstCities);
        /**
         * One variant
         */
        if ( $lstCitiesSize == 1 ) {
            $isRECOGNIZED   = true;
            $outRECOGNIZED  = $lstCities[0]->name.', '.$lstCities[0]->getState()->name.', '.$lstCities[0]->getState()->getCountry()->name;
            // recognization status (left block)
            $text = '<label style="color:green;">'.Warecorp::t('RECOGNIZED').( ($outRECOGNIZED) ? ' : '.$outRECOGNIZED : '' ).'</label>';
            $objResponse->addAssign("cityavailable", "innerHTML", $text);
            // variat choose box
            $objResponse->addAssign('cityavailableResults', 'style.display', 'none');
            $objResponse->addAssign('cityavailableResultsMessage', 'innerHTML', '');
            //  custom variant choose box
            $objResponse->addAssign('cityavailableChoose', 'style.display', 'none');
            $objResponse->addAssign('city_correct', 'checked', false);
            // upate query string selected
            $objResponse->addAssign("cityQuerySelected", "value", $city); 
	        // selected alias
	        $objResponse->addAssign("cityAliasSelected", "value", $lstCities[0]->id);            
        } 
        /**
         * More then one variants
         */
        elseif ( $lstCitiesSize > 1 ) {
        	$out = '';
            foreach ( $lstCities as $_city ) {
                $out .= '<label><a id="cityAlias'.$_city->id.'" href="#null" onclick="chooseAlias('.$_city->id.', \''.$city.'\')">'.( $_city->name.', '.$_city->getState()->name.', '.$_city->getState()->getCountry()->name ).'</a></label><br>';
            }        	
            $isRECOGNIZED   = false;
            $outRECOGNIZED  = null;
            // recognization status (left block)
            $text = '<label style="color:red;">'.Warecorp::t('UNRECOGNIZED').'</label>';
            $objResponse->addAssign("cityavailable", "innerHTML", $text);
            // variat choose box
            $objResponse->addAssign('cityavailableResults', 'style.display', '');
            $objResponse->addAssign('cityavailableResultsMessage', 'innerHTML', $out);
            //  custom variant choose box
            $objResponse->addAssign('cityavailableChoose', 'style.display', ''); 
            $objResponse->addAssign('city_correct', 'checked', false);        
            // upate query string selected
            $objResponse->addAssign("cityQuerySelected", "value", $city);
            // selected alias
            $objResponse->addAssign("cityAliasSelected", "value", '');                        
        }
        /**
         * No variants
         */
        else {
            // recognization status (left block)
            $text = '<label style="color:red;">'.Warecorp::t('UNRECOGNIZED').'</label>';
            $objResponse->addAssign("cityavailable", "innerHTML", $text);
            // variat choose box
            $objResponse->addAssign('cityavailableResults', 'style.display', 'none');
            $objResponse->addAssign('cityavailableResultsMessage', 'innerHTML', '');
            //  custom variant choose box
            $objResponse->addAssign('cityavailableChoose', 'style.display', '');
            $objResponse->addAssign('city_correct', 'checked', false);         
            // upate query string selected
            $objResponse->addAssign("cityQuerySelected", "value", $city); 
            // selected alias
            $objResponse->addAssign("cityAliasSelected", "value", '');                        
        }
    }
    else {
	    // recognization status (left block)
	    $text = '<label style="color:red;">'.Warecorp::t('UNRECOGNIZED').'</label>';
	    $objResponse->addAssign("cityavailable", "innerHTML", $text);
	    // variat choose box
	    $objResponse->addAssign('cityavailableResults', 'style.display', 'none');
	    $objResponse->addAssign('cityavailableResultsMessage', 'innerHTML', '');
	    //  custom variant choose box
	    $objResponse->addAssign('cityavailableChoose', 'style.display', 'none');
	    $objResponse->addAssign('city_correct', 'checked', false);         
	    // upate query string selected
	    $objResponse->addAssign("cityQuerySelected", "value", $city);  
        // selected alias
        $objResponse->addAssign("cityAliasSelected", "value", '');            	    
    }
    return;