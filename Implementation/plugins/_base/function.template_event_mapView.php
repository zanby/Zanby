<?php
function smarty_function_template_event_mapView($params, &$smarty) {
    Warecorp::addTranslation('/plugins/function.template_event_listView.php.xml');
    $theme = Zend_Registry::get('AppTheme');

    //$userAttendee = $params['object']->getAttendee()->findAttendee($params['user']);
    $userAttendee = null;
    if ( $params['user']->getId() ) {
        unset($_SESSION['_RSVP_']);
        $userAttendee = $params['object']->getAttendee()->findAttendee($params['user']);
    } elseif ( !empty($_SESSION['_RSVP_'][$params['object']->getId()]['_access_code_']) ) {
        if ( $_SESSION['_RSVP_'][$params['object']->getId()]['_access_mode_'] == 'user' ) {
            $userAttendee = $params['object']->getAttendee()->findAttendeeByCode($_SESSION['_RSVP_'][$params['object']->getId()]['_access_code_']);
        } else {
            $objAttendee = $_SESSION['_RSVP_'][$params['object']->getId()]['_attendee_'];
            $userAttendee = $params['object']->getAttendee()->findObjectsAttendeeByCode($_SESSION['_RSVP_'][$params['object']->getId()]['_access_code_'], $objAttendee->getOwnerType());
        }
    }
    
    $output = "<div class='prEventListBlock prEventMap";
    if ($params['last']) {
        $output .= " prNoBorder";
    }
    $output .= "'>";
    
    $canManage = $params['Warecorp_ICal_AccessManager']->canManageEvent($params['object'], $params['currentOwner'], $params['user']);
    $eventURL = $params['object']->entityURL();
    
    $output .= "<h4>";
        $output .= "<a href='".$eventURL."'>".htmlspecialchars(strip_tags($params['object']->getTitle()))."</a>";
        $output .= "<div class='prEventList-rsvp'>";

        if ( $params['object']->getMaxRsvp() && 
             $params['object']->getMaxRsvp() <= $params['object']->getAttendee()->setAnswerFilter('YES')->getCount() && 
             (null !== $userAttendee && $userAttendee->getAnswer() !== 'YES' || $params['object']->getInvite()->getIsAnybodyJoin())
        ) {
            $output .= "Event Full";
        } elseif (null !== $userAttendee) {
            if ($userAttendee->getAnswer() == 'NONE') {
                $output .="<a href='javascript:void(0)' onclick=\"xajax_doAttendeeEvent(".$params['object']->getId().", ".$params['object']->getUid().", 'list'); return false;\"><img src='{$theme->images}/decorators/event/btnRSVP.gif' style='width: 38px; height: 19px;' /></a>";
            } elseif ($userAttendee->getAnswer() == 'YES') {
                $output .="<a href='javascript:void(0)' onclick=\"xajax_doAttendeeEvent(".$params['object']->getId().", ".$params['object']->getUid().", 'list'); return false;\"><img src='{$theme->images}/decorators/event/btnAttending.gif' /></a>";
            } elseif ($userAttendee->getAnswer() == 'NO') {
                $output .= "<a href='javascript:void(0)' onclick=\"xajax_doAttendeeEvent(".$params['object']->getId().", ".$params['object']->getUid().", 'list'); return false;\"><img src='{$theme->images}/decorators/event/btnNotAttending.gif' /></a>";
            } elseif ($userAttendee->getAnswer() == 'MAYBE') {
                $output .= "<a href='javascript:void(0)' onclick=\"xajax_doAttendeeEvent(".$params['object']->getId().", ".$params['object']->getUid().", 'list'); return false;\"><img src='{$theme->images}/decorators/event/btnMaybe.gif' /></a>";
            }
        } elseif ( $params['object']->getInvite()->getIsAnybodyJoin() ) {
            $output .= "<a href='#null' onclick=\"xajax_doAttendeeEventSignup(".$params['object']->getId().", ".$params['object']->getUid().", 'list'); return false;\"><img src='{$theme->images}/decorators/event/btnRSVP.gif' /></a>";
        }        
        $output .= "</div>";
    $output .= "</h4>";
    $output .= "<div class='prEventList-detailTop'>";
        $output .= "<div class='prEventDate'>";
            $output .= "".$params['object']->displayDate('list.view', $params['user'], $params['currentTimezone'])."";
            if ( $objVenue = $params['object']->getEventVenue() ) {
				if ( $query = $objVenue->getGoogleQueryLatLng() ) {
					$output .= " at ".htmlspecialchars(strip_tags($objVenue->getName())); 
					$output .= " <a href='{$query}' target='_blank'>Get directions</a>";
				}
            }
        $output .= "</div>"; 
        $output .= "<div class='prEventList-detail'>";
            $output .= $params['object']->getDescription();
#            $output .= substr(($params['object']->getDescription()), 0, 200)."&nbsp;";
#            if (strlen($params['object']->getDescription()) > 200) $output .= "...";
        $output .= "</div>"; 
    $output .= "</div>";
    $output .= "<div class='prEventList-detailBottom'>";
        if ( null !== $params['user']->getId() && $params['user']->getId() == $params['object']->getCreatorId() ) {
            $output .= "<span class='prText4'>".Warecorp::t('Organizer :')."</span> ".Warecorp::t('You are organizer');
        } else {
            $output .= "<span class='prText4'>".Warecorp::t('Organizer :')."</span> <a href='".$params['object']->getCreator()->getOwnerPath('profile')."'>".$params['object']->getCreator()->getLogin()."</a>";
        }
        $output .= "<br />";
        if ($params['object']->getOwnerType() == 'group') {
            $output .= "<span class='prText4'>".Warecorp::t('Group event :')."</span> <a href='".$params['object']->getOwner()->getGroupPath('summary')."'>".$params['object']->getOwner()->getName()."</a><br />";
        }
        $output .= "<span class='prText4'>".Warecorp::t('Event Category :')."</span> ";
        $sep = " ";
        foreach ($params['object']->getCategories()->setFetchMode('object')->getList() as $category) {
            $output .= $sep.$category->getCategory()->getName();
            $sep = ", ";
        }
        $output .= "<br />";
    $output .= "</div>";

    $output .="</div>";
    return $output;
}
