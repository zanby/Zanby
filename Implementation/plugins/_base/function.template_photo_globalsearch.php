<?php
    function smarty_function_template_photo_globalsearch($params, &$smarty)
    {
		Warecorp::addTranslation('/plugins/function.template_photo_globalsearch.php.xml');
		$output = "<div class='prMediaItem'><div class='prMediaInner'>";
        $output .= "<a href='".$params['object']->getPhotoPath()."'><img src='".$params['object']->entityPicture()->setWidth(175)->setHeight(175)->getImage($params['user'])."' /></a>";
        $output .= "<h4>".substr(htmlspecialchars(strip_tags($params['object']->entityTitle())), 0, 20)."</h4>";
        $output .= "<div class=\"prIndentBottom\">";
		if ($IsShared && $IsWatched) {

            $output .= Warecorp::t('Shared with you on %s', Warecorp:: user_date_format($params['object']->getShareDate($params['currentOwner']), $params['currentOwner']->getTimezone()))."<br />";

            $output .= "<span class=\"prText4\">". Warecorp::t('Uploaded on&#160;');
            $output .= Warecorp:: user_date_format($params['object']->getCreateDate(), $params['object']->getOwner()->getTimezone())."<br />";
            $output .= Warecorp::t('by&#160;');
            $output .= "<a class=\"prLink3\" href=\"".$params['object']->getCreator()->getUserPath()."profile/\">" .substr($params['object']->getCreator()->getLogin(),0,20)."</a>";
        }

        elseif ($IsShared) {
            $output .= Warecorp::t('Shared with you on %s', Warecorp:: user_date_format($params['object']->getShareDate($params['currentOwner']), $params['currentOwner']->getTimezone()))."<br />";
        }
            else {
                $output .= "<span class=\"prText4\">". Warecorp::t('Uploaded on&#160;');
                $output .= Warecorp::user_date_format($params['object']->getCreateDate())."<br />";
                $output .= Warecorp::t('by&#160;')."</span>";
                $output .= "<a class=\"prLink3\" href=\"".$params['object']->getCreator()->getUserPath()."profile/\">" .substr($params['object']->getCreator()->getLogin(),0,20)."</a>";
            }

		$output .= "</div>";
        $output .= "<span class=\"prText4\">".$params['object']->getCommentsCount()." ".Warecorp::t('comments')."</span><br />";
        if ( empty($params['user']) || $params['user']->getId() != null )
            $output .= "<a class='prLink3' href='javascript:void(0)' onclick=\"SearchApplication.photoAddToMy('".$params['object']->getGalleryId()."', '".$params['object']->getId()."', 0); return false;\">";
        else
            $output .= "<a class='prLink3' href='".BASE_URL."/".LOCALE."/users/login/'>";
		$output .= Warecorp::t('Add to My Photos')."</a>";
        $output .= "</div></div>";
        return $output;
    }
