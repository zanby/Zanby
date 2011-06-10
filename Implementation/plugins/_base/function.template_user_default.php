<?php
    function smarty_function_template_user_default($params, &$smarty)
    {
		Warecorp::addTranslation('/plugins/function.template_user_default.php.xml');
        $output  = "<td style='border: 1px solid black;'> ".Warecorp::t('login')." - ".htmlspecialchars(strip_tags($params['object']->entityTitle()))."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('entityPicture')." - <img src='".$params['object']->entityPicture()->setWidth(37)->setHeight(37)->setBorder(1)->getImage()."' /></td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('City')." - ".$params['object']->entityCity()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('State')." - ".$params['object']->entityState()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('Country')." - ".$params['object']->entityCountry()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('Joined date')." - ".Warecorp::user_date_format($params['object']->entityCreationDate())."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('Last activee')." - ".Warecorp::user_date_format($params['object']->entityUpdateDate())."</td>";

        return $output;
    }
    