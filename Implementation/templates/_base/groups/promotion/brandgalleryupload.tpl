{*popup_item*}
{form from=$form enctype="multipart/form-data" id="form0" name="UploadForm"}
<div id="swferror" style="display:none;">
</div>
<div id="error">
</div>
{form_hidden id="upload_type" name="upload_type" value="upload"}
<div class="prInnerTop" id="fields_table"_style="display:none;">
	<table class="prForm">
		<col width="12%" />
		<col width="88%" />
  		<!--<tr>
			<td colspan="2">
  			<iframe name="ifr1" id="ifr1" width="100%" height="25" frameborder="0" scrolling="no"></iframe>
  			</td>
		</tr>-->
  		<tr>
   			<td colspan="2"><p>{t}Find the image you want on your computer{/t}</p></td>
  		</tr>
  		<tr>
			<td></td>
			<td>
			{form_file name="brand_image" id="brand_image" size=40}
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
            {linkbutton name=$in_button onclick="uploadandsubmit(function()"|cat:$smarty.ldelim|cat:"uploadBrandImage();"|cat:$smarty.rdelim|cat:"); return false;"}                         
        <span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t}&nbsp;<a id="btnCancel1" href="#" onclick="cancelupload(function() {$smarty.ldelim}{$smarty.rdelim}); if (swfversion == true) uploadBrandImage(); else popup_window.close(); return false;">{t}Cancel{/t}</a> {t}and go back to brand gallery.{/t}</span>
		</div>         
		</td>
	</tr>
</table>
{/form}
{*popup_item*}