<?php
    function smarty_function_template_videogallery_default($params, &$smarty)
    {
		Warecorp::addTranslation('/plugins/function.template_videogallery_default.php.xml');
		$theme = Zend_Registry::get('AppTheme');
		$IsShared = false;
		$IsWatched = false;
		$lastVideo = false;
		if (isset($params['lastVideo'])) {
			$lastVideo = $params['lastVideo'];
		}
		if (isset($params['IsShared'])) {
			$IsShared = $params['IsShared'];
		}
		if (isset($params['IsWatched'])) {
			$IsWatched = $params['IsWatched'];
		}
		$output = "<div class='prMediaItem'><div class='prMediaInner'>";

		if ($lastVideo->getId()){
			$output .= "<a href='".$params['currentOwner']->getGlobalPath()."videogalleryView/id/".$lastVideo->getId()."/'>";
			$output .= "<img  height='175' width='175' src='".$lastVideo->getCover()->setWidth(175)->setHeight(175)->getImage($params['user'])."' />";
			$output .= "</a>";
		}
		else{
			$output .= "<img  height='175' width='175' src='".$lastVideo->getCover()->setWidth(175)->setHeight(175)->getImage()."' />";
		}



		if (($IsShared || $IsWatched) && $params['object']->isGalleryUpdated($params['object'], $params['user'])){
			$output .= "<h4 class='prEllipsis' title='".$lastVideo->getTitle()."'><span class='ellipsis_init'>".htmlspecialchars(strip_tags($lastVideo->getTitle()));
			$output .= "<span class=\"prMarkRequired\">";
			$output .= Warecorp::t('&#160;NEW');
			$output .= "</span>";
		}
		else {
			$output .= "<h4 class='prEllipsis' title='".$lastVideo->getTitle()."'><span class='ellipsis_init'>".htmlspecialchars(strip_tags($lastVideo->getTitle()));
		}
		$output .= "</span></h4>";
		$output .= "<div>";
		if ($IsShared && $IsWatched){
			if ($params['AccessManager']->canUnShareGallery($params['object'], $params['currentOwner'], $params['user'])){
				$output .= "<a href='javascript:void(0)' onclick=\"PGLApplication.showUnsharePanel('".$params['object']->getId()."'); return false;\">".Warecorp::t('Unshare')."</a>&#160;";
			}
			if ($params['AccessManager']->canStopWatchingGallery($params['object'], $params['currentOwner'], $params['user'])){
				$output .= "<a href='javascript:void(0)' onclick=\"PGLApplication.showStopWatchingPanel('".$params['object']->getId()."'); return false;\">".Warecorp::t('Stop Watching')."</a>&#160;";
			}
		}
		elseif ($IsShared){
			if ($params['AccessManager']->canUnShareGallery($params['object'], $params['currentOwner'], $params['user'])){
				$output .= "<a href='javascript:void(0)' onclick=\"PGLApplication.showUnsharePanel('".$params['object']->getId()."'); return false;\">".Warecorp::t('Unshare')."</a>&#160;";
			}
		}
		elseif ($IsWatched){
			if ($params['AccessManager']->canStopWatchingGallery($params['object'], $params['currentOwner'], $params['user'])){
				$output .= "<a href='javascript:void(0)' onclick=\"PGLApplication.showStopWatchingPanel('".$params['object']->getId()."'); return false;\">".Warecorp::t('Stop Watching')."</a>&#160;";
			}
		}
		else {
			if ($params['AccessManager']->canEditGallery($params['object'], $params['currentOwner'], $params['user'])){
				$output .= "<a href='".$params['currentOwner']->getGlobalPath()."videogalleryedit/gallery/".$params['object']->getId()."/'>".Warecorp::t('Edit')."</a>&#160;";
			}
			if ($params['AccessManager']->canShareGallery($params['object'], $params['currentOwner'], $params['user'])) {
                $output .= "<a href='javascript:void(0)' onclick=\"PGLApplication.showShareMenu(this, '".$params['object']->getId()."', null); return false;\">Share</a>&#160;";
			}
			if ($params['AccessManager']->canDeleteGallery($params['object'], $params['currentOwner'], $params['user'])) {
                $output .="<a href='javascript:void(0)' onclick=\"PGLApplication.showDeletePanel('".$params['object']->getId()."', null, 300, 80); return false;\">".Warecorp::t('Delete')."</a>&#160;";
			}
		}
		$output .= "</div>"; 
		$output .= "<div class='prIndentBottom'>";
		if ($IsShared || $IsWatched){
			if ($IsWatched) {
				$output .= "<span class=\"prText4\">".Warecorp::t("Watched")."</span>";
				}
			elseif ($IsShared) {
				$output .= "<span class=\"prText4\">".Warecorp::t("Shared by")."</span>";
			}

			if ($params['object']->getOwnerType() == 'user')
				{
				$output .= "&nbsp;<a class=\"prLink3\" href=\"".$params['object']->getCreator()->getUserPath()."profile/\">".substr($params['object']->getOwner()->getLogin(), 0, 20)."</a><br />";
				}
			else {
				$output .= "<span class='znPI-byName prText4'>&#160;".substr(Warecorp::t('%s Group', $params['object']->getOwner()->getName()), 0, 20)."</span><br />";
			}
		}
		if ($lastVideo->getId()){
			if ($IsShared && $IsWatched)
			{
				$output .= "<span class='prText4'>".Warecorp::t('Shared with you on %s', Warecorp:: user_date_format($params['object']->getShareDate($params['currentOwner']), $params['object']->getCreator()->getTimezone()))."</span><br />";
				$output .= "<span class='prText4'>".Warecorp::t('Posted on %s', Warecorp:: user_date_format($params['object']->getCreateDate(), $params['object']->getCreator()->getTimezone()))."<br />";
				$output .= Warecorp::t('By </span><a class="prLink3" href="'.$params['object']->getCreator()->getUserPath().'">%s</a>', substr($params['object']->getCreator()->getLogin(), 0, 20))."<br />";
			}
			elseif ($IsShared){
				$output .= "<span class='prText4'>".Warecorp::t('Shared with you on %s', Warecorp:: user_date_format($params['object']->getShareDate($params['currentOwner']), $params['object']->getCreator()->getTimezone()))."</span>";
			}
			else{
				$output .= "<span class='prText4'>".Warecorp::t('Posted on %s  <br /> By ', array(Warecorp::user_date_format($params['object']->getCreateDate())))." </span>";
				$output .= '<a class="prLink3" href="'.$params['object']->getCreator()->getUserPath().'">'.substr($params['object']->getCreator()->getLogin(), 0, 20).'</a><br />';
				//$output .= Warecorp::t('By </span><a class="prLink3" href="'.$params['object']->getCreator()->getUserPath().'">%s</a>', substr($params['object']->getCreator()->getLogin(), 0, 20))."<br />";
			}
		}
		$output .= "<div class='prText4'>".$lastVideo->getCommentsCount()." ".Warecorp::t('comments')."</div>";
		$output .= "</div></div>";
		$output .= "</div>";
        return $output;
    }
?>