{if $currentUser->getId() == $user->getId()}{t var="title"}My Events{/t}
{else}{assign var="title" value=$currentUser->getLogin()|escape:"html"|cat:"'s events"}{/if}

{if $currentUser->getId() == $user->getId()}
	{assign var="addLink" value=$currentUser->getUserPath('calendar.event.create')}
{/if}


{if $currentUser->getId() != $user->getId()}
    <div class="prInnerBottom"></div>
{/if}

{* PAGE CONTENT START *}

{literal}
<script type="text/javascript">
	var EasyAddApp = null;
	if ( !EasyAddApp ) {
		EasyAddApp = function () {
			return {
				EasyAddInit : null,
				onAllDayClick : function () {
					var obj = YAHOO.util.Dom.get("event_is_allday");
					if ( true == obj.checked ) {
						YAHOO.util.Dom.get("event_time_hour").disabled 		= true;
						YAHOO.util.Dom.get("event_time_minute").disabled 	= true;
					} else {
						YAHOO.util.Dom.get("event_time_hour").disabled 		= false;
						YAHOO.util.Dom.get("event_time_minute").disabled 	= false;
					}
				},
				onDtstartDateChanged : function () {
					var event_dtstart_date_Year = YAHOO.util.Dom.get('event_dtstart_date_Year');
					var event_dtstart_date_Month = YAHOO.util.Dom.get('event_dtstart_date_Month');
					var event_dtstart_date_Day = YAHOO.util.Dom.get('event_dtstart_date_Day');
					event_dtstart_date_Year = event_dtstart_date_Year.options[event_dtstart_date_Year.selectedIndex].value;
					event_dtstart_date_Month = event_dtstart_date_Month.options[event_dtstart_date_Month.selectedIndex].value;
					event_dtstart_date_Day = event_dtstart_date_Day.options[event_dtstart_date_Day.selectedIndex].value;

					var daysInMonth = EasyAddApp.daysInMonth(event_dtstart_date_Year, event_dtstart_date_Month);
					if ( event_dtstart_date_Day > daysInMonth ) {
						YAHOO.util.Dom.get('event_dtstart_date_Day').options.selectedIndex = daysInMonth - 1;
						event_dtstart_date_Day = daysInMonth;
					}
				},
				daysInMonth : function(year,month){
    				var D=new Date(year, month-1, 1, 12);
					return parseInt((-Date.parse(D)+D.setMonth(D.getMonth()+1)+36e5)/864e5);
				}
			}
		}();
	}
	var MonthViewApp = null;
	if ( !MonthViewApp ) {
		MonthViewApp = function () {
			return {
				currentTooltipId : null,
				currentLinkObj : null,
				eventTooltipTimer : null,
				showEventTooltip : function (id, linkObj) {
					clearTimeout(MonthViewApp.eventTooltipTimer);
					MonthViewApp.currentTooltipId = id;
					MonthViewApp.currentLinkObj = YAHOO.util.Dom.getRegion(linkObj);
					MonthViewApp.eventTooltipTimer = setTimeout(MonthViewApp.doShowEventTooltip, 1000);
					/*
					linkObjRegion = YAHOO.util.Dom.getRegion(linkObj);
					EventTooltipContentObj = YAHOO.util.Dom.get('EventTooltipContent');
					TooltipLinkHiddenContent = YAHOO.util.Dom.get('TooltipLinkHiddenContent'+id);
					EventTooltipContentObj.style.top = (linkObjRegion.bottom + 5) + 'px';
					EventTooltipContentObj.style.left = linkObjRegion.left + 'px';
					EventTooltipContentObj.innerHTML = TooltipLinkHiddenContent.innerHTML;
					EventTooltipContentObj.style.display = '';
					*/
				},
				doShowEventTooltip : function () {
					linkObjRegion = MonthViewApp.currentLinkObj;
					EventTooltipContentObj = YAHOO.util.Dom.get('EventTooltipContent');
					TooltipLinkHiddenContent = YAHOO.util.Dom.get('TooltipLinkHiddenContent'+MonthViewApp.currentTooltipId);
					EventTooltipContentObj.style.top = (linkObjRegion.bottom + 5) + 'px';
					EventTooltipContentObj.style.left = linkObjRegion.left + 'px';
					EventTooltipContentObj.innerHTML = TooltipLinkHiddenContent.innerHTML;
					EventTooltipContentObj.style.display = 'block';
				},
				hideEventTooltip : function () {
					clearTimeout(MonthViewApp.eventTooltipTimer);
					MonthViewApp.eventTooltipTimer = setTimeout(MonthViewApp.doHideEventTooltip, 300);
				},
				doHideEventTooltip : function () {
					clearTimeout(MonthViewApp.eventTooltipTimer);
					EventTooltipContentObj = YAHOO.util.Dom.get('EventTooltipContent');
					EventTooltipContentObj.style.display = 'none';
				},
				onTooltipOver : function () {
					clearTimeout(MonthViewApp.eventTooltipTimer);
				},
				onTooltipOut : function () {
					MonthViewApp.eventTooltipTimer = setTimeout(MonthViewApp.doHideEventTooltip, 300);
				}
			}
		}();
	}
</script>
{/literal}
<div class="prEvents-calendar">
	<div class="prClr3">
        <h2 class="prFloatLeft prNoInner">
            <a  href="{$currentUser->getUserPath()}calendar.month.view/year/{$objPrevDate->toString('yyyy')}/month/{$objPrevDate->toString('MM')}/">&laquo;</a>
            &nbsp;{$objCurrDate->toString('MMMM')}, {$objCurrDate->toString('yyyy')}&nbsp;
            <a href="{$currentUser->getUserPath()}calendar.month.view/year/{$objNextDate->toString('yyyy')}/month/{$objNextDate->toString('MM')}/">&raquo;</a>
        </h2>
        <div class="prClr3">
        {form from=$form_sel class=""}
            {form_hidden name="url" value=$currentUser->getUserPath('calendar.month.view')}
            <div class="prFloatRight">{form_select name='year' class='sel' size='1' 	selected=$objCurrDate->toString('yyyy') 	options=$years 		onChange="window.location.href=document.form_select_month.url.value + 'year/' + document.form_select_month.year.value + '/month/' + document.form_select_month.month.value + '/';"}</div>
            <div class="prFloatRight">{form_select name='month' class='sel'  size='1' selected=$objCurrDate->get('MONTH') options=$monthnames onChange="window.location.href=document.form_select_month.url.value + 'year/' + document.form_select_month.year.value + '/month/' + document.form_select_month.month.value + '/';"}</div>
        {/form}
        </div>
    </div>
	{foreach from=$objYear->getMonths() item=month}
    <div class="prInnerTop">
		<table cellspacing="0" cellpadding="0">
			<thead>
				{foreach from=$month->getWeekdaysHeader('FULL') item=wh}
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
						{if $arrDates[$strDate]}{assign var='tdStyle' value='prPreviewMonth prPreviewMonth-active'}
						{else}{assign var='tdStyle' value='prPreviewMonth'}
						{/if}
					{else}
						{if $strDate < $strObjDateNow}
							{assign var='tdStyle' value='prCurrentMonth-preview'}
						{else}
							{if $arrDates[$strDate]}
								{if $strDate != $strObjDateNow}{assign var='tdStyle' value='prCurrentMonth-event'}
								{else}{assign var='tdStyle' value='prCurrentMonth-event prCurrentMonth-active'}
								{/if}
							{else}
								{if $strDate != $strObjDateNow}{assign var='tdStyle' value='prCurrentMonth'}
								{else}{assign var='tdStyle' value='prCurrentMonth prCurrentMonth-active'}
								{/if}
							{/if}
						{/if}
					{/if}
					<td class="{$tdStyle}">
						<div class="prTRight">
							{$day->getDay()}
							<ol class="prTLeft">
                            {assign var='currIndex' value=0}
							{if $arrDates[$strDate]}
								{foreach from=$arrDates[$strDate] key=currentTimeOfDay item=TimesOfDay}
									{foreach from=$TimesOfDay item=currentEvent}
                                    	{assign var='currIndex' value=$currIndex+1}
                                        {if $currIndex <= 5}
                                            {assign var='objCEvent' value=$objEventList->createEvent($currentEvent, $currentTimezone)}
                                            <li>
											<span class="prEllipsis prCalendarCell">
                                                            {*
                                                                @todo I mean that we should search attendee for certain date $strDate,
                                                                not for all dates
                                                            *}
                                                {assign var='userAttendee' value=$objCEvent->getAttendee()->findAttendee($user)}
                                                {if $objCEvent->getOwnerType() == 'user'}
                                                    {if $user->getId() && null !== $userAttendee && $userAttendee->getAnswer() == 'NONE'}
                                                        <a class="ellipsis_init" href="{$objCEvent->getOwner()->getUserPath('calendar.event.view')}id/{$currentEvent.id}/uid/{$currentEvent.uid}/year/{$currentEvent.year}/month/{$currentEvent.month}/day/{$currentEvent.day}/"  onmouseover="MonthViewApp.showEventTooltip('{$currentEvent.id}{$currentEvent.uid}{$currentEvent.year}{$currentEvent.month}{$currentEvent.day}', this);" onmouseout="MonthViewApp.hideEventTooltip('{$currentEvent.id}{$currentEvent.uid}{$currentEvent.year}{$currentEvent.month}{$currentEvent.day}', this);">{*if $currentTimeOfDay != 'allday'}{$currentTimeOfDay}<br>{/if*}{$currentEvent.title|escape:html}</a> <span style="color:#7f7f7f;">(</span><img src="{$AppTheme->images}/decorators/horly_glass.gif" alt="" /><span style="color:#7f7f7f;">)</span>
                                                    {else}
                                                        <a class="ellipsis_init" href="{$objCEvent->getOwner()->getUserPath('calendar.event.view')}id/{$currentEvent.id}/uid/{$currentEvent.uid}/year/{$currentEvent.year}/month/{$currentEvent.month}/day/{$currentEvent.day}/"  onmouseover="MonthViewApp.showEventTooltip('{$currentEvent.id}{$currentEvent.uid}{$currentEvent.year}{$currentEvent.month}{$currentEvent.day}', this);" onmouseout="MonthViewApp.hideEventTooltip('{$currentEvent.id}{$currentEvent.uid}{$currentEvent.year}{$currentEvent.month}{$currentEvent.day}', this);">{*if $currentTimeOfDay != 'allday'}{$currentTimeOfDay}<br>{/if*}{$currentEvent.title|escape:html}</a>
                                                    {/if}
                                                {else}
                                                    {if $user->getId() && null !== $userAttendee && $userAttendee->getAnswer() == 'NONE'}
                                                        <a class="ellipsis_init" href="{$objCEvent->getOwner()->getGroupPath('calendar.event.view')}id/{$currentEvent.id}/uid/{$currentEvent.uid}/year/{$currentEvent.year}/month/{$currentEvent.month}/day/{$currentEvent.day}/"  onmouseover="MonthViewApp.showEventTooltip('{$currentEvent.id}{$currentEvent.uid}{$currentEvent.year}{$currentEvent.month}{$currentEvent.day}', this);" onmouseout="MonthViewApp.hideEventTooltip('{$currentEvent.id}{$currentEvent.uid}{$currentEvent.year}{$currentEvent.month}{$currentEvent.day}', this);">{*if $currentTimeOfDay != 'allday'}{$currentTimeOfDay}<br>{/if*}{$currentEvent.title|escape:html}</a> <span style="color:#7f7f7f;">(</span><img src="{$AppTheme->images}/decorators/horly_glass.gif" alt="" /><span style="color:#7f7f7f;">)</span>
                                                    {else}
                                                        <a class="ellipsis_init" href="{$objCEvent->getOwner()->getGroupPath('calendar.event.view')}id/{$currentEvent.id}/uid/{$currentEvent.uid}/year/{$currentEvent.year}/month/{$currentEvent.month}/day/{$currentEvent.day}/"  onmouseover="MonthViewApp.showEventTooltip('{$currentEvent.id}{$currentEvent.uid}{$currentEvent.year}{$currentEvent.month}{$currentEvent.day}', this);" onmouseout="MonthViewApp.hideEventTooltip('{$currentEvent.id}{$currentEvent.uid}{$currentEvent.year}{$currentEvent.month}{$currentEvent.day}', this);">{*if $currentTimeOfDay != 'allday'}{$currentTimeOfDay}<br>{/if*}{$currentEvent.title|escape:html}</a>
                                                    {/if}
                                                {/if}
											</span>
                                            </li>
                                            {stringbuilder mode='append' var='dContent'}
                                                <div  id="TooltipLinkHiddenContent{$currentEvent.id}{$currentEvent.uid}{$currentEvent.year}{$currentEvent.month}{$currentEvent.day}" style="display:none">
                                                    <div>
                                                                    {$objCEvent->displayDate('month.view.day.details', $user, $currentTimezone)}
                                                    </div>
                                                    <div>
                                                        {if $objCEvent->getOwnerType() == 'user'}
                                                            {if $user->getId() && null !== $userAttendee && $userAttendee->getAnswer() == 'NONE'}
                                                                <a href="{$objCEvent->getOwner()->getUserPath('calendar.event.view')}id/{$currentEvent.id}/uid/{$currentEvent.uid}/year/{$currentEvent.year}/month/{$currentEvent.month}/day/{$currentEvent.day}/"><b>{$objCEvent->getTitle()|escape:html}</b></a> <span style="color:#7f7f7f;"><b>(</b></span><img src="{$AppTheme->images}/decorators/horly_glass.gif" alt="" /><span style="color:#7f7f7f;"><b>)</b></span>
                                                            {else}
                                                                <a href="{$objCEvent->getOwner()->getUserPath('calendar.event.view')}id/{$currentEvent.id}/uid/{$currentEvent.uid}/year/{$currentEvent.year}/month/{$currentEvent.month}/day/{$currentEvent.day}/"><b>{$objCEvent->getTitle()|escape:html}</b></a>
                                                            {/if}
                                                        {else}
                                                            {if $user->getId() && null !== $userAttendee && $userAttendee->getAnswer() == 'NONE'}
                                                                <a href="{$objCEvent->getOwner()->getGroupPath('calendar.event.view')}id/{$currentEvent.id}/uid/{$currentEvent.uid}/year/{$currentEvent.year}/month/{$currentEvent.month}/day/{$currentEvent.day}/"><b>{$objCEvent->getTitle()|escape:html}</b></a> <span style="color:#7f7f7f;"><b>(</b></span><img src="{$AppTheme->images}/decorators/horly_glass.gif" alt="" /><span style="color:#7f7f7f;"><b>)</b></span>
                                                            {else}
                                                                <a href="{$objCEvent->getOwner()->getGroupPath('calendar.event.view')}id/{$currentEvent.id}/uid/{$currentEvent.uid}/year/{$currentEvent.year}/month/{$currentEvent.month}/day/{$currentEvent.day}/"><b>{$objCEvent->getTitle()|escape:html}</b></a>
                                                            {/if}
                                                        {/if}
                                                    </div>
                                                    <div>
                                                        {if null !== $user->getId() && $user->getId() == $objCEvent->getCreatorId()}
                                                            {t}Organizer : You are organizer{/t}
                                                        {else}
                                                            {t}Organizer :{/t} <a href="{$objCEvent->getCreator()->getUserPath('profile')}">{$objCEvent->getCreator()->getLogin()|escape:"html"}</a>
                                                        {/if}
                                                    </div>
                                                    <div>
                                                        {if $objCEvent->getOwnerType() == 'group'}
                                                            {t}Group event :{/t}
                                                            <a href="{$objCEvent->getOwner()->getGroupPath('summary')}">{$objCEvent->getOwner()->getName()|escape:html}</a>
                                                            <br />
                                                        {/if}
                                                    </div>
                                                    <div>
                                                        {if $objCEvent->getPrivacy()}{t}Private Event{/t}{else}{t}Public Event{/t}{/if}
                                                    </div>
                                                    <div class="prFloatRight prClr3">
                                                        {if $objCEvent->getOwnerType() == 'user'}
                                                            <a href="{$objCEvent->getOwner()->getUserPath('calendar.event.view')}id/{$objCEvent->getId()}/uid/{$objCEvent->getUid()}/year/{$objCEvent->getDtstart()->toString('yyyy')}/month/{$objCEvent->getDtstart()->toString('MM')}/day/{$objCEvent->getDtstart()->toString('dd')}/" class="">{t}See details{/t} &raquo;</a>
                                                        {else}
                                                            <a href="{$objCEvent->getOwner()->getGroupPath('calendar.event.view')}id/{$objCEvent->getId()}/uid/{$objCEvent->getUid()}/year/{$objCEvent->getDtstart()->toString('yyyy')}/month/{$objCEvent->getDtstart()->toString('MM')}/day/{$objCEvent->getDtstart()->toString('dd')}/" class="">{t}See details{/t} &raquo;</a>
                                                        {/if}
                                                    </div>
                                                </div>
                                            {/stringbuilder}
                                        {/if}
									{/foreach}
								{/foreach}
							{/if}
							{if $currIndex > 5}
							<li class="prCalendar-moreevents"><a href="#" onclick="xajax_doViewDayDetails('{$strDate}'); return false;" class="">+{$currIndex-5} {t}more{/t}</a></li>
                            {/if}
                            {if $Warecorp_ICal_AccessManager->canCreateEvent($currentUser, $user) }
							<a class="prCalendar-addevent" href="#null" onclick="xajax_doEasyAddEvent('{$day->getYear()}', '{$day->getMonth()}', '{$day->getDay()}'); return true;"></a>
                            {/if}
                           	</ol>
							
                            {stringbuilder mode='get_flush' var='dContent'}{/stringbuilder}
						</div>
					</td>
				{/foreach}
			</tr>
			{/foreach}
			</tbody>
		</table>
        </div>
	{/foreach}
</div>
<div id="EventTooltipContent" class="prToolTipContainer" style="position:absolute; display:none; border: 1px solid #F6A565; background-color:#FFFBE9; width: 250px; padding: 5px; font-size:12px" onmouseover="MonthViewApp.onTooltipOver();" onmouseout="MonthViewApp.onTooltipOut();"></div>

{* PAGE CONTENT END *}
