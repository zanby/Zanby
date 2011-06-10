
<a href="{$currentUser->getUserPath('lists')}">{t}Back to Lists{/t}</a>
       <div class="prListContent prClr3">
			<div class="prListContentLeft prClr3">
				{include file="users/lists/lists.view.base.tpl"}
			</div>
			<div class="prListContentRight prClr3">
				{include file="users/lists/lists.view.left.column.tpl"}
			</div>
		</div>
        <a name="add_form"></a>
		<div id="new_record" class="prInnerTop"></div>
    <!-- inner end -->

{include file="users/lists/block_layer.tpl"}
{*popup_item*}
<div id="deleteRecordPanel" style="display: none;">
    <div class="prText2 prTCenter">{t}Do you really want to delete this item?{/t}</div>
    <input type="hidden" id="deleteRecordId" value="">
    <div class="prInnerTop prTCenter">
	{t var='button_01'}Delete{/t}
		{linkbutton name=$button_01 onclick="xajax_list_view_delete_record(YAHOO.util.Dom.get('deleteRecordId').value); return false;"}
		<span class="prIEVerticalAling">{t}or{/t}<a class="prIndentLeftSmall" href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
    </div>
</div>
{*popup_item*}
<script type="text/javascript" src="{$JS_URL}/lists.js"></script>
{literal}
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
