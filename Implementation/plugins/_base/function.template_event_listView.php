<?php
function smarty_function_template_event_listView($params, &$smarty) {
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


    $output = "<div class='prEventListBlock";
    if ($params['last']) {
        $output .= " prNoBorder";
    }
    $output .="'>";

    $canManage = $params['Warecorp_ICal_AccessManager']->canManageEvent($params['object'], $params['currentOwner'], $params['user']);

        $output .="<div class='prEventDate'>";
            $output .="<div class='prFloatRight'>";
            if ($canManage) {
                $output .= " <a href='javascript:void(0)' onclick=\"xajax_doEventShare(".$params['object']->getId().", ".$params['object']->getUid()."); return false;\">".Warecorp::t('Share')."</a> | ";
                foreach ($params['arrEventsLinks'] as $eventId=>$bool) {
                    if ($params['object']->getId() == $eventId && $bool) {
                        $output .= " <a href='javascript:void(0)' onclick=\"xajax_doEventUnShare(".$params['object']->getId().", ".$params['object']->getUid().", '".$params['object']->getOwnerId()."'); return false;\">".Warecorp::t('Unshare')."</a> | ";
                    }
                }
                $output .= "<a href='".$params['object']->getOwner()->getOwnerPath('calendar.event.edit')."id/".$params['object']->getId()."/uid/".$params['object']->getUid()."/'>".Warecorp::t('Edit')."</a> | ";
                $output .= "<a href='javascript:void(0)' onClick=\"xajax_doCancelEvent('ROW', '".$params['object']->getId()."', '".$params['object']->getUid()."', '".$params['viewMode']."'); return false;\">".Warecorp::t('Cancel')."</a>";
            }
            elseif ($params['currentOwner']->getId() == $params['user']->getId()) {
                foreach ($params['arrEventsLinks'] as $eventId=>$bool) {
                    if ($params['object']->getId() == $eventId && $bool) {
                        $output .= "<a href='javascript:void(0)' onclick=\"xajax_doClientUnshareEvent(".$params['object']->getId().", ".$params['object']->getUid()."); return false;\"> ".Warecorp::t('Unshare')."</a>";
                    }
                }
            }
            $output .="</div>";
        $output .= $params['object']->displayDate('list.view', $params['user'], $params['currentTimezone']);
        $output .="</div>";
        $output .="<h4>";
            if ($params['object']->getOwnerType() == 'user') {
                $output .="<a href='".$params['object']->entityURL()."'>".htmlspecialchars(strip_tags($params['object']->getTitle()));
                $output .="</a>";
            }
            else {
                $output .="<a href='".$params['object']->entityURL()."'>".htmlspecialchars(strip_tags($params['object']->getTitle()));
                $output .="</a>";
            }
        $output .="</h4>";
        $output .="<div class='prEventList-detailTop'>";

        $output .="<div class='prEventList-detail'>";
            if ($params['object']->getPictureId()) {
                $output .= "<img class='prEventImg' border=1  src='".$params['object']->getEventPicture()->setWidth(37)->setHeight(38)->getImage($params['user'])."'>";
            }
            $output .= $params['object']->getDescription();
#            $output .= substr(($params['object']->getDescription()), 0, 200)."&nbsp";
#            if (strlen($params['object']->getDescription()) > 200) {
#                    $output .= "...";
#            }
            if ($params['user']->getId()) {
                if (null !== $userAttendee && $userAttendee->getAnswer() == 'NONE') {
                    $output .= "<span class='prText5'>&nbsp;Waiting for a response...</span>";
                }
            }
        $output .="</div>";
        $output .="<div class='prEventList-rsvp'>";
            $showViewAttendee = $canManage;
            if ($params['viewMode'] == 'active') {
                if ( $params['object']->getMaxRsvp() && 
                     $params['object']->getMaxRsvp() <= $params['object']->getAttendee()->setAnswerFilter('YES')->getCount() && 
                     (null !== $userAttendee && $userAttendee->getAnswer() !== 'YES' || $params['object']->getInvite()->getIsAnybodyJoin())
                ) {
                    $output .= "Event Full";
                    if ( null !== $userAttendee || $params['object']->getCreatorId() === $params['user']->getId() )
                        $showViewAttendee = true;
                } elseif (null !== $userAttendee) {
                    if ($userAttendee->getAnswer() == 'NONE') {
                            $output .="<a href='javascript:void(0)' onclick=\"xajax_doAttendeeEvent(".$params['object']->getId().", ".$params['object']->getUid().", 'list'); return false;\"><img src='{$theme->images}/decorators/event/btnRSVP.gif' style='width: 38px; height: 19px;' /></a>";
                    }
                    elseif ($userAttendee->getAnswer() == 'YES') {
                            $output .="<a href='javascript:void(0)' onclick=\"xajax_doAttendeeEvent(".$params['object']->getId().", ".$params['object']->getUid().", 'list'); return false;\"><img src='{$theme->images}/decorators/event/btnAttending.gif' /></a>";
                    }
                    elseif ($userAttendee->getAnswer() == 'NO') {
                            $output .= "<a href='javascript:void(0)' onclick=\"xajax_doAttendeeEvent(".$params['object']->getId().", ".$params['object']->getUid().", 'list'); return false;\"><img src='{$theme->images}/decorators/event/btnNotAttending.gif' /></a>";
                    }
                    elseif ($userAttendee->getAnswer() == 'MAYBE') {
                            $output .= "<a href='javascript:void(0)' onclick=\"xajax_doAttendeeEvent(".$params['object']->getId().", ".$params['object']->getUid().", 'list'); return false;\"><img src='{$theme->images}/decorators/event/btnMaybe.gif' /></a>";
                    }
                    $showViewAttendee = true;
                } elseif ( $params['object']->getInvite()->getIsAnybodyJoin() ) {
                    $output .= "<a href='#null' onclick=\"xajax_doAttendeeEventSignup(".$params['object']->getId().", ".$params['object']->getUid().", 'list'); return false;\"><img src='{$theme->images}/decorators/event/btnRSVP.gif' /></a>";
                }
            }
            if ($showViewAttendee) {
                $output .="<br /><a href='javascript:void(0)' onclick=\"xajax_viewAttendeeEvent(".$params['object']->getId().", ".$params['object']->getUid()."); return false;\" class='znTCente'>".Warecorp::t('View Attendance Details')."</a>";
            }
        $output .="</div>";
        $output .="</div>";
        $output .="<div class='prEventList-detailBottom'>";
            if (null !== $params['user']->getId() && $params['user']->getId() == $params['object']->getCreatorId()){
                $output .= "<span class='prText4'>".Warecorp::t('Organizer :')."</span> ".Warecorp::t('You are organizer');
            }
            else {
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
            $output .= "<span class='prText4'>".Warecorp::t('Repeats:')."</span>";
            if ($params['object']->getRrule() !== null) {
                $output .=" ".Warecorp::t('Yes');
            }
            else {
            $output .=" ".Warecorp::t('No');
            }
            $output .= "<br />";
            if ($params['object']->getAttendee()->findAttendee($params['user']) && $params['object']->getCreator()->getId()!==$params['user']->getId()) {
                $output .= "<a href='javascript:void(0)' onclick=\"xajax_doEventRemoveMe(".$params['object']->getId().", ".$params['object']->getUid()."); return false;\">".Warecorp::t('Remove Me from the Guest List')."</a>";
            }
        $output .="</div>";
    $output .="</div>";
    return $output;
}
?>
