{include file="content_objects/edit_mode_settings_narrow.tpl"}

<!-- ============================================== -->
<div class="prInnerSmall">
	<table class="prForm">
		<tbody>
			<tr>
				<td class="prTBold prInnerSmallTop">
					<label>{t}Select event display style{/t}</label>
				</td>
			</tr>
			<tr>
				<td>
					<input name="event_display_style_{$cloneId}" id="event_display_style_1_{$cloneId}" type="radio" class="prAutoWidth prNoBorder" {if !$event_display_style || $event_display_style==1}checked="checked"{/if} onclick="event_display_style_change('{$cloneId}',1);" /><label for="event_display_style_1_{$cloneId}"> {t}Automatically rotate featured events{/t}</label>
				</td>
			</tr>
			<tr>
				<td>
					<input name="event_display_style_{$cloneId}" id="event_display_style_2_{$cloneId}" type="radio" class="prAutoWidth prNoBorder" {if $event_display_style==2}checked="checked"{/if} onclick="event_display_style_change('{$cloneId}',2);" /><label for="event_display_style_2_{$cloneId}"> {t}Manually select events to display{/t}</label>
				</td>
			</tr>
			<tr>
				<td>
					<input name="event_display_style_{$cloneId}" id="event_display_style_3_{$cloneId}" type="radio" class="prAutoWidth prNoBorder" {if $event_display_style==3}checked="checked"{/if} onclick="event_display_style_change('{$cloneId}',3);" /><label for="event_display_style_3_{$cloneId}"> {t}Automatically rotate events on a list with calendar{/t}</label>
				</td>
			</tr>
			<tr>
				<td class="prInnerSmallTop">
					<div class="prInnerTop">
						{if $event_display_style == 1}
							<label for="events_futered_display_number_{$cloneId}">{t}Display featured  event summaries for the next{/t}</label>
							<select class="prAutoWidth" id="events_futered_display_number_{$cloneId}" onchange="set_events_futered_display_number(this.value, '{$cloneId}');return false;">
					            <option value="1" {if $events_futered_display_number==1}selected="selected"{/if}>1</option>
					            <option value="2" {if $events_futered_display_number==2}selected="selected"{/if}>2</option>
					            <option value="3" {if $events_futered_display_number==3}selected="selected"{/if}>3</option>
					        </select>
							<label>{t}events{/t}</label>
						 {elseif $event_display_style == 3}
							<label for="events_display_number_{$cloneId}">{t}Display events for next{/t}</label>
							<select class="prAutoWidth" id="events_display_number_{$cloneId}" onchange="set_events_display_number(this.value, '{$cloneId}');return false;">
					            <option value="1" {if $events_futered_display_number==1}selected="selected"{/if}>1</option>
					            <option value="2" {if $events_futered_display_number==2}selected="selected"{/if}>2</option>
					            <option value="3" {if $events_futered_display_number==3}selected="selected"{/if}>3</option>
					        </select>
							<label>{t}days{/t}</label>
						{/if}
					</div>
				</td>
			</tr>
			{if $event_display_style == 3}
				<tr>
					<td>
						 <input id="events_show_calendar_{$cloneId}"  type="checkbox" class="prAutoWidth prNoBorder" {if $events_show_calendar}checked="checked"{/if} onclick="events_show_calendar_check((document.getElementById('events_show_calendar_{$cloneId}').checked)?1:0,'{$cloneId}');" /><label for="events_show_calendar_{$cloneId}"> {t}Show calendar{/t}</label>
					</td>
				</tr>
				<tr>
					<td>
						 <input id="events_show_summaries_{$cloneId}"  type="checkbox" class="prAutoWidth prNoBorder" {if $events_show_summaries}checked="checked"{/if} onclick="events_show_summaries_check((document.getElementById('events_show_summaries_{$cloneId}').checked)?1:0,'{$cloneId}');" /><label for="events_show_summaries_{$cloneId}"> {t}Show event description summaries{/t}</label>
					</td>
				</tr>
				<tr>
					<td>
						 <input id="events_show_venues_{$cloneId}"  type="checkbox" class="prAutoWidth prNoBorder" {if $events_show_venues}checked="checked"{/if} onclick="events_show_venues_check((document.getElementById('events_show_venues_{$cloneId}').checked)?1:0,'{$cloneId}');" /><label for="events_show_venues_{$cloneId}"> {t}Show Venue info if available{/t}</label>
					</td>
				</tr>
			{/if}
		</tbody>
	</table>
</div>
<!-- ============================================== -->

{include file="content_objects/headline_block_narrow.tpl"}
{include file="content_objects/ddGroupEvents/light_block_narrow.tpl"}
{include file="content_objects/edit_mode_buttons.tpl"}
