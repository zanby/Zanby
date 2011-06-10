<?php
    function smarty_function_template_discussion_globalsearch($params, &$smarty)
    {    
		Warecorp::addTranslation('/plugins/function.template_discussion_globalsearch.php.xml');
		$theme = Zend_Registry::get('AppTheme');
        $output  = "<td class='prText5 prTCenter'> <img src='{$theme->images}/decorators/discussions/discuss.jpg' width='55' height='31' /><br />".Warecorp::t('Discussion')."</td>";
        $output .= "<td colspan='5'><div><a class='prLink2' href='".$params['object']->entityUrl()."'>".htmlspecialchars(strip_tags($params['object']->entityTitle()))."</a></div>";
		$output .= "<div class='prText4 prIndentTopSmall'>".Warecorp::t('Created:')."&#160;";
		$output .= $params['object']->getUserCreated($params['user']->getTimezone());
		$output .= "</div>";
		$output .= "<div class='prIndentTopSmall'>".substr(htmlspecialchars(strip_tags($params['object']->getTextContent())), 0, 200);
		if (strlen($params['object']->getTextContent()) > 200){
		$output .= "...";
		}
		$output .= "<div class='prIndentTopSmall'><img src='{$theme->images}/decorators/discussions/arrow.jpg' width='9' height='11' /><a class='prLink3' href='".$params['object']->entityUrl()."' title='".$params['object']->entityUrl()."'>".substr($params['object']->entityUrl(), 0, 50);
		if (strlen($params['object']->entityUrl()) > 50){
		$output .= "...";
		}
		$output .= "</a></div>";
		$output .= "</td>";
        return $output;

    }