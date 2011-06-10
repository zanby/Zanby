<div id="thumbs_area">

	<div class="prTRight prInnerSmallTop prIndentBottom">
	{t}{tparam value=$currentPage*$perPage+1}{tparam value=$currentPage*$perPage+$currentCount}{tparam value=$total}%s-%s&nbsp;from&nbsp;%s{/t}
	<label for="numberpics" class="prInnerLeft">{t}Show:{/t}</label>
	<select name="numberpics" onchange="xajax_update_thumbs_area(0, this.value);return false;"">
	{section start=10 loop=30 step=10 name=paging}
		<option {if $perPage == $smarty.section.paging.index || !$perPage}selected="selected"{/if} value="{$smarty.section.paging.index}">
			{$smarty.section.paging.index}
		</option>
	{/section}
	</select>
	</div>
    
	<div class="prClr2">
		{foreach from=$a_thumbs_hash item=current} 
			<div class="prFloatLeft">
			<a href="#"><img height="36" width="36"  src="{$current->setWidth(36)->setHeight(36)->getImage()}" onclick="xajax_ddImage_show_avatar_preview('{$current->setWidth(120)->setHeight(90)->getImage()}', '{$current->getTitle()}', '{$current->getId()}');return false;" /></a>
			</div>
		{/foreach} 		
	</div>
	
	
	<div class="prTRight prInnerSmallTop">
	{if $pagesCount > 1}
		{section loop=$pagesCount name=paging}
			{if $smarty.section.paging.iteration == $currentPage+1}
				{$smarty.section.paging.iteration}&nbsp;
			{else}
				<a href="#" onclick="xajax_update_thumbs_area({$smarty.section.paging.iteration-1}, {$perPage});return false;">{$smarty.section.paging.iteration}</a>&nbsp;
			{/if}
		{/section}
		
		{if $pagesCount != $currentPage+1}
			&nbsp;<a href="#" onclick="xajax_update_thumbs_area({$currentPage+1}, {$perPage});return false;">{t}Next{/t}</a>
		{/if}
	{/if}
	</div>
</div>
