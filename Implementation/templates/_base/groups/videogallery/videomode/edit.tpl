{literal}
<script type="text/javascript" src="/js/tinymceBlog/tiny_mce.js"></script>
<script>
    //var TMheight = "300";
	var TMwidth = "520";
</script>
{/literal}
<a href="{$currentGroup->getGroupPath('videos')}">{t}Back to Videos{/t}</a>
    <h2 class="prInnerTop">{t}Edit Video{/t}</h2>      
      <div id="videosRows">
		{include file="groups/videogallery/`$VIDEOMODEFOLDER`template.edit.videos.rows.tpl" videoslist=$videoslist gallery=$gallery}
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
    <div id="shareMenuPanelContent"></div>
</div>
<div id="previewPanel" title="{t}Preview{/t}" style="visibility:hidden; display:none;">
    <div id="previewPanelContent">
		<img style="cursor:pointer;" onclick="PGEApplication.hidePreviewPanel(); return false;" src="" id="previewPanelImg" />
	</div>
	<div class="prInnerTop">
		{t var="in_button"}Close{/t}
		{linkbutton name=$in_button onclick="PGEApplication.hidePreviewPanel(); return false;"}
    </div>
</div>
<div id="deletePhotoPanel" title="{t}Delete Video{/t}" style="visibility:hidden; display:none;">
    <div id="deletePhotoPanelContent">
		<p class="prTCenter prText2">{t}Are you sure you want to delete this video?{/t}</p>
		<div class="prInnerTop prTCenter">
			{t var="in_button_2"}Delete Video{/t}
			{linkbutton name=$in_button_2 onclick="PGEApplication.showDeletePhotoPanelHandle(); return false;"}
			<span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="PGEApplication.hideDeletePhotoPanel(); return false;">{t}Cancel{/t}</a></span>		
		</div>
	</div>
</div>
<div id="uploadPanel" style="visibility:hidden; display:none;">
    <div class="hd">
        <div class='tl'></div>
            <span id="uploadPanelTitle">{t}Add videos to collection{/t}</span>
        <div class='tr'></div>
    </div>
    <div id="uploadPanelContent"></div>
</div>
