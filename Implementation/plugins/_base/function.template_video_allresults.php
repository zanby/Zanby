<?php
    function smarty_function_template_video_allresults($params, &$smarty)
    {      
        $src = $params['object']->entityPicture()->setWidth(55)->setHeight(55)->getImage($user);
		$theme = Zend_Registry::get('AppTheme');
		Warecorp::addTranslation('/plugins/function.template_video_allresults.php.xml');
		$output = "<td class='prTCenter prText5'><a class='prSearchIconVideo' style='background:url(".$src.") no-repeat center;'><img src='{$theme->images}/decorators/icons/video.png' /></a><br />".Warecorp::t('Video')."</td>";
        $output .= "<td><a class='prLink2' href='".$params['object']->getVideoPath()."videogalleryView/id/".$params['object']->getId()."/'>".htmlspecialchars(strip_tags($params['object']->getTitle()))."</a>";
        $output .= "<div>".substr(htmlspecialchars(strip_tags($params['object']->entityDescription())), 0, 200);
		if (strlen($params['object']->entityDescription()) > 200){
			$output .= "...";
		}
		$output .= "</div></td>";
		$output .= "<td>".substr($params['object']->getCreator()->getLogin(), 0, 20)."</td>";
		$output .= "<td>".Warecorp::user_date_format($params['object']->getCreateDate())."</td>";
        $output .= "<td>&#160;</td><td><a class='prLink3' href='#null' onclick=\"SearchApplication.videoAddToMy('".$params['object']->getGalleryId()."', '".$params['object']->getId()."', 0); return false;\">".Warecorp::t('Add to My Videos')."</a></td>";
        return $output;
    }
    
?>
