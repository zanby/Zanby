			<div class="prToggleArea">
				<!-- form container -->
				<div id="reminder-options-full">
				   <h4>{t}Send an event reminder by email{/t}</h4>
				   {form_radio name="event_reminder_mode" id="event_reminder_mode_1" value="1" checked=$formParams.event_reminder_mode}<label for="event_reminder_mode_1" class=""> {t}Do not send a reminder{/t}</label>
				   <div class="prClr3 prInnerSmallTop">
						<div class="prFloatLeft">
							{form_radio name="event_reminder_mode" id="event_reminder_mode_2" value="2" checked=$formParams.event_reminder_mode}<label for="event_reminder_mode_2" class="prInnerRight"> {t}Send a reminder{/t}</label>
						</div>
						<div class="prFloatLeft">
							{form_select name="event_reminder_1" id="event_reminder_1" selected=$formParams.event_reminder_1 options=$ReminderOptions1 class=""}<label for="event_reminder_1"> {t}before and{/t}</label>
							<div class="prInnerSmallTop">
							{form_select name="event_reminder_2" id="event_reminder_2" selected=$formParams.event_reminder_2 options=$ReminderOptions2 class=""}<label for="event_reminder_2"> {t}before the event{/t}</label>
							</div>
						</div>
					</div>
					<div class="prInnerSmallTop">
					{form_checkbox name="event_reminder_to_guest_list" id="event_reminder_to_guest_list" value="1" checked=$formParams.event_reminder_to_guest_list}
					<label for="event_reminder_to_guest_list" class=""> {t}Entire Guest list{/t}</label>
					</div>
				 </div>	
				{form_hidden name="show_reminder_block" id="show_reminder_block" value=$formParams.show_reminder_block}
			</div>
