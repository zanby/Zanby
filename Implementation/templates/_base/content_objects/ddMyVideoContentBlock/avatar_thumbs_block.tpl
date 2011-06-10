<div id="thumbs_area">
	<div class="prTRight prIndentTop">
	{if $total}
        {$currentPage*$perPage+1}-{$currentPage*$perPage+$currentCount}&nbsp;{t}from{/t}&nbsp;{$total}
        <label for="numberpics">Show:</span>	
     	<select name="numberpics" onchange="xajax_update_thumbs_area_mv('{$cloneId}', 0, this.value);return false;">
        {section start=10 loop=110 step=10 name=paging}
            <option {if $perPage == $smarty.section.paging.index || !$perPage}selected="selected"{/if} value="{$smarty.section.paging.index}">
                {$smarty.section.paging.index}
            </option>
        {/section}
     </select>
	{/if}
	</div>
    
	<div class="prClr3">
	<div class="prScroll">	
		{foreach from=$a_thumbs_hash item=current} 
			<div class="prFloatLeft">
			<a href="#"><img height="36" width="36"  src="{$current->getCover()->setWidth(36)->setHeight(36)->getImage()}" onclick="xajax_ddMyVideoContentBlock_show_avatar_preview({$current->getId()});return false;" /></a>
			</div>
		{/foreach}         
        {if !$total}<span>{t}No Videos{/t}</span>{/if}		
	</div></div>	
	
	<div class="prTRight">
	{if $pagesCount > 1}
		{section loop=$pagesCount name=paging}
			{if $smarty.section.paging.iteration == $currentPage+1}
				{$smarty.section.paging.iteration}&nbsp;
			{else}
				<a href="#" onclick="xajax_update_thumbs_area_mv('{$cloneId}', {$smarty.section.paging.iteration-1}, {$perPage});return false;">{$smarty.section.paging.iteration}</a>&nbsp;
			{/if}
		{/section}
		
		{if $pagesCount != $currentPage+1}
			&nbsp;<a href="#" onclick="xajax_update_thumbs_area_mv('{$cloneId}', {$currentPage+1}, {$perPage});return false;">{t}Next{/t}</a>
		{/if}
	{/if}
	</div>

</div>