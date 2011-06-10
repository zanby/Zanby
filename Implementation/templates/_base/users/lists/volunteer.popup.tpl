{*popup_item*}
{form from=$form id="valuteer_form"}
{form_hidden name=record_id value=$record->getId()}
{t}You are volunteering to...{/t} {$record->getTitle()}
	<div class="prInnerTop">        
		<label for="volunteerComment">{t}Comment{/t}</label><span> {t}(optional){/t}</span>
	</div>
	<div class="prIndentTop prForm">
	{form_textarea id="volunteerComment" rows=3 name=comment value=$comment|escape}
	</div>
	<div class="prTCenter prInnerTop">
	{t var='button_01'}Volunteer{/t}            
			{linkbutton name=$button_01 link="#" onclick="xajax_list_volunteer_popup_close(xajax.getFormValues('valuteer_form')); return false;"}
			<span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a>{t}Cancel{/t}</a></span></div>
{/form}
{*popup_item*}