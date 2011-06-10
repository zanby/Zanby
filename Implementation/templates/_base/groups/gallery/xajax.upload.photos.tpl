{*popup_item*}
<!-- user content -->
<p class="prTCenter prText2">
	{t}Find the image(s) you want on your computer.{/t}
</p>

{form from=$uploadPhotosForm id="uploadPhotosForm" enctype="multipart/form-data"}
{form_hidden id="upload_type" name="upload_type" value="upload"}
<div class="prInnerTop">
	<div id="swferror" style="display:none;">
	</div>
	<div id="error">
	</div>
</div>
{form_hidden name="gallery_id" value=$gallery->getId()}
<div class="prInnerTop" id="fields_table" _style="display:none;">
	<table class="prForm">
		<col width="12%" />
		<col width="88%" />
		{section name=files loop=5}
		<tr>
			<td class="prTRight">{$smarty.section.files.index_next}</td>
			<td>{form_file name="img_"|cat:$smarty.section.files.index_next size="45"}</td>
		</tr>
		{/section}
		{section name=files loop=20 start=5}
		<tr title="file_field" style="display:none;">
			<td class="prTRight">{$smarty.section.files.index_next}</td>
			<td>{form_file name="img_"|cat:$smarty.section.files.index_next size="45"}</td>
		</tr>
		{/section}
		<tr>
			<td></td>
			<td>
				<a id="more_avatars_link" href="#" OnClick="show_more_advanced(20); return false;">{t}More fields{/t}</a>
			</td>
		</tr>
	</table>
</div>
<div class="prInnerTop" id="SWFUpload" style="display:none;"> 
	<table class="prForm">
		<col width="12%" />
		<col width="88%" />
		<tr>
			<td class="prTRight"><label for="files_box_height">{t}Upload Files:{/t}</label></td>    	
			<td>
				<div id="flashUI1">
					<input type="hidden" id="files_box_height" name="files_box_height" value="98px">
					<div id="files_box" style="overflow:auto; position: relative;">
						<fieldset class="flash" id="fsUploadProgress1">
							<legend style="display:none;"></legend>
						</fieldset>
					</div>
					<div class="prClr2 prInnerSmall">						
						<div class="prFloatLeft prIndentRight">{t var="in_button"}Choose Files{/t}{linkbutton id = "browse" name=$in_button onclick="upload1.selectFiles(); return false;"}</div>						
						<div id="filesCount" class="prFloatLeft"><strong>0</strong> {t}Files{/t}</div>
						<div id="totalSize" class="prFloatLeft">{t}Total:{/t} <strong>0</strong> {t}Kb{/t}</div>
					</div>                     
				</div>
			</td>
		</tr>
		      
	</table>
</div> 
{/form}
<div class="prInnerTop prTCenter">
	<a class="prButton" href="#null" onClick="uploadandsubmit(function() {$smarty.ldelim}PGEApplication.showUploadPanelHandle({$gallery->getId()});{$smarty.rdelim});  return false;"><span>{t}Add photos{/t}</span></a>
	<span class="prIndentLeftSmall">{t}or{/t} <a id="btnCancel1" href="#null" onClick="cancelupload(function (){$smarty.ldelim}cancelClick();{$smarty.rdelim}); return false;"><span>{t}Cancel{/t}</span></a></span>
</div>

<!-- /user content -->
{*popup_item*}