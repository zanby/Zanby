{*popup_item*}
{if $currentUser->getId() == $user->getId()}
    {assign var="title" value="My Videos"}
{else}
    {assign var="title" value=$currentUser->getLogin()|escape:"html"|cat:"'s videos"}
	{if $showEmail}{assign var="iShowEmail" value=0}{else}{assign var="iShowEmail" value=1}{/if}{*invert variable*}
{/if}

{if $AccessManager->canCreateGallery($currentUser, $user)}
	{assign var="addLink" value=$currentUser->getUserPath('videogallerycreate/step/1')}
{/if}

<!---BUUU--->
    <!-- C O N T E N T -->
    {*if $galleriesList*}
    <div class="prInner">
			{form from=$form name="sortForm" class="prInnerSmallTop"}
        	<label for="sort">{t}Show: {/t}</label>
			{form_select name="sort" options=$sortList onchange="document.sortForm.submit();" selected=$currentsort}
        {/form}

    </div>
    {*/if*}
	<div class="prInnerBottom prClr2">
    	<div>
      	{if $galleriesList}
        	<div class="prGrayBorder">
			<div>

       			<div class="prIndentBottom">
            	{$paging}
				</div>
				<!-- photo container -->
				<div class="prGrayBorder prInnerLeft prClr2">
					<!--<ul>-->
					{foreach item=g name='gall' from=$galleriesList}
					{assign var='IsShared' value=$g->isShared($currentUser)}
					{assign var='IsWatched' value=$g->isWatched($currentUser)}
						<div>
							{if $IsShared || $IsWatched}
								<div>
								    {if $IsShared}
									<img src="{$AppTheme->images}/decorators/sharedBy.gif" alt="" />
									{else}
									<img src="{$AppTheme->images}/decorators/watchedBy.gif" alt="" />
									{/if}
									{if ($IsShared || $IsWatched) && $g->isGalleryUpdated($g, $user)}<span>{t}NEW{/t}</span>{/if}

									{if $g->getOwnerType() == 'user'}
									<span>{$g->getOwner()->getLogin()|escape:"html"}</span>
									{else}
									<span>
									{t}{tparam value=$g->getOwner()->getName()|escape:"html"}%s Group{/t}
									</span>
									{/if}
								</div>
							{else}
								<div>
									<!--<p class="prIndentBottom8">&nbsp;
									</p>
									<p class="prIndentBottom17">&nbsp;
									</p>-->
								</div>
							{/if}

							{if $user->getId() == $g->getCreatorId()}
								{assign var="lastVideo" value=$g->getVideos()->getLastVideo()}
							{else}
								{assign var="lastVideo" value=$g->getVideos()->getLastVideo()}
							{/if}
								<div>
									<div>
										<div class="prGrayBorder">
										{if $lastVideo->getId()}
											<a href="{$currentUser->getUserPath()}videogalleryView/id/{$lastVideo->getId()}/">
												<img  height="100" width="100" src="{$lastVideo->getCover()->setWidth(100)->setHeight(100)->getImage($user)}" />
											</a>
										{else}
											<img  height="100" width="100" src="{$lastVideo->getCover()->setWidth(100)->setHeight(100)->getImage()}" />
										{/if}
										</div>
										<span>{$g->getTitle()|longwordsimp:20|escape:"html"}</span>
										<div class="prIndentBottom14">
											<a href="{$currentUser->getUserPath()}videogalleryView/id/{$lastVideo->getId()}/">
												{if $user->getId() == $g->getCreatorId()}
													{$g->getVideos()->getCount()}
												{else}
													{$g->getVideos()->getCount()}
												{/if}
												&nbsp;{t}Videos{/t}
											</a>
											<div>
											{if $IsShared && $IsWatched}
												{if $AccessManager->canUnShareGallery($g, $currentUser, $user)}<a href="#null" onclick="PGLApplication.showUnsharePanel('{$g->getId()}'); return false;">{t}Unshare{/t}</a>&#160;{/if}
												{if $AccessManager->canStopWatchingGallery($g, $currentUser, $user)}<a href="#null" onclick="PGLApplication.showStopWatchingPanel('{$g->getId()}'); return false;">{t}Stop Watching{/t}</a>&#160;{/if}
											{elseif $IsShared}
												{if $AccessManager->canUnShareGallery($g, $currentUser, $user)}<a href="#null" onclick="PGLApplication.showUnsharePanel('{$g->getId()}'); return false;">{t}Unshare{/t}</a>&#160;{/if}
											{elseif $IsWatched}
												{if $AccessManager->canStopWatchingGallery($g, $currentUser, $user)}<a href="#null" onclick="PGLApplication.showStopWatchingPanel('{$g->getId()}'); return false;">{t}Stop Watching{/t}</a>&#160;{/if}
											{else}
												{if $AccessManager->canEditGallery($g, $currentUser, $user)}<a href="{$currentUser->getUserPath()}videogalleryedit/gallery/{$g->getId()}/">{t}Edit{/t}</a>&#160;{/if}
												{if $AccessManager->canShareGallery($g, $currentUser, $user)}<a href="#null" onclick="PGLApplication.showShareMenu(this, '{$g->getId()}'); return false;">{t}Share{/t}</a>&#160;{/if}
                                                dfsdfsdfsdffdsfdsfdsfsdfds
												{if $AccessManager->canDeleteGallery($g, $currentUser, $user)}<a href="#null" onclick="PGLApplication.showDeletePanel('{$g->getId()}'); return false;">{t}Delete{/t}</a>{/if}
											{/if}
											</div>
										</div>
										<div class="prIndentBottom">
											{if $lastVideo->getId()}
												{if $IsShared && $IsWatched}
													{t}Shared with you on{/t} {$g->getShareDate($currentUser)|user_date_format:$user->getTimezone()} <br />
													{t}Posted{/t} {$g->getCreateDate()|user_date_format:$user->getTimezone()} by {$g->getCreator()->getLogin()|escape:"html"}
												{elseif $IsShared}
													{t}Shared with you on{/t} {$g->getShareDate($currentUser)|user_date_format:$user->getTimezone()}
												{else}
													{t}Posted{/t} {$g->getCreateDate()|user_date_format:$user->getTimezone()} by {$g->getCreator()->getLogin()|escape:"html"}
												{/if}
											{else}
												&nbsp;
											{/if}
										</div>
									</div>
								</div>
						</div>

					<!--</li>-->
					{/foreach}
					</div>
				<!--</ul>-->
				<!-- /photo container -->
					 <div class="prInnerSmallTop">
					{$paging}
					</div>
			</div>
	  	</div>
      	{else}
			<div>{t}No Videos{/t}</div>
      	{/if}
	</div>
    <div>
                <h3>{t}Video Tags:{/t}</h3>

                {if $tags}
				<ul>
                {foreach item=g name='tags' from=$tags}
                 <li class="prInnerSmallTop"><a href="{$user->getUserPath('videossearch')}preset/tag/id/{$g.id}/">({$g.count}){$g.name|escape:"html"}</a></li>
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
<div id="deletePanel" style="visibility:hidden; display:none;">
    <div class="hd">
        <div class='tl'></div>
            <span id="deletePanelTitle">{t}Delete Collection{/t}</span>
        <div class='tr'></div>
    </div>
    <div class="bd" id="deletePanelContent">
		<p>{t}Are you sure you want to delete this collection?{/t}</p>
        <div class="prInnerTop prTCenter">
		{t var="in_button_01"}Delete collection{/t}
		{linkbutton name=$in_button_01 link="#" onclick="PGLApplication.showDeletePanelHandle(); return false;"}
		<span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t} <a href="#" onclick="PGLApplication.hideDeletePanel(); return false;">{t}Cancel{/t}</a></span>
		</div>
	</div>
</div>
<div id="unsharePanel" title="{t}Unshare Collection{/t}" style="visibility:hidden; display:none;">
    <div id="unsharePanelContent">
        <p class="prTCenter prText2">{t}Are you sure you want to unshare this collection?{/t}</p>
         <div class="prInnerTop prTCenter">
		 {t var="in_button_02"}Unshare collection{/t}
		{linkbutton name=$in_button_02 link="#" onclick="PGLApplication.showUnsharePanelHandle(); return false;"}
		<span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="PGLApplication.hideUnSharePanel(); return false;">{t}Cancel{/t}</a></span>
		</div>
    </div>
</div>
<div id="stopWatchingPanel" title="{t}Stop Watching Collection{/t}" style="visibility:hidden; display:none;">
    <div id="stopWatchingPanelContent">
        <p>{t}Are you sure you want to stop watching this collection?{/t}</p>
         <div class="prInnerTop prTCenter">
		 {t var="in_button_03"}Stop watching{/t}
		{linkbutton name=$in_button_03 link="#" onclick="PGLApplication.showStopWatchingPanelHandle(); return false;"}
		<span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t} <a href="#" onclick="PGLApplication.hideStopWatchingPanel(); return false;">{t}Cancel{/t}</a></span>
		</div>
    </div>
</div>
{*popup_item*}