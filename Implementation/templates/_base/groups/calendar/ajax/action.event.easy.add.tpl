{*popup_item*}
{form from=$form id="easyAddEvent" onsubmit="xajax_doEasyAddEvent('"|cat:$year|cat:"', '"|cat:$month|cat:"', '"|cat:$day|cat:"', xajax.getFormValues('easyAddEvent'));  return false;"}
{form_errors_summary}   
<table class="prForm">
<col width="15%" />
<col width="85%" />
	<tr>
		<td class="prTRight prInnerTop"><label for="event_title">{t}Name:{/t}</label></td>
		<td>
			<div class="prIndentTopSmall">
			{form_text name='event_title' value=$event_title|escape:html}
			</div>
		</td>
	</tr>
	<tr>
		<td class="prTRight prInnerTop"><label>{t}Date:{/t}</label></td>
		<td>
		<div class="prIndentTopSmall">		
		{form_select_date start_year="-20" end_year="+20" prefix="date_" field_array="event_dtstart" time=$objDate->toString('yyyy-MM-dd')}		
		</div>
		</td>
	</tr>
	<tr>
		<td class="prTRight prInnerTop"><label for="event_time_hour">{t}Time:{/t}</label></td>
		<td>
		{if $event_is_allday == 1}
			{form_select disabled='disabled' name="event_time_hour" id="event_time_hour" selected=$objDate->toString('H') options=$hours class="prEventTime prNoMargin"}
			{form_select disabled='disabled' name="event_time_minute" id="event_time_minute" selected=$objDate->toString('mm') options=$minutes class="prEventTime"}
		{else}
			{form_select name="event_time_hour" id="event_time_hour" selected=$objDate->toString('H') options=$hours class="prEventTime prNoMargin"}
			{form_select name="event_time_minute" id="event_time_minute" selected=$objDate->toString('mm') options=$minutes class="prEventTime"}
		{/if}
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
		{form_checkbox name="event_is_allday" id="event_is_allday" checked=$event_is_allday value="1" onclick="EasyAddApp.onAllDayClick();"}<label for="event_is_allday"> {t}This is an all day event{/t}</label>
		</td>
	</tr>
	<tr>
		<td class="prTRight prInnerTop"><label for="event_category">{t}Category:{/t}</label></td>
		<td>
			<div class="prIndentTopSmall">
			{form_select name='event_category' options=$categories selected=$event_category}
			</div>
		</td>
	</tr>
	<tr>
		<td class="prTRight prInnerTop"><label for="event_tags">{t}Tags:{/t}</label></td>
		<td>
			<div class="prIndentTopSmall">
			{form_text name='event_tags' value=$event_tags|escape:html}
			</div>
		</td>
	</tr>
</table>
<div class="prTCenter prIndentTop">{t var="in_submit"}Add New Event{/t}{form_submit name="add" value=$in_submit onclick="xajax_doEasyAddEvent('"|cat:$year|cat:"', '"|cat:$month|cat:"', '"|cat:$day|cat:"', xajax.getFormValues('easyAddEvent'));  return false;"}</div>
{/form}
{*popup_item*}