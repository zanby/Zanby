{*popup_item*}
{form from=$form onsubmit="xajax_doAttachList(document.list_select_form.list.value); return false;"}

{if $lstLists}
	<div class="prTCenter">
		<label for="list">{t}Select the list you would like to attach to the event{/t}</label>
		<div class="prInnerSmallTop">
		<select name="list" id="list" class="prLargeFormItem">
		{foreach from=$lstLists item='item'}
			<option value="{$item->getId()}">{$item->getTitle()|escape:html}</option>			
		{/foreach}
		</select>
		</div>
	</div>
{else}
	<div class="prTCenter prText2">{t}No Lists{/t}</div>
{/if}
<div class="prTCenter prIndentTop">
	{if $lstLists}
		{t var="in_button"}Attach List{/t}{linkbutton name=$in_button onclick="xajax_doAttachList(document.list_select_form.list.value); return false;"} <span class="prIEVerticalAling">{t}or{/t}
	{/if}
	<a href="#" onclick="popup_window.close();return false;">{t}Cancel{/t}</a>{if $lstLists}</span>{/if}
</div>		
{/form}
{*popup_item*}