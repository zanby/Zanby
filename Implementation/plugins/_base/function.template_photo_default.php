<?php
    function smarty_function_template_photo_default($params, &$smarty)
    {
		Warecorp::addTranslation('/plugins/function.template_photo_default.php.xml');
        $output = "<td style='border: 1px solid black;'> ".Warecorp::t('entityPicture')." - <img src='".$params['object']->entityPicture()->setWidth(50)->setHeight(50)->getImage($user)."' /></td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('title')." - ".$params['object']->entityTitle()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('Date')." - ".Warecorp::user_date_format($params['object']->entityCreationDate())."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('entityAuthor')." - ".$params['object']->entityAuthor()."</td>";
        $output .= "<td style='border: 1px solid black;'> ".Warecorp::t('entityCommentsCount')." - ".$params['object']->entityItemsCount()."</td>";

        return $output;
    }
    