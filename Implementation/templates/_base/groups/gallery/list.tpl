{form from=$form name="sortForm"}
		<label for="show">{t}Show:{/t} </label>
	{form_select name="show" options=$showList onchange="document.sortForm.submit();" selected=$currentshow}
		<label for="sort">{t}Sort by:{/t} </label>
	{form_select name="sort" options=$sortList onchange="document.sortForm.submit();" selected=$currentsort}
{/form}    
	<div class="prMediaContent">
    	<div class="prMediaContentLeft">
		{if $galleriesList}
				{$paging}
			 <!-- photo container -->
			 <div class="prIndentTop prClr">
				{foreach item=g name='gall' from=$galleriesList}
						{assign var='IsShared' value=$g->isShared($CurrentGroup)}

						{view_factory entity="photogallery" 
							object=$g 
							IsShared=$IsShared
							lastPhoto=$g->getPhotos()->getLastPhoto()
							currentOwner=$CurrentGroup
							AccessManager=$AccessManager 
							user=$user
							item_width=$item_width
							item_height=$item_height
						}
					{/foreach}
				</div>
				<!-- photo list end -->
				{$paging}
				
		{else}
			<div class="prNoItems">
				{t}No Photos{/t}
			</div>
		{/if}
		</div>
		
		<!-- right area begin -->
		<div class="prMediaContentRight">
			<h3>{t}Photo Tags:{/t}</h3>
			{if $tags}
			<ul>
				{foreach item=g name='tags' from=$tags}
					<li><a href="{$BASE_URL}/{$LOCALE}/search/photos/preset/new/keywords/{$g.name|escape:html}/">({$g.count}) {$g.name|escape:"html"}</a></li>
				{/foreach}
			</ul>
			{else}
				{t}No Tags{/t}
			{/if}
		</div>
		<!-- right area end -->
	</div>
	
	
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
<div id="deletePanel" title="{t}Delete Gallery{/t}" style="visibility:hidden; display:none;">
	<div class="hd">
		<div class='tl'></div>
			<span id="deletePanelTitle"></span>
		<div class='tr'></div>
	</div>
	<div class="bd" id="deletePanelContent">
		<p class="prText2 prTCenter">{t}Are you sure you want to delete this gallery?{/t}</p>
		 <div class="prInnerTop prTCenter">
		 	{t var="in_button"}Delete gallery{/t}
			{linkbutton name=$in_button link="#" onclick="PGLApplication.showDeletePanelHandle(); return false;"}
			<span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t} <a href="#" onclick="PGLApplication.hideDeletePanel(); return false;">{t}Cancel{/t}</a></span>
		</div>
	</div>
</div>
<div id="unsharePanel" title="{t}Unshare Gallery{/t}" style="visibility:hidden; display:none;">
	<div id="unsharePanelContent">
		<p class="prText2 prTCenter">{t}Are you sure you want to unshare this gallery?{/t}</p>
		<div class="prInnerTop prTCenter">
			{t var="in_button_2"}Unshare gallery{/t}
			{linkbutton name=$in_button_2 link="#" onclick="PGLApplication.showUnsharePanelHandle(); return false;"}
			<span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="PGLApplication.hideUnSharePanel(); return false;">{t}Cancel{/t}</a></span>
		</div>		
	</div>
</div>
<div id="stopWatchingPanel" style="visibility:hidden; display:none;">
	<div class="bd" id="stopWatchingPanelContent">
		<p>{t}Are you sure you want to stop watching this gallery?{/t}</p>
		<div class="prInnerTop prTCenter">
			{t var="in_button_3"}Stop watching{/t}
			{linkbutton name=$in_button_3 link="#" onclick="PGLApplication.showStopWatchingPanelHandle(); return false;"}
			<span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t} <a href="#" onclick="PGLApplication.hideStopWatchingPanel(); return false;">{t}Cancel{/t}</a></span>
		</div>		
	</div>
</div>
<!-- /OLD ************************************* -->