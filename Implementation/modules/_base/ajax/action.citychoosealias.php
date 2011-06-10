<?php
    Warecorp::addTranslation("/modules/ajax/action.citychoosealias.php.xml");
    $objResponse = new xajaxResponse();
    if ( $alias ) {
    	$objCity = Warecorp_Location_City::create($alias);
    	if ( null === $objCity->id ) return;
        
    	$lstCityAliasIds = Warecorp_Location_Alias_List::detectAliasByQueryString($query, $objCity->getState()->getCountry()->id);
        $lstCities = $objCity->getState()->getCountry()->findByCityNameOrIds($query, $lstCityAliasIds, $objCity->id);
        $lstCitiesSize = sizeof($lstCities);

        $isRECOGNIZED   = true;
        $outRECOGNIZED  = $objCity->name.', '.$objCity->getState()->name.', '.$objCity->getState()->getCountry()->name;
        // recognization status (left block)
        $text = '<label style="color:green;">'.Warecorp::t('RECOGNIZED').( ($outRECOGNIZED) ? ' : '.$outRECOGNIZED : '' ).'</label>';
        $objResponse->addAssign("cityavailable", "innerHTML", $text);
        // selected alias
        $objResponse->addAssign("cityAliasSelected", "value", $alias);
        
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
            $objResponse->addAssign('city_correct', 'checked', false);        
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
            $objResponse->addAssign('city_correct', 'checked', false);        
            // upate query string selected
            $objResponse->addAssign("cityQuerySelected", "value", $query);        	
        }        
        return;
    }
