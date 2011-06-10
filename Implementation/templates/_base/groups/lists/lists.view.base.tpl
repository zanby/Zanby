<h3>{$list->getTitle()|wordwrap:20:"\n":true|escape:"html"}</h3>
{if $list->getDescription()}
    <div class="prInnerSmallTop">
        {$list->getDescription()|escape|longwords:25:"\n":true}
    </div>
{else}
{/if}
<div class="prClr3 prInnerTop">
    <div class="prFloatLeft" id="records_count">
		{if $list->getRecordsCount() != 1}
			{t}{tparam value=$list->getRecordsCount()}There are %s items in this list{/t}
		{else}
			{t}{tparam value=$list->getRecordsCount()}There is %s item in this list{/t}
		{/if}
	</div>
	<div class="prFloatRight">
	<label class="prFloatLeft prInnerLeft" for="order_id">{t}Sort list:{/t}</label>
	<select id="order_id" class="prFloatLeft prIndentLeftSmall" onchange="xajax_list_view_onchange_order({$list->getId()}, this.options[this.selectedIndex].value)">
        {foreach from=$orderVariants key=val item=title}
            <option value="{$val}"{if $val==$defaultOrder} selected="selected"{/if}>{$title|escape}</option>
        {/foreach}
   </select>
   </div>
</div>
<div id="list_items">
    {foreach item=record name=records from=$records}
        <div id="item_{$record->getId()}">
        {include file="groups/lists/lists.view.record.tpl"}
        </div>
    {/foreach}
</div>
<div id="deleteRecordPanel" title="{t}Delete{/t}" style="display: none;">
    <div class="prTCenter prText2">{t}Do you really want to delete this item?{/t}</div>
    <input type="hidden" id="deleteRecordId" value="">
     <div class="prInnerTop prTCenter">
            {t var="in_button_2"}Delete{/t}
            {linkbutton name=$in_button_2 onclick="xajax_list_view_delete_record(YAHOO.util.Dom.get('deleteRecordId').value); return false;"}
            <span class="prIEVerticalAling prIndentLeftSmall">{t} or {/t}<a class="prIndentLeftSmall" onclick="popup_window.close(); return false;" href="#">{t}Cancel{/t}</a></span>
     </div>
</div>