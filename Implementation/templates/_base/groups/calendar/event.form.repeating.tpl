	<div {if $viewMode != 'ROW' && !$editFutureDates} style="display:none;"{/if}>
			<div>
				<div class="prToggleArea">
				<!-- form container -->
					<div class="prInnerTop prClr2">
						<div class="">{form_radio name="rrule_freq" id='rrule_freq_NONE' value="NONE" onclick="CreateEventApp.changeRruleType(this);" checked=$rrule_freq} <label for="rrule_freq_NONE" class="">{t}Not repeat{/t}</label>
						<div class="prIndentTopSmall">
						{form_radio name="rrule_freq" id='rrule_freq_DAILY' value="DAILY" onclick="CreateEventApp.changeRruleType(this);" checked=$rrule_freq} <label for="rrule_freq_DAILY" class="">{t}Daily{/t}</label>
						</div>
						<div class="prIndentTopSmall">
						{form_radio name="rrule_freq" id='rrule_freq_WEEKLY' value="WEEKLY" onclick="CreateEventApp.changeRruleType(this);" checked=$rrule_freq} <label for="rrule_freq_WEEKLY" class="">{t}Weekly{/t}</label>
						</div>
						<div class="prIndentTopSmall">
						{form_radio name="rrule_freq" id='rrule_freq_MONTHLY' value="MONTHLY" onclick="CreateEventApp.changeRruleType(this);" checked=$rrule_freq} <label for="rrule_freq_MONTHLY" class="">{t}Monthly{/t}</label>
						</div>
						<div class="prIndentTopSmall">
						{form_radio name="rrule_freq" id='rrule_freq_YEARLY' value="YEARLY" onclick="CreateEventApp.changeRruleType(this);" checked=$rrule_freq} <label for="rrule_freq_YEARLY" class="">{t}Yearly{/t}</label>
						</div>
					</div>
					<div class="prInner">
						<div id="RRULE_DAILY_OPTIONS" style="{if $rrule_freq == 'DAILY'}{else}display: none;{/if}" class="prInner">
							{form_radio name="rrule_daily_option" value="1" id="rrule_daily_option1" checked=$formParams.rrule_daily_option}<label for="rrule_daily_option1" class="prInnerRight"> {t}repeat{/t}</label>
							{form_select name="rrule_daily_interval1" options=$every selected=$formParams.rrule_daily_interval1 class=""} <label for="rrule_daily_option1" class="prInnerRight">{t}day{/t}</label>
							 <div class="prInnerSmallTop">
							 {form_radio name="rrule_daily_option" value="2" id="rrule_daily_option2" checked=$formParams.rrule_daily_option} <label for="rrule_daily_option2" class=""> {t}repeat every work day{/t}</label>
							 </div>
						</div>
						<div class="prInner prClr3" id="RRULE_WEEKLY_OPTIONS" style="{if $rrule_freq == 'WEEKLY'}{else}display: none;{/if}">
						{form_radio name="rrule_weekly_option" value="1" checked=$formParams.rrule_weekly_option}<label class="prInnerRight" for="rrule_weekly_option"> {t}repeat{/t}</label>
						{form_select name="rrule_weekly_interval1" options=$every selected=$formParams.rrule_weekly_interval1 class=""} <label class="prInnerRight" for="rrule_weekly_option">{t}week on:{/t}</label>

						<table class="prForm" id="WEEKLY_BY_WEEKDAY">
							<tr>
								<td>{form_checkbox name="rrule_weekly_byday1[]" value="MO" id="rrule_weekly_byday1_1" checked=$formParams.rrule_weekly_byday1.MO}<label for="rrule_weekly_byday1_1" class=""> {t}Monday{/t}</label></td>
								<td>{form_checkbox name="rrule_weekly_byday1[]" value="TU" id="rrule_weekly_byday1_2" checked=$formParams.rrule_weekly_byday1.TU}<label for="rrule_weekly_byday1_2" class=""> {t}Tuesday{/t}</label></td>
								<td>{form_checkbox name="rrule_weekly_byday1[]" value="WE" id="rrule_weekly_byday1_3" checked=$formParams.rrule_weekly_byday1.WE}<label for="rrule_weekly_byday1_3" class=""> {t}Wednesday{/t}</label></td>
								<td>{form_checkbox name="rrule_weekly_byday1[]" value="TH" id="rrule_weekly_byday1_4" checked=$formParams.rrule_weekly_byday1.TH}<label for="rrule_weekly_byday1_4" class=""> {t}Thursday{/t}</label></td>
							</tr>
							<tr>
								<td>{form_checkbox name="rrule_weekly_byday1[]" id="rrule_weekly_byday1_5" value="FR" checked=$formParams.rrule_weekly_byday1.FR}<label for="rrule_weekly_byday1_5" class=""> {t}Friday{/t}</label></td>
								<td>{form_checkbox name="rrule_weekly_byday1[]" id="rrule_weekly_byday1_6" value="SA" checked=$formParams.rrule_weekly_byday1.SA}<label for="rrule_weekly_byday1_6" class=""> {t}Saturday{/t}</label></td>
								<td>{form_checkbox name="rrule_weekly_byday1[]" id="rrule_weekly_byday1_7" value="SU" checked=$formParams.rrule_weekly_byday1.SU}<label for="rrule_weekly_byday1_7" class=""> {t}Sunday{/t}</label></td>

								<td></td>
							</tr>
						</table>
						</div>
						<div class="prInner prClr3" id="RRULE_MONTHLY_OPTIONS" style="{if $rrule_freq == 'MONTHLY'}{else}display: none;{/if}">
						   {form_radio name="rrule_monthly_option" value="1" checked=$formParams.rrule_monthly_option id="rrule_monthly_option1"}<label class="prInnerRight" for="rrule_monthly_option1"> {t}repeat{/t}</label>
						   {form_text name="rrule_monthly_bymonthday1" id="rrule_monthly_bymonthday1" readonly='readonly' value=$formParams.rrule_monthly_bymonthday1|escape:html class=""}
						   <label class="prInnerRight" for="rrule_monthly_option1"> {t}day of the month {/t}</label>
						   {form_select name="rrule_monthly_interval1" options=$every selected=$formParams.rrule_monthly_interval1 class=""} <label class="prInnerRight" for="rrule_monthly_option1">{t}month{/t}</label>

                           <div class="prInnerSmallTop">
                            {form_radio name="rrule_monthly_option" value="3" checked=$formParams.rrule_monthly_option id="rrule_monthly_option2"}
                            <label class="prInnerRight" for="rrule_monthly_option2"> {t}repeat{/t}</label>
                            {form_select name="rrule_monthly_bymonthday3" options=$month_side selected=$formParams.rrule_monthly_bymonthday3 class=""}
                             <label class="prInnerRight" for="rrule_monthly_option2">{t}day of the month {/t}</label>
                            {form_select name="rrule_monthly_interval3" options=$every selected=$formParams.rrule_monthly_interval3 class=""} <label class="prInnerRight" for="rrule_monthly_option2">
                            {t}month{/t}</label>
                            </div>
							
						   <div class="prInnerSmallTop">
						   {form_radio name="rrule_monthly_option" value="2" checked=$formParams.rrule_monthly_option id="rrule_monthly_option3"} <label class="prInnerRight" for="rrule_monthly_option3">{t}on{/t}</label>
						   {form_select name="rrule_monthly_setpos2" selected=$formParams.rrule_monthly_setpos2 options=$setpos class=""}
						   {form_select name="rrule_monthly_byday2" id="rrule_monthly_byday2" selected=$formParams.rrule_monthly_byday2 options=$weekdays class=""} 
						   {form_select name="rrule_monthly_interval2" options=$every selected=$formParams.rrule_monthly_interval2 class=""}<label class="prInnerRight" for="rrule_monthly_option3"> {t}month{/t}</label>
							 </div>
						</div>
						<div class="prInner prClr3" id="RRULE_YEARLY_OPTIONS" style="{if $rrule_freq == 'YEARLY'}{else}display: none;{/if}">
							{form_radio name="rrule_yearly_option" value="1" checked=$formParams.rrule_yearly_option id="rrule_yearly_option1"} <label class="prInnerRight" for="rrule_yearly_option1">{t}repeat every{/t}</label>
							{form_text name="rrule_yearly_bymonthday1" id="rrule_yearly_bymonthday1" readonly='readonly' value=$formParams.rrule_yearly_bymonthday1|escape:html class=""}  <label class="prInnerRight" for="rrule_yearly_option1">{t}day of month{/t}</label>
							{form_select name="rrule_yearly_bymonth1" id="rrule_yearly_bymonth1" selected=$formParams.rrule_yearly_bymonth1 options=$months class=""}
							<div class="prInnerSmallTop">
							{form_radio name="rrule_yearly_option" value="2" checked=$formParams.rrule_yearly_option id="rrule_yearly_option2"} <label class="prInnerRight" for="rrule_yearly_option2">{t}on{/t}</label>
							{form_select name="rrule_yearly_setpos2" selected=$formParams.rrule_yearly_setpos2 options=$setpos class=""}&#160;
							{form_select name="rrule_yearly_byday2" id="rrule_yearly_byday2" selected=$formParams.rrule_yearly_byday2 options=$weekdays class=""}  <label class="prInnerRight" for="rrule_yearly_option2">
							{t}of month{/t}</label> 
							{form_select name="rrule_yearly_bymonth2" id="rrule_yearly_bymonth2" selected=$formParams.rrule_yearly_bymonth2 options=$months  class=""}
							</div>
						</div>
						<div class="prInner" id="RRULE_UNTIL_OPTIONS" style="{if $rrule_freq == 'NONE'}display: none;{/if}">
							<h3>{t}Repeating Range{/t}</h3>
						{form_radio name="rrule_until_option" id="rrule_until_option_1" checked=$formParams.rrule_until_option value="1"}<label for="rrule_until_option_1" class=""> {t}No End Date{/t}</label>
							<div class="prInnerSmallTop prClr2">
								<div class="prFloatLeft">{form_radio name="rrule_until_option" id="rrule_until_option_3" checked=$formParams.rrule_until_option value="3"}<label for="rrule_until_option_3" class="prInnerRight"> {t}Until{/t}</label></div>
								<div class="prFloatLeft prDateWidth" id="startDateContainer">
									{form_select_date start_year="-20" end_year="+20" prefix="date_" field_array="rrule_until_date" time=$formParams.rrule_until_date->toString('yyyy-MM-dd')}</div>
								<div class="prFloatLeft"><a href="#null" id="rruleUntilCalendarDialogContainerLink"><img src="{$AppTheme->images}/decorators/event/icon-calendar.gif" class="" /></a></div>
								<div id="rruleUntilCalendarDialogContainerDIV"></div>
							</div>
						</div>
					</div>
				</div>
			{form_hidden name="show_repeating_block" id="show_repeating_block" value=$formParams.show_repeating_block}
				</div>
            </div>
	</div>