{*popup_item*}
{form from=$form enctype="multipart/form-data" id="form0"}
<div id="swferror" style="display:none;">
</div>
<div id="error">
</div> 
{form_hidden id="upload_type" name="upload_type" value="upload"}
<div class="prInnerTop" id="fields_table"_style="display:none;">
<table class="prForm">
	<col width="12%" />
	<col width="88%" />
    <tr>
		<td colspan="2"><p class="prText2 prTCenter">{t}Find the images you want on your computer{/t}</p> </td>
  	</tr>
  	<tr>
  	<td></td>    
    <td>
		{form_file type="file" name="badge_image" id="custom_badge" size=40}
	</td>
  </tr>
</table>
</div>
{include file="groups/promotion/swfupload.tpl"}
<table class="prForm">
	<col width="12%" />
	<col width="88%" />   
	<tr>
		<td></td>
		<td>
		<div class="prInnerTop"> 
			{t var="in_button"}Upload Image{/t}
            {linkbutton name=$in_button onclick="uploadandsubmit(function()"|cat:$smarty.ldelim|cat:"uploadBadgeImage();"|cat:$smarty.rdelim|cat:"); return false;"}
			<span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t}&nbsp;<a id="btnCancel1" href="#" onclick="cancelupload(function() {$smarty.ldelim}{$smarty.rdelim}); if (swfversion == true) uploadBadgeImage(); else popup_window.close(); return false;">{t}Cancel{/t}</a> {t}and go back to badge gallery.{/t}</span>
		</div>         
		</td>
	</tr>
</table>
{/form}
{*popup_item*}