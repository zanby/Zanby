<div class="prTCenter">
	{if $tmbCurrentPage != 1}
		<a href="#null" onclick="xajax_show_tmb_page({$tmbCurrentPage}-1, {$gallery->getId()})">&laquo;</a>
	{/if}
	<span>
		{if $tmpCountVideos > $tmbCurrentPage*$tmbOnPage}
			{$tmbCurrentPage*$tmbOnPage-$tmbOnPage+1} - {t}{tparam value=$tmbCurrentPage*$tmbOnPage}{tparam value=$tmpCountVideos}%s of %s{/t}
		{else}
			{$tmbCurrentPage*$tmbOnPage-$tmbOnPage+1} - {t}{tparam value=$tmpCountVideos}{tparam value=$tmpCountVideos}%s of %s{/t}                   
		{/if}    
	</span>
	{if $tmbCurrentPage < $tmbCountPage}
		 <a href="#" onclick="xajax_show_tmb_page({$tmbCurrentPage}+1, {$gallery->getId()})">&raquo;</a>
	{/if}
</div>
<div class="prInnerSmallTop prClr2">
	<!-- -->
	{foreach item=p name='videos' from=$videosList}
		<div class="prFloatLeft"><a href="{$currentUser->getUserPath('videogalleryView')}id/{$p->getId()}/page/{$tmbCurrentPage}/"><img  height="50" width="50" src="{$p->getCover()->setWidth(50)->setHeight(50)->getImage($user)}" /></a></div>
	{/foreach}
	<!-- -->
</div>