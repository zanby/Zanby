{assign var="login" value=$currentUser->getLogin()}
{if $currentUser->getId() == $user->getId()}
	{assign var="title" value="My Photos"}
{else}
	{assign var="title" value=$currentUser->getLogin()|escape:"html"|cat:"'s photos"}
	{if $showEmail}{assign var="iShowEmail" value=0}{else}{assign var="iShowEmail" value=1}{/if}{*invert variable*}
{/if}
	{assign var='IsShared' value=$gallery->isShared($currentUser)}
	{assign var='IsWatched' value=$gallery->isWatched($currentUser)}
{if $currentUser->getId() == $user->getId()}
    {if $AccessManager->canCreateGallery($currentUser, $user) && !$IsShared && !$IsWatched}
		{assign var="addLink" value=$currentUser->getUserPath('gallerycreate/step/2/gallery')|cat:$gallery->getId()} 
	{/if}
{/if}
	<a href="{$currentUser->getUserPath('photos')}">{t}Back to Photo Galleries{/t}</a>
	<div class="prMediaContent">
	<!-- left column -->
     	<div class="prMediaContentLeft">
			<h2>
				{$gallery->getTitle()|longwordsimp:22|escape:"html"}&#160;
				{if $AccessManager->canEditGallery($gallery, $currentUser, $user)}
					 <span class="prLink4">[ <a href="{$currentUser->getUserPath('galleryedit/gallery')|cat:$gallery->getId()}">
						{t}Edit Gallery{/t}</a> ]
					</span>
				{/if}
			</h2>        
			<h3>{$photo->getTitle()|escape:"html"}</h3>
			
			<div class="prInnerTop prTCenter">
				<a href="#null" onclick="PGPLApplication.showPreviewPanel('{$photo->setBorder(0)->setWidth(800)->setHeight(600)->setProportional(1)->getImage($user)}', '{$photo->setBorder(0)->setWidth(800)->setHeight(600)->setProportional(1)->getThumbnailWidth()}', '{$photo->setBorder(0)->setWidth(800)->setHeight(600)->setProportional(1)->getThumbnailHeight()}'); return false;">
					<img title="" alt="" src="{$photo->setBorder(1)->setWidth(400)->setHeight(300)->setProportional(1)->getImage($user)}" />
				</a>
			</div>
			<div class="prTRight prInnerRight">
			   <a href="#null" onclick="PGPLApplication.showPreviewPanel('{$photo->setBorder(0)->setWidth(800)->setHeight(600)->setProportional(1)->getImage($user)}', '{$photo->setBorder(0)->setWidth(800)->setHeight(600)->setProportional(1)->getThumbnailWidth()}', '{$photo->setBorder(0)->setWidth(800)->setHeight(600)->setProportional(1)->getThumbnailHeight()}'); return false;"><img src="{$AppTheme->images}/decorators/btnLargeImage2.gif" align="top" /></a> {t}Click Photo to View Larger{/t}
			</div>
			<p class="prInnerTop prText4">
				{$photo->getDescription()|escape:"html"|nl2br}
			</p>
        
			<!-- comment begin -->
			<div id="commentListContent" class="prInnerTop">
			{include file="users/gallery/template.comments.list.tpl"}
			</div>
			<!-- comment end -->
		</div>
		<!-- right column -->
		<div class="prMediaContentRight">
        	<span class="prText4">{t}Posted{/t}</span> {$photo->getCreateDate()|user_date_format:$user->getTimezone()}<br/>
        	<span class="prText4">{t}by{/t}</span> <strong>{$photo->getCreator()->getLogin()|escape:"html"}</strong>
			{if $isShared}
			{if $AccessManager->canUnShareGallery($gallery, $currentUser, $user)}
				<div class="prInnerTop">
				<a href="#null" onclick="PGPLApplication.showUnsharePanel('{$gallery->getId()}'); return false;">{t}Unshare{/t}</a>
				</div>
			{/if}
			{/if}

			{if $AccessManager->canShareGallery($gallery, $currentUser, $user)}
				<div class="prInnerTop prClr">
				<a href="#" onclick="PGPLApplication.showShareMenu(this, '{$gallery->getId()}', null); return false;">{t}Share{/t}</a>
				</div>
			{/if}
			{if $AccessManager->canEditPhoto($photo, $currentUser, $user)}
				<div class="prInnerTop">
				<a href="#null" onclick="PGPLApplication.showEditPhotoPanel('{$gallery->getId()}', '{$photo->getId()}'); return false;">
					{t}Edit Photo{/t}
				</a>
				</div>
			{/if}
            {if !$IsShared && !$IsWatched}            
                {if $AccessManager->canUploadPhotos($currentUser, $user)}
                    {assign var=galCount value=$user->getGalleries()->setSharingMode('own')->setWatchingMode('own')->getCount()}            
                    {if $galCount > 1 && $user->getId() == $currentUser->getId()}
                    <div class="prInnerTop">
                        <a href="#null" onclick="PGPLApplication.showMoveToPanel('{$photo->getId()}'); return false;">{t}Move to Gallery{/t}</a>
                    </div>
                    {/if}
                {/if}
            {/if}         
			{if $AccessManager->canDeletePhoto($photo, $currentUser, $user)}
				<div class="prInnerTop">
				<a href="#null" onclick="PGPLApplication.showDeletePhotoPanel('{$gallery->getId()}', '{$photo->getId()}'); return false;">{t}Delete Photo{/t}</a>
				</div>
            {/if}
			{if $AccessManager->canCopyGallery($gallery, $currentUser, $user)}
				<div class="prInnerTop prClr">
				<a href="#null" onclick="PGPLApplication.showAddMenu(this, '{$gallery->getId()}', '{$photo->getId()}', false); return false;">
					{t}Add to My Photos{/t}
				</a>
				</div>
			{/if}
			{if $isShared && $AccessManager->canUnShareGallery($gallery, $currentUser, $user)}
				<div class="prInnerTop">
					<a href="#null" onclick="PGPLApplication.showUnsharePanel('{$gallery->getId()}'); return false;">{t}Unshare{/t}</a>
				</div>
			{/if}
			<!-- photos -->
			<div id="tmbPanel" class="prInnerTop">
				<div class="prTCenter prBlockPaginator">
					{if $tmbCurrentPage != 1}
						<a href="#null" onclick="xajax_show_tmb_page({$tmbCurrentPage}-1, {$gallery->getId()})">&laquo;</a>
					{/if}
					<span>
					{if $tmpCountPhotos > $tmbCurrentPage*$tmbOnPage}
						{$tmbCurrentPage*$tmbOnPage-$tmbOnPage+1} - {t}{tparam value=$tmbCurrentPage*$tmbOnPage}{tparam value=$tmpCountPhotos}%s of %s{/t}
					{else}
						{$tmbCurrentPage*$tmbOnPage-$tmbOnPage+1} - {t}{tparam value=$tmpCountPhotos}{tparam value=$tmpCountPhotos}%s of %s {/t}                 
					{/if}    
					</span>
					{if $tmbCurrentPage < $tmbCountPage}
						<a href="#" onclick="xajax_show_tmb_page({$tmbCurrentPage}+1, {$gallery->getId()})">&raquo;</a>
					{/if}
				</div>
				<div class="prInnerSmallTop prClr2">					
					{foreach item=p name='photos' from=$photosList}
						<div class="prFloatLeft prIndentRightSmall"><a href="{$currentUser->getUserPath('galleryView')}id/{$p->getId()}/page/{$tmbCurrentPage}/"><img src="{$p->setWidth(50)->setHeight(50)->getImage($user)}" /></a></div>
					{/foreach}					
				</div>
			</div>
			<!-- /photos -->
        	<div class="prInnerTop">
				<strong>{t}Photo Tags:{/t}</strong>
				<p class="prInnerSmallTop">
				{if $tags}
					<ul>
						{foreach item=g from=$tags}
							<li class="prIndentTopSmall">
							<a href="{$BASE_URL}/{$LOCALE}/search/photos/preset/new/keywords/{$g->getPreparedTagName()|escape:html}/">{$g->getPreparedTagName()|escape:"html"}</a></li>	
						{/foreach}
					</ul>
				{else}
					<div class="prInnerSmallTop">
						{t}No Tags{/t}
					</div>
				{/if}
				</p>
			</div>
        	<div id="importHistoryBlock" class="prInnerTop">
        		{include file="users/gallery/template.import.history.tpl"}
        	</div>
		</div>    
	</div>

<script type="text/javascript" src="/js/PhotoGalleryPhotosListApplication.js"></script>
{literal}
<script type="text/javascript">
$(function(){ PGPLApplication.init(); });
</script>
{/literal}
{if !$gallery->isShareHistoryExists()}
	{literal}
	<script type="text/javascript">	
	$(function(){ PGPLApplication.disableSaherHistoryView(); });
	</script>
	{/literal}
{/if}
{*popup_item*}
<div id="shareMenuTarget" style="visibility:hidden; display:none;"></div>
<div id="shareMenuPanel" style="visibility:hidden; display:none;">
    <span id="shareMenuPanelTitle">{t}Message{/t}</span>
    <div id="shareMenuPanelContent"></div>
</div>
<div id="addMenuTarget" style="visibility:hidden; display:none;"></div>
<div id="addMenuPanel" style="visibility:hidden; display:none;">
	<span id="addMenuPanelTitle">{t}Message{/t}</span>
    <div id="addMenuPanelContent"></div>
</div>
<div id="previewPanel" title="{t}Preview{/t}" style="visibility:hidden; display:none;">
    <div id="previewPanelContent" class="prTCenter">
		<img style="cursor:pointer;" onclick="PGPLApplication.hidePreviewPanel(); return false;" src="" id="previewPanelImg" />
    </div>
   <div class="prInner">
   {t var='button_01'}Close{/t}
   {linkbutton name=$button_01 link="#" onclick="PGPLApplication.hidePreviewPanel(); return false;"}
   </div>    
</div>
<div id="deleteCommentPanel" title="{t}Delete Comment{/t}" style="visibility:hidden; display:none;">
            <span id="deleteCommentPanelTitle"></span>
    <div id="deleteCommentPanelContent">
		<p class="prText2 prTCenter">{t}Are you sure you want to delete this comment?{/t}</p>
	  	<div class="prInnerTop prTCenter">
		{t var='button_02'}Delete comment{/t}
		 {linkbutton name=$button_02 link="#" onclick="PGPLApplication.showDeleteCommentPanelHandle(); return false;"}
		 <span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="PGPLApplication.hideDeleteCommentPanel(); return false;">{t}Cancel{/t}</a></span>
		</div>		
	</div>
</div>

<div id="editPhotoPanel" title="{t}Edit Photo{/t}" style="visibility:hidden; display:none;">
    <span id="editPhotoPanelTitle"></span>
    <div id="editPhotoPanelContent"></div>
</div>

<div id="deletePhotoPanel" title="{t}Delete Photo{/t}" style="visibility:hidden; display:none;">
    <span id="deletePhotoPanelTitle"></span>
    <div id="deletePhotoPanelContent">
		<p class="prText2 prTCenter">{t}Are you sure you want to delete this photo?{/t}</p> 
	  	<div class="prInnerTop prTCenter">
		{t var='button_03'}Delete photo{/t}
			{linkbutton name=$button_03 link="#" onclick="PGPLApplication.showDeletePhotoPanelHandle(); return false;"}
			<span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t} <a href="#" onclick="PGPLApplication.hideDeletePhotoPanel(); return false;">{t}Cancel{/t}</a></span>
		</div>		
	</div>
</div>
<div id="unsharePanel" title="{t}Unshare Gallery{/t}" style="visibility:hidden; display:none;">
    <div id="unsharePanelContent">
		<p class="prText2 prTCenter">{t}Are you sure you want to unshare this gallery?{/t}</p> 
		<div class="prInnerTop prTCenter">
		{t var='button_04'}Unshare gallery{/t}
			{linkbutton name=$button_04 link="#" onclick="PGPLApplication.showUnsharePanelHandle(); return false;"}
			<span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t} </span><a class="prIndentLeftSmall" href="#" onclick="PGPLApplication.hideUnSharePanel(); return false;">{t}Cancel{/t}</a></span>
		</div>		
	</div>
</div>
{*popup_item*}
