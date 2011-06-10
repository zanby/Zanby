{assign var='IsShared' value=$gallery->isShared($CurrentGroup)}

<a href="{$currentGroup->getGroupPath('photos')}">{t}Back to Photo Galleries{/t}</a>
<div class="prMediaContent">
<!-- left column -->
	<div class="prMediaContentLeft">
			<h2>{$gallery->getTitle()|longwordsimp:30|escape:"html"}
			{if $AccessManager->canEditGallery($gallery, $CurrentGroup, $user)}<span class="prLink4">[ <a href="{$CurrentGroup->getGroupPath('galleryedit')}gallery/{$gallery->getId()}/">{t}Edit Gallery{/t}</a> ]</span>{/if}</h2>
			<h3>{$photo->getTitle()|escape:"html"}</h3>

			<div class="prInnerTop prTCenter">
				 <a href="javascript:void(0)" onclick="PGPLApplication.showPreviewPanel('{$photo->setBorder(0)->setWidth(800)->setHeight(600)->setProportional(1)->getImage($user)}', '{$photo->setBorder(0)->setWidth(800)->setHeight(600)->setProportional(1)->getThumbnailWidth()}', '{$photo->setBorder(0)->setWidth(800)->setHeight(600)->setProportional(1)->getThumbnailHeight()}'); return false;">
					<img title="" alt="" src="{$photo->setBorder(1)->setWidth(400)->setHeight(300)->setProportional(1)->getImage($user)}"/><br/>
				</a>
			</div>
			<div class="prInnerRight prTRight">
                <a href="javascript:void(0)" onclick="PGPLApplication.showPreviewPanel('{$photo->setBorder(0)->setWidth(800)->setHeight(600)->setProportional(1)->getImage($user)}', '{$photo->setBorder(0)->setWidth(800)->setHeight(600)->setProportional(1)->getThumbnailWidth()}', '{$photo->setBorder(0)->setWidth(800)->setHeight(600)->setProportional(1)->getThumbnailHeight()}'); return false;"><img alt="" src="{$AppTheme->images}/decorators/btnLargeImage2.gif" align="top" /></a> {t}Click Photo to View Larger{/t}
			</div>
			<p class="prInnerTop prText4">
				{$photo->getDescription()|escape:"html"|nl2br}
			</p>

			<!-- comment begin -->
			{if $AccessManager->canViewCommentsGallery($gallery, $CurrentGroup, $user)}
			<div id="commentListContent" class="prInnerTop">
			{include file="groups/gallery/template.comments.list.tpl"}
			</div>
			{/if}
			<!-- comment end -->
	</div>
	<!-- right column -->
	<div class="prMediaContentRight">
			<span class="prText4">{t}Posted{/t}</span> {$photo->getCreateDate()|user_date_format:$user->getTimezone()}<br/>
			<span class="prText4">{t}by{/t}</span> <strong>{$photo->getCreator()->getLogin()|escape:"html"}</strong>
			{if $isShared}
				{if $AccessManager->canUnShareGallery($gallery, $CurrentGroup, $user)}
				<div class="prInnerTop">
				<a href="javascript:void(0)" onclick="PGPLApplication.showUnsharePanel('{$gallery->getId()}'); return false;">{t}Unshare{/t}</a></div>
				{/if}
			{/if}
			{if $AccessManager->canShareGallery($gallery, $CurrentGroup, $user)}
				<div class="prInnerTop prClr">
					<a href="javascript:void(0)" onclick="PGPLApplication.showShareMenu(this, '{$gallery->getId()}', null); return false;">{t}Share{/t}</a>
				</div>
			{/if}
			{if $AccessManager->canEditPhoto($photo, $CurrentGroup, $user)}
				<div class="prInnerTop">
					<a href="javascript:void(0)" onclick="PGPLApplication.showEditPhotoPanel('{$gallery->getId()}', '{$photo->getId()}'); return false;">
						{t}Edit Photo{/t}
					</a>
				 </div>
			{/if}
			{if !$IsShared}
				{if $AccessManager->canUploadPhotos($CurrentGroup, $user)}
				{assign var=galCount value=$CurrentGroup->getGalleries()->setSharingMode('own')->setWatchingMode('own')->getCount()}
					{if $galCount > 1}
					<div class="prInnerTop">
						<a href="javascript:void(0)" onclick="PGPLApplication.showMoveToPanel('{$photo->getId()}'); return false;">{t}Move to Gallery{/t}</a>
					</div>
					{/if}
				{/if}
			{/if}
			{if $AccessManager->canDeletePhoto($photo, $CurrentGroup, $user)}
			<div class="prInnerTop">
				<a href="javascript:void(0)" onclick="PGPLApplication.showDeletePhotoPanel('{$gallery->getId()}', '{$photo->getId()}'); return false;">{t}Delete Photo{/t}</a>
			</div>
			{/if}
			{if $AccessManager->canCopyGallery($gallery, $CurrentGroup, $user)}
			<div class="prInnerTop prClr">
				<a href="javascript:void(0)" onclick="PGPLApplication.showAddMenu(this, '{$gallery->getId()}', '{$photo->getId()}', 'false'); return false;">
					{t}Add to My Photos{/t}
				</a>
			</div>
			{/if}
			{if $IsShared}
				{if $AccessManager->canUnShareGallery($gallery, $CurrentGroup, $user)}
				<div class="prInnerTop">
					<a href="javascript:void(0)" onclick="PGPLApplication.showUnsharePanel('{$gallery->getId()}'); return false;">{t}Unshare{/t}</a>
				</div>
				{/if}
			{/if}

				{if $AccessManager->canPublishGallery($gallery, $CurrentGroup, $user)}
				<div class="prInnerTop"><a href="javascript:void(0)" onclick="PGPLApplication.showPublishPanel('{$gallery->getId()}'); return false;">{t}Publish To Groups{/t}</a>
				</div>
				{/if}

			<!-- photos -->
			<div id="tmbPanel" class="prInnerTop">
				<div class="prTCenter prBlockPaginator">
				{if $tmbCurrentPage != 1}
					<a href="javascript:void(0)" onclick="xajax_show_tmb_page({$tmbCurrentPage}-1, {$gallery->getId()})">&laquo;</a>
				{/if}
					<span class="prInnerLeft prInnerRight">
					{if $tmpCountPhotos > $tmbCurrentPage*$tmbOnPage}
						{$tmbCurrentPage*$tmbOnPage-$tmbOnPage+1} - {t}{tparam value=$tmbCurrentPage*$tmbOnPage}{tparam value=$tmpCountPhotos}%s of %s{/t}
					{else}
						{$tmbCurrentPage*$tmbOnPage-$tmbOnPage+1} - {t}{tparam value=$tmpCountPhotos}{tparam value=$tmpCountPhotos}%s of %s {/t}
					{/if}
					</span>
				{if $tmbCurrentPage < $tmbCountPage}
					<a href="javascript:void(0)" onclick="xajax_show_tmb_page({$tmbCurrentPage}+1, {$gallery->getId()})">&raquo;</a>
				{/if}
				</div>
				<div class="prIndentTopSmall prClr2">
					<!-- -->
					{foreach item=p name='photos' from=$photosList}
                        <div class="prFloatLeft prIndentRightSmall"><a href="{$CurrentGroup->getGroupPath('galleryView')}id/{$p->getId()}/page/{$tmbCurrentPage}/"><img alt="" src="{$p->setWidth(50)->setHeight(50)->getImage($user)}" /></a></div>
					{/foreach}
					<!-- -->
				</div>
			</div>
			<!-- /photos -->
			<div class="prInnerTop">
				<label>{t}Photo Tags:{/t}</label>
				<p class="prIndentTopSmall">
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
				{include file="groups/gallery/template.import.history.tpl"}
			</div>
	</div>
</div>
	    <!-- / NEW CONTENT -->
<script type="text/javascript" src="/js/PhotoGalleryPhotosListApplication.js" ></script>
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
<div id="shareMenuTarget" style="visibility:hidden; display:none;"></div>
<div id="shareMenuPanel" style="visibility:hidden; display:none;">
    <div class="hd">
        <div class='tl'></div>
            <span id="shareMenuPanelTitle">{t}Message{/t}</span>
        <div class='tr'></div>
    </div>
    <div class="bd" id="shareMenuPanelContent"></div>
</div>
<div id="addMenuTarget" style="visibility:hidden; display:none;"></div>
<div id="addMenuPanel" style="visibility:hidden; display:none;">
    <div class="hd">
        <div class='tl'></div>
            <span id="addMenuPanelTitle">{t}Message{/t}</span>
        <div class='tr'></div>
    </div>
    <div class="bd" id="addMenuPanelContent"></div>
</div>

<div id="previewPanel" title="{t}Preview{/t}" style="visibility:hidden; display:none;">
	<div class="prTCenter" id="previewPanelContent">
        <img style="cursor:pointer;" onclick="PGPLApplication.hidePreviewPanel(); return false;" alt="" src="" id="previewPanelImg" />
    </div>
   	<div class="prInner">
		{t var="in_button"}Close{/t}
		{linkbutton name=$in_button onclick="PGPLApplication.hidePreviewPanel(); return false;"}
    </div>
</div>

<div id="deleteCommentPanel" title="{t}Delete Comment{/t}" style="visibility:hidden; display:none;">
            <span id="deleteCommentPanelTitle"></span>
    <div class="bd" id="deleteCommentPanelContent">
		<p class="prTCenter prText2">{t}Are you sure you want to delete this comment?{/t}</p>
		<div class="prInnerTop prTCenter">
			{t var="in_button_2"}Delete comment{/t}
		 {linkbutton name=$in_button_2 onclick="PGPLApplication.showDeleteCommentPanelHandle(); return false;"}
		 <span class="prIEVerticalAling">{t}or{/t} <a href="javascript:void(0)" onclick="PGPLApplication.hideDeleteCommentPanel(); return false;">{t}Cancel{/t}</a></span>
		</div>
	</div>
</div>
<div id="editPhotoPanel" style="visibility:hidden; display:none;">
    <div class="hd">
        <div class='tl'></div>
            <span id="editPhotoPanelTitle">{t}Edit Photo{/t}</span>
        <div class='tr'></div>
    </div>
    <div class="bd" id="editPhotoPanelContent">

	</div>
</div>
<div id="deletePhotoPanel" title="{t}Delete Photo{/t}" style="visibility:hidden; display:none;">
	<span id="deletePhotoPanelTitle"></span>
    <div class="bd" id="deletePhotoPanelContent">
		<p class="prText2 prTCenter">{t}Are you sure you want to delete this photo?{/t}</p>
		<div class="prInnerTop prTCenter">
				{t var="in_button_3"}Delete photo{/t}
			  {linkbutton name=$in_button_3 onclick="PGPLApplication.showDeletePhotoPanelHandle(); return false;"}
			  <span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t} <a href="javascript:void(0)" onclick="PGPLApplication.hideDeletePhotoPanel(); return false;">{t}Cancel{/t}</a></span>
		</div>
	</div>
</div>
<div id="unsharePanel" title="{t}Unshare Gallery{/t}" style="visibility:hidden; display:none;">
    <div id="unsharePanelContent">
		<p class="prText2 prTCenter">{t}Are you sure you want to unshare this gallery?{/t}</p>

		<div class="prInnerTop prTCenter">
			{t var="in_button_4"}Unshare gallery{/t}
			{linkbutton name=$in_button_4 onclick="PGPLApplication.showUnsharePanelHandle(); return false;"}
			  <span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="javascript:void(0)" onclick="PGPLApplication.hideUnSharePanel(); return false;">{t}Cancel{/t}</a></span>
		</div>
	</div>
</div>
