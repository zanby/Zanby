{*popup_item*}
    <!-- inner begin -->
<a href="{$currentUser->getUserPath('lists')}">{t}Back to Lists{/t}</a>
        <div class="prListContent prClr">
        	<div class="prListContentLeft">
            	{include file="users/lists/lists.view.base.special.tpl"}
       		</div>
        	<div class="prListContentRight">
            	{include file="users/lists/lists.view.left.column.tpl"}
        	</div>
        </div>
        <a name="add_form"></a>
        <div id="new_record" class="prInnerTop"></div>

{include file="users/lists/block_layer.tpl"}

<div id="deleteRecordPanel" style="display: none;">
    <p class="prTCenter prText2">{t}Do you really want to delete this item?{/t}</p>
    <input type="hidden" id="deleteRecordId" value="">

    <div class="prInnerTop">
	{t var='butoon'}Yes{/t}
            {linkbutton style="" name=$butoon onclick="xajax_list_view_delete_record(YAHOO.util.Dom.get('deleteRecordId').value); return false;"}
            <span class="prIndentLeftSmall">
			{t var='butoon_01'}No{/t}
			{linkbutton style="" name=$butoon_01 onclick="popup_window.close(); return false;"}</span>
    </div>
</div>
{literal}
<script type="text/javascript" src="{$JS_URL}/lists.js"></script>
<script type="text/javascript">
    YAHOO.util.Event.onDOMReady(initaddForm);
    function initaddForm()
    {
        {/literal}
        xajax_list_view_add_form({$list->getId()});
        {literal}
    }
</script>
{/literal}
{*popup_item*}