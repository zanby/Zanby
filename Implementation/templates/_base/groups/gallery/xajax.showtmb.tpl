{*popup_item*}
<!-- photos -->
<div id="tmbPanel">
	<div class="prTCenter">
	{if $tmbCurrentPage != 1}
		<a href="#null" onclick="xajax_show_tmb_page({$tmbCurrentPage-1}, {$gallery->getId()})">&laquo;</a>
	{/if}
		<span class="prInnerLeft prInnerRight">
		{if $tmpCountPhotos > $tmbCurrentPage*$tmbOnPage}
			{$tmbCurrentPage*$tmbOnPage-$tmbOnPage+1} - {t}{tparam value=$tmbCurrentPage*$tmbOnPage}{tparam value=$tmpCountPhotos}%s of %s{/t}
		{else}
			{$tmbCurrentPage*$tmbOnPage-$tmbOnPage+1} - {t}{tparam value=$tmpCountPhotos}{tparam value=$tmpCountPhotos}%s of %s{/t}                   
		{/if}                    
		</span>
	{if $tmbCurrentPage < $tmbCountPage}
		<a href="#" onclick="xajax_show_tmb_page({$tmbCurrentPage}+1, {$gallery->getId()})">&raquo;</a>
	{/if}
	</div>
	<div class="prIndentTopSmall prClr2">
		<!-- -->
		{foreach item=p name='photos' from=$photosList}
			<div class="prFloatLeft prInnerSmallTop"><a href="{$CurrentGroup->getGroupPath('galleryView')}id/{$p->getId()}/page/{$tmbCurrentPage}/"><img src="{$p->setWidth(50)->setHeight(50)->getImage($user)}" /></a></div>
		{/foreach}
		<!-- -->
	</div>
</div>
<!-- /photos -->
{*popup_item*}