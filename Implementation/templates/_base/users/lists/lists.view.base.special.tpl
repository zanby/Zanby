<h3>{$list->getListTypeName()} {$list->getTitle()|wordwrap:20:"\n":true|escape:"html"}
{if $Warecorp_List_AccessManager->canManageList($list, $currentUser, $user)}
	<span>[ <a href="{$editListLink}listid/{$list->getId()}/">{t}Edit{/t}</a> ]</span>
{/if}
</h3>
{if $list->getDescription()}
    <div class="prInnerSmallTop">
        {$list->getDescription()|escape|wordwrap:25:"\n":true}
    </div>
{else}
	<div class="prInnerTop"></div>
{/if}
<div class="prInnerSmallTop">
    {t}Grab an item.{/t} {if $list->getAdding()}{t}Add an item then grab it.{/t}{/if}
</div>
<div class="prClr prInnerTop">
    <div class="prFloatLeft" id="records_count">
	 {if $list->getRecordsCount() != 1}
        {t}{tparam value=$list->getRecordsCount()}There are %s items in this list{/t}
    {else}
        {t}{tparam value=$list->getRecordsCount()}There is %s item in this list{/t}
    {/if}
	</div>
	<label class="prFloatLeft prInnerLeft" for="order_id"> {t}Sort list:{/t}</label>
	<select id="order_id" class="prFloatLeft prIndentLeftSmall" onchange="xajax_list_view_onchange_order({$list->getId()}, this.options[this.selectedIndex].value)">
        {foreach from=$orderVariants key=val item=title}
            <option value="{$val}"{if $val==$defaultOrder} selected="selected"{/if}>{$title|escape}</option>
        {/foreach}
    </select>   
</div>
<div id="list_items">
    {foreach item=record name=records from=$records}
        <div id="item_{$record->getId()}">
        {include file="users/lists/lists.view.record.special.tpl"}
        </div>
    {/foreach}
</div>