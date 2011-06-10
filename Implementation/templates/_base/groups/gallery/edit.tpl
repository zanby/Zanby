{literal}
<script type="text/javascript" src="/js/SWFUpload/swfupload.js"></script>
<script type="text/javascript" src="/js/SWFUpload/handlers.js"></script>
<script type="text/javascript" src="/js/SWFUpload/swfuploadcode.js"></script>
<script type="text/javascript" src="/js/SWFUpload/swfupload.graceful_degradation.js"></script>
<script>
    function setSWFUploadParams() {
        setUploadURL("{/literal}{$currentGroup->getGroupPath('gallerycreate/step/4')}{literal}");
        setPostParams({{/literal}"SWFUploadID" : '{$SWFUploadID}', "galleryId" : '{if $gallery}{$gallery->getId()}{else}0{/if}'{literal}});
        setFileTypes("{/literal}{$IMAGES_EXT}{literal}", "{/literal}Images Files ({$IMAGES_EXT}){literal}");
        setFileSizeLimit("{/literal}{$IMAGES_SIZE_LIMIT/1024}{literal}");
        setQueuedLimit(50);
    }    
    function cancelClick() {
        if (swfversion == true) { PGEApplication.showUploadPanelHandle({/literal}{$gallery->getId()}{literal}); } 
        else { PGEApplication.hideUploadPanel(); }
        return false;
    }         
</script>
{/literal}
<a href="{$currentGroup->getGroupPath('photos')}">{t}Back to Photo Galleries{/t}</a>
<h2>{t}Edit Gallery{/t}</h2>
	{form from=$gEditForm id="galleryEditForm"}
	{form_errors_summary}
	{form_hidden name="faction" value="save"}
		<table class="prForm">
		<col width="30%" />
		<col width="70%" />
		<tr>
			<td class="prTRight"><label for="title">{t}Gallery Title:{/t}</label></td>
			<td>{form_text name="title" size="40" maxlength="100" value=$gallery->getTitle()|escape:html}</td>
		</tr>
		<tr>
			<td class="prTRight"><label>{t}Privacy:{/t}</label></td>
			<td>
			{form_radio name="isPrivate" id="isPrivate1" value="0" checked=$gallery->getPrivate()}<label for="isPrivate1"> {t}Public{/t}</label>
			{form_radio name="isPrivate" id="isPrivate2" value="1" checked=$gallery->getPrivate()}<label for="isPrivate2"> {t}Private{/t}</label>
			</td>
		</tr>
		{if $AccessManager->canDeleteGallery($gallery, $currentGroup, $user)}
		<tr>
			<td></td>
			<td>
			{form_checkbox name="remove" value="1"}<label for="remove"> {t}{tparam value=$SITE_NAME_AS_STRING}Remove Gallery From %s{/t}</label>
			</td>
		</tr>                    
		{/if}
		<tr>
			<td></td>
			<td>    {t var="in_button"}Save Changes{/t}     	      
					{linkbutton name=$in_button onclick = "PGEApplication.saveGallery(); return false;"}        	     	                     
			</td>
		</tr>
	 </table>
	{/form}      
	<div id="photosRows" class="prInnerTop">
		{include file="groups/gallery/template.edit.photos.rows.tpl" photoslist=$photoslist gallery=$gallery}
	</div>	 
<script type="text/javascript" src="/js/PhotoGalleryEditApplication.js" ></script>
<script src="/js/upload_fields.js" type="text/javascript"></script>
{literal}
<script type="text/javascript">
$(function(){ PGEApplication.init(); });
</script>
{/literal}
{if !$gallery->isShareHistoryExists()}
	{literal}
	<script type="text/javascript">	
	$(function(){ PGEApplication.disableSaherHistoryView(); });
	</script>
	{/literal}
{/if}
<div id="shareMenuTarget" style="visibility:hidden; display:none;"></div>
<div id="shareMenuPanel" style="visibility:hidden; display:none;">
    <div class="hd">
        <div class='tl'></div>
            <span id="shareMenuPanelTitle">{t}Message{/t}</span>
        <div class='tr'></div>
    </div>
    <div id="shareMenuPanelContent"></div>
</div>
<div id="previewPanel" title="{t}Preview{/t}" style="visibility:hidden; display:none;">
   
    <div id="previewPanelContent" style="text-align:center;">
		<img style="cursor:pointer;" onclick="PGEApplication.hidePreviewPanel(); return false;" src="" id="previewPanelImg" />
	</div>
	<div class="prInnerTop">
		{t var="in_button_2"}Close{/t}
		{linkbutton name=$in_button_2 onclick="PGEApplication.hidePreviewPanel(); return false;"}
    </div>
</div>
<div id="deletePhotoPanel" title="{t}Delete Photo{/t}" style="visibility:hidden; display:none;">
    <div id="deletePhotoPanelContent">
		<p class="prText2 prTCenter">{t}Are you sure you want to delete this photo?{/t}</p>
	  	<div class="prInnerTop prTCenter">
			{t var="in_button_3"}Delete photo{/t}
			{linkbutton name=$in_button_3 onclick="PGEApplication.showDeletePhotoPanelHandle(); return false;"}
			<span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="PGEApplication.hideDeletePhotoPanel(); return false;">{t}Cancel{/t}</a></span>			
		</div>
	</div>
</div>
<div id="uploadPanel" title="{t}Add photos to gallery{/t}" style="visibility:hidden; display:none;">
    <span id="uploadPanelTitle"></span>
    <div id="uploadPanelContent"></div>
</div>