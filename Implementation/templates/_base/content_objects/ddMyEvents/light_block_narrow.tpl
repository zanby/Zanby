<div class="themeA" id="light_{$cloneId}"> {if $event_display_style == 1 || $event_display_style == 2}
    {if $event_display_style == 1}{assign var=events_threads value="array(0)"}{/if}
    {foreach from=$events_threads item=currentG name=threadsG}
    {assign var=iterG value=$smarty.foreach.threadsG.iteration-1}
    {if $event_display_style == 2}
    <div class="prInnerTop">
        <div class="prClr3">
            <label for="events_threads_select_{$cloneId}" class="prFloatLeft">{t}Event to display:{/t}</label>
            <a href="#null" onclick="events_remove_section('{$cloneId}',{$iterG});return false;" class="prFloatRight">{t}remove{/t}</a>
        </div>
        <select id="events_threads_select_{$cloneId}" onchange="set_events_thread_value({$iterG}, this.value, '{$cloneId}');return false;" class="prFullWidth prIndentTop">
            <option value="0" selected="selected">{t}Select event{/t}</option>
            
                        {foreach from=$selectEventsList item=event2}
                         
            <option value="{$event2->getId()}" {if $event2->getId() == $currentG}selected="selected"{/if}>{$event2->getTitle()|escape:"html"}</option>
            
                        {/foreach}
                    
        </select>
    </div><div class="prClearer"></div>
    {/if}
    {assign var=uje value=0}    
    {foreach from=$eventsList item=event name=threads key=key}
    {assign var=iter value=$smarty.foreach.threads.iteration-1}
    {if ($event->getId() == $currentG && !$uje) || $event_display_style == 1}
    {assign var=uje value=1}
    {assign var='objCDate' value=$event->convertTZ($event->getDtstart(), $currentTimezone)}
    <h3 class="prInnerTop"> {if $event_display_style == 2}
        {$objCDate->toString('MM/dd/yyyy')}
        {else}
        {$eventsDates[$key]}
        {/if} </h3>
    <div>
        <div>
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
            <div>
                <h4>
                    <a href="{$event->entityURL()}/">{$event->getTitle()|escape:"html"}</a>
                </h4>
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
                {if $events_show_summaries && $event->getDescription()}
                <div class="prIndentTop"> {if strlen($event->getDescription())>250}
                    {$event->getDescription()}...
                    <a href="{$event->entityURL()}/">{t}More{/t} &raquo;</a>
                    {else}
                    {$event->getDescription()}
                    {/if} </div>
                {/if} </div>
        </div>
    </div>
    {/if}
    {/foreach}
    {/foreach}
    {if $event_display_style == 2}
    <div class="prInnerTop">
        <a href="#null" onclick="events_add_thread('{$cloneId}');return false;">{t}+ Display another Event{/t}</a>
    </div>
    {/if}
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
    <!-- /CALENDAR -->
    {/if}
    
    {foreach from=$daysList item=day key=key}
    <h3 class="prInner">{$day.date}</h3>
    {foreach from=$calendar_data item=_datehash key=_dateval}
    {if $_dateval == $day.check}
    {foreach from=$_datehash item=_id_id key=_time}
    {foreach from=$_id_id item=_info}
    {assign var=event value=$_info._event}
    {assign var='objCDate' value=$event->convertTZ($event->getDtstart(), $currentTimezone)}
    <div>
        <div>
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
        <div>
            <h4>
                <a id="ddMyEvents_{$cloneId}_{$event->getId()}_{$_dateval|replace:'-':'_'}" href="{$event->entityURL()}/">{$event->getTitle()|escape:"html"}</a>
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
    </div>
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
