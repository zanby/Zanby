{form from=$form name="sortForm"}
		<label for="show">{t}Show: {/t}</label>
	{form_select name="show" options=$showList onchange="document.sortForm.submit();" selected=$currentshow}
		<label for="sort">{t}Sort by:{/t} </label>
	{form_select name="sort" options=$sortList onchange="document.sortForm.submit();" selected=$currentsort}
{/form}   	

{if $AccessManager->canCreateGallery($currentUser, $user)}
	{assign var="addLink" value=$currentUser->getUserPath('gallerycreate/step/1')}
{/if}
    <div class="prMediaContent">
    	<div class="prMediaContentLeft">
		{if $galleriesList}
            {$paging}
            <!-- photo container -->
			<div class="prIndentTop prClr">
                {foreach item=g name='gall' from=$galleriesList}
					{view_factory entity="photogallery" 
						object=$g 
						IsShared=$g->isShared($currentUser)
						IsWatched=$g->isWatched($currentUser)
						lastPhoto=$g->getPhotos()->getLastPhoto()
						currentOwner=$currentUser 
						AccessManager=$AccessManager 
						user=$user
						item_width=$item_width
						item_height=$item_height
					}
				{/foreach}
			</div>
            <!-- /photo container -->
            {$paging}
	  {else}
	      <div class="prNoItems">{t}No Photos{/t}</div>
	  {/if}
		</div>      
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
	</div> 
      <!-- / C O N T E N T -->
{*popup_item*}
<script type="text/javascript" src="/js/PhotoGalleriesListApplication.js" ></script>
<script type="text/javascript">
    YAHOO.util.Event.onDOMReady(PGLApplication.init);
</script>
<div id="shareMenuTarget" style="visibility:hidden; display:none;"></div>
<div id="shareMenuPanel" style="visibility:hidden; display:none;">
    <div>
            <span id="shareMenuPanelTitle">{t}Message{/t}</span>
    </div>
    <div id="shareMenuPanelContent"></div>
</div>
<div id="deletePanel" title="{t}Delete Gallery{/t}" style="visibility:hidden; display:none;">
    <div id="deletePanelContent">	
		<p class="prText2 prTCenter">{t}Are you sure you want to delete this gallery?{/t}</p>
		<div class="prInnerTop prTCenter">
		{t var='button_01'}Delete gallery{/t}
	  	{linkbutton name=$button_01 link="#" onclick="PGLApplication.showDeletePanelHandle(); return false;"}
		<span class="prInnerSmallLeft prIEVerticalAling"> {t}or{/t} <a href="#" onclick="PGLApplication.hideDeletePanel(); return false;">{t}Cancel{/t}</a></span>
		</div>		
	</div>
</div>
<div id="unsharePanel" title="{t}Unshare Gallery{/t}" style="visibility:hidden; display:none;">
    <div id="unsharePanelContent">
		<p class="prText2 prTCenter">{t}Are you sure you want to unshare this gallery?{/t}</p>
	  	<div class="prTCenter">
		{t var='button_02'}Unshare gallery{/t}
	  		{linkbutton name=$button_02 link="#" onclick="PGLApplication.showUnsharePanelHandle(); return false;"}
			<span class="prInnerSmallLeft prIEVerticalAling">{t}or{/t}</span><a class="prInnerSmallLeft" href="#" onclick="PGLApplication.hideUnSharePanel(); return false;">{t}Cancel{/t}</a>
		</div>		
	</div>
</div>
<div id="stopWatchingPanel" title="{t}Stop Watching Gallery{/t}" style="visibility:hidden; display:none;">
    <div id="stopWatchingPanelContent">
		<p class="prText2 prTCenter">{t}Are you sure you want to stop watching this gallery?{/t}</p>
	  	<div class="prTCenter">
		{t var='button_03'}Stop watching{/t}
	  		{linkbutton name=$button_03 link="#" onclick="PGLApplication.showStopWatchingPanelHandle(); return false;"}
			<span class="prInnerSmallLeft prIEVerticalAling">{t}or{/t}</span><a class="prInnerSmallLeft" href="#" onclick="PGLApplication.hideStopWatchingPanel(); return false;">{t}Cancel{/t}</a>
		</div>		
	</div>
</div>
{*popup_item*}