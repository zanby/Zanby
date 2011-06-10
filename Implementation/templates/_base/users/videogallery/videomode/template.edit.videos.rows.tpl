	{assign var="p" value=$videoslist.0}
<div id="photoContent{$p->getId()}" class="prInnerSmallTop">
{if $p->getSource() != 'nonvideo'}
	{include file="users/videogallery/`$VIDEOMODEFOLDER`template.edit.video.edit.tpl" video=$p gallery=$gallery}
{else}
	{include file="users/videogallery/`$VIDEOMODEFOLDER`template.edit.video.editNV.tpl" video=$p gallery=$gallery}
{/if}
</div>
