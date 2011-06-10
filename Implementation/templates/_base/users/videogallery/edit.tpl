{*popup_item*}
{literal}
<script type="text/javascript" src="/js/SWFUpload/swfupload.js"></script>
<script type="text/javascript" src="/js/SWFUpload/handlers.js"></script>
<!--<script type="text/javascript" src="/js/SWFUpload/swfobject.js"></script>-->
<script type="text/javascript" src="/js/SWFUpload/swfuploadcode.js"></script>
<script type="text/javascript" src="/js/SWFUpload/swfupload.graceful_degradation.js"></script>
<script type="text/javascript" src="/js/tinymceBlog/tiny_mce.js"></script>
<script type="text/javascript" src="/js/tinymceStory/settings.js"></script>  
<script>
    function AddTMControlsForAllDescriptions()
    {
        for (i = 0; i < document.forms.length; i++) {
            form_id = document.forms[i].id;
            if (form_id.indexOf('editPhotoForm', 0) == 0) {
                videoId = form_id.replace(/editPhotoForm/, '');
                tinyMCE.execCommand('mceAddControl', true, 'videoDescription' + videoId);
            }
        }    
    }
    
    function RemoveTMControlsFromAllDescriptions()
    {
        for (i = 0; i < document.forms.length; i++) {
            form_id = document.forms[i].id;
            if (form_id.indexOf('editPhotoForm', 0) == 0) {
                videoId = form_id.replace(/editPhotoForm/, '');
                tinyMCE.execCommand('mceRemoveControl', true, 'videoDescription' + videoId);
            }
        }        
    }
       
    function setSWFUploadParams()
    {
        setUploadURL("{/literal}{$currentUser->getUserPath('videogallerycreate/step/4')}{literal}");
        setPostParams({{/literal}"SWFUploadID" : '{$SWFUploadID}', "galleryId" : '{if $gallery}{$gallery->getId()}{else}0{/if}'{literal}});
        setFileTypes("{/literal}{$VIDEOS_EXT}{literal}", "{/literal}Video Files ({$VIDEOS_EXT}){literal}");
        setFileSizeLimit("{/literal}{$VIDEOS_SIZE_LIMIT/1024}{literal}");
        setQueuedLimit(1);
    }
    
    function cancelClick()
    {
        if (swfversion == true)
        {
            PGEApplication.showUploadPanelHandle({/literal}{$gallery->getId()}{literal});
        } else {
            PGEApplication.hideUploadPanel();
        }
        return false;
    }
    
    function turnOnUploadVersion()
    {        
        emptyErrors();
        document.getElementById("error").innerHTML="";
        document.getElementById('URLVersion').style.display = "none";
        document.getElementById('uploadVersion').style.display = "";
    }    

    function turnOnURLVersion()
    {
        emptyErrors();
        document.getElementById("error").innerHTML="";  
        document.getElementById('uploadVersion').style.display = "none";
        document.getElementById('URLVersion').style.display = "";    
    }
    
    function sendStatusRequest()
    {
        var callback = {
            success: receiveStatusResponse
        }
        action = "{/literal}{$currentUser->getUserPath('videogallerytrackstatus')}gallery/{$gallery->getId()}/{literal}";        
        var cObj = YAHOO.util.Connect.asyncRequest('GET', action, callback);    
    }
    
    function receiveStatusResponse(oResponse)
    {        
        xajax.processResponse(oResponse.responseXML);
        timeout = setTimeout(sendStatusRequest, 4000);        
    }
    
    //YAHOO.util.Event.onDOMReady(sendStatusRequest);

</script>
{/literal}
    
<a href="{$currentUser->getUserPath('videos')}">{t}Back to Video Collections{/t}</a>

<div class="prInner">
	 <h2>{t}Edit Collection{/t}</h2>
		  
	{form from=$gEditForm id="galleryEditForm"}
	{form_errors_summary}
	{form_hidden name="faction" value="save"}
	<table class="prForm">
		<col width="30%" />
		<col width="70%" />
		<tr>
			<td class="prTRight"><label for="title">{t}Collection Title:{/t}</label></td>
			<td>{form_text name="title" maxlength="100" value=$gallery->getTitle()|escape:html}</td>
		</tr>
		<tr>
			<td class="prTRight"><label>{t}Privacy:{/t}</label></td>
			<td>{form_radio name="isPrivate" id="isPrivate1" value="0" checked=$gallery->getPrivate()}<label for="isPrivate1"> {t}Public{/t}</label>
			<div class="prIndentTopSmall">
			{form_radio name="isPrivate" id="isPrivate2" value="1" checked=$gallery->getPrivate()}<label for="isPrivate2"> {t}Private{/t}</label>
			</div>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
			{form_checkbox name="remove" value="1"}<label for="remove"> {t}{tparam value=$SITE_NAME_AS_STRING}Remove Collection From %s{/t}</label>
		  </td>
		</tr>                    
		<tr>
			<td></td>
			<td>
			{t var="in_button_01"}Save Changes{/t}                          
					{linkbutton name=$in_button_01 onclick = "RemoveTMControlsFromAllDescriptions(); PGEApplication.saveGallery(); return false;"}                                             
			</td>
		</tr>
	 </table>
	{/form}
  
	<div id="videosRows" class="prInnerSmallTop">
	{include file="users/videogallery/template.edit.videos.rows.tpl" videoslist=$videoslist gallery=$gallery}
	</div>
</div>

<script type="text/javascript" src="/js/PhotoGalleryEditApplication.js" ></script>
<script src="/js/upload_fields.js" type="text/javascript"></script>
{literal}
	<script type="text/javascript">
		$(function(){ PGEApplication.init(); });
	</script>
{/literal}
<div id="shareMenuTarget" style="visibility:hidden; display:none;"></div>
<div id="shareMenuPanel" style="visibility:hidden; display:none;">
    <div class="hd">
        <div class='tl'></div>
            <span id="shareMenuPanelTitle">{t}Message{/t}</span>
        <div class='tr'></div>
    </div>
    <div class="bd" id="shareMenuPanelContent"></div>
</div>
<div id="previewPanel" title="{t}Preview{/t}" style="visibility:hidden; display:none;">
   <div class="bd" id="previewPanelContent" style="text-align:center;">
        <img style="cursor:pointer;" onclick="PGEApplication.hidePreviewPanel(); return false;" src="" id="previewPanelImg" />
    </div>
    <div style="height: 8px;" class="clear">&nbsp;</div>
    <div class="co-button" onclick="PGEApplication.hidePreviewPanel(); return false;" onmouseover="this.className = 'co-button co-btn-active'" onmouseout="this.className = 'co-button'" style="margin-left: 15px; display: inline; float: left;"><a href="#null">{t}Close{/t}</a></div>
</div>
<div id="deletePhotoPanel" style="visibility:hidden; display:none;">
    <div class="hd">
        <div class='tl'></div>
            <span id="deletePhotoPanelTitle">{t}Delete Video{/t}</span>
        <div class='tr'></div>
    </div>
    <div class="bd" id="deletePhotoPanelContent">
        <p>{t}Are you sure you want to delete this video?{/t}</p>
          <div class="prInnerSmallTop prTCenter">
		  {t var="in_button_02"}Delete Video{/t}
			{linkbutton name=$n_button_02 onclick="PGEApplication.showDeletePhotoPanelHandle(); return false;"}
			<span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t} <a href="#" onclick="PGEApplication.hideDeletePhotoPanel(); return false;">{t}Cancel{/t}</a></span>			
		</div>
	</div>
</div>
<div id="uploadPanel" style="visibility:hidden; display:none;">
    <div class="hd">
        <div class='tl'></div>
            <span id="uploadPanelTitle">{t}Add videos to collection{/t}</span>
        <div class='tr'></div>
    </div>
    <div class="bd" id="uploadPanelContent"></div>
</div>
{*popup_item*}