{if is_numeric($record->getId())}
<div class="prListBox prInnerBottom prIndentTop">
	<div id="display_index_{$record->getId()}">{$record->displayIndex}</div>
	<div id="record_errors_{$record->getId()}" class="prIndentBottom1">
		{include file="users/lists/errors.tpl"}
	</div>
	{form from=$form_record id="record_form_"|cat:$record->getId()}
	{form_hidden name=list_id value=$list_id}
	{$XSLTProcessor->transformToXml($record->domXml)}
	<table class="prForm">
		<col width="31%"/>
		<col width="59%"/>
		<col width="10%"/>		
		<tbody>
		<tr {if !$showExtraFields} style="display:none;"{/if}>
			<td class="prTRight"><label for="item_entry">{if $record->getRecordName()}{$record->getRecordName()} {/if}{t}Entry:{/t}</label></td>
			<td>
				{form_textarea rows=6 name=item_entry value=$record->getEntry()|escape}
			</td>
			<td></td>
		</tr>   
		<tr {if !$showExtraFields} style="display:none;"{/if}>
			<td class="prTRight"><label for="item_tags">{if $record->getRecordName()}{$record->getRecordName()} {/if}{t}Tags:{/t}</label></td>
			<td>
				{form_text name="item_tags" value=$record->tags|escape}
			</td>
			<td></td>
		</tr>
        {if HTTP_CONTEXT == 'zccf' || HTTP_CONTEXT == 'zccf-alt' || HTTP_CONTEXT == 'zccf-base'}
		<tr {if !$showExtraFields} style="display:none;"{/if}>
			<td class="prTRight">&nbsp;</td>
			<td colspan=2><div class="prTip">{t}Tags are a way to group your lists and are separated by spaces. Use quotes around multi-word tags like "Public LIbrary"{/t}</div></td>
		</tr>
        {/if}
		<tr>
			<td></td>
			<td class="prTRight">
			{t var='button_01'}Save{/t}
				{linkbutton name=$button_01 onclick="xajax_list_view_save("|cat:$record->getId()|cat:" ,xajax.getFormValues('record_form_"|cat:$record->getId()|cat:"')); return false;"}
			</td>
			<td></td>
		</tr>       
		</tbody>
	</table>
	{/form}
</div>
{else}
    <h3>{t}+ Add list item{/t}</h3>
    <div id="record_errors_{$record->getId()}" class="prIndentBottom1">
        {include file="users/lists/errors.tpl"}
    </div>
	<div class="prLabelTabWidth">
    {form from=$form_record id="record_form_"|cat:$record->getId()}
    {form_hidden name=list_id value=$list_id}
    {$XSLTProcessor->transformToXml($record->domXml)}
    <table class="prForm">
        <col width="31%"/>
		<col width="59%"/>
		<col width="10%"/>
        <tbody>
        <tr {if !$showExtraFields} style="display:none;"{/if}>
            <td class="prTRight"><label for="item_entry">{if $record->getRecordName()}{$record->getRecordName()} {/if}{t}Entry:{/t}</label></td>
            <td>
                {form_textarea rows=6 name=item_entry value=$record->getEntry()|escape}
            </td>
			<td></td>
        </tr>   
        <tr {if !$showExtraFields} style="display:none;"{/if}>
            <td class="prTRight"><label for="item_tags">{if $record->getRecordName()}{$record->getRecordName()} {/if}{t}Tags:{/t}</label></td>
            <td>
                {form_text name="item_tags" value=$record->tags|escape}
            </td>
			<td></td>
        </tr>
        {if HTTP_CONTEXT == 'zccf' || HTTP_CONTEXT == 'zccf-alt' || HTTP_CONTEXT == 'zccf-base'}
        <tr {if !$showExtraFields} style="display:none;"{/if}>
            <td class="prTRight">&nbsp;</td>
            <td colspan=2><div class="prTip">{t}Tags are a way to group your lists and are separated by spaces. Use quotes around multi-word tags like "Public LIbrary"{/t}</div></td>
        </tr>
        {/if}
        <tr>
            <td></td>
            <td>
			{t var='button_02'}+ Add Item{/t}
                {linkbutton name=$button_02 onclick="lock_content(); xajax_list_view_save('new',xajax.getFormValues('record_form_new'));  return false;"}
            </td>
			<td></td>
        </tr>
		</tbody>
	</table>
    {/form}
	</div>
{/if}