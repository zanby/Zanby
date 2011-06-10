{form from=$form name="sortForm" class="prIndentTopSmall"}
<label for="show">{t}Show:{/t}</label>
{form_select name="show" options=$showList onchange="document.sortForm.submit();" selected=$currentshow}
<label class="prIndentLeftSmall" for="sort">{t}Sort by:{/t} </label>
{form_select name="sort" options=$sortList onchange="document.sortForm.submit();" selected=$currentsort}
{/form}

<!-- C O N T E N T -->
<div class="prMediaContent">
	<!-- left area begin -->
	<div class="prMediaContentLeft"> {if $galleriesList}
		{$paging}
		<!-- photo list begin -->
		<div class="prIndentTop prClr"> 
            {foreach item=g name='gall' from=$galleriesList}
                {assign var='IsShared' value=$g->isShared($CurrentGroup)}
                {view_factory entity='videogallery' object=$g lastVideo=$g->getVideos()->getLastVideo() currentOwner=$CurrentGroup AccessManager=$AccessManager galleryId=$g->getId() user=$user IsShared=$IsShared}
			{/foreach} 
		</div>
		<!-- photo list end -->
		{$paging}
		{else}
		<div>{t}No Videos{/t}</div>
		{/if} </div>
	<!-- right area begin -->
	<div class="prMediaContentRight">
		<h3>{t}Video Tags:{/t}</h3>
		{if $tags}
		<ul>
			{foreach item=g name='tags' from=$tags}
				<li class="prIndentTopSmall"> <a href="{$BASE_URL}/{$LOCALE}/search/videos/preset/new/keywords/{$g.name|escape:html}/">({$g.count}) {$g.name|escape:"html"}</a></li>
			{/foreach}
		</ul>
		{else}
		<div class="prInnerSmallTop"> {t}No Tags{/t} </div>
		{/if} </div>
	<!-- right area end -->
</div>
<script type="text/javascript" src="{$JS_URL}/PhotoGalleriesListApplication.js" ></script>
<script type="text/javascript">
    YAHOO.util.Event.onDOMReady(PGLApplication.init);
</script>
<div id="shareMenuTarget" style="visibility:hidden; display:none;"></div>
<div id="shareMenuPanel" style="visibility:hidden; display:none;">
	<div id="shareMenuPanelContent"></div>
</div>
<div id="deletePanel" title="{t}Delete Video{/t}" style="visibility:hidden; display:none;">
	<div id="deletePanelContent">
		<p class="prTCenter prText2">{t}Are you sure you want to delete this video?{/t}</p>
		<div class="prInnerTop prTCenter"> {t var="in_button"}Delete Video{/t}{linkbutton name=$in_button link="#" onclick="PGLApplication.showDeletePanelHandle(); return false;"} <span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span> <a  class="prIndentLeftSmall" href="#" onclick="PGLApplication.hideDeletePanel(); return false;">{t}Cancel{/t}</a></span> </div>
	</div>
</div>
<div id="unsharePanel" title="{t}Unshare Video{/t}" style="visibility:hidden; display:none;">
	<div id="unsharePanelContent">
		<p class="prText2 ptTCenter">{t}Are you sure you want to unshare this video?{/t}</p>
		<div class="prInnerTop prTCenter"> {t var="in_button_2"}Unshare Video{/t}{linkbutton name=$in_button_2 link="#" onclick="PGLApplication.showUnsharePanelHandle(); return false;"} <span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="PGLApplication.hideUnSharePanel(); return false;">{t}Cancel{/t}</a></span> </div>
	</div>
</div>
<div id="stopWatchingPanel" style="visibility:hidden; display:none;">
	<div id="stopWatchingPanelContent">
		<p>{t}Are you sure you want to stop watching this video?{/t}</p>
		<div class="prInnerTop prTCenter"> {t var="in_button_3"}Stop watching{/t}{linkbutton name=$in_button_3 link="#" onclick="PGLApplication.showStopWatchingPanelHandle(); return false;"}
		<span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t} <a href="#" onclick="PGLApplication.hideStopWatchingPanel(); return false;">{t}Cancel{/t}</a></span> </div>
	</div>
</div>
