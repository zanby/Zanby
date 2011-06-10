{*popup_item*}
{form from=$form id="form_copy_event" onsubmit="xajax_doCopyEvent("|cat:$objEvent->getId()|cat:", "|cat:$objEvent->getUid()|cat:", xajax.getFormValues('form_copy_event')); return false;"}
    	
		{if $errors}
				<div>
					{foreach item=e from=$errors}
						<strong>{t}ERROR:{/t}</strong> {$e|escape:"html"} 
					{/foreach}
				</div>
		{/if}
		<div class="prTCenter">
			<label for="event_name">{t}Rename the event{/t}</label>
		</div>
		<div class="prIndentTopSmall prTCenter">
		{form_text name="event_name" value=$all_data.event_name class="prMiddleFormItem"}
		</div>		
		<div class="prTCenter prInnerTop">
		<span class="prIndentLeftSmall">
		{t var='button'}Copy Event{/t}
		{linkbutton name=$button onclick="xajax_doCopyEvent("|cat:$objEvent->getId()|cat:", "|cat:$objEvent->getUid()|cat:", xajax.getFormValues('form_copy_event')); return false;"}</span>
			<span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="popup_window.close();return false;">{t}Cancel{/t}</a></span>
		</div>
		
{/form}
{*popup_item*}