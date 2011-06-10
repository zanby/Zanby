<?php
    function smarty_function_template_lists_default ($params, &$smarty)
    {      
	Warecorp::addTranslation('/plugins/function.template_lists_default.php.xml');
	$theme = Zend_Registry::get('AppTheme');
	$output = "";
	$ListTypePrinted = $params['ListTypePrinted'];
	if ($ListTypePrinted ==0){
	$output .= "<div class='prClr2'>";
	$output .= "<h2>".$params['object']->getListTypeName()."</h2>";
	$output .= "</div>";
	}
	$output .= "<div class='prListItem'><h3>";
	if ($params['object']->getIsWatched()){ 
		$output .= "<a href='".$params['object']->getListPath()."'>".htmlspecialchars(strip_tags($params['object']->getTitle()))."</a></h3> <span class='prText4'>";
		if ($params['object']->getRecordsCount() != 1){
			$output .= Warecorp::t('%s items', $params['object']->getRecordsCount());
		}
		else {
			$output .= Warecorp::t('%s item', $params['object']->getRecordsCount());
		}
		$output .= "</span>";		
		$output .= "<img class='prListFlag' src='{$theme->images}/decorators/watching.gif'/>";
		if ($params['object']->getViewDate() && $params['object']->getViewDate()<$params['object']->getUpdateDate()){
			$output .= "<img class='prListFlag' src='{$theme->images}/decorators/updated.gif'/>";
		}
	}
	else{
			$output .= "<a href='".$params['currentOwner']->getGlobalPath('listsview')."listid/".$params['object']->getId()."/'>".htmlspecialchars(strip_tags($params['object']->getTitle()))."</a></h3> <span class='prText4'>";
			if ($params['object']->getRecordsCount() != 1){
				$output .= Warecorp::t('%s items', $params['object']->getRecordsCount());
			}
			else {
				$output .= Warecorp::t('%s item', $params['object']->getRecordsCount());
			}
			$output .= "</span>";
	} 
	$output .= "<br /><span class='prText4'>".Warecorp::user_date_format($params['object']->getCreationDate(), $params['object']->getCreator()->getTimezone())."</span> ";
	$output .= "<span>";


    $manageLinks = array();
	if ($params['object']->getIsShared()){
		if ($params['list_Access']->canManageLists($params['currentOwner'], $params['user'])){
            $manageLinks[] = "<a href='javascript:void(0)' onclick=\"xajax_list_confirm_popup_show(".$params['object']->getId().", 'unshare'); return false;\">".Warecorp::t('Unshare')."</a>";
		}
	} elseif ($params['object']->getIsWatched()){
        if ( !$params['object']->isSystemWhoWillFor(HTTP_CONTEXT) ) {
			if ($params['list_Access']->canManageLists($params['currentOwner'], $params['user'])){
                $manageLinks[] = "<a href='javascript:void(0)' onclick=\"xajax_list_confirm_popup_show(".$params['object']->getId().", 'offwatch'); return false;\">".Warecorp::t('Delete')."</a>";
			}
        }
	} else{
		if ($params['list_Access']->canManageList($params['object'], $params['currentOwner'], $params['user'])){
            $manageLinks[] = "<a href='".$params['editListLink']."listid/".$params['object']->getId()."/'>".Warecorp::t('Edit')."</a>";
		}
        if ( !$params['object']->isSystemWhoWillFor(HTTP_CONTEXT) ) {
            if ($params['list_Access']->canManageList($params['object'], $params['currentOwner'], $params['user'])){
                $manageLinks[] = "<a href='javascript:void(0)' onclick=\"xajax_list_confirm_popup_show(".$params['object']->getId().", 'delete'); return false;\">".Warecorp::t('Delete')."</a>";
            }
        }
		if ($params['list_Access']->canShareList($params['object'], $params['currentOwner'], $params['user'])){
            $manageLinks[] = "<a href='javascript:void(0)' onclick=\"xajax_list_share_popup_show(".$params['object']->getId()."); return false;\">".Warecorp::t('Share')."</a>";
		}
	}
	if ($params['listsList'] != 1) {
		if ($params['list_Access']->canManageLists($params['currentOwner'], $params['user'])){
            $manageLinks[] = "<a href='".$params['currentOwner']->getGlobalPath('listsexport')."id/".$params['object']->getId()."/'> ".Warecorp::t('Export to CSV')."</a>";
		}
	}

    $output .= join(' | ', $manageLinks);

	$output .= "</span></div>";
    return $output;
	}