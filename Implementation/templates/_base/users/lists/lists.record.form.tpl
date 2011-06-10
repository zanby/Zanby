<div class="prListBox prIndentBottom prInnerBottom">
	<div class="prDropHeader prIndentTop">
		<h3><span id="display_index_{$id}">{$record.display_index}</span></h3>
		{if $action=='edit'}
		<div class="prHeaderTools"><a href="#" onclick="xajax_list_{$action}_save({$id},xajax.getFormValues('record_form_{$id}')); return false;">{t}Close{/t}</a></div> 
		{/if}
	</div>
	<div id="record_errors_{$id}">
		{include file="users/lists/errors.tpl"}
	</div>
	{form from=$form_record id=record_form_`$id`}
	{$XSLTProcessor->transformToXml($record.xml)}
		<table class="prForm">
			<col width="31%" />
			<col width="59%" />
			<col width="10%" />
			<tbody>
				<tr {if !$showExtraFields} style="display:none;"{/if}>
					<td class="prTRight"><label for="item_entry">{if $recordObj->getRecordName($listType)}{$recordObj->getRecordName($listType)} {/if}{t}Entry:{/t}</label></td>
					<td>
						{form_textarea rows=6 name=item_entry value=$record.data.item_entry|escape}
					</td>
					<td>&#160;</td>
				</tr>   
				<tr {if !$showExtraFields} style="display:none;"{/if}>
					<td class="prTRight"><label for="item_tags">{if $recordObj->getRecordName($listType)}{$recordObj->getRecordName($listType)} {/if}{t}Tags:{/t}</label></td>
					<td>
						{form_text name="item_tags" value=$record.data.item_tags|escape}
					</td>
					<td>&#160;</td>
				</tr>
                {if HTTP_CONTEXT == 'zccf' || HTTP_CONTEXT == 'zccf-alt' || HTTP_CONTEXT == 'zccf-base'}
				<tr {if !$showExtraFields} style="display:none;"{/if}>
					<td class="prTRight">&nbsp;</td>
					<td colspan=2><div class="prTip">{t}Tags are a way to group your lists and are separated by spaces. Use quotes around multi-word tags like "Public LIbrary"{/t}</div></td>
				</tr>
                {/if}
				<tr>
					<td>&#160;</td>
					<td>
					{t var='button'}Save{/t}
						{linkbutton name=$button onclick="xajax_list_`$action`_save(`$id`,xajax.getFormValues('record_form_`$id`')); return false;"}
					</td>
					<td>&#160;</td>
				</tr>
			</tbody>
		</table>
	{/form}
</div>