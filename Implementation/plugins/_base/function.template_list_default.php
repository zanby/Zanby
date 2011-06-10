<?php
    function smarty_function_template_list_default($params, &$smarty)
    {
		Warecorp::addTranslation('/plugins/function.template_list_default.php.xml');
        $output  = "<td> ".Warecorp::t('icon')." </td>";
        $output .= "<td> ".Warecorp::t('title')." - ".htmlspecialchars(strip_tags($params['object']->entityTitle()))."</td>";
        $output .= "<td> ".Warecorp::t('Decription')." - ".htmlspecialchars(strip_tags($params['object']->entityDescription()))."</td>";
        $output .= "<td> ".Warecorp::t('Date')." - ".Warecorp::user_date_format($params['object']->entityCreationDate())."</td>";
        $output .= "<td> ".Warecorp::t('entityAuthor')." - ".$params['object']->entityAuthor()."</td>";
        $output .= "<td> ".Warecorp::t('entityItemsCount')." - ".$params['object']->entityItemsCount()."</td>";
        return $output;
    }
    