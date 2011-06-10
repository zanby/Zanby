<?php
	function smarty_function_template_group_default($params, &$smarty)
	{
		Warecorp::addTranslation('/plugins/function.template_group_default.php.xml');
		// $output = "<td>qw";
		// $output .= "<img src=".$params['object']->entityPicture()->setWidth(37)->setHeight(37)->setBorder(1)->getImage()." title='' />";
		// $output .= "</td>";
		$output .= "<td style='border: 1px solid black;'> ".Warecorp::t('title')." - ".htmlspecialchars(strip_tags($params['object']->entityTitle()))."</td>";
		$output .= "<td style='border: 1px solid black;'> ".Warecorp::t('Decription')." - ".htmlspecialchars(strip_tags($params['object']->entityDescription()))."</td>";
		$output .= "<td style='border: 1px solid black;'> ".Warecorp::t('City')." - ".$params['object']->entityCity()."</td>";
		$output .= "<td style='border: 1px solid black;'> ".Warecorp::t('State')." - ".$params['object']->entityState()."</td>";
		$output .= "<td style='border: 1px solid black;'> ".Warecorp::t('Country')." - ".$params['object']->entityCountry()."</td>";
		$output .= "<td style='border: 1px solid black;'> ".Warecorp::t('Creation date')." - ".Warecorp::user_date_format($params['object']->entityCreationDate())."</td>";
		$output .= "<td style='border: 1px solid black;'> ".Warecorp::t('Members Count')." - ".$params['object']->entityItemsCount()."</td>";
		$output .= "<td style='border: 1px solid black;'> ".Warecorp::t('Link')." - ".$params['object']->getGroupPath('joingroup')."</td>";
		return $output;
	}
    