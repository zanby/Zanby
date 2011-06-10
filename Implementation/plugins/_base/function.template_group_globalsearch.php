<?php
function smarty_function_template_group_globalsearch($params, &$smarty)
{
	Warecorp::addTranslation('/plugins/function.template_group_globalsearch.php.xml');  
	$src = $params['object']->entityPicture()->setWidth(55)->setHeight(55)->setBorder(1)->getImage();
	$theme = Zend_Registry::get('AppTheme');
   $output = "<td class='prTCenter prText5'>";
   $output .= "<a class='prSearchIcon' href='".$params['object']->getGroupPath('summary')."' style='background:url(".$src.") no-repeat center;'><img src='{$theme->images}/decorators/icons/group.gif' title='' /></a><br />".Warecorp::t('Group')."</td>";
	$output  .= "<td><div><a class='prLink2' href='".$params['object']->getGroupPath('summary')."'>".htmlspecialchars(strip_tags($params['object']->entityTitle()))."</a></div>";
	$output .= "<div class='prIndentTopSmall'>".substr(htmlspecialchars(strip_tags($params['object']->entityDescription())), 0, 200);
	if (strlen($params['object']->entityDescription()) > 200){
	$output .= "...";
	}
	$output .= "</div></td>";
	$output .= "<td>".$params['object']->entityCity().",<br />".$params['object']->entityState()."</td>";
	$output .= "<td>".Warecorp::user_date_format($params['object']->entityCreationDate())."</td>";
	$output .= "<td>".$params['object']->entityItemsCount()." ".Warecorp::t('members')."</td>";
	$output .= "<td><a class='prLink3' href='".$params['object']->getGroupPath('joingroup')."'>".Warecorp::t('Join the group')."</a><div>(";
	if ($params['object']->getJoinMode() == 0){
		$output .= Warecorp::t("Anyone may join");
	}
	elseif ($params['object']->getJoinMode() == 1){
		$output .= Warecorp::t("Contact Host to request membership");
	}
	elseif ($params['object']->getJoinMode() == 2){
		$output .= Warecorp::t("Join with Code");
	}
	$output .= ")</div></td>";
	return $output;
}