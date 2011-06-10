{*popup_item*}
{form from=$form id="valuteer_form"}
{form_hidden name=record_id value=$record->getId()}
{t}{tparam value=$record->getTitle()}You are volunteering to... %s{/t}
	<div class="prInnerTop">
		<label for="volunteerComment">{t}Comment{/t}</label><span> {t}(optional){/t}</span>
	</div>
	<div class="prIndentBottom" style="display:none" id="ErrorMessageMainTooLong"></div>
	<div class="prIndentTop prForm">
		{form_textarea id="volunteerComment" rows=3 name=comment value=$comment|escape}
	</div>
	<div class="prTCenter prInnerTop">
	{t var="in_button"}Volunteer{/t}
	{linkbutton name=$in_button link="#" onclick="xajax_list_volunteer_popup_close(xajax.getFormValues('valuteer_form')); return false;"}
			<span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="xajax_list_volunteer_popup_close(); return false;">{t}Cancel{/t}</a></span>
	</div>
{/form}
{*popup_item*}