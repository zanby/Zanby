{literal}
<script type="text/javascript" src="/js/SWFUpload/swfupload.js"></script>
<script type="text/javascript" src="/js/SWFUpload/handlers.js"></script>
<script type="text/javascript" src="/js/SWFUpload/swfobject.js"></script>
<script type="text/javascript" src="/js/SWFUpload/swfuploadcode.js"></script>
<script type="text/javascript" src="/js/SWFUpload/swfupload.graceful_degradation.js"></script> 
<script>
    function setSWFUploadParams()
    {
        setUploadURL("{/literal}{$currentGroup->getGroupPath('videogallerycreate/step/4')}{literal}");
        setPostParams({{/literal}"SWFUploadID" : '{$SWFUploadID}', "galleryId" : '{if $gallery}{$gallery->getId()}{else}0{/if}'{literal}});
        setFileTypes("{/literal}{$VIDEOS_EXT}{literal}", "{/literal}Video Files ({$VIDEOS_EXT}){literal}");        
        setFileSizeLimit("{/literal}{$VIDEOS_SIZE_LIMIT/1024}{literal}");
        setQueuedLimit(50);
    }

    function beforeupload()
    {
        if (swfversion == true) {
            xajax_uploadandsubmit("{/literal}{$gallery->getId()}{literal}", document.getElementById('gallery_title').value, upload1.getStats().files_queued);
        } else {
            document.uploadVideosForm.submit();   
        }
        
        return false;    
    }
    
    function beforecancel()
    {
     {/literal}
            location.href = '{$currentGroup->getGroupPath("videos")}';
     {literal}
        return false;
    }
      
    function turnOnUploadVersion()
    {
        document.getElementById('URLVersion').style.display = "none";
        document.getElementById('uploadVersion').style.display = "";
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
        document.getElementById('uploadVersion').style.display = "none";
        document.getElementById('URLVersion').style.display = "";    
    }    
          
    YAHOO.util.Event.onDOMReady(init);
</script>
{/literal}

<div class="prInner">

<a href="{$currentGroup->getGroupPath('videos')}">{t}Back to Video Collections{/t}</a>
	<h2 class="prInnerTop">{t}Upload Videos{/t}</h2>
	
	<script src="/js/upload_fields.js" type="text/javascript"></script>
	{form from=$form id="uploadVideosForm" enctype="multipart/form-data"}
	{form_hidden id="upload_type" name="upload_type" value="upload"}
	<div class="prInnerTop"> 
        <div id="swferror" style="display:none;">
        </div>
        <div id="error" style="display:block;">
        {form_errors_summary} 
        </div>
	</div>
	<div class="prInnerTop prInnerRight prInnerLeft">    
        <table class="prForm">
			<col width="20%" />
			<col width="80%" />
            <tr>
				<td class="prTRight"><label for"gallery_title">{t}Collection Title:{/t}</label></td>
				<td>
					{if !$new}
						<h3>{$gallery->getTitle()|escape:"html"}</h3>
						{form_hidden name="gallery_id" value=$gallery->getId()}
						{form_hidden id="gallery_title" name="gallery_title" value=$gallery->getTitle()|escape:"html"}
					{else}
						{form_text id="gallery_title" name="gallery_title" value=$galleryTitle|escape:"html" size="60" maxlength="100"}
						{form_hidden name="gallery_id" value=$gallery->getId()}
								
					{/if}		      
					<p class="prInnerTop">
						{t}Find the image(s) you want on your computer.{/t}
					</p>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					{form_radio id="uploadversionSwitcher" name="versionSwitcher"  value="0" checked=$versionSwitcher onclick="turnOnUploadVersion();"}<label for="uploadversionSwitcher">{t}Upload Videos From My Computer{/t}</label>                
                     <div class="prIndentTopSmall">
                     {form_radio id="URLversionSwitcher" name="versionSwitcher"  value="1" checked=$versionSwitcher onclick="turnOnURLVersion();"}<label for="URLversionSwitcher">{t}Embed Videos To External URL{/t}</label>
					 </div>
				</td>
			</tr>
		</table>
	</div>
	<div id="uploadVersion">
		<div id="fields_table" _style="display:none;" class="prInnerTop prInnerRight prInnerLeft">
        	<table class="prForm"> 
				<col width="20%" />
				<col width="80%" /> 
				{section name=files loop=5}
				<tr>
					<td class="prTRight">{$smarty.section.files.index_next}.</td>
					<td>{form_file name="img_"|cat:$smarty.section.files.index_next size="45"}</td>
				</tr>
				{/section}	
				{section name=files loop=20 start=5}
				<tr title="file_field" style="display:none;">
					<td class="prTRight">{$smarty.section.files.index_next}.</td>
					<td>{form_file name="img_"|cat:$smarty.section.files.index_next size="45"}</td>
				</tr>
				{/section}				    
				<tr>
					<td class="prTRight"></td>
					<td><a id="more_avatars_link" href="#null" OnClick="show_more_advanced(20); return false;">+ {t}More Fields{/t}</a></td>
				</tr>                                                                                
				<tr>
				<td></td>
				<td>
					<div class="prClr2">                    
                    	<div class="prFloatLeft prIndentTopSmall">
							{t var="in_button"}Upload Videos{/t}
							{linkbutton name=$in_button onclick="beforeupload(); return false;"}                        </div>                   
                    	<div class="prFloatLeft prIndentLeftSmall">{t}or{/t}&nbsp;<a id="btnCancel1" href="#" onclick="cancelupload(function (){literal}{beforecancel();}{/literal}); return false;">{t}Cancel{/t}</a> {t}and go back to galleries.{/t}
						</div>  
					</div>                        		      
				</td>
			</tr>
		</table>
	</div>
	
	<div class="prInnerTop prInnerRight prInnerLeft" id="SWFUpload" style="display:none;">
        <table class="prForm">
			<col width="20%" />
			<col width="80%" />
            <tr>
                <td class="prTRight"><label>{t}Upload Files:{/t}</label></td>
				<td>
					<div id="flashUI1">
						<input type="hidden" id="files_box_height" name="files_box_height" value="98px">
						<div id="files_box" style="overflow:auto;">
							<fieldset class="flash" id="fsUploadProgress1">
							<legend style="display:none;"></legend>
							</fieldset>
						</div>
						<div class="prInnerSmall prClr2">
							<div class="prFloatLeft prIndentBottom">{t var="in_button_2"}Choose Files{/t}{linkbutton id = "browse" name=$in_button_2 onclick="upload1.selectFiles(); return false;"}</div>                                        
							<div id="filesCount" class="prFloatLeft prIndentLeft"><strong>0</strong> {t}Files{/t}</div>
							<div id="totalSize" class="prFloatLeft">{t}Total:{/t} <strong>0</strong> {t}Kb{/t}</div>
						</div>                     
					</div>
					<div class="prClr2 prInnerTop"> 
						<div class="prFloatLeft prIndentBottom">
						{t var="in_button_3"}Upload Videos{/t}
						{linkbutton name=$in_button_3 onclick="beforeupload(); return false;"}
						</div>
						<div class="prFloatLeft prIndentLeftSmall">{t}or{/t}&nbsp;<a id="btnCancel1" href="#" onclick="cancelupload(function (){literal}{beforecancel();}{/literal}); return false;">{t}Cancel{/t}</a> {t}and go back to galleries.{/t}
						</div>
					</div>
				</td>
			</tr>
		</table>
	</div>
	</div>
	<div id="URLVersion" style="display:none;" class="prInnerTop prInnerRight prInnerLeft">
        <table class="prForm">
            <col width="20%" />
            <col width="80%" />      
            <tr>
                <td class="prFloatLeft">
                    {form_radio id="source1" name="source"  value=$sourceEnum->translate('youtube') checked=$source|default:"1"}<label for="source1"> {t}YouTube.com{/t}</label><br />
                    {form_radio id="source2" name="source"  value=$sourceEnum->translate('bliptv') checked=$source}<label for="source2"> {t}Blip.tv{/t}</label>
                
                </td>
                <td>
                    {form_textarea name="customSrc" id="customSrc" value=$customSrc|escape:"html" }
                </td>
            </tr>
            <tr>
                <td class="prFloatLeft">
                    {t}Thumbnail URL{/t}
                </td>
                <td>
                    {form_text id="customSrcImg" name="customSrcImg" value=$customSrcImg|escape:"html" size="60" maxlength="100"}
                </td>
            
            </tr>              
            <tr>
                  <td></td>
                  <td>                    
                     <div class="prClr2 prInnerTop">         
                           <div class="prFloatLeft"> 
						   {t var="in_button_4"}Upload Videos{/t}   
                        {linkbutton name=$in_button_4 onclick="document.uploadVideosForm.submit(); return false;"}                         
                        </div>
                        <div class="prFloatLeft prIndentLeftSmall">{t}or{/t}&nbsp;<a id="btnCancel1" href="#" onclick="cancelupload(function (){literal}{beforecancel();}{/literal}); return false;">{t}Cancel{/t}</a> {t}and go back to galleries.{/t}
                        </div>   
                    </div>                 
              </td>
            </tr>
        </table>
    </div>                                            
				
</div>
{/form}		
