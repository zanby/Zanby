<?php
    function smarty_function_template_photogallery_default ($params, &$smarty)
    {      
		Warecorp::addTranslation('/plugins/function.template_photogallery_default.php.xml');
		$IsShared  = false;
		$IsWatched  = false;
		$lastPhoto  = false;
		$theme = Zend_Registry::get('AppTheme');
		if (isset($params['lastPhoto'])) {
			$lastPhoto = $params['lastPhoto'];
		}
		if (isset($params['IsShared'])) {
			$IsShared = $params['IsShared'];
		}
		if (isset($params['IsWatched'])) {
			$IsWatched = $params['IsWatched'];
		}
		
		if ($params['object']->getOwnerType() == 'user'){
			$username = $params['object']->getOwner()->getLogin();
		}
		else{
			 $username=  $params['object']->getOwner()->getName();
		}
		$output = "<div class=\"prMediaItem\">";

        $output .= "<div class=\"prMediaInner\">";

        if ($lastPhoto->getId()) {

            $output .= "<a href=\"".$params['currentOwner']->getGlobalPath()."galleryView/id/".$lastPhoto->getId()."/\">";
            $output .= "<img src=\"".$lastPhoto->setWidth($params['item_width'])->setHeight($params['item_height'])->getImage($params['user'])."\" />";
            $output .= "</a>";
            }
        else {
            if ($params['AccessManager']->canEditGallery($params['object'], $params['currentOwner'], $params['user'])) {
            $output .= "<a href=\"".$params['currentOwner']->getGlobalPath()."galleryedit/gallery/".$params['object']->getId()."/\">";
            $output .= "<img src=\"".$lastPhoto->setWidth($params['item_width'])->setHeight($params['item_height'])->getImage($params['user'])."\" />";
            $output .= "</a>";
            }
            else {
            $output .= "<img src=\"".$lastPhoto->setWidth($params['item_width'])->setHeight($params['item_height'])->getImage($params['user'])."\" />";
            }
        }
		if (($IsShared || $IsWatched) && $params['object']->isGalleryUpdated($params['object'], $params['user'])){
			$output .= "<h4 class='prEllipsis' title='".$params['object']->getTitle()."'><span class='ellipsis_init'>".htmlspecialchars($params['object']->getTitle());
			$output .= "<span class=\"prMarkRequired\">";
			$output .= Warecorp::t('&#160;NEW');
			$output .= "</span>";
		}
        
		else {
				$output .= "<h4 class='prEllipsis' title='".$params['object']->getTitle()."'><span class='ellipsis_init'>".htmlspecialchars($params['object']->getTitle());
        }
        $output .= "</span></h4>";
			
		        if ( $IsShared || $IsWatched ) {
            if ($params['AccessManager']->canUnShareGallery($params['object'], $params['currentOwner'], $params['user'])) {
                $output .= "<a href=\"javascript:void(0)\" onclick=\"PGLApplication.showUnsharePanel('".$params['object']->getId()."'); return false;\">".Warecorp::t('Unshare')."</a> &#160;";
            } elseif ($params['AccessManager']->canStopWatchingGallery($params['object'], $params['currentOwner'], $params['user'])) {
                $output .= "<a href=\"javascript:void(0)\" onclick=\"PGLApplication.showStopWatchingPanel('".$params['object']->getId()."'); return false;\">".Warecorp::t('Stop Watching')."</a> &#160;";
            }
        }
        elseif ($IsShared) {
            if ($params['AccessManager']->canUnShareGallery($params['object'], $params['currentOwner'], $params['user'])) {
                $output .= "<a href=\"javascript:void(0)\" onclick=\"PGLApplication.showUnsharePanel('".$params['object']->getId()."'); return false;\">".Warecorp::t('Unshare')."</a> &#160;";
            }
            elseif ($IsWatched) {
                if ($params['AccessManager']->canStopWatchingGallery($params['object'], $params['currentOwner'], $params['user'])) {
                    $output .= "<a href=\"javascript:void(0)\" onclick=\"PGLApplication.showStopWatchingPanel('".$params['object']->getId()."'); return false;\">".Warecorp::t('Stop Watching')."</a> &#160;";
                }
            }
        }
        else {

            $output .= "<input type=\"hidden\" id=\"showHistory".$params['object']->getId()."\" name=\"showHistory".$params['object']->getId()."\" value=\"";
            if ($params['object']->isShareHistoryExists()) {
                $output .="1";
            } else {
                $output .="0";
            }
            $output .="\" />";

            if ($params['AccessManager']->canEditGallery($params['object'], $params['currentOwner'], $params['user'])) {
                $output .= "<a href=\"".$params['currentOwner']->getGlobalPath()."galleryedit/gallery/".$params['object']->getId()."/\">".Warecorp::t('Edit')."</a> ";}
            if ($params['AccessManager']->canShareGallery($params['object'], $params['currentOwner'], $params['user'])) {
                $output .= "<a href=\"javascript:void(0)\" onclick=\"PGLApplication.showShareMenu(this, '".$params['object']->getId()."', null); return false;\">".Warecorp::t('Share')."</a> ";}
            if ($params['AccessManager']->canDeleteGallery($params['object'], $params['currentOwner'], $params['user'])){
                $output .= "<a href=\"javascript:void(0)\" onclick=\"PGLApplication.showDeletePanel('".$params['object']->getId()."'); return false;\">".Warecorp::t('Delete')."</a> ";}
        }
        
        $output .= "<div>";
            /* $output .= "<a href='".$params['currentOwner']->getUserPath()."galleryView/id/".$lastPhoto->getId()."/'>";
            $output .= Warecorp::t('%s Photos', $params['object']->getPhotos()->getCount());
            $output .= "</a> <br />"; */

        $output .= "</div>";
        $output .= "<div class=\"prIndentBottom\">";
		
		if ($IsShared || $IsWatched) {
            if ($IsWatched) {
                $output .= "<span class=\"prText4\">".Warecorp::t("Watched")."</span>";
                }
            elseif ($IsShared) {
                $output .= "<span class=\"prText4\">".Warecorp::t("Shared by")."</span>";
            }
            if ($params['object']->getOwnerType() == 'user') {
                $output .= "&nbsp;<a class=\"prLink3\" href=\"".$params['object']->getCreator()->getUserPath()."profile/\">";
                $output .= $params['object']->getOwner()->getLogin();
                $output .= "</a><br />";
            }
            else {
                $output .= "<span class=\"znPI-byName\">&#160;";
                $output .= Warecorp::t('%s Group', $params['object']->getOwner()->getName());
                $output .= "</span><br />";
            }
        }

        if ($lastPhoto->getId()) {

            if ( $IsShared && $IsWatched ) {
                $output .="<span class=\"prText4\">". Warecorp::t('Shared with you on %s', Warecorp::user_date_format($params['object']->getShareDate($params['currentOwner']), $params['object']->getCreator()->getTimezone()))."</span><br />";
                $output .= "<span class=\"prText4\">". Warecorp::t('Uploaded on&#160;');
                $output .= Warecorp::user_date_format($params['object']->getCreateDate(), $params['object']->getOwner()->getTimezone())."<br />";
                $output .= Warecorp::t('by&#160;');
                $output .= "<a class=\"prLink3\" href=\"".$params['object']->getCreator()->getUserPath()."profile/\">" .substr($params['object']->getCreator()->getLogin(),0,20)."</a>";
            }
            elseif ($IsShared) {
                $output .="<span class=\"prText4\">". Warecorp::t('Shared with you on %s', Warecorp::user_date_format($params['object']->getShareDate($params['currentOwner']), $params['object']->getCreator()->getTimezone()))."</span><br />";
            }
            else {
                $output .= "<span class=\"prText4\">". Warecorp::t('Uploaded on&#160;');
                $output .= Warecorp::user_date_format($params['object']->getCreateDate(), $params['user']->getTimezone())."<br />";
                $output .= Warecorp::t('by&#160;')."</span>";
                $output .= "<a class=\"prLink3\" href=\"".$params['object']->getCreator()->getUserPath()."profile/\">" .substr($params['object']->getCreator()->getLogin(),0,20)."</a>";
            }
        }
        else {
            $output .= "";
        }

        $output .= "</div>";
						
		$output .= "</div>";
	$output .= "</div>"; 
    return $output;

}