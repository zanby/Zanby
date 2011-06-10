{*popup_item*}
<div id="thumbs_area">
	<div class="prTRight prIndentTopSmall prIndentBottom">
		{t}{tparam value=$rangeStart}{tparam value=$rangeEnd}{tparam value=$total}%s - %s&nbsp;from&nbsp;%s{/t}
		<label for="numberpages" class="prInnerSmallLeft">Show:</label>
		<select name="numberpages" onchange="xajax_updateAttachPhoto(1, this.value);return false;">
			{section start=10 loop=110 step=10 name=paging}
				<option {if $perPage == $smarty.section.paging.index || !$perPage}selected="selected"{/if} value="{$smarty.section.paging.index}">
				{$smarty.section.paging.index}
				</option>
			{/section}
		</select>
	</div>
	
	<div class="prPopupScroll">
		{foreach from=$photos item=current} 
			<div class="prFloatLeft prInnerSmall">
				<a href="#">
				<img class="image_thumb" height="36" width="36"  
					src="{$current->setWidth(36)->setHeight(36)->getImage()}" 
					onclick="xajax_chooseAttachPhoto('{$current->setWidth(120)->setHeight(90)->getImage()}', '{$current->getTitle()}', '{$current->getId()}');return false;">					
				</a>
			</div>
		{/foreach} 
		<div class="prClearer"></div> 		
	</div>
	
	<div class="prTRight prInnerSmallTop">
		{if $pagesCount > 1}
			{section loop=$pagesCount name=paging}
				{if $smarty.section.paging.iteration == $currentPage}
					{$smarty.section.paging.iteration}&nbsp;
				{else}
					<a href="#" onclick="xajax_updateAttachPhoto({$smarty.section.paging.iteration}, {$perPage});return false;">{$smarty.section.paging.iteration}</a>&nbsp;
				{/if}
			{/section}
			{if $pagesCount != $currentPage}
				&nbsp;<a href="#" onclick="xajax_updateAttachPhoto({$currentPage+1}, {$perPage});return false;">{t}Next{/t}</a>
			{/if}
		{/if}
	</div>
</div>
{*popup_item*}