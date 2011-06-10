{form from=$form name="sortForm" class="prIndentTopSmall"}
	<label for="show">{t}Show:{/t}</label>
	{form_select name="show" options=$showList onchange="document.sortForm.submit();" selected=$currentshow}
	<label class="prIndentLeftSmall" for="sort">{t}Sort by:{/t} </label>
	{form_select name="sort" options=$sortList onchange="document.sortForm.submit();" selected=$currentsort}
{/form}

<!-- photo container -->
<div class="prMediaContent">
	<div class="prMediaContentLeft">
	{if $galleriesList}
		{$paging}
		<!--<ul>-->  
		<div class="prIndentTop prClr">
			{foreach item=g name='gall' from=$galleriesList}
				{view_factory entity='videogallery' object=$g IsShared=$g->isShared($currentUser) isWatched=$g->isWatched($currentUser) lastVideo=$g->getVideos()->getLastVideo() currentOwner=$currentUser AccessManager=$AccessManager user=$user} 
			{/foreach}
		</div>
		{$paging}
	{else}
		<div>{t}No Videos{/t}</div>			
	{/if}
	</div>	
	<div class="prMediaContentRight">
		<h3>{t}Video Tags:{/t}</h3>

		{if $tags}
			<ul>
			{foreach item=g name='tags' from=$tags}
				<li class="prInnerSmallTop"><a href="{$BASE_URL}/{$LOCALE}/search/videos/preset/new/keywords/{$g.name|escape:html}/">({$g.count}){$g.name|escape:"html"}</a></li>
			{/foreach}
				</ul>
		{else}
			<div class="prInnerSmallTop">
				{t}No Tags{/t}
			</div>
		{/if}

	</div>
</div> 
     <!-- / C O N T E N T -->
<!---BUUU--->



<script type="text/javascript" src="/js/PhotoGalleriesListApplication.js" ></script>
<script type="text/javascript">
    YAHOO.util.Event.onDOMReady(PGLApplication.init);
</script>
<div id="shareMenuTarget" style="visibility:hidden; display:none;"></div>
<div id="shareMenuPanel" style="visibility:hidden; display:none;">
    <div class="hd">
        <div class='tl'></div>
            <span id="shareMenuPanelTitle">{t}Message{/t}</span>
        <div class='tr'></div>
    </div>
    <div class="bd" id="shareMenuPanelContent"></div>
</div>
<div id="deletePanel" title="Delete Video" style="visibility:hidden; display:none;">
    <div id="deletePanelContent">
		<p class="prTCenter prText2">{t}Are you sure you want to delete this video?{/t}</p>
        <div class="prInnerTop prTCenter">
		{t var="in_button_01"}Delete Video{/t}
		{linkbutton name=$in_button_01 link="javascript:void(0)" onclick="PGLApplication.showDeletePanelHandle(); return false;"}
		<span class="prIEVerticalAling prIndentLeftSmall"> {t}or{/t} <a href="javascript:void(0)" onclick="PGLApplication.hideDeletePanel(); return false;">{t}Cancel{/t}</a></span>
		</div>
	</div>       
</div>
<div id="unsharePanel" title="{t}Unshare Video{/t}" style="visibility:hidden; display:none;">
    <div id="unsharePanelContent">
        <p class="prText2 prTCenter">{t}Are you sure you want to unshare this video?{/t}</p>
         <div class="prInnerTop prTCenter">
		 {t var="in_button_02"}Unshare Video{/t}
		{linkbutton name=$in_button_02 link="javascript:void(0)" onclick="PGLApplication.showUnsharePanelHandle(); return false;"}
		<span class="prIEVerticalAling"><span class="prIndentLeftSmall">or</span><a class="prIndentLeftSmall" href="javascript:void(0)" onclick="PGLApplication.hideUnSharePanel(); return false;">Cancel</a></span>
		</div>        
    </div>
</div>
<div id="stopWatchingPanel" style="visibility:hidden; display:none;">
    <div class="hd">
        <div class='tl'></div>
            <span id="stopWatchingPanelTitle">{t}Stop Watching Video{/t}</span>
        <div class='tr'></div>
    </div>
    <div class="bd" id="stopWatchingPanelContent">
        <p>{t}Are you sure you want to stop watching this video?{/t}</p>
         <div class="prInnerTop prTCenter">
		 {t var="in_button_03"}Stop watching{/t}
		{linkbutton name=$in_button_03 link="javascript:void(0)" onclick="PGLApplication.showStopWatchingPanelHandle(); return false;"}
		<span class="prIEVerticalAling prIndentLeftSmall"> or <a href="javascript:void(0)" onclick="PGLApplication.hideStopWatchingPanel();return false;">Cancel</a></span>
		</div>        
    </div>    
</div>
