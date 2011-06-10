{*popup_item*}
<div class="prClr3">
	
    <div class="prFloatLeft" id="video_preview_block_MVCB">
    	{include file="content_objects/ddMyVideoContentBlock/videoPreviewBlock.tpl"}
	</div>		
    
    <div id="a_gallery_thumbs" class="prFloatRight">{$a_thumbs_content}</div>
</div>
<div class="prTCenter">
<a class="prButton" href="#null" onClick="storeDDMyVideo('{$cloneId}',document.getElementById('a_image_preview_ddMyVideoContentBlock').name);popup_window.close(); return false;"><span>{t}Ok{/t}</span></a>
<span class="prIndentLeft"><a class="prButton" href="#null" onClick="popup_window.close(); return false;"><span>{t}Cancel{/t}</span></a></span>
</div>
{*popup_item*}