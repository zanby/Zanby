<a href="{$CurrentGroup->getGroupPath('lists')}">{t}Back to Lists{/t}</a>
	<div class="prListContent prClr3">
	<div class="prListContentLeft prClr3">
			{include file="groups/lists/lists.view.base.special.tpl"}
		</div>
        <div class="prListContentRight prClr3">
			{include file="groups/lists/lists.view.left.column.tpl"}
		</div>
	</div>
	<a name="add_form"></a>
	<div class="prInnerTop">
		<div id="new_record"></div>
	</div>        

<!-- tabs2 area end -->
{include file="groups/lists/block_layer.tpl"}
<script type="text/javascript" src="{$JS_URL}/lists.js"></script>
<script type="text/javascript">
xajax_list_view_add_form({$list->getId()});
</script>
<div id="deleteRecordPanel" style="display: none;">
	<p class="prText2 prTCenter">{t}Do you really want to delete this item?{/t}</p>
	<input type="hidden" id="deleteRecordId" value="">
	<div class="prInnerTop prTCenter">
			{t var="in_button_2"}Delete{/t}
			{linkbutton name=$in_button_2 onclick="xajax_list_view_delete_record(YAHOO.util.Dom.get('deleteRecordId').value); return false;"}
			<span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
	</div>
</div>