{*popup_item*}
{assign var="p" value=$videoslist.0}
<div id="photoContent{$p->getId()}"> {if $p->getSource() != 'nonvideo'}
	{include file="groups/videogallery/`$VIDEOMODEFOLDER`template.edit.video.edit.tpl" video=$p}
	{else}
	{include file="groups/videogallery/`$VIDEOMODEFOLDER`template.edit.video.editNV.tpl" video=$p}
	{/if}</div>
{*popup_item*}