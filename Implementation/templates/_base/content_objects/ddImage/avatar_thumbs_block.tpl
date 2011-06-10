{*popup_item*}
<div id="thumbs_area_ddImage">
	<div class="prCOCentrino">
	{$currentPage*$perPage+1}-{$currentPage*$perPage+$currentCount}&nbsp;{t}from{/t}&nbsp;{$total}
	 <label for="numberpics">{t}Show:{/t}</label>
	<select name="numberpics" onchange="xajax_update_thumbs_area('{$cloneId}', 0, this.value);return false;">
	{section start=10 loop=110 step=10 name=paging}
		<option {if $perpage == $smarty.section.paging.index || !$perpage}selected="selected"{/if} value="{$smarty.section.paging.index}">
			{$smarty.section.paging.index}
		</option>
	{/section}
	</select>
	</div>
    
	
	<div>
	{foreach from=$a_thumbs_hash item=current} 
		<div class="prFloatLeft prInnerSmall">
		     <a href="#"><img class="image_thumb" height="36" width="36"  src="{$current->setWidth(36)->setHeight(36)->getImage()}" onclick="xajax_ddImage_show_avatar_preview('{$current->setWidth(120)->setHeight(90)->getImage()}', '{$current->getTitle()}', '{$current->getId()}');return false;" /></a>
		</div>
	{/foreach} 
	<div class="prClearer"></div>
	</div>
	
	<div class="prTRight">
	{if $pagesCount > 1}
		{section loop=$pagesCount name=paging}
			{if $smarty.section.paging.iteration == $currentPage+1}
				{$smarty.section.paging.iteration}&nbsp;
			{else}
				<a href="#" onclick="xajax_update_thumbs_area('{$cloneId}', {$smarty.section.paging.iteration-1}, {$perPage});return false;">{$smarty.section.paging.iteration}</a>&nbsp;
			{/if}
		{/section}
		
		{if $pagesCount != $currentPage+1}
			&nbsp;<a href="#" onclick="xajax_update_thumbs_area('{$cloneId}', {$currentPage+1}, {$perPage});return false;">{t}Next{/t}</a>
		{/if}
	{/if}
	</div>
</div>
{*popup_item*}