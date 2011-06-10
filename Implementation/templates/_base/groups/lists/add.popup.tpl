{*popup_item*}
{form from=$form id="list_add_form"}
{form_hidden name=list_id value=$list->getId()}
{if !$importType || $importType == 'watch'}
	{if !$listsList}
		{form_radio name="add_type" id="radio_merge" value="merge" checked=$checkedType disabled="disabled"}<label for="radio_merge"> {t}Merge this list with an existing list{/t}</label>
		<div class="prIndentTop">
		{form_select name="merge_list" escape="html" options=$listsList disabled="disabled"  class="prMiddleFormItem"}
		</div>
		<div class="prInnerTop">
		{form_radio name="add_type" id="radio_new" value="new" checked=$checkedType}
	{else}
		{form_radio name="add_type" id="radio_merge" value="merge" checked=$checkedType}<label for="radio_merge">  {t}Merge this list with an existing list{/t}</label>
		<div class="prIndentTop">                
		{form_select name="merge_list" escape="html" options=$listsList class="prMiddleFormItem"}
		</div>
		<div class="prInnerTop">
		{form_radio name="add_type" id="radio_new" value="new" checked=$checkedType}
	{/if}<label for="radio_new">  {t}Save as new list{/t}</label>
	</div>
	<div class="prIndentTop">
	{form_text name="title"  class="prMiddleFormItem" value="Copy of "|cat:$list->getTitle()|escape}
	</div>           
	{if $importType == 'watch'}
		<div class="prInnerTop">
			{form_radio name="add_type" id="radio_watch" value="offwatch" checked=$checkedType}<label for="radio_watch">  {t}Take off watch list{/t}</label> 
			<div class="prIndentTop">
				 {t}{tparam value=$importDate|user_date_format:$user->getTimezone()|date_locale:'DATETIME'}{tparam value=$TIMEZONE}You started watching this list on %s %s{/t}
			</div>
		</div>
	{else}
		<div class="prInnerTop">
			{form_radio name="add_type" id="radio_watch" value="watch" checked=$checkedType}<label for="radio_watch"> {t}Watch list{/t}</label> 
			<div class="prIndentTop">
			 {t}Keep informed about additions and comments.{/t}
			</div>
		</div>
	{/if}
	
{elseif $importType == 'merge' || $importType == 'new'}
	{form_radio name="add_type" id="radio_merge" value="merge" checked=$checkedType}<label for="radio_merge">  {t}Merge this list with an existing list{/t}</label>
	<div class="prIndentTop">
		{if $importType == 'merge'} {t}You last merged this list with{/t}
		{else} {t}You saved this list as{/t}
		{/if}
	</div>
	<div class="prIndentTop">
	{form_select name="merge_list" escape="html" options=$listsList selected=$lastTarget->getId() class="prMiddleFormItem"}
	</div>
	<div class="prIndentTop">
	 {t}{tparam value=$importDate|user_date_format:$user->getTimezone()|date_locale:'DATETIME'}{tparam value=$TIMEZONE}on %s %s{/t}</div>
	<div class="prInnerTop">
		{form_radio name="add_type" id="radio_new" value="new" checked=$checkedType}<label for="radio_new">  {t}Save as New List{/t}</label>
	</div>
	<div class="prIndentTop">
		{form_text name="title" class="prMiddleFormItem" value="Copy of "|cat:$list->getTitle()|escape}
	</div>
	<div class="prInnerTop">
	{form_radio name="add_type" id="radio_watch" value="watch" checked=$checkedType}<label for="radio_watch">  {t}Watch List{/t}</label> 
	</div>
	<div class="prIndentTop">
	 {t}Keep informed about additions and comments.{/t}
	</div>            
{/if}
<div class="prTCenter prInnerTop">
		{if $importType}{t var="buttonTitle"}Update{/t}{assign var='buttonTitle' value=$buttonTitle}{else}{t var="buttonTitle"}Add List{/t}{assign var='buttonTitle' value=$buttonTitle}{/if}
		<span class="prIndentLeftSmall">{linkbutton name=$buttonTitle link="#" onclick="xajax_list_add_popup_close(xajax.getFormValues('list_add_form')); return false;"}</span>
		<span class="prIEVerticalAling">{t} or{/t}<a class="prIndentLeftSmall" href="#" onclick="xajax_list_add_popup_close(); return false;">{t}Cancel{/t}</a></span>
</div>
{/form}
{*popup_item*}