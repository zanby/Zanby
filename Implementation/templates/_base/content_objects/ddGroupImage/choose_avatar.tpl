{*popup_item*}
<div class="bd">
	<table width=100% border=0 cellpadding="2" cellspacing="2">
	  <tr>
		<td align="center" valign="top">
			<img width="120" height="90" id="a_image_preview" src="{$currentImage->setWidth(120)->setHeight(90)->getImage()}" name="{$a_preview_nid}"/> </td>
		<td rowspan="3" valign=top>
			<div id="a_gallery_thumbs">{$a_thumbs_content}</div>
		</td>
	  </tr>
	  <tr></tr>
	</table>
</div>
<div class="prInnerTop prTCenter">
	<a class="prButton" href="#null" onClick="storeDDImageAvatar('{$cloneId}',document.getElementById('a_image_preview').name);popup_window.close(); return false;"><span>{t}OK{/t}</span></a>	
	<span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
</div>
{*popup_item*}