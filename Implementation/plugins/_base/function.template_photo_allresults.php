<?php
    function smarty_function_template_photo_allresults($params, &$smarty)
    {    
        $src = $params['object']->entityPicture()->setWidth(55)->setHeight(55)->getImage($user);
		$theme = Zend_Registry::get('AppTheme');
		Warecorp::addTranslation('/plugins/function.template_photo_allresults.php.xml');
		$output = "<td class='prTCenter prText5'><a class='prSearchIcon' style='background:url(".$src.") no-repeat center;' href='".$params['object']->getPhotoPath()."'><img src='{$theme->images}/decorators/icons/photo.gif' /></a><br />Photo</td>";
        $output .= "<td><a class='prLink2' href='".$params['object']->getPhotoPath()."'>".substr(htmlspecialchars(strip_tags($params['object']->entityTitle())), 0, 20)."</a>";
        $output .= "<div class=\"prIndentBottom\">";
		if ($IsShared && $IsWatched) {
										$output .= Warecorp::t('By&#160;');
										$output .= substr($params['object']->getCreator()->getLogin(),0,20).", ".Warecorp::user_date_format($params['object']->getCreateDate())."<br />";
										$output .= Warecorp::t('Shared with you on %s', Warecorp:: user_date_format($params['object']->getShareDate($params['currentOwner']), $params['currentOwner']->getTimezone()))."<br />";
									}
										
									elseif ($IsShared) {
										$output .= Warecorp::t('Shared with you on %s', Warecorp:: user_date_format($params['object']->getShareDate($params['currentOwner']), $params['currentOwner']->getTimezone()))."<br />";
									}
									else {
										$output .= Warecorp::t('By&#160;')."</span>";
										$output .= substr($params['object']->getCreator()->getLogin(),0,20).", ".Warecorp::user_date_format($params['object']->getCreateDate())."<br />";
									}                                       
									    
		$output .= "</div>";
        $output .= "<span class=\"prText4\">".$params['object']->getCommentsCount()." ".Warecorp::t('comments')."</span><br /></td>";
		$output .= "<td></td>";
		$output .= "<td></td>";
		$output .= "<td></td>";
        $output .= "<td><a class='prLink3' href='#null' onclick=\"SearchApplication.photoAddToMy('".$params['object']->getGalleryId()."', '".$params['object']->getId()."', 0); return false;\">".Warecorp::t('Add to My Photos')."</a></td>"; 
        return $output;
    }
    
