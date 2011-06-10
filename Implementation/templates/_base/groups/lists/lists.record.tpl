<div class="prDropBoxInner prIndentBottom">
	<div class="prDropHeader">
		<h3>
			<span id="display_index_{$id}">{$record.display_index}</span>
			<a href="#"onclick="lock_content(); xajax_list_{$action}_expand({$id}); return false;">
				{if $record.title}{$record.title|escape|wordwrap:30:"\n":true}{else}&lt;{t}Empty title{/t}&gt;{/if}
			</a> {if $recordObj->getExtraTitleStr($listType, $record.data.item_fields)}<span>{$recordObj->getExtraTitleStr($listType, $record.data.item_fields)}</span>{/if}
			
		</h3>
		<div class="prHeaderTools">
			<a href="#" title="Delete" onclick="lock_content(); xajax_list_{$action}_delete_record({$id}); return false;">{t}Delete{/t}</a>
		</div>
	</div>	
</div>        