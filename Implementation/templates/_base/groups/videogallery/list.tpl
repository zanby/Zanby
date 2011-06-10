

<div>
	{form from=$form name="sortForm"}
		<label for="show">{t}Show:{/t} </label>
		{form_select name="show" options=$showList onchange="document.sortForm.submit();" selected=$currentshow}
		<label class="prIndentLeftSmall" for="sort">{t}Sort by:{/t} </label>
		{form_select name="sort" options=$sortList onchange="document.sortForm.submit();" selected=$currentsort}
	{/form}
    <!-- C O N T E N T -->
    <div class="prInnerSmall prClr2">
		<!-- left area begin -->
		<div>
		{if $galleriesList}
		<div>
			<div>
				<div class="prIndentBottomSmall">
				{$paging}
				</div>
				<!-- photo list begin -->
				<div class="prGrayBorder prInnerSmall prClr2">
				<!--<ul>-->

					{foreach item=g name='gall' from=$galleriesList}
					{assign var='IsShared' value=$g->isShared($CurrentGroup)}
						<div>
							<div>
							{if $IsShared}
								<img src="{$AppTheme->images}/decorators/sharedBy.gif" alt="" />
								{if $IsShared && $g->isGalleryUpdated($g, $user)}<span class="prMarkRequired">{t}NEW{/t}</span>{/if}
								{if $g->getOwnerType() == 'user'}
								<span>{$g->getOwner()->getLogin()|escape:"html"}</span>
								{else}
								<span>{t}{tparam value=$g->getOwner()->getName()|escape:"html"}%s Group{/t}</span>
								{/if}


							{/if}
							</div>
							{if $user->getId() == $g->getCreatorId()}
								{assign var="lastVideo" value=$g->getVideos()->getLastVideo()}
							{else}
								{assign var="lastVideo" value=$g->getVideos()->getLastVideo()}
							{/if}
							<div>
								<div class="prGrayBorder">
								{if $lastVideo->getId()}
									<a href="{$CurrentGroup->getGroupPath()}videogalleryView/id/{$lastVideo->getId()}/">
									<img height="{$item_height}" width="{$item_width}" src="{$lastVideo->getCover()->setWidth($item_width)->setHeight($item_height)->getImage($user)}" />
								</a>
								{else}
									<img height="{$item_height}" width="{$item_width}" src="{$lastVideo->getCover()->setWidth($item_width)->setHeight($item_height)->getImage($user)}" />
								{/if}
								</div>
								<span>{$g->getTitle()|longwordsimp:20|escape:"html"}</span>
								<div class="prInnerSmall">
									<a href="{$CurrentGroup->getGroupPath()}videogalleryView/id/{$lastVideo->getId()}/">
										{if $user->getId() == $g->getCreatorId()}
											{$g->getVideos()->getCount()}
										{else}
											{$g->getVideos()->getCount()}
										{/if}
										&nbsp;{t}Videos{/t}
									</a><br />

									{if $IsShared}
										{if $AccessManager->canUnShareGallery($g, $CurrentGroup, $user)}
											<a href="#null" onclick="PGLApplication.showUnsharePanel('{$g->getId()}'); return false;">{t}Unshare{/t}</a>&#160;
										{/if}
									{else}
										{if $AccessManager->canEditGallery($g, $CurrentGroup, $user)}
											<a href="{$CurrentGroup->getGroupPath()}/videogalleryedit/gallery/{$g->getId()}/">{t}Edit{/t}</a>&#160;
										{/if}
										{if $AccessManager->canShareGallery($g, $CurrentGroup, $user)}
											<a href="#null" onclick="PGLApplication.showShareMenu(this, '{$g->getId()}'); return false;">{t}Share{/t}</a>&#160;
										{/if}
										{if $AccessManager->canDeleteGallery($g, $CurrentGroup, $user)}
											<a href="#null" onclick="PGLApplication.showDeletePanel('{$g->getId()}'); return false;">{t}Delete{/t}</a>&#160;
										{/if}
									{/if}
								</div>
								<div class="prInnerLeft prInnerRight">
									{if $lastVideo->getId()}
										{if $IsShared}
											{t}{tparam value=$g->getShareDate($CurrentGroup)|user_date_format:$user->getTimezone()}Shared with group on %s{/t}<br />
										{else}
											{t}{tparam value=$g->getCreateDate()|user_date_format:$user->getTimezone()}{tparam value=$g->getCreator()->getLogin()|escape:"html"}Posted %s by %s{/t}
										{/if}
									{else}
										&nbsp;
									{/if}
								</div>





							</div>
						</div>

				{/foreach}
				<!--</ul>-->
				</div>
				<!-- photo list end -->
				<div class="prIndentTopSmall">{$paging}</div>
			</div>
		</div>
	  	{else}
	      	<div>{t}No Videos{/t}</div>
	  	{/if}
      	</div>

      	<!-- right area begin -->
		<div>
      		<h3>{t}Video Tags:{/t}</h3>
			{if $tags}
				<ul>
					{foreach item=g name='tags' from=$tags}
						<li class="prIndentTopSmall">
                        {if $user->getId()}
                        <a href="{$user->getUserPath('videossearch')}preset/tag/id/{$g.id}/">({$g.count}){$g.name|escape:"html"}</a></li>
                        {else}
                        ({$g.count}){$g.name|escape:"html"}
                        {/if}
					{/foreach}
				</ul>
			{else}
				<div class="prInnerSmallTop">
					{t}No Tags{/t}
				</div>
			{/if}
      	</div>
      <!-- right area end -->
</div>
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
<div id="deletePanel" style="visibility:hidden; display:none;">
    <div class="hd">
        <div class='tl'></div>
            <span id="deletePanelTitle">{t}Delete Collection{/t}</span>
        <div class='tr'></div>
    </div>
    <div class="bd" id="deletePanelContent">
		<p>{t}Are you sure you want to delete this Collection?{/t}</p>
	  	 <div class="prInnerTop prTCenter">
		 {t var="in_button"}Delete Collection{/t}
		{linkbutton name=$in_button link="#" onclick="PGLApplication.showDeletePanelHandle(); return false;"}
		<span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t} <a href="#" onclick="PGLApplication.hideDeletePanel(); return false;">{t}Cancel{/t}</a></span>
		</div>
	</div>
</div>
<div id="unsharePanel" title="{t}Unshare Collection{/t}" style="visibility:hidden; display:none;">
    <div id="unsharePanelContent">
		<p class="prText2 prTCenter">{t}Are you sure you want to unshare this Collection?{/t}</p>
	  	 <div class="prInnerTop prTCenter">
		 {t var="in_button_2"}Unshare Collection{/t}
		{linkbutton name=$in_button_2 link="#" onclick="PGLApplication.showUnsharePanelHandle(); return false;"}
		<span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="PGLApplication.hideUnSharePanel(); return false;">{t}Cancel{/t}</a></span>
		</div>
	</div>
</div>
<div id="stopWatchingPanel" style="visibility:hidden; display:none;">
    <div class="hd">
        <div class='tl'></div>
            <span id="stopWatchingPanelTitle">{t}Stop Watching Collection{/t}</span>
        <div class='tr'></div>
    </div>
    <div class="bd" id="stopWatchingPanelContent">
		<p>{t}Are you sure you want to stop watching this Collection?{/t}</p>
	  	 <div class="prInnerTop prTCenter">
		 {t var="in_button_3"}Stop watching{/t}
		{linkbutton name=$in_button_3 link="#" onclick="PGLApplication.showStopWatchingPanelHandle(); return false;"}
		<span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t} <a href="#" onclick="PGLApplication.hideStopWatchingPanel(); return false;">{t}Cancel{/t}</a></span>
		</div>
	</div>
</div>



