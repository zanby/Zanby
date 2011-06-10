{*popup_item*}
{foreach item=p name='videos' from=$videosList}
	 <div class="prFloatLeft prInnerSmallTop"><a href="{$CurrentGroup->getGroupPath('videogalleryView')}id/{$p->getId()}/"><img  height="80" width="80" src="{$p->getCover()->setWidth(50)->setHeight(50)->getImage($user)}" /></a></div>
{/foreach}
<div id="addVideoList{$tmbCurrentPage+1}"></div>
{*popup_item*}