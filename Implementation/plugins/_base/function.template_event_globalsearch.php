<?php
function smarty_function_template_event_globalsearch($params, &$smarty)
{
        $theme = Zend_Registry::get('AppTheme');
        Warecorp::addTranslation('/plugins/function.template_event_globalsearch.php.xml');
        $theme = Zend_Registry::get('AppTheme');
            if ($params['object']->getPictureId()) {
                $src = $params['object']->getEventPicture()->setWidth(55)->setHeight(55)->getImage($params['user']);
            }
            else {
                $src = $theme->images."/decorators/event/fakeImage.gif";
            }
    if ($params['object']->getOwnerType() == 'user') {
        $output = "<td class='prNoBorder prTCenter prText5'>";
        $output .= "<a class='prSearchIcon' style='background:url(".$src.") no-repeat center;'><img src='{$theme->images}/decorators/icons/calendar.gif' /></a><br />".Warecorp::t('Event')."</td>";
        $output .= "<td class='prNoBorder'>";
        $output .= "<a class='prLink2' href='".$params['object']->entityURL()."'>".htmlspecialchars(strip_tags($params['object']->getTitle()))."</a>";
        $output .= "<div class='prIndentTopSmall'>";
        $output .= "<div class='prEventsInfo'>";
        $output .= "<div class='prEventsLeft'>".Warecorp::t('Organizer:')."</div>";
        $output .= "<div class='prEventsRight'>".$params['object']->getCreator()->getLogin()."</div>";
        $output .= "</div>";
        if ($params['object']->getCategories()->getCount()) {
            $output .= "<div class='prEventsInfo'>";
            $output .= "<div class='prEventsLeft'>".Warecorp::t('Event Category:')."</div>";
            $output .= "<div class='prEventsRight'>";
            $sep = " ";
            foreach ($params['object']->getCategories()->setFetchMode('object')->getList() as $category){
                $output .= $sep.$category->getCategory()->getName();
                $sep = ", ";
            }
            $output .="</div></div>";
        }
        $output .="</div>";
        $output .= "</td>";
        $output .= "<td class='prNoBorder'>";
        if (Warecorp_Venue_AccessManager::canViewPrivateVenue($params['object'], $params['object']->getOwner(), $params['user'])) {
            if ($params['object']->getEventVenue()) {
                if ( $params['object']->getEventVenue()->getType() == 'worldwide') {
                    $output .= htmlspecialchars(strip_tags($params['object']->getEventVenue()->getCategory()->getName()));
                }
                else {
					$separator = '';
                    if (isset($params['object']->getEventVenue()->getCity()->name)) {
                        $output .= $params['object']->getEventVenue()->getCity()->name;
						$separator = ',';
                    }
                    if ($params['object']->getEventVenue()->getCity()->getState()->name) {
                        $output .= $separator."<br />".$params['object']->getEventVenue()->getCity()->getState()->name;
                    }
                    if ($params['object']->getEventVenue()->getCity()->getState()->code) {
                        $output .= ", ".$params['object']->getEventVenue()->getCity()->getState()->code;
                    }
                }
            }
        }
        $output .= "</td>";
        $output .= "<td class='prNoBorder'>".$params['object']->displayDate('search.results', $params['user'], $params['currentTimezone'])."</td>";
        $output .= "<td class='prNoBorder'>&#160;</td><td class='prNoBorder'><a class='prLink3' href='".$params['object']->entityURL()."'>".Warecorp::t('More')."</a><br/>";
        if ($params['user']->getId() != null)
            $output .= "<a href='javascript:void(0)' class='prLink3' onclick=\"SearchApplication.eventAddToMy(".$params['object']->entityURL().", 0); return false;\">";
        else
            $output .= "<a href='".BASE_URL."/".LOCALE."/users/login/' class='prLink3'>";
        $output .= Warecorp::t('Add to My')."<br/>".Warecorp::t('Calendar')."</a></td>";
        $output .= "</tr>";
        $output .= "<tr";
        if ($params['num']==1){
            if (($params['even'] % 2) != 0){
                $output .= " class='prEvenBg'";
            }
            else {
                $output .= " class='prOddBg'";
            }
        }
        else {
            if (($params['even'] % 2) == 0){
                $output .= " class='prEvenBg'";
            }
            else {
                $output .= " class='prOddBg'";
            }
        }
        $output .= "><td>&#160;</td><td colspan='5' class='prText4 prEventViewNotes'>".substr(htmlspecialchars(strip_tags($params['object']->getDescription())), 0, 200);
        if (strlen($params['object']->getDescription()) > 200){
            $output .= "...";
            }
        $output .= "&#160;</td>";
    }
    else {
        $output = "<td class='prNoBorder prText5'>";
        $output = "<td class='prNoBorder prTCenter prText5'>";
        $output .= "<a class='prSearchIcon' style='background:url(".$src.") no-repeat center;'><img src='{$theme->images}/decorators/icons/calendar.gif' /></a><br />".Warecorp::t('Event')."</td>";
        $output .= "<td class='prNoBorder'><a class='prLink2' href=".$params['object']->entityURL().">".htmlspecialchars(strip_tags($params['object']->getTitle()))."</a>";
        $output .= "<div class='prIndentTopSmall'>";
        $output .= "<div class='prEventsInfo'>";
        $output .= "<div class='prEventsLeft'>".Warecorp::t('Organizer:')."</div>";
        $output .= "<div class='prEventsRight'>".$params['object']->getCreator()->getLogin()."</div>";
        $output .= "</div>";
        $output .= "<div class='prEventsInfo'>";
        $output .= "<div class='prEventsLeft'>".Warecorp::t('Group Event:')."</div>";
        $output .= "<div class='prEventsRight'>".$params['object']->getOwner()->getName()."</div>";
        $output .= "</div>";
        if ($params['object']->getCategories()->getCount()) {
            $output .= "<div class='prEventsInfo'>";
            $output .= "<div class='prEventsLeft'>".Warecorp::t('Event Category:')."</div>";
            $output .= "<div class='prEventsRight'>";
            $sep = " ";
            foreach ($params['object']->getCategories()->setFetchMode('object')->getList() as $category){
                $output .= $sep.$category->getCategory()->getName();
                $sep = ", ";
            }
            $output .= "</div></div>";
        }
        $output .= "</div>";
        $output .= "</td>";
        $output .= "<td class='prNoBorder'>";
        if (Warecorp_Venue_AccessManager::canViewPrivateVenue($params['object'], $params['object']->getOwner(), $params['user'])) {
            if ($params['object']->getEventVenue()) {
                $output .="<a>".htmlspecialchars(strip_tags($params['object']->getEventVenue()->getName()))."</a><br />";
                if ($params['object']->getEventVenue()->getType() == 'worldwide') {
                    $output .= htmlspecialchars(strip_tags($params['object']->getEventVenue()->getCategory()->getName()));
                }
                else {
					$separator = '';
                    if ($params['object']->getEventVenue()->getAddress1()) {
                        $output .= $params['object']->getEventVenue()->getAddress1();
						$separator = '<br/> ';
                    }
                    if ($params['object']->getEventVenue()->getCity()->name) {
                        $output .= $separator.$params['object']->getEventVenue()->getCity()->name ;
						$separator = ', ';
                    }
                    if ($params['object']->getEventVenue()->getCity()->getState()->code) {
                        $output .= $separator.$params['object']->getEventVenue()->getCity()->getState()->code;
						$separator = ', ';
                    }
                    elseif ($params['object']->getEventVenue()->getCity()->getState()->name) {
                        $output .= $separator.$params['object']->getEventVenue()->getCity()->getState()->name;
                    }
                }
            }
        }
        if ($userAttendee !== null) {
            if ($userAttendee->getAnswer() == 'NONE'){
                $output .= "<a href='#null' onclick=\"xajax_doAttendeeEvent(".$params['object']->getId().", ".$params['object']->getUid().", 'list', null, null, params); return false;\"><img src='".$theme->images."/decorators/event/btnRSVP.gif' /></a>";
            }
            elseif ($userAttendee->getAnswer() == 'YES') {
                $output .= "<a href='#null' onclick=\"xajax_doAttendeeEvent(".$params['object']->getId().", ".$params['object']->getUid().", 'list', null, null, params); return false;\"><img src='".$theme->images."/decorators/event/btnAttending.gif' /></a>";
            }
            elseif ($userAttendee->getAnswer() == 'NO') {
                $output .= "<a href='#null' onclick=\"xajax_doAttendeeEvent(".$params['object']->getId().", ".$params['object']->getUid().", 'list', null, null, params); return false;\"><img src='".$theme->images."/decorators/event/btnNotAttending.gif' /></a>";
            }
            elseif ($userAttendee->getAnswer() == 'MAYBE') {
                $output .= "<a href='#null' onclick=\"xajax_doAttendeeEvent(".$params['object']->getId().", ".$params['object']->getUid().", 'list', null, null, params); return false;\"><img src='".$theme->images."/decorators/event/btnMaybe.gif' /></a>";
            }
        }
        $output .= "</td>";
        $output .= "<td class='prNoBorder'>".$params['object']->displayDate('search.results', $params['user'], $params['currentTimezone'])."</td>";
        $output .= "<td class='prNoBorder'>&#160;</td><td class='prNoBorder'>";
        if ($params['user']->getId() != null)
            $output .= "<a class='prLink3' href='".$params['object']->entityURL()."'>".Warecorp::t('More')."</a><br/><a href='#null' onclick=\"SearchApplication.eventAddToMy(".$params['object']->getId().", ".$params['object']->getUid().", 0); return false;\">";
        else
            $output .= "<a class='prLink3' href='".BASE_URL.'/'.LOCALE."/users/login/'>";
        $output .= Warecorp::t('Add to My Calendar')."</a></td>";
        $output .= "</tr>";
        $output .= "<tr";
        if ($params['num']==1){
            if (($params['even'] % 2) != 0){
                $output .= " class='prEvenBg'";
            }
            else {
                $output .= " class='prOddBg'";
            }
        }
        else {
            if (($params['even'] % 2) == 0){
                $output .= " class='prEvenBg'";
            }
            else {
                $output .= " class='prOddBg'";
            }
        }
        $output .= "><td>&#160;</td><td colspan='5' class='prText4 prEventViewNotes'>".substr(htmlspecialchars(strip_tags($params['object']->getDescription())), 0, 200);
        if (strlen($params['object']->getDescription()) > 200){
        $output .= "...";
        }
        $output .= "&#160;</td>";
    }
    return $output;
}
?>
