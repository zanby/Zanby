{literal}
<script type="text/javascript" src="/js/SWFUpload/swfupload.js"></script>
<script type="text/javascript" src="/js/SWFUpload/handlers.js"></script>
<script type="text/javascript" src="/js/SWFUpload/swfuploadcode.js"></script>
<script type="text/javascript" src="/js/SWFUpload/swfupload.graceful_degradation.js"></script>
<script>
    function setSWFUploadParams()
    {        
        setUploadURL("{/literal}{$currentUser->getUserPath('videogallerycreate/step/4')}{literal}");
        setPostParams({{/literal}"SWFUploadID" : '{$SWFUploadID}', "galleryId" : '{if $gallery}{$gallery->getId()}{else}0{/if}'{literal}});        
        setFileTypes("{/literal}{$VIDEOS_EXT}{literal}", "{/literal}Video Files ({$VIDEOS_EXT}){literal}");
        setFileSizeLimit("{/literal}{$VIDEOS_SIZE_LIMIT/1024}{literal}"); 
        setQueuedLimit(1);        
    }

    function beforeupload()
    {
        if (swfversion == true && document.getElementById('URLVersion').style.display == "none") {
            xajax_uploadandsubmit("{/literal}{$gallery->getId()}{literal}", document.getElementById('gallery_title').value, upload1.getStats().files_queued);
        } else {
            document.uploadVideosForm.submit();   
        }
        
        return false;    
    }
    
    function beforecancel()
    {
     {/literal}
            location.href = '{$currentUser->getUserPath("videos")}';
     {literal}
        return false;
    }
    
    function turnOnUploadVersion()
    {
        document.getElementById('URLVersion').style.display = "none";
		if (swfversion === false){
			document.getElementById('uploadVersion').style.display = "";
		}else{
			showSWFUpload();
		}
    }
    
    function init()
    {
        if ({/literal}{$versionSwitcher|default:0}{literal} == 1) {
            turnOnURLVersion();        
        } else {
            turnOnUploadVersion();
        }
        turnOnSWFUpload();
    }    

    function turnOnURLVersion()
    {
		if (swfversion === false){
			document.getElementById('uploadVersion').style.display = "none";
		}else {
			hideSWFUpload();
		}
        document.getElementById('URLVersion').style.display = "";    
    }    
          
    YAHOO.util.Event.onDOMReady(init);    
    //YAHOO.util.Event.onDOMReady(checkFlash);
</script>
{/literal} <a href="{$currentUser->getUserPath('videos')}">{t}Back to Videos{/t}</a>
<h2 class="prInnerSmallTop">{t}Upload Video{/t}</h2>
<script src="/js/upload_fields.js" type="text/javascript"></script>
{form from=$form id="uploadVideosForm" name="uploadVideosForm" enctype="multipart/form-data"}
		{form_hidden id="upload_type" name="upload_type" value="upload"}
		<div class="prInnerSmallTop">
	<div id="swferror" class="prFormErrors" style="display:none;"> </div>
	<div id="error" style="display:block;"> {form_errors_summary} </div>
</div>
		<div class="prInnerSmallTop ">
	<table class="prForm">
				<col width="25%" />
				<col width="49%" />
				<col width="25%" />
				<tr>
			<td class="prTRight"></td>
			<td> {form_hidden id="gallery_id" name="gallery_id" value=$gallery->getId()}
						{form_hidden id="gallery_title" name="gallery_title" value=$gallery->getTitle()|escape:"html"}
						<p class="prInnerSmallTop"> </p></td>
						<td></td>
		</tr>
				<tr>
			<td></td>
			<td> {form_radio id="uploadversionSwitcher" name="versionSwitcher"  value="0" checked=$versionSwitcher
						onclick="
						turnOnUploadVersion();
						"
						}
						<label for="uploadversionSwitcher">{t} Upload Video{/t}</label>
						<div class="prIndentTopSmall"> {form_radio id="URLversionSwitcher" name="versionSwitcher"  value="1" checked=$versionSwitcher
					onclick="
					if (swfversion == true)
					upload1.cancelUpload();
					turnOnURLVersion();
					"
					}
					<label for="URLversionSwitcher"> {t}Embed Video{/t}</label>
				</div></td>
				<td></td>
		</tr>
			</table>
</div>
		<div id="uploadVersion">
	<div id="fields_table" _style="display:none;" class="prInnerSmallTop ">
				<table class="prForm">
			<col width="25%" />
			<col width="49%" />
			<col width="25%" />
			{section name=files loop=1}
			<tr>
						<td class="prTRight">{$smarty.section.files.index_next}.</td>
						<td>{form_file name="img_"|cat:$smarty.section.files.index_next size="45"}</td>
						<td></td>
					</tr>
			{/section}
		</table>
			</div>
	<div class="prInnerSmallTop" id="SWFUpload" style="display:none;">
				<table class="prForm">
			<col width="25%" />
			<col width="49%" />
			<col width="25%" />
			<tr>
						<td class="prTRight prInnerTop"><label>{t}Upload File:{/t}</label></td>
						<td><div id="flashUI1" class="prIndentBottom">
								<input type="hidden" id="files_box_height" name="files_box_height" value="30px">
								<div id="files_box"  style="overflow:auto; position:relative;">
								<fieldset class="flash" id="fsUploadProgress1">
									<legend style="display:none;"></legend>
									</fieldset>
							</div>
								<div class="prClr3">
								<div class="prFloatLeft">
								{t var='in_button'}Choose Files{/t}
								{linkbutton id = "browse" name=$in_button onclick="upload1.selectFiles(); return false;"}
								</div>
								<div id="filesCount" class="prFloatLeft prIndentLeft"><strong>0</strong> {t}Files{/t}</div>
								<div id="totalSize" class="prFloatLeft prIndentLeft">{t}Total:{/t} <strong>0</strong> {t}Kb{/t}</div>
							</div>
							</div></td>
						<td>&#160;</td>
					</tr>
		</table>
			</div>
</div>
		<div id="URLVersion" style="display:none;" class="prInnerSmallTop ">
	<table class="prForm">
				<col width="25%" />
				<col width="49%" />
				<col width="25%" />
				<tr>
			<td class="prFloatLeft"> {form_radio id="source1" name="source"  value=$sourceEnum->translate('youtube') checked=$source|default:"1"}
						<label for="source1"> {t}YouTube.com{/t}</label>
						<br />
						{form_radio id="source2" name="source"  value=$sourceEnum->translate('bliptv') checked=$source}
						<label for="source2"> {t}Blip.tv{/t}</label>
					</td>
			<td> {form_textarea name="customSrc" id="customSrc" value=$customSrc|escape:"html" } </td>
			<td></td>
		</tr>
				<tr>
			<td class="prFloatLeft"> {t}Thumbnail URL{/t} </td>
			<td> {form_text id="customSrcImg" name="customSrcImg" value=$customSrcImg|escape:"html" size="60" maxlength="100"} </td>
			<td></td>
			</table>
</div>
<div class="prTCenter">
{t var='in_button_01'}Upload Video{/t}
{linkbutton name=$in_button_01 onclick="beforeupload(); return false;"}
								<span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t}&nbsp;<a id="btnCancel1" href="#" onclick="cancelupload(function (){literal}{beforecancel();}{/literal}); return false;">{t}Cancel{/t}</a> {t}and go back to videos.{/t}  </span> </div>
		{/form} 