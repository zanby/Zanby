{literal}
<script type="text/javascript" src="/js/SWFUpload/swfupload.js"></script>
<script type="text/javascript" src="/js/SWFUpload/handlers.js"></script>
<script type="text/javascript" src="/js/SWFUpload/swfuploadcode.js"></script>
<script type="text/javascript" src="/js/SWFUpload/swfupload.graceful_degradation.js"></script>
<script>
    function setSWFUploadParams()
    {
        setUploadURL("{/literal}{$currentUser->getUserPath('gallerycreate/step/4')}{literal}");
        setPostParams({{/literal}"SWFUploadID" : '{$SWFUploadID}', "galleryId" : '{if $gallery}{$gallery->getId()}{else}0{/if}'{literal}});
        setFileTypes("{/literal}{$IMAGES_EXT}{literal}", "{/literal}Images Files ({$IMAGES_EXT}){literal}");
        setFileSizeLimit("{/literal}{$IMAGES_SIZE_LIMIT/1024}{literal}"); 
        setQueuedLimit(50);
    }

    function beforeupload()
    {
        if (swfversion == true) {
            xajax_uploadandsubmit("{/literal}{$gallery->getId()}{literal}", document.getElementById('gallery_title').value, upload1.getStats().files_queued);
        } else {
            document.uploadPhotosForm.submit();   
        }
        
        return false;    
    }
    
    function createEmptyGallery()
    {
        xajax_uploadandsubmit("{/literal}{$gallery->getId()}{literal}", document.getElementById('gallery_title').value, '-1');
        return false;   
    }
        
    function beforecancel()
    {
     {/literal}
            location.href = '{$currentUser->getUserPath("photos")}';
     {literal}
        return false;
    }    
      
    YAHOO.util.Event.onDOMReady(turnOnSWFUpload);
</script>
{/literal} <a href="{$currentUser->getUserPath('photos')}">{t}Back to Photo Galleries{/t}</a>
<h2 class="prInnerSmallTop">{t}Upload Photos{/t}</h2>
<script src="/js/upload_fields.js" type="text/javascript"></script>
{form from=$form id="uploadPhotosForm" name="uploadPhotosForm" enctype="multipart/form-data" onsubmit="return false;"}
	{form_hidden id="upload_type" name="upload_type" value="upload"}
	<div class="prInnerSmallTop">
	<div id="swferror" class="prFormErrors" style="display:none;"> </div>
	{form_errors_summary} </div>
	<div class="prInnerSmallTop">
	<table class="prForm">
			<col width="20%" />
			<col width="40%" />
			<col width="20%" />
			<tr>
			<td class="prTRight"><label for"gallery_title">{t}Gallery Title:{/t}</label></td>
			<td> {if !$new}
					<h3>{$gallery->getTitle()|escape:"html"}</h3>
					{form_hidden id="gallery_id" name="gallery_id" value=$gallery->getId()}
					{form_hidden id="gallery_title" name="gallery_title" value=$gallery->getTitle()|escape:"html"}
					{else}
					{form_text id="gallery_title" name="gallery_title" value=$galleryTitle|escape:"html" size="60" maxlength="100"}
					{form_hidden id="gallery_id" name="gallery_id" value=$gallery->getId()}
					{/if} </td>
			<td></td>
		</tr>
		</table>
</div>
	<div class="prIndentTop prText2 prTCenter">{t}Find the image(s) you want on your computer.{/t}</div>
	<div class="prInnerSmallTop " id="fields_table">
	<table class="prForm">
			<col width="20%" />
			<col width="40%" />
			<col width="20%" />
			{section name=files loop=5}
			<tr>
			<td class="prTRight">{$smarty.section.files.index_next}.</td>
			<td>{form_file name="img_"|cat:$smarty.section.files.index_next size="45"}</td>
			<td></td>
		</tr>
			{/section}	
			{section name=files loop=20 start=5}
			<tr title="file_field" style="display:none;">
			<td class="prTRight">{$smarty.section.files.index_next}.</td>
			<td>{form_file name="img_"|cat:$smarty.section.files.index_next size="45"}</td>
			<td></td>
		</tr>
			{/section}
			<tr>
			<td></td>
			<td><a id="more_avatars_link" href="#null" onclick="show_more_advanced(20); return false;">+ {t}More Fields{/t}</a></td>
			<td></td>
		</tr>
		<tr>
			<td>&#160;</td>
			<td>
			{t var='button_01'}Upload Photos{/t}
			{linkbutton name=$button_01 onclick="beforeupload(); return false;"}
					<span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t}&nbsp;<a id="btnCancel1" href="#" onclick="cancelupload(function (){literal}{beforecancel();}{/literal}); return false;">{t}Cancel{/t}</a> {t}and go back to galleries.{/t} </span></td>
			<td>&#160;</td>
		</tr>
		</table>
</div>
	<div class="prInnerSmallTop " id="SWFUpload" style="display:none;">
	<table class="prForm">
			<col width="25%" />
			<col width="49%" />
			<col width="25%" />
			<tr>
			<td class="prTRight prInnerTop"><label for="files_box_height">{t}Upload Files:{/t}</label></td>
			<td><div id="flashUI1">
					<input type="hidden" id="files_box_height" name="files_box_height">
					<div id="files_box" style="overflow:auto;" class="prRelative">
						<fieldset id="fsUploadProgress1">
						<legend style="display:none;"></legend>
						</fieldset>
					</div>
					<div class="prClr3 prIndentTopSmall">
						<div class="prFloatLeft prIndentBottomSmall">
						{t var='button_02'}Choose Files{/t}
						{linkbutton id = "browse" name=$button_02 onclick="upload1.selectFiles(); return false;"}</div>
						<div id="filesCount" class="prFloatLeft prIndentLeft"><strong>0</strong> {t}Files{/t}</div>
						<div id="totalSize" class="prFloatLeft prIndentLeft">{t}Total:{/t} <strong>0</strong> {t}Kb{/t}</div>
					</div>
				</div></td>
			<td>&#160;</td>
		</tr>
		<tr>
			<td>&#160;</td>
			<td>
			{t var='button_03'}Upload Photos{/t}
			{linkbutton name=$button_03 onclick="beforeupload(); return false;"}
					<span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t}&nbsp;<a id="btnCancel1" href="#" onclick="cancelupload(function (){literal}{beforecancel();}{/literal}); return false;">{t}Cancel{/t}</a> {t}and go back to galleries.{/t} </span></td>
			<td>&#160;</td>
		</tr>
		</table>
</div>
	{/form} 