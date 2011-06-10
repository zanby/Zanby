<?php
	function smarty_function_template_group_custom($params, &$smarty)
	{
		Warecorp::addTranslation('/plugins/function.template_group_custom.php.xml');
		$output = "<td>qw";
		$output .= "<img src=".$params['object']->entityPicture()->setWidth(37)->setHeight(37)->setBorder(1)->getImage()." title='' />";
		$output .= "</td>";
		$output .= "<td style='border: 1px solid black;'> ".Warecorp::t('title')." - ".htmlspecialchars(strip_tags($params['object']->entityTitle()))."</td>";
		$output .= "<td style='border: 1px solid black;'> ".Warecorp::t('Creation date')." - ".Warecorp::user_date_format($params['object']->entityCreationDate())."</td>";
		$output .= "<td style='border: 1px solid black;'> ".Warecorp::t('Members Count')." - ".$params['object']->entityItemsCount()."</td>";
		$output .= "<td style='border: 1px solid black;'> ".Warecorp::t('Link')." - ".$params['object']->getGroupPath('joingroup')."</td>";
		return $output;
    }
    