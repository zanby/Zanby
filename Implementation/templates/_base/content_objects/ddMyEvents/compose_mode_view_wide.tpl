<div class="themeA"> {include file="content_objects/headline_block_view.tpl"}
    
    {if $event_display_style == 1 || $event_display_style == 2}
    {foreach from=$eventsList item=event name=threads key=key}
    {assign var=iter value=$smarty.foreach.threads.iteration-1}
    {assign var='objCDate' value=$event->convertTZ($event->getDtstart(), $currentTimezone)}
    <div class="prInner prClr3{if !$smarty.foreach.threads.first}{/if}">
        <div class="prFloatLeft">
            <div> {if $event_display_style == 2}
                {$objCDate->toString('MM/dd/yyyy')}
                {else}
                {$eventsDates[$key]}
                {/if} </div>
            <div> 
            {* Removed accourding #4241
            {if $event->isAllDay()}
                {t}All day{/t}
            {else}
                {$objCDate->toString('h:mm')} {$objCDate->get('MERIDIEM')} {if $event->isTimezoneExists()}{$objCDate->get('TIMEZONE')}{/if}
            {/if}
            *}
            {$event->displayDate('dd.myevents.narrow.event.list', $user, $currentTimezone)}
            </div>
            <div class="prIndentTop">
                <strong>{t}By:{/t}</strong> {if $event->getOwner()->EntityTypeName == 'user'} <img src="{$event->getOwner()->getAvatar()->setWidth(28)->setHeight(28)->getImage()}"  alt="" title=""  class="prVMiddle" /><br />
                {$event->getOwner()->getLogin()|escape:html}
                {else} <img src="{$event->getCreator()->getAvatar()->setWidth(28)->setHeight(28)->getImage()}"  alt="" title=""  class="prVMiddle" /><br />
                {$event->getCreator()->getLogin()|escape:html}
                {/if} </div>
            <ul>
                <li>
                    <a href="#null" onclick="xajax_doEventOrganizerSendMessage({$event->getId()}, {$event->getUid()}); return false;">{t}Contact Organizer{/t}</a>
                </li>
                <li>
                    <a href="#null" onclick="xajax_doEventInvite({$event->getId()}, {$event->getUid()}); return false;">{t}Invite Friends{/t}</a>
                </li>
            </ul>
        </div>
        <div class="prFloatRight">
            <h3>
                <a href="{$event->entityURL()}">{$event->getTitle()|escape:"html"}</a>
            </h3>
            {if $event->getEventVenue()}
            <div class="prInnerTop">
                <h4>{$event->getEventVenue()->getName()|escape:html}</h4>
                <address>
                {if $event->getEventVenue()->getAddress1()}
                {$event->getEventVenue()->getAddress1()|escape:html},&nbsp;
                {/if}
                {if $event->getEventVenue()->getAddress2()}
                {$event->getEventVenue()->getAddress2()|escape:html},&nbsp;
                {/if}
                {if $event->getEventVenue()->getCity()}
                {$event->getEventVenue()->getCity()->name|escape:html},&nbsp; 
                {$event->getEventVenue()->getCity()->getState()->name|escape:html}&nbsp;
                {if $event->getEventVenue()->getZipcode()}
                {$event->getEventVenue()->getZipcode()|escape:html}
                {/if}
                {/if}
                </address>
            </div>
            {/if}
            {if $event->getDocuments()->setFetchMode('object')->getList()}
            <ul class="prInnerTop">
                {foreach from=$event->getDocuments()->setFetchMode('object')->getList() item=item}
                <li>
                    <a href="{$currentUser->getUserPath('docget')}docid/{$item->getId()}/">{$item->getOriginalName()|escape:'html'}</a>
                    <span>{$item->getFileSize()|replace:" ":"&nbsp;"|escape:'html'} {if $item->getFileExt()}|{/if} {$item->getFileExt()|escape:'html'}</span>
                </li>
                {/foreach}
            </ul>
            {/if}
            {if $event->getLists()->setFetchMode('object')->getList()}
            <div class="prInnerTop">
                <h4>{t}Lists:{/t}</h4>
                <ul>
                    {foreach from=$event->getLists()->setFetchMode('object')->getList() item=item}
                    <li>
                        <a href="{$item->getListPath()}">{$item->getTitle()|escape:'html'}</a>
                    </li>
                    {/foreach}
                </ul>
            </div>
            {/if}
            <div class="prInnerTop">
                <span>{t}NOTE:{/t}</span> {if $event->getDescription()}
                {$event->getDescription()}
                {else}
                none
                {/if} </div>
            <div class="prInnerTop"> {assign var='objEventDtstart' value=$eventsDatesAtt[$key]}
                <a href="#null" onclick="xajax_doAttendeeEvent('{$event->getId()}', '{$event->getUid()}', 'month', 0, '{$objEventDtstart}'); return false;"> {assign var='userAttendee' value=$event->getAttendee()->setDateFilter($objEventDtstart)->findAttendee($user)}
                    {if $userAttendee}
                    {if $userAttendee->getAnswer() == 'NONE'} <img src="{$AppTheme->images}/decorators/event/btnRSVP.gif" /> {elseif $userAttendee->getAnswer() == 'YES'} <img src="{$AppTheme->images}/decorators/event/btnAttending.gif" /> {elseif $userAttendee->getAnswer() == 'NO'} <img src="{$AppTheme->images}/decorators/event/btnNotAttending.gif" /> {elseif $userAttendee->getAnswer() == 'MAYBE'} <img src="{$AppTheme->images}/decorators/event/btnMaybe.gif" /> {/if}
                    {/if} </a>
            </div>
        </div>
    </div><div class="prClearer"></div> 
    {/foreach}
    {elseif $event_display_style == 3}
    {if $events_show_calendar}
    {*<h3 class="prTCenter prInner">
        <a  href="{$currentUser->getUserPath()}calendar.month.view/year/{$objPrevDate->toString('yyyy')}/month/{$objPrevDate->toString('MM')}/">&laquo;</a>
        &nbsp;{$objCurrDate->toString('MMMM')}, {$objCurrDate->toString('yyyy')}&nbsp;
        <a href="{$currentUser->getUserPath()}calendar.month.view/year/{$objNextDate->toString('yyyy')}/month/{$objNextDate->toString('MM')}/">&raquo;</a>
    </h3>*}
    <!-- ======================================== OLD ======================================= -->
    <div class="prEvents-calendar prEvents-calendar-small">
            <div class="prTCenter"><a href="{$currentUser->getUserPath()}calendar.month.view/year/{$objPrevDate->toString('yyyy')}/month/{$objPrevDate->toString('MM')}/">&laquo;</a>&nbsp;{$objCurrDate->toString('MMMM')}, {$objCurrDate->toString('yyyy')}&nbsp;<a href="{$currentUser->getUserPath()}calendar.month.view/year/{$objNextDate->toString('yyyy')}/month/{$objNextDate->toString('MM')}/">&raquo;</a></div>
                <!-- / -->
                <!-- -->
                {foreach from=$objYear->getMonths() item=month}
                <div class="prInnerTop">
                <table cellspacing="0" cellpadding="0">
                        <thead>
                            {foreach from=$month->getWeekdaysHeader('3CHAR') item=wh}
                                <th class="prTCenter">{$wh}</th>
                            {/foreach}
                        </thead>
                        <tbody>
                        {foreach from=$month->getWeeks() key=weekNo item=week}
                            <tr>
                                {foreach from=$week->getDays() item=day}
                                    {assign var='strDate' value=$day->getDateAsString()}
                                    {assign var='tdStyle' value=''}
                                    {if $day->getMonth() != $month->getMonth()}
                                        {if $arrDates[$strDate]}{assign var='tdStyle' value='prPreviewMonth prPreviewMonth-active'}{*   *}
                                        {else}{assign var='tdStyle' value='prPreviewMonth'}{*   *}
                                        {/if}
                                    {else}
                                        {if $strDate < $objDateNow->toString('yyyy-MM-dd')}
                                            {assign var='tdStyle' value='prCurrentMonth-preview'}
                                        {else}
                                            {if $arrDates[$strDate]}
                                                {if $strDate != $objDateNow->toString('yyyy-MM-dd')}{assign var='tdStyle' value='prCurrentMonth-event'}
                                                {else}{assign var='tdStyle' value='prCurrentMonth-event prCurrentMonth-active'}
                                                {/if}
                                            {else}
                                                {if $strDate != $objDateNow->toString('yyyy-MM-dd')}{assign var='tdStyle' value='prCurrentMonth'}
                                                {else}{assign var='tdStyle' value='prCurrentMonth prCurrentMonth-active'}
                                                {/if}
                                            {/if}
                                        {/if}
                                    {/if}
                               <td class="{$tdStyle}">
                                    <div class="prEventsDaySmallBlock">

                                            <span>{$day->getDay()}</span>
                                    </div>
                               </td>
                            {/foreach}
                            </tr>
                         {/foreach}
                        </tbody>
                    </table>
                    </div>
                    {/foreach}
                <!-- / -->
            </div>
    {/if}
    {foreach from=$daysList item=day key=key}
    <h3 class="prInner">{$day.date}</h3>
    {foreach from=$calendar_data item=_datehash key=_dateval}
    {if $_dateval == $day.check}
    {foreach from=$_datehash item=_id_id key=_time}
    {foreach from=$_id_id item=_info}
    {assign var=event value=$_info._event}
    {assign var='objCDate' value=$event->convertTZ($event->getDtstart(), $currentTimezone)}
    <div class="prClr3">
        <div class="prFloatLeft">
            <div>
            {* Removed accourding #4241
            {if $event->isAllDay()}
                {t}All day{/t}
            {else}
                {$objCDate->toString('h:mm')} {$objCDate->get('MERIDIEM')} {if $event->isTimezoneExists()}{$objCDate->get('TIMEZONE')}{/if}
            {/if}
            *}
            {$event->displayDate('dd.myevents.narrow.event.list', $user, $currentTimezone)}
            </div>
        </div>
        <div class="prFloatRight">
            <h4>
                <a id="ddMyEvents_{$cloneId}_{$event->getId()}_{$_dateval|replace:'-':'_'}" href="{$event->entityURL()}">{$event->getTitle()|escape:"html"}</a>
            </h4>
            {if $events_show_venues}
            {if $event->getEventVenue()}
            <div>
                <h4>{$event->getEventVenue()->getName()|escape:html}</h4>
                <address>
                {if $event->getEventVenue()->getAddress1()}
                {$event->getEventVenue()->getAddress1()|escape:html},&nbsp;
                {/if}
                {if $event->getEventVenue()->getAddress2()}
                {$event->getEventVenue()->getAddress2()|escape:html},&nbsp;
                {/if}
                {if $event->getEventVenue()->getCity()}
                {$event->getEventVenue()->getCity()->name|escape:html},&nbsp; 
                {$event->getEventVenue()->getCity()->getState()->name|escape:html}&nbsp;
                {if $event->getEventVenue()->getZipcode()}
                {$event->getEventVenue()->getZipcode()|escape:html}
                {/if}
                {/if}
                </address>
            </div>
            {/if}
            {/if}
            {if $events_show_summaries && $event->getDescription()}
            <div class="prIndentTop"> {if strlen($event->getDescription())>250}
                {$event->getDescription()}...
                <a href="{$event->entityURL()}">{t}More{/t}&nbsp;&raquo;</a>
                {else}
                {$event->getDescription()}
                {/if} </div>
            {/if} </div>
    </div><div class="prClearer"></div>
    {/foreach}
    {/foreach}
    {/if}
    {/foreach}
    {/foreach}
    {/if}
    <div class="prClearer"></div>
    <div class="prInnerTop">
        <a class="prLink2" href="{$currentUser->getUserPath('calendar.month.view')}">{t}Browse events{/t}&raquo;</a>
    </div>
</div>
