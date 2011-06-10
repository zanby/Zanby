<?php
    function smarty_function_template_event_default($params, &$smarty)
    {    
		Warecorp::addTranslation('/plugins/function.template_event_default.php.xml');  
        $output  = "<td style='border: 1px solid black;'> ".Warecorp::t('title')." - ".$params['object']->entityTitle()."</td>";
      //  $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('entityPicture')." - ".$CurrentGroup->getGroupPath('videogalleryView')."id/".$this->getId()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('URL')." - ".$params['object']->entityURL()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('Description')." - ".$params['object']->entityDescription()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('City')." - ".$params['object']->entityCity()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('State')." - ".$params['object']->entityState()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('Country')." - ".$params['object']->entityCountry()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('Date')." - ".$params['object']->entityCreationDate()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('entityObjectId')." - ".$params['object']->entityObjectId()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('entityOwnerType')." - ".$params['object']->entityOwnerType()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('entityHeadline')." - ".$params['object']->entityHeadline()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('entityAuthor')." - ".$params['object']->entityAuthor()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('entityCreationDate')." - ".$params['object']->entityCreationDate()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('entityUpdateDate')." - ".$params['object']->entityUpdateDate()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('entityItemsCount')." - ".$params['object']->entityItemsCount()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('entityCategory')." - ".$params['object']->entityCategory()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('entityCountry')." - ".$params['object']->entityCountry()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('entityCountryId')." - ".$params['object']->entityCountryId()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('entityCity')." - ".$params['object']->entityCity()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('entityCityId')." - ".$params['object']->entityCityId()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('entityZIP')." - ".$params['object']->entityZIP()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('entityState')." - ".$params['object']->entityState()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('entityStateId')." - ".$params['object']->entityStateId()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('entityVideo')." - ".$params['object']->entityVideo()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".$params['object']->entityURL()."</td>";

        return $output;
    }
    