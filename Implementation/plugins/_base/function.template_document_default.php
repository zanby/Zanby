<?php
    function smarty_function_template_document_default($params, &$smarty)
    {
		Warecorp::addTranslation('/plugins/function.template_document_default.php.xml');
		$output  = "<td style='border: 1px solid black;'> <img src='{$params['object']->entityPicture()}'></td>";
		$output .= "<td style='border: 1px solid black;'> ".Warecorp::t('title')." - ".htmlspecialchars(strip_tags($params['object']->entityTitle()))."</td>";
		$output .= "<td style='border: 1px solid black;'> ".Warecorp::t('Decription')." - ".htmlspecialchars(strip_tags($params['object']->entityDescription()))."</td>";
		$output .= "<td style='border: 1px solid black;'> ".Warecorp::t('Date')." - ".$params['object']->entityCreationDate()."</td>";
		$output .= "<td style='border: 1px solid black;'> ".Warecorp::t('entityAuthor')." - ".$params['object']->entityAuthor()."</td>";
		$output .= "<td> URL - ".$params['object']->entityURL()."</td>";
		return $output;
	}