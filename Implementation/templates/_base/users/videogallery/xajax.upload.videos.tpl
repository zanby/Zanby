{*popup_item*}
<!-- user content -->
<p>{t}{tparam value=$gallery->getTitle()|escape:html}Add videos to %s{/t}</p>
<p class="prInnerSmallTop">
        {t}Find the video you want on your computer.{/t}
</p>

{form from=$uploadPhotosForm id="uploadPhotosForm" enctype="multipart/form-data"}
{form_hidden id="upload_type" name="upload_type" value="upload"}
<div class="prInnerSmallTop">
	<div id="swferror" class="prFormErrors" style="width:91%;display:none;">
	</div>
	<div id="error">
	</div>
</div>
<table class="prForm">
	<col width="12%" />
	<col width="88%" />
	<tr>
		<td></td>
		<td>
        {form_radio id="uploadversionSwitcher" name="versionSwitcher"  value="0" checked=$versionSwitcher onclick="turnOnUploadVersion();"}<label for="uploadversionSwitcher"> {t}Upload Videos From My Computer{/t}</label>                
        <div class="prIndentTopSmall">
        {form_radio id="URLversionSwitcher" name="versionSwitcher"  value="1" checked=$versionSwitcher onclick="turnOnURLVersion();"}<label for="URLversionSwitcher"> {t}Embed Videos From External URL{/t}</label>
		</div>
    </td>           
    </tr>
</table>
{form_hidden name="gallery_id" value=$gallery->getId()}
<div id="uploadVersion">
<div class="prInnerSmallTop" id="fields_table" _style="display:none;">
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
				<a id="more_avatars_link" href="#" onclick="show_more_advanced(20); return false;">{t}More fields{/t}</a>
			</td>
		</tr>
        <tr>
            <td></td>
            <td>
            <div class="prInnerSmallTop">
            <a class="prButton" href="#null" onclick="uploadandsubmit(function() {$smarty.ldelim}PGEApplication.showUploadPanelHandle({$gallery->getId()});{$smarty.rdelim});  return false;"><span>{t}Add Videos{/t}</span></a>
            <span class="prIndentLeftSmall">{t}or{/t} <a id="btnCancel1" href="#null" onclick="cancelupload(function (){$smarty.ldelim}cancelClick();{$smarty.rdelim}); return false;"><span>{t}Cancel{/t}</span></a></span>
            </div>
            </td>
        </tr>         
	</table>  
</div>
<div class="prInnerSmallTop" id="SWFUpload" style="display:none;">        
 	<table class="prForm">
		<col width="12%" />
		<col width="88%" />
		<tr>
			<td class="prTRight"><label for="files_box_height">{t}Upload Files:{/t}</label></td>    	 
			<td>
				<div id="flashUI1">
					<input type="hidden" id="files_box_height" name="files_box_height" value="98px">
					<div id="files_box" style="overflow:auto;">
						<fieldset class="flash" id="fsUploadProgress1">
							<legend style="display:none;"></legend>
						</fieldset>
					</div>
					<div class="prInnerSmall">
						<div class="prClr2 prInnerSmallTop">						
							<div class="prFloatLeft">
							{t var="button_01"}Choose Files{/t}
							{linkbutton id = "browse" name=$button_01 onclick="upload1.selectFiles(); return false;"}</div>						
							<div id="filesCount" class="prFloatLeft"><strong>0</strong> {t}Files{/t}</div>
							<div id="totalSize" class="prFloatLeft">{t}Total:{/t} <strong>0</strong> {t}Kb{/t}</div>                        
						</div>						
					</div>                     
				</div>
			</td>
		</tr>      
	</table>
				<div class="prInnerTop prTCenter">
			<a class="prButton" href="#null" onclick="uploadandsubmit(function() {$smarty.ldelim}PGEApplication.showUploadPanelHandle({$gallery->getId()});{$smarty.rdelim});  return false;"><span>{t}Add Videos{/t}</span></a>
			<span class="prIndentLeftSmall">{t}or{/t} <a id="bttnCancel1" href="#null" onclick="cancelupload(function (){$smarty.ldelim}cancelClick();{$smarty.rdelim}); return false;"><span>{t}Cancel{/t}</span></a></span>
			</div>
</div>
</div>
<div id="URLVersion" style="display:none;">
	<table class="prForm">
		<col width="30%" />
		<col width="70%" />
		<tr>
        <td>
            {form_radio id="source1" name="source"  value=$sourceEnum->translate('youtube') checked=$source|default:"1"}<label for="source1"> {t}YouTube.com{/t}</label>
			<div class="prIndentTopSmall">
            {form_radio id="source2" name="source"  value=$sourceEnum->translate('bliptv') checked=$source}<label for="source2"> {t}Blip.tv{/t}</label>
			</div>
        </td>
        <td>
            {form_textarea name="customSrc" id="customSrc" value=$customSrc|escape:"html"}
        </td>        
    </tr>
    <tr>
        <td>
            {t}Thumbnail URL{/t}
        </td>
        <td>
            {form_text id="customSrcImg" name="customSrcImg" value=$customSrcImg|escape:"html" size="60" maxlength="100"}
        </td>    
    </tr>    
</table>
	<div class="prInnerTop prTCenter">
			<a class="prButton" href="#null" onclick="PGEApplication.showUploadPanelHandle({$gallery->getId()}); return false;"><span>{t}Add Videos{/t}</span></a>
			<span class="prIndentLeftSmall">{t}or{/t} <a id="btnCancel1" href="#null" onclick="PGEApplication.hideUploadPanel();  return false;"><span>{t}Cancel{/t}</span></a></span>
	</div>
	
</div>
{/form}
<!-- popup -->
<!-- /popup -->
<!-- /user content -->
{*popup_item*}