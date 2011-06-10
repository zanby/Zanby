{*popup_item*}
<div class="prIndent">
	
    <div class="prCOCentrino prTCenter" id="video_preview_block_FVCB">
    	{include file="content_objects/ddFamilyVideoContentBlock/videoPreviewBlock.tpl"}
	</div>		
    
    <div id="a_gallery_thumbs" class="prFloatRight">{$a_thumbs_content}</div>
</div>
<div class="prInnerTop prCOCentrino">
<a class="prButton" href="#null" onClick="storeDDFamilyVideo('{$cloneId}',document.getElementById('a_image_preview_ddFamilyVideoContentBlock').name);popup_window.close(); return false;"><span>{t}Ok{/t}</span></a>
<span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t} <a href="#null" onClick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
</div>
{*popup_item*}