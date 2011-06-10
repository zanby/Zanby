<?php
    function smarty_function_template_video_globalsearch($params, &$smarty)
    {      
        Warecorp::addTranslation('/plugins/function.template_video_globalsearch.php.xml');
		$src = $params['object']->getCover()->setWidth(175)->setHeight(175)->getImage();
		$theme = Zend_Registry::get('AppTheme');
		$output = "<div class='prMediaItemSearch'><div class='prMediaInner'>";
		$output .= "<a href='".$params['object']->getVideoPath()."videogalleryView/id/".$params['object']->getId()."/' class='prSearchIconVideo' style='background:url(".$src.") no-repeat center;'><img src='{$theme->images}/decorators/icons/video.png' alt='' title='' /></a>";
		$output .= "<h4>".substr(htmlspecialchars(strip_tags($params['object']->getTitle())), 0, 20);
		if (strlen ($params['object']->getTitle())>20){
		$output .= "...";
		}
		$output .= "</h4>";
		if ($params['object']->getId()){
			if ($IsShared && $IsWatched)
			{			
				$output .= Warecorp::t('Shared with you on %s', Warecorp:: user_date_format($params['object']->getShareDate($params['currentOwner']), $params['currentOwner']->getTimezone()))."<br />";
				$output .= "<span class='prText4'>".Warecorp::t('Uploaded on %s', Warecorp:: user_date_format($params['object']->getCreateDate(), $params['currentOwner']->getTimezone()))."<br />";
				$output .= Warecorp::t('By </span><a class="prLink3" href="'.$params['object']->getCreator()->getUserPath().'">%s</a>', substr($params['object']->getCreator()->getLogin(), 0, 20))."<br />";
			}
			elseif ($IsShared){
				$output .= Warecorp::t('Shared with you on %s', Warecorp:: user_date_format($params['object']->getShareDate($params['currentOwner']), $params['currentOwner']->getTimezone()));
			}
			else{				
				$output .= "<span>".Warecorp::t('Uploaded on %s', Warecorp::user_date_format($params['object']->getCreateDate()))."<br />";
				$output .= Warecorp::t('By </span><a class="prLink3" href="'.$params['object']->getCreator()->getUserPath().'">%s</a>', substr($params['object']->getCreator()->getLogin(), 0, 20))."<br />";
			}  
		}
		$output .= "<span class='prText4'>".$params['object']->getCommentsCount()." ".Warecorp::t('comments')."</span><br />";
        if ( empty($params['user']) || $params['user']->getId() != null )
            $output .= "<a class='prLink3' href='javascript:void(0)' onclick=\"SearchApplication.videoAddToMy('".$params['object']->getGalleryId()."', '".$params['object']->getId()."', 0); return false;\">";
        else
            $output .= "<a class='prLink3' href='".BASE_URL."/".LOCALE."/users/login/'>";
        $output .= Warecorp::t('Add to My Videos')."</a>";
		$output .= "</div>";
		$output .= "</div>";
        return $output;
    }
    
?>
