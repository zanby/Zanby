<?php
    function smarty_function_template_discussion_default($params, &$smarty)
    {      
		Warecorp::addTranslation('/plugins/function.template_Discussion_default.php.xml');
        $output  = "<td style='border: 1px solid black;'> ".Warecorp::t('Discussion')."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('title')." - ".htmlspecialchars(strip_tags($params['object']->entityTitle()))."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('Decription')." - ".substr($params['object']->entityDescription(), 0, 200)."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('Date')." - ".$params['object']->entityCreationDate()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('entityItemsCount')." - ".$params['object']->entityItemsCount()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('entityCommentsCount')." - ".$params['object']->entityCommentsCount()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('URL')." - ".$params['object']->entityUrl()."</td>";
        return $output;

    }                                           