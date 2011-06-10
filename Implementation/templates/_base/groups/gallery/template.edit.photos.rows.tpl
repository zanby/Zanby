<div class="prClr">
	<h2 class="prFloatLeft">{t}Below are all the photos in:{/t} <span>{$gallery->getTitle()|escape:html}</span></h2>
	<div class="prFloatRight prIndentTop"> <a href="#null" onclick="xajax_editshowpage('{$page}','{$gallery->getId()}','all'); return false;">{t}Expand All{/t}</a> &nbsp;|&nbsp; <a href="#null" onclick="xajax_editshowpage('{$page}','{$gallery->getId()}','none'); return false;">{t}Collapse All{/t}</a></div>
</div>
<div class="prIndentBottom">
	{$infoPaging}
	{$paging}
</div>
{foreach item=p name='photos' from=$photoslist}
<div id="photoContent{$p->getId()}" class="prInnerSmallTop prClr3">			
	{if $expand == 'all'}{include file="groups/gallery/template.edit.photo.edit.tpl" photo=$p}    
	{else}{include file="groups/gallery/template.edit.photo.view.tpl" photo=$p}{/if}			
</div> 	
{foreachelse}
	{t}No Photos{/t}	
{/foreach}
<div class="prInnerSmallTop">
	{$infoPaging}
	{$paging}
</div>
{if $percent < 100}
	<div class="prInnerTop"><a href="#null" onclick="PGEApplication.showUploadPanel({$gallery->getId()}); return false;">+ {t}Add Photos to Gallery{/t}</a></div>
{/if}