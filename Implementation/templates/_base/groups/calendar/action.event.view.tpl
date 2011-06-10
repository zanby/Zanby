{literal}
<script type="text/javascript">
    function printEventDetails() {
        window.print();
    }
    function show_MessageBox()
    {
        var AddSendMessageBox = YAHOO.util.Dom.get("AddSendMessageBox");
        if ( AddSendMessageBox.style.display == 'none' ) {
            AddSendMessageBox.style.display = '';
            YAHOO.util.Dom.get("showMessageBoxLink").style.display = 'none';
            YAHOO.util.Dom.get("hideMessageBoxLink").style.display = '';
            YAHOO.util.Dom.get("ButtonsSet").style.display = 'none';
        } else {
            AddSendMessageBox.style.display = 'none';
            YAHOO.util.Dom.get("showMessageBoxLink").style.display = '';
            YAHOO.util.Dom.get("hideMessageBoxLink").style.display = 'none';
            YAHOO.util.Dom.get("ButtonsSet").style.display = '';
        }
    }
    function doInvite()
    {
        {/literal}
        var prop = [];
        prop["entityType"]  = "event";
        prop["entityId"]    = "{$objCopyEvent->getId()}";
        prop["entityUid"]   = "{$objCopyEvent->getUid()}";
        prop["returnUrl"]   = location.href;
        xajax_setInviteProperties(prop);
        {literal}
    }
    function doInviteConsecutively(callback)
    {
        var prop = {
            entityType : "event",
            entityId   : "{/literal}{$objCopyEvent->getId()}{literal}",
            entityUid  : "{/literal}{$objCopyEvent->getUid()}{literal}",
            returnUrl  : location.href
        };
        $.post('{/literal}{$SITE_URL}/{$LOCALE}{literal}/ajax/setInviteProperties/', prop, callback);
    }
    function doInviteCallback () {
        {/literal}
        xajax_doEventInvite({$objCopyEvent->getId()}, {$objCopyEvent->getUid()});
        {literal}
    }
</script>
{/literal}
{if $FACEBOOK_USED}
    {literal}
        <script type="text/javascript">//<![CDATA[
            {/literal}{assign_adv var="url_oninvite_friends_toevent" value="array('controller' => 'facebook', 'action' => 'invitefriendstoevent')"}{literal}
            FBCfg.url_oninvite_friends_toevent = '{/literal}{$Warecorp->getCrossDomainUrl($url_oninvite_friends_toevent)}{literal}';
            {/literal}{assign_adv var="url_onremove_from_eventinvite" value="array('controller' => 'facebook', 'action' => 'removefromeventinvite')"}{literal}
            FBCfg.url_onremove_from_eventinvite = '{/literal}{$Warecorp->getCrossDomainUrl($url_onremove_from_eventinvite)}{literal}';
        //]]></script>
    {/literal}
{/if}
{if $FACEBOOK_USED && empty($_RSVP__attendee_) && !$user->getId() && !$objCopyEvent->getInvite()->getIsAnybodyJoin()}
    {literal}
    <script type="text/javascript">
        {/literal}{assign_adv var="url_oncheck_rsvp_status_ready" value="array('controller' => 'facebook', 'action' => 'checkrsvpstatus')"}{literal}
        FBCfg.url_oncheck_rsvp_status_ready = '{/literal}{$Warecorp->getCrossDomainUrl($url_oncheck_rsvp_status_ready)}{literal}';
        $(function(){
            FBApplication.check_rsvp_status({/literal}{$objEvent->getId()}, {$objEvent->getUid()}{literal});
        })
    </script>
    {/literal}
{/if}

{if !$objCopyEvent->getRrule()}{assign var='rrule_freq' value='NONE'}{else}
{assign var='rrule_freq' value=$objCopyEvent->getRrule()->getFreq()}{/if}
<div class="prInner prClr2">
    <div class="prEventList-left">
        {* LEFT COLUMN START *}
        <div class="prClr3">
            <div class="prEventViewImg">
                {if $objCopyEvent->getEventPicture() !== null}
                    <img src="{$objCopyEvent->getEventPicture()->setWidth(75)->setHeight(75)->getImage($user)}" />
                {else}
                    <img src="{$AppTheme->images}/decorators/event/no_event_image.jpg" />
                {/if}
            </div>

            <div class="prEventViewDescr">
                <div class="prInnerRight">
                    {$objCopyEvent->displayDate('event.view', $user, $currentTimezone)}

                    <p class="prInnerSmallTop">
                    {$objCopyEvent->getTitle()|escape:html}
                    </p>

                    {if $Warecorp_Venue_AccessManager->canViewPrivateVenue($objCopyEvent, $currentGroup, $user)}
                    <p class="prInnerTop">
                        {assign var="objVenue" value=$objCopyEvent->getEventVenue()}
                        {if null !== $objVenue}
                            <strong>{$objVenue->getName()|escape:html}</strong> {if $objVenue->getGoogleQueryLatLng()}<a target="_blank" href="{$objVenue->getGoogleQueryLatLng()}">{t}Get directions{/t}</a>{/if}<br />
                            {$objVenue->getCategory()->getName()|escape:html}<br />
                            {if $objVenue->getAddress1()}
                                {$objVenue->getAddress1()|escape:html}<br />
                            {/if}
                            {if $objVenue->getAddress2()}{$objVenue->getAddress2()|escape:html}<br />{/if}
                            {if $objVenue->getCity()}
                                {$objVenue->getCity()->getState()->getCountry()->name|escape:html}<br />
                                {$objVenue->getCity()->getState()->name|escape:html}<br />
                                {$objVenue->getCity()->name|escape:html}<br />
                                {if $objVenue->getZipcode()} {$objVenue->getZipcode()|escape}<br />{/if}
                            {/if}
                            {if $objVenue->getPhone()}
                                {t}Tel:{/t}{$objVenue->getPhone()|escape:html}<br />
                            {/if}
                            {if $objVenue->getWebsite()}
                                <a href="{$objVenue->getWebsite()|escape:html}">{$objVenue->getWebsite()|escape:html|escape:html}</a><br />
                            {/if}
                            {if $objVenue->getDescription()}
                                <br />
                                {$objVenue->getDescription()|escape:html|nl2br}<br />
                            {/if}
                        {/if}
                    </p>
                    {/if}
                </div>
            </div>
            <div class="prEventList-rsvp">
                {if !$objCopyEvent->isExpired()}
                    {if empty($_RSVP__access_mode_) && !$user->getId() && $objCopyEvent->getInvite()->getIsAnybodyJoin()}
                        <div id="AttendeeButtonDiv">
                            <a href="#null" onclick="xajax_doAttendeeEventSignup({$objCopyEvent->getId()}, {$objCopyEvent->getUid()}, 'view'); return false;"><img src="{$AppTheme->images}/decorators/event/btnRSVP.gif" /></a>
                        </div>
                    {else}
                        {if empty($_RSVP__access_mode_) || $_RSVP__access_mode_ == 'user'}
                            {assign var='userAttendee' value=$objCopyEvent->getAttendee()->setDateFilter($objEventDtstart->toString('yyyy-MM-ddTHHmmss'))->findAttendee($user)}
                        {else}
                            {assign var='userAttendee' value=$objCopyEvent->getAttendee()->setDateFilter($objEventDtstart->toString('yyyy-MM-ddTHHmmss'))->findObjectsAttendee($_RSVP__attendee_->getOwnerType(), $_RSVP__attendee_->getOwnerId())}
                        {/if}
                        {if $objCopyEvent->getMaxRsvp() && 
                            $objCopyEvent->getMaxRsvp() <= $objCopyEvent->getAttendee()->setAnswerFilter('YES')->getCount() && 
                            (null !== $userAttendee && $userAttendee->getAnswer() != 'YES' || $objCopyEvent->getInvite()->getIsAnybodyJoin())}
                            Event Full
                        {elseif null !== $userAttendee}
                            <div id="AttendeeButtonDiv">
                            {if $userAttendee->getAnswer() == 'NONE'}
                                <!--{linkbutton name="RSVP" color="yellow" onclick=$attendeeAjaxParamsOnClick}-->
                                <a href="#null" onclick="xajax_doAttendeeEvent({$objCopyEvent->getId()}, {$objCopyEvent->getUid()}, 'list'); return false;"><img src="{$AppTheme->images}/decorators/event/btnRSVP.gif" /></a>
                            {elseif $userAttendee->getAnswer() == 'YES'}
                                <!--{linkbutton name="Attending" color="green" onclick=$attendeeAjaxParamsOnClick}-->
                                 <a href="#null" onclick="xajax_doAttendeeEvent({$objCopyEvent->getId()}, {$objCopyEvent->getUid()}, 'list'); return false;"><img src="{$AppTheme->images}/decorators/event/btnAttending.gif" /></a>
                            {elseif $userAttendee->getAnswer() == 'NO'}
                                <!--{linkbutton name="Not Attending" color="red" onclick=$attendeeAjaxParamsOnClick}-->
                                 <a href="#null" onclick="xajax_doAttendeeEvent({$objCopyEvent->getId()}, {$objCopyEvent->getUid()}, 'list'); return false;"><img src="{$AppTheme->images}/decorators/event/btnNotAttending.gif" /></a>
                            {elseif $userAttendee->getAnswer() == 'MAYBE'}
                                <!--{linkbutton name="Maybe" color="yellow" onclick=$attendeeAjaxParamsOnClick}-->
                                 <a href="#null" onclick="xajax_doAttendeeEvent({$objCopyEvent->getId()}, {$objCopyEvent->getUid()}, 'list'); return false;"><img src="{$AppTheme->images}/decorators/event/btnMaybe.gif" /></a>
                            {/if}
                            </div>
                        {elseif  $objCopyEvent->getInvite()->getIsAnybodyJoin()}
                            <div id="AttendeeButtonDiv">
                                <a href="#null" onclick="xajax_doAttendeeEventSignup({$objCopyEvent->getId()}, {$objCopyEvent->getUid()}, 'view'); return false;"><img src="{$AppTheme->images}/decorators/event/btnRSVP.gif" /></a>
                            </div>
                        {/if}
                    {/if}
                {/if}

                {assign var="attachments" value=$objCopyEvent->getDocuments()->setFetchMode('object')->getList()}
                {if $attachments}
                    <h4>{t}Attachments{/t}</h4>
                    {foreach from=$attachments item=item}
                    <p id="doc_{$item->getId()}">

                        <a href="{$currentGroup->getGroupPath('calendar.event.docget')}docid/{$item->getId()}/id/{$objCopyEvent->getId()}/">{$item->getOriginalName()|escape:html}</a>

                    </p>
                    {/foreach}
                {/if}
            </div>
        </div>
		<div class="prEventViewNotes">
        	<h4>{t}Notes{/t}</h4>
       		{$objCopyEvent->getDescription()}
		</div>


        {* EVENT LISTS BLOCK START *}
        {if $lstLists}
            <h4>{t}Events List{/t}</h4>

            <div id="lists_list">
                {foreach from=$lstLists item=item}
                    {include file="groups/calendar/action.event.view.template.list.collapsed.tpl" list=$item objEvent=$objCopyEvent}
                {/foreach}
            </div>
        {/if}
        {* EVENT LISTS BLOCK END *}

        <h4>
                {if $Warecorp_ICal_AccessManager->canManageEvent($objCopyEvent, $CurrentGroup, $user)}
                    {t}Organizer Options{/t}
                {else}
                    {t}Guest Options{/t}
                {/if}
        </h4>

        {assign var="canManageEvent" value=$Warecorp_ICal_AccessManager->canManageEvent($objCopyEvent, $CurrentGroup, $user)}
        {assign var="allowDisplayListToGuest" value=0}
        {if $canManageEvent}
            {assign var="allowDisplayListToGuest" value=1}
        {else}
            {if $objCopyEvent->getInvite()->getDisplayListToGuest() && $userAttendee!=null}
                {assign var="allowDisplayListToGuest" value=1}
            {else}
                {if $objCopyEvent->getOwnerType() == 'user'}
                    {if $objCopyEvent->getOwnerId() == $user->getId()}
                        {assign var="allowDisplayListToGuest" value=1}
                    {/if}
                {else}
                    {if $objCopyEvent->getCreatorId() == $user->getId()}
                        {assign var="allowDisplayListToGuest" value=1}
                    {/if}
                {/if}
            {/if}
        {/if}

        {if $canManageEvent}
        <div class="prClr3">
            <div class="prEventViewOptBlock">
                {if $rrule_freq == 'NONE' && $objCopyEvent->getRecurrenceId() === null}
                    <a href="{$objCopyEvent->getOwner()->getOwnerPath('calendar.event.edit')}id/{$objCopyEvent->getId()}/uid/{$objCopyEvent->getUid()}/year/{$year}/month/{$month}/day/{$day}/">{t}Edit event{/t}</a><br />
                {else}
                <br/>
                    {if $viewMode == 'ROW'}
                        <a href="{$objCopyEvent->getOwner()->getOwnerPath('calendar.event.edit')}id/{$objCopyEvent->getId()}/uid/{$objCopyEvent->getUid()}/">{t}Edit event{/t}</a><br />
                    {else}
                        <a href="{$objCopyEvent->getOwner()->getOwnerPath('calendar.event.edit')}id/{$objCopyEvent->getId()}/uid/{$objCopyEvent->getUid()}/">{t}Edit event for all dates{/t}</a><br />
                        <a href="{$objCopyEvent->getOwner()->getOwnerPath('calendar.event.edit')}id/{$objCopyEvent->getId()}/uid/{$objCopyEvent->getUid()}/year/{$year}/month/{$month}/day/{$day}/mode/future/">{t}Edit event for all future dates{/t}</a><br />
                        <a href="{$objCopyEvent->getOwner()->getOwnerPath('calendar.event.edit')}id/{$objCopyEvent->getId()}/uid/{$objCopyEvent->getUid()}/year/{$year}/month/{$month}/day/{$day}/">{t}Edit event for this date{/t}</a><br />
                    {/if}
                {/if}
                <a href="#null" onclick="doInviteConsecutively(doInviteCallback); return false;">{t}Invite more people{/t}</a><br>
                <a href="#null" onclick="xajax_doEventSendMessage({$objCopyEvent->getId()}, {$objCopyEvent->getUid()}); return false;">{t}Send a Message to Guest(s){/t}</a><br />
                <a href="#null" onclick="xajax_doEventRemoveGuest({$objCopyEvent->getId()}, {$objCopyEvent->getUid()}); return false;">{t}Remove Guest(s){/t}</a><br />
                <a href="#null" onclick="xajax_doChangeHost({$objCopyEvent->getId()}, {$objCopyEvent->getUid()}); return false;">{t}Change Event Host{/t}</a><br />
            </div>
            <div class="prEventViewOptBlock">
                <a href="#null" onclick="printEventDetails();">{t}Print Events Details{/t}</a><br />

                {if $rrule_freq == 'NONE' && $objCopyEvent->getRecurrenceId() === null}
                    <a href="#null" onClick="xajax_doCancelEvent('ROW', '{$objCopyEvent->getId()}', '{$objCopyEvent->getUid()}', 'month'); return false;">{t}Cancel event{/t}</a><br />
                {else}
                    {if $viewMode == 'ROW'}
                        <a href="#null" onClick="xajax_doCancelEvent('ROW', '{$objCopyEvent->getId()}', '{$objCopyEvent->getUid()}', 'month'); return false;">{t}Cancel event{/t}</a><br />
                    {else}
                        <a href="#null" onClick="xajax_doCancelEvent('ROW', '{$objCopyEvent->getId()}', '{$objCopyEvent->getUid()}', 'month'); return false;">{t}Cancel event for all dates{/t}</a><br />
                        <a href="#null" onClick="xajax_doCancelEvent('FUTURE', '{$objCopyEvent->getId()}', '{$objCopyEvent->getUid()}', 'month', '{$year}', {$month}, {$day}); return false;">{t}Cancel event for all future dates{/t}</a><br>
                        <a href="#null" onClick="xajax_doCancelEvent('COPY', '{$objCopyEvent->getId()}', '{$objCopyEvent->getUid()}', 'month', '{$year}', {$month}, {$day}); return false;">{t}Cancel event for this date{/t}</a><br />
                    {/if}
                {/if}
                <a href="#null" onclick="xajax_doCopyEvent('{$objCopyEvent->getId()}', '{$objCopyEvent->getUid()}'); return false;">{t}Copy this Event{/t}</a><br />
                {if $viewMode == 'ROW'}
                    <a href="{$currentGroup->getGroupPath('calendar.event.attendee.print')}id/{$objCopyEvent->getId()}/uid/{$objCopyEvent->getUid()}/">{t}Print list of attendees{/t}</a><br />
                    <a href="{$currentGroup->getGroupPath('calendar.event.attendee.download')}id/{$objCopyEvent->getId()}/uid/{$objCopyEvent->getUid()}/">{t}Download list of attendees{/t}</a><br />
                {else}
                    <a href="{$currentGroup->getGroupPath('calendar.event.attendee.print')}id/{$objCopyEvent->getId()}/uid/{$objCopyEvent->getUid()}/year/{$year}/month/{$month}/day/{$day}/">{t}Print list of attendees{/t}</a><br />
                    <a href="{$currentGroup->getGroupPath('calendar.event.attendee.download')}id/{$objCopyEvent->getId()}/uid/{$objCopyEvent->getUid()}/year/{$year}/month/{$month}/day/{$day}/">{t}Download list of attendees{/t}</a><br />
                {/if}
                <a href="{$currentGroup->getGroupPath('calendar.event.export')}id/{$objCopyEvent->getId()}/uid/{$objCopyEvent->getUid()}/type/ical/">{t}Download event in iCal format{/t}</a>
           </div>
        </div>


        {else}
            <p class="prInnerSmallTop">
                {if $user->getId()}
                    {if $objCopyEvent->getAttendee()->findAttendee($user)}
                        <a href="#null" onclick="xajax_doEventRemoveMe({$objCopyEvent->getId()}, {$objCopyEvent->getUid()}); return false;">{t}Remove me{/t}</a><br />
                    {/if}
                    {if $objCopyEvent->getInvite()->getAllowGuestToInvite()	}
                        <a href="#null" onclick="doInviteConsecutively(doInviteCallback);  return false;">{t}Invite more people{/t}</a><br />
                    {/if}
                    {if $objCopyEvent->getOwnerType() == 'user' && $objCopyEvent->getOwnerId() == $user->getId()}
                    {elseif $objCopyEvent->getOwnerType() == 'group' && $objCopyEvent->getCreatorId() == $user->getId()}
                    {else}
                    <a href="#null" onclick="xajax_doEventOrganizerSendMessage({$objCopyEvent->getId()}, {$objCopyEvent->getUid()}); return false;">{t}Send a Message to Organizer{/t}</a><br />
                    {/if}
                {/if}
                <a href="#null" onclick="printEventDetails();">{t}Print Event Details{/t}</a><br />
                {if $allowDisplayListToGuest}
                    {if $viewMode == 'ROW'}
                        <a href="{$currentGroup->getGroupPath('calendar.event.attendee.print')}id/{$objCopyEvent->getId()}/uid/{$objCopyEvent->getUid()}/">{t}Print list of attendees{/t}</a><br />
                        <a href="{$currentGroup->getGroupPath('calendar.event.attendee.download')}id/{$objCopyEvent->getId()}/uid/{$objCopyEvent->getUid()}/">{t}Download list of attendees{/t}</a><br />
                    {else}
                        <a href="{$currentGroup->getGroupPath('calendar.event.attendee.print')}id/{$objCopyEvent->getId()}/uid/{$objCopyEvent->getUid()}/year/{$year}/month/{$month}/day/{$day}/">{t}Print list of attendees{/t}</a><br />
                        <a href="{$currentGroup->getGroupPath('calendar.event.attendee.download')}id/{$objCopyEvent->getId()}/uid/{$objCopyEvent->getUid()}/year/{$year}/month/{$month}/day/{$day}/">{t}Download list of attendees{/t}</a><br />
                    {/if}
                {/if}
                {if $user->getId() && $objCopyEvent->getAttendee()->findAttendee($user)}
                <a href="{$currentGroup->getGroupPath('calendar.event.export')}id/{$objCopyEvent->getId()}/uid/{$objCopyEvent->getUid()}/type/ical/">{t}Download event in iCal format{/t}</a>
                {/if}
            </p>
        {/if}


        {* ATTENDANCE BLOCK START *}
       <h4>{t}Attendance{/t}</h4>
       <div class="prClr3">
            <div class="prEventType">
                <div class="prEvents-att-attending">
                    <span class="prEvents-att-attending-action">&#160;</span>
                    <span class="prEvents-att-attending-count" href="#null">{$objCopyEvent->getAttendee()->setDateFilter($objEventDtstart->toString('yyyy-MM-ddTHHmmss'))->setAnswerFilter('YES')->getCount()}</span>
                    <span class="prEvents-att-text">{t}Attending{/t}</span>
                </div>
                <ul class="prInnerSmallTop">
                    {if $allowDisplayListToGuest}
                        {foreach from=$objCopyEvent->getAttendee()->setFetchMode('object')->setDateFilter($objEventDtstart->toString('yyyy-MM-ddTHHmmss'))->setAnswerFilter('YES')->getList() item='attendee'}
                            <li>
                            {if $attendee->getOwnerType() == 'user'}
                                {if null === $attendee->getOwner()->getId()}
                                    {if $attendee->getName()}
                                        {$attendee->getName()|escape:'html'}
                                    {else}
                                        {$attendee->getOwner()->getEmail()|cleanemaildomain} {* For backward compatibility *}
                                    {/if}
                                {else}
                                    <a href="{$attendee->getOwner()->getUserPath('profile')}">{$attendee->getOwner()->getLogin()|escape:html}</a>{if $attendee->isOrganizer()} - {t}Organizer{/t}{/if}
                                {/if}
                                {if $attendee->getAnswerText()}
                                    <p>{$attendee->getAnswerText()|escape|nl2br}</p>
                                {/if}
                            {elseif $attendee->getOwnerType() == 'fbuser'}
                                {$attendee->getName()}
                                {if $attendee->getAnswerText()}
                                    <p>{$attendee->getAnswerText()|escape|nl2br}</p>
                                {/if}
                            {else}
                            {/if}
                            </li>
                        {/foreach}
                    {/if}
                </ul>
            </div>
            <div class="prEventType">
                <div class="prEvents-att-attending">
                    <span class="prEvents-att-denying-action">&#160;</span>
                    <span class="prEvents-att-denying-count" href="#null">{$objCopyEvent->getAttendee()->setDateFilter($objEventDtstart->toString('yyyy-MM-ddTHHmmss'))->setAnswerFilter('NO')->getCount()}</span>
                    <span class="prEvents-att-text">{t}Not Attending{/t}</span>
                </div>
                <ul class="prInnerSmallTop prClr3">
                    {if $allowDisplayListToGuest}
                        {foreach from=$objCopyEvent->getAttendee()->setFetchMode('object')->setDateFilter($objEventDtstart->toString('yyyy-MM-ddTHHmmss'))->setAnswerFilter('NO')->getList() item='attendee'}
                            <li>
                            {if $attendee->getOwnerType() == 'user'}
                                {if null === $attendee->getOwner()->getId()}
                                    {if $attendee->getName()}
                                        {$attendee->getName()|escape:'html'}
                                    {else}
                                        {$attendee->getOwner()->getEmail()|cleanemaildomain} {* For backward compatibility *}
                                    {/if}
                                {else}
                                    <a href="{$attendee->getOwner()->getUserPath('profile')}">{$attendee->getOwner()->getLogin()|escape:html}</a>{if $attendee->isOrganizer()} - {t}Organizer{/t}{/if}
                                {/if}
                                {if $attendee->getAnswerText()}
                                    <p>{$attendee->getAnswerText()|escape|nl2br}</p>
                                {/if}
                            {elseif $attendee->getOwnerType() == 'fbuser'}
                                {$attendee->getName()}
                                {if $attendee->getAnswerText()}
                                    <p>{$attendee->getAnswerText()|escape|nl2br}</p>
                                {/if}
                            {else}
                            {/if}
                            </li>
                        {/foreach}
                    {/if}
                </ul>
            </div>
            <div class="prEventType">
                <div class="prEvents-att-attending">
                    <span class="prEvents-att-maybe-action">&#160;</span>
                    <span class="prEvents-att-maybe-count" href="#null">{$objCopyEvent->getAttendee()->setDateFilter($objEventDtstart->toString('yyyy-MM-ddTHHmmss'))->setAnswerFilter('MAYBE')->getCount()}</span>
                    <span class="prEvents-att-text">{t}Maybe{/t}</span>
                </div>
                 <ul class="prInnerSmallTop prClr3">
                    {if $allowDisplayListToGuest}
                        {foreach from=$objCopyEvent->getAttendee()->setFetchMode('object')->setDateFilter($objEventDtstart->toString('yyyy-MM-ddTHHmmss'))->setAnswerFilter('MAYBE')->getList() item='attendee'}
                            <li>
                            {if $attendee->getOwnerType() == 'user'}
                                {if null === $attendee->getOwner()->getId()}
                                    {if $attendee->getName()}
                                        {$attendee->getName()|escape:'html'}
                                    {else}
                                        {$attendee->getOwner()->getEmail()|cleanemaildomain} {* For backward compatibility *}
                                    {/if}
                                {else}
                                    <a href="{$attendee->getOwner()->getUserPath('profile')}">{$attendee->getOwner()->getLogin()|escape:html}</a>{if $attendee->isOrganizer()} - {t}Organizer{/t}{/if}
                                {/if}
                                {if $attendee->getAnswerText()}
                                    <p>{$attendee->getAnswerText()|escape|nl2br}</p>
                                {/if}
                            {elseif $attendee->getOwnerType() == 'fbuser'}
                                {$attendee->getName()}
                                {if $attendee->getAnswerText()}
                                    <p>{$attendee->getAnswerText()|escape|nl2br}</p>
                                {/if}
                            {else}
                            {/if}
                            </li>
                        {/foreach}
                    {/if}
                </ul>
            </div>
        </div>


        <div class="prEvents-att-BottomBlock">
            <div class="prEventType">
                <span class="prEvents-att-not-action">&#160;</span>
                <span class="prEvents-att-not-count" href="#null">{$objCopyEvent->getAttendee()->setDateFilter($objEventDtstart->toString('yyyy-MM-ddTHHmmss'))->setAnswerFilter('NONE')->getCount()}</span>
                <span class="prEvents-att-text">{t}Have not responded{/t}</span>
            </div>
        </div>
        <div class="prClr3">
            <ul class="prInnerSmallTop prClr3">
                {if $allowDisplayListToGuest}
                    {foreach from=$objCopyEvent->getAttendee()->setFetchMode('object')->setAnswerFilter('NONE')->getList() item='attendee'}
                        <li>
                        {if $attendee->getOwnerType() == 'user'}
                            {if null === $attendee->getOwner()->getId()}
                                {*<a href="mailto:{$attendee->getOwner()->getEmail()}">{$attendee->getOwner()->getEmail()|cleanemaildomain}</a>*}
                                {if $attendee->getName()}
                                    {$attendee->getName()|escape:'html'}
                                {else}
                                    {$attendee->getOwner()->getEmail()|cleanemaildomain} {* For backward compatibility *}
                                {/if}
                            {else}
                                <a href="{$attendee->getOwner()->getUserPath('profile')}">{$attendee->getOwner()->getLogin()|escape:html}</a>{if $attendee->isOrganizer()} - {t}Organizer{/t}{/if}
                            {/if}
                            {if $attendee->getAnswerText()}
                                <p>{$attendee->getAnswerText()|escape|nl2br}</p>
                            {/if}
                        {elseif $attendee->getOwnerType() == 'fbuser'}
                            {$attendee->getName()}
                            {if $attendee->getAnswerText()}
                                <p>{$attendee->getAnswerText()|escape|nl2br}</p>
                            {/if}
                        {else}
                        {/if}
                        </li>
                    {/foreach}
                {/if}
            </ul>
        </div>
    </div>
    {* ATTENDANCE BLOCK END *}
    {* LEFT COLUMN END *}

    <div class="prEventList-right">{* RIGHT COLUMN START *}

        {t}Posted{/t} {$objCopyEvent->getCreateTime()|date_locale}<br />
        {t}by{/t} <a href="{$objCopyEvent->getCreator()->getUserPath('profile')}">{$objCopyEvent->getCreator()->getLogin()|escape}</a>


        {if !$objCopyEvent->isExpired()}
        {if $user->getId()}
            {if $objCopyEvent->getCreatorId() != $user->getId()}
                {if $objCopyEvent->getOwnerType() == 'group' || ($objCopyEvent->getOwnerType() == 'user' && $user->getId() != $objCopyEvent->getOwner()->getId())}
                    {if !$objCopyEvent->getSharing()->isShared($user)}
                        <div class="prInnerSmallTop prClr3">
                            <a href="#null" onclick="xajax_doAddToMy({$objCopyEvent->getId()}, {$objCopyEvent->getUid()}); return false;">{t}Add to My Events{/t}</a></div>
                    {/if}
                {/if}
            {/if}
        {/if}

        {if $Warecorp_ICal_AccessManager->canShareEvent($objCopyEvent, $CurrentGroup, $user)}
             <div class="prInnerSmallTop prClr3">
                    <a href="#" onclick="xajax_doEventShare({$objCopyEvent->getId()}, {$objCopyEvent->getUid()}); return false;">{t}Share this Event{/t}</a></div>

        {/if}
        {if $showUnShareLink}
            <div class="prInnerTop prClr3">
                <a href="#null" onclick="xajax_doEventUnShare({$objCopyEvent->getId()}, {$objCopyEvent->getUid()}, '{$unShareGroupId}'); return false;">{t}Unshare this Event{/t}</a></div>

        {/if}
        {/if}

        <h4>{t}Event Type:{/t}</h4>
        {assign var='eventCategories' value=$objCopyEvent->getCategories()->setFetchMode('object')->getList()}
        {if $eventCategories}
            <ul>
            {foreach from=$eventCategories item=category}
                <li>{$category->getCategory()->getName()}</li>
            {/foreach}
            </ul>
        {else}
        {t}None{/t}
        {/if}

        <h4>{t}Event Tags:{/t}</h4>
            {foreach name=tags item=tag from=$objCopyEvent->getTags()->getList()}
                <a href="{$BASE_URL}/{$LOCALE}/search/events/preset/new/keywords/{$tag->name}/">{$tag->name|escape}</a><br />
            {foreachelse}
                {t}No Tags{/t}
            {/foreach}


        {*
        <a href="/{$LOCALE}/calendarical/id/{$objCopyEvent->getId()}/">{t}Export to ICal{/t}</a>
        *}
    </div>
    {* RIGHT COLUMN END *}
</div>
<div id="TopicTooltipContent" class="TooltipContent" style="position:absolute; display:none; width: 400px; padding: 5px; font-size:12px" onmouseover="onTooltipOver();" onmouseout="onTooltipOut();"></div>

{if $lstLists}
    {include file="groups/lists/block_layer.tpl"}
    <script type="text/javascript" src="/js/lists.js"></script>
{/if}
