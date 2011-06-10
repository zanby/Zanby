<?php
function smarty_function_template_event_ournew($params, &$smarty) {
	Warecorp::addTranslation('/plugins/function.template_event_ournew.php.xml');
	$theme = Zend_Registry::get('AppTheme');
	if ($params['object']->getOwnerType() == 'user') {
		$output .= $params['object']->displayDate('search.results', $params['user'], $params['currentTimezone']);
		if ($params['object']->getPictureId()) {
			$src = $params['object']->getEventPicture()->setWidth(37)->setHeight(38)->getImage($params['user']);
		}
		else {
			$src = "{$theme->images}/decorators/fakeImage.gif";
		}
		$output .= "<td><img class='prFloatLeft' src='".$src."' /></td>";
		$output .= "<h4><a href='".$params['object']->entityURL()."'>".$params['object']->getTitle()."</a></h4>";
		$output .= "<div class='prClr2'>";
		$output .= "<div class='prFloatLeft'>".Warecorp::t('Organizer:')."</div>";
		$output .= "<div class='prFloatLeft'>".$params['object']->getCreator()->getLogin()."</div>";
		$output .= "</div>";
		if ($params['object']->getCategories()->getCount()) {
			$output .= "<div class='prClr2'>";
			$output .= "<div class='prFloatLeft'>".Warecorp::t('Event Category:')."</div>";
			$output .= "<div class='prFloatLeft'>";
			foreach ($params['object']->getCategories()->setFetchMode('object')->getList() as $params['object']){
				$output .= $params['object']->getCategory()->getName().",";
			}
			$output .="</div></div>";
		}
		$output .= "</td>";
		$output .= "<td>";
		if ($params['Warecorp_Venue_AccessManager']->canViewPrivateVenue($params['object'], $params['currentUser'], $params['user'])) {
			if ($params['object']->getEventVenue()) {
				if ( $params['object']->getEventVenue()->getType() == 'worldwide') {
					$output .= $params['object']->getEventVenue()->getCategory()->getName();
				}
				else {
					if (isset($params['object']->getEventVenue()->getCity()->name)) {
						$output .= $params['object']->getEventVenue()->getCity()->name;
					}
					if ($params['object']->getEventVenue()->getCity()->getState()->name) {
						$output .= "<br />".$params['object']->getEventVenue()->getCity()->getState()->name.", ";
					}
					if ($params['object']->getEventVenue()->getCity()->getState()->code) {
						$output .= $params['object']->getEventVenue()->getCity()->getState()->code;
					}
				}
			}
		}	
		$output .= "</td>";
		$output .= "<td>".$params['object']->displayDate('search.results', $params['user'], $params['currentTimezone'])."</td>";
		$output .= "<td><a href=\'#null\' onclick=\'xajax_doAddToMy(".$objCopyEvent->getId().", ".$objCopyEvent->getUid()."); return false;\'></td>";
	}
	else {
		$output .= "<tr>";
		$output .= "<td class='freeClass'>".$params['object']->displayDate('search.results', $params['user'], $params['currentTimezone'])."</td>";
		$output .= "<td class='freeClass'><h4><a href=".$params['object']->entityURL().">".$params['object']->getTitle()."</a></h4>";
		$output .= "<div class='prIndentTop'>";
		if ($params['object']->getPictureId()) {
			$output .= "<img class='image_thumb prFloatLeft' src='".$params['object']->getEventPicture()->setWidth(37)->setHeight(38)->getImage($params['user'])."' />";
		}
		else {
			$output .= "<img src='{$theme->images}/decorators/fakeImage.gif' class='prFloatLeft' />";
		}
		$output .= "<div>";
		$output .= $params['object']->getDescription();
		$output .= "<a href='".$params['object']->entityURL()."' class='znIndentLeft'>".Warecorp::t('More')."</a>";
		$output .= "</div>";
        $output .= "<div class='prClr2'>";
		$output .= "<div class='prFloatLeft'>".Warecorp::t('Organizer:')."</div>";
		$output .= "<div class='prFloatLeft'>".$params['object']->getCreator()->getLogin()."</div>";
		$output .= "</div>";
		$output .= "<div class='prClr2'>";
		$output .= "<div class='prFloatLeft'>".Warecorp::t('Group Event:')."</div>";
		$output .= "<div class='prFloatLeft'>".$params['object']->getOwner()->getName()."</div>";
		$output .= "</div>";
		if ($params['object']->getCategories()->getCount()) {
			$output .= "<div class='prClr2'>";
			$output .= "<div class='prFloatLeft'>".Warecorp::t('Event Category:')."</div>";
			$output .= "<div class='prFloatLeft'>";
			foreach ($params['object']->getCategories()->setFetchMode('object')->getList() as $params['object']){
				$output .= $params['object']->getCategory()->getName().",";
			}
			$output .="</div></div>";
		}
		$output .= "</div>";
		$output .= "</td>";
		$output .= "<td>";
        if ($params['Warecorp_Venue_AccessManager']->canViewPrivateVenue($params['object'], $params['Warecorp_Group_Factory']->loadById($params['object']->getOwnerId()), $params['user'])) {
            if ($params['object']->getEventVenue()) {
                $output .="<a>".$params['object']->getEventVenue()->getName()."</a><br />";
                if ($params['object']->getEventVenue()->getType() == 'worldwide') {
                    $output .= $params['object']->getEventVenue()->getCategory()->getName();
				}
                else {
                    if ($params['object']->getEventVenue()->getAddress1()) {
						$output .= $params['object']->getEventVenue()->getAddress1();
					}
                    if ($params['object']->getEventVenue()->getCity()->name) {
						$output .= $params['object']->getEventVenue()->getCity()->name ;
					}
                    if ($params['object']->getEventVenue()->getCity()->getState()->code) {
						$output .= $params['object']->getEventVenue()->getCity()->getState()->code;
					}
                    elseif ($params['object']->getEventVenue()->getCity()->getState()->name) {
						$output .= $params['object']->getEventVenue()->getCity()->getState()->name;
					}
				}
            }
        }
        if (null !== $userAttendee) {
			if ($userAttendee->getAnswer() == 'NONE'){
				$output .= "<a href='#null' onclick='xajax_doAttendeeEvent(".$params['object']->getId().", ".$params['object']->getUid().", 'list', null, null, params); return false;'><img src='{$theme->images}/decorators/event/btnRSVP.gif' /></a>";
			}
			elseif ($userAttendee->getAnswer() == 'YES') {
				$output .= "<a href='#null' onclick='xajax_doAttendeeEvent(".$params['object']->getId().", ".$params['object']->getUid().", 'list', null, null, params); return false;'><img src='{$theme->images}/decorators/event/btnAttending.gif' /></a>";
			}
			elseif ($userAttendee->getAnswer() == 'NO') {
				$output .= "<a href='#null' onclick='xajax_doAttendeeEvent(".$params['object']->getId().", ".$params['object']->getUid().", 'list', null, null, params); return false;'><img src='{$theme->images}/decorators/event/btnNotAttending.gif' /></a>";
			}
			elseif ($userAttendee->getAnswer() == 'MAYBE') {
				$output .= "<a href='#null' onclick='xajax_doAttendeeEvent(".$params['object']->getId().", ".$params['object']->getUid().", 'list', null, null, params); return false;'><img src='{$theme->images}/decorators/event/btnMaybe.gif' /></a>";
			}	
		}
		$output .= "</td>";
		$output .= "</tr>";
	}
}
?>
