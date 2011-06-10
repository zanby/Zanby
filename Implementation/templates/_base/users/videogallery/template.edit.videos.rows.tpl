<div class="prClr2">
	<h3 class="prFloatLeft"> {t}Below are all the videos in:{/t} <span>{$gallery->getTitle()|escape:html}</span>
		<!--<span><a href="#null" onclick="PGEApplication.showShareMenu(this, '{$gallery->getId()}'); return false;">Share</a></span>     -->
	</h3>
	<div class="prFloatRight"> <a href="#null" onclick="RemoveTMControlsFromAllDescriptions(); xajax_editshowpage('{$page}','{$gallery->getId()}','all'); return false;">{t}Expand All{/t}</a> &nbsp;|&nbsp; <a href="#null" onclick="RemoveTMControlsFromAllDescriptions();xajax_editshowpage('{$page}','{$gallery->getId()}','none'); return false;">{t}Collapse All{/t}</a> </div>
</div>
<div class="prInnerSmallTop">
	<div class="prIndentBottom"> {$infoPaging} 
		{$paging} </div>
	{foreach item=p name='videos' from=$videoslist}
	<div id="photoContent{$p->getId()}" class="prInnerSmallTop"> {if $expand == 'all'}    
		{include file="users/videogallery/template.edit.video.edit.tpl" video=$p}    
		{else}    
		{include file="users/videogallery/template.edit.video.view.tpl" video=$p}
		{/if} </div>
	{foreachelse}
	No Videos
	{/foreach}
	<div class="prInnerSmallTop"> {$infoPaging}	
		{$paging} </div>
</div>
<h4 class="prInnerTop"><a href="#null" onclick="PGEApplication.showUploadPanel({$gallery->getId()}); return false;">+ {t}Add Videos to Collection{/t}</a></h4>