{*popup_item*}
<div class="prInnerTop">
	
	<div class="prCOCentrino">
    	<img width="120" height="90" id="a_image_preview_ddImage" src="{$currentImage->setWidth(120)->setHeight(90)->getImage()}" name="{$currentImage->getId()}"/>
	</div>
    
    <div id="a_gallery_thumbs_ddImage">
   	    {include file = "content_objects/ddImage/avatar_thumbs_block.tpl"}
    </div>
    
</div>
<div class="prInnerTop prCOCentrino">
	<a class="prButton" href="#null" onclick="storeDDImageAvatar('{$cloneId}',document.getElementById('a_image_preview_ddImage').name);popup_window.close(); return false;"><span>{t}OK{/t}</span></a>	
	 <span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
</div>
{*popup_item*}