{*popup_item*}
<script type="text/javascript" src="/js/tinymceBlog/tiny_mce.js"></script>
<script type="text/javascript" src="/js/tinymceStory/settings.js"></script>
{if $currentUser->getId() == $user->getId()}
    {assign var="title" value="My Videos"}
{else}
    {assign var="title" value=$currentUser->getLogin()|escape:"html"|cat:"'s videos"}
    {if $showEmail}{assign var="iShowEmail" value=0}{else}{assign var="iShowEmail" value=1}{/if}{*invert variable*}
{/if}
{if $currentUser->getId() == $user->getId()}
       {if $AccessManager->canCreateGallery($currentUser, $user)}
    {assign var="addLink" value=$currentUser->getUserPath('videogallerycreate/step/1')}
    {/if}
{/if}

<a href="{$currentUser->getUserPath('videos')}">{t}Back to Video Collections{/t}</a>

    {assign var='IsShared' value=$gallery->isShared($currentUser)}
    {assign var='IsWatched' value=$gallery->isWatched($currentUser)}
    <div class="prClr2 prInner">
    <!-- left column -->
        <div>
             <h2>
            {$gallery->getTitle()|longwordsimp:22|escape:"html"}&#160;
            {if $AccessManager->canEditGallery($gallery, $currentUser, $user)}
                <span>[ <a href="{$currentUser->getUserPath()}videogalleryedit/gallery/{$gallery->getId()}">
                    {t}Edit{/t}</a> ]
                </span>
            {/if}
            </h2>

               <h3>{$video->getTitle()|longwordsimp:40|escape:"html"}</h3>


            <div class="prInnerTop prTCenter">


            {if $video->getSource() != 'nonvideo'}
                {show_video video=$video user = $user}
            {else}
                <img title="" alt="" src="{$video->getCover()->getImage($user)}" class="prGrayBorder" />
            {/if}

            </div>
            <p>
                {$video->getDescription()}
            </p>

            <!-- comment begin -->
            <div id="commentListContent">
            {include file="users/videogallery/template.comments.list.tpl"}
            </div>
            <!-- comment end -->
        </div>

        <!-- right column -->
        <div>
                <span>{t}Posted{/t}</span> {$video->getCreateDate()|user_date_format:$user->getTimezone()}<br/>
                <span>{t}by{/t}</span> <strong>{$video->getCreator()->getLogin()|escape:"html"}</strong>



                {if $isShared}
                {if $AccessManager->canUnShareGallery($gallery, $currentUser, $user)}
                <div class="prInnerTop"><a href="#null" onclick="PGPLApplication.showUnsharePanel('{$gallery->getId()}'); return false;">{t}Unshare{/t}</a>
                </div>
                {/if}
                {/if}


                {if $AccessManager->canShareGallery($gallery, $currentUser, $user)}
                <div class="prInnerTop prClr">
                    <a href="#" onclick="PGPLApplication.showShareMenu(this, '{$gallery->getId()}'); return false;">{t}Share{/t}</a>
                </div>
                {/if}
                {if $AccessManager->canEditGallery($gallery, $currentUser, $user)}
                <div class="prInnerTop">
                    <a href="#null" onclick="PGPLApplication.showEditPhotoPanel('{$gallery->getId()}', '{$video->getId()}'); return false;">
                        {t}Edit Video{/t}
                    </a>
                </div>
                {/if}
                {if !$IsShared && !$IsWatched}
                {if $AccessManager->canUploadVideos($currentUser, $user)}
                    {assign var=galCount value=$user->getVideoGalleries()->setSharingMode('own')->setWatchingMode('own')->getCount()}
                    {if $galCount > 1 && $user->getId() == $currentUser->getId()}
                    <div class="prInnerTop">
                        <a href="#null" onclick="PGPLApplication.showMoveToPanel('{$video->getId()}'); return false;">{t}Move to Collection{/t}</a>
                    </div>
                    {/if}
                {/if}
                {/if}
                {if $AccessManager->canEditGallery($gallery, $currentUser, $user)}
                <div class="prInnerTop">
                    <a href="#null" onclick="PGPLApplication.showDeletePhotoPanel('{$gallery->getId()}', '{$video->getId()}'); return false;">{t}Delete Video{/t}</a>
                </div>
                {/if}
                {if $AccessManager->canCopyGallery($gallery, $currentUser, $user)}
                <div class="prInnerTop prClr">
                    <a href="#null" onclick="PGPLApplication.showAddVideoMenu(this, '{$gallery->getId()}', '{$video->getId()}'); return false;">
                        {t}Add to My Videos{/t}
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
                    <div class="prTCenter">
                        {if $tmbCurrentPage != 1}
                            <a href="#null" onclick="xajax_show_tmb_page({$tmbCurrentPage}-1, {$gallery->getId()})">&laquo;</a>
                        {/if}
                        <span>
                        {if $tmpCountVideos > $tmbCurrentPage*$tmbOnPage}
                            {$tmbCurrentPage*$tmbOnPage-$tmbOnPage+1} - {t}{tparam value=$tmbCurrentPage*$tmbOnPage}{tparam value=$tmpCountPhotos}%s of %s{/t}
                        {else}
                            {$tmbCurrentPage*$tmbOnPage-$tmbOnPage+1} - {t}{tparam value=$tmpCountVideos}{tparam value=$tmpCountVideos}%s of %s {/t}
                        {/if}
                        </span>
                        {if $tmbCurrentPage < $tmbCountPage}
                         <a href="#" onclick="xajax_show_tmb_page({$tmbCurrentPage}+1, {$gallery->getId()})" >&raquo;</a>
                        {/if}
                    </div>
                    <div class="prInnerSmallTop prClr2">
                        {foreach item=p name='videos' from=$videosList}
                             <div class="prFloatLeft"><a href="{$currentUser->getUserPath('videogalleryView')}id/{$p->getId()}/page/{$tmbCurrentPage}/"><img  height="50" width="50" src="{$p->getCover()->setWidth(50)->setHeight(50)->getImage($user)}" /></a></div>
                        {/foreach}
                    </div>
                </div>
                <!-- /photos -->

                <div class="prInnerTop">
                    <strong>{t}Video Tags:{/t}</strong>
                    <p class="prInnerSmallTop">
                    {$ptags}
                    </p>
                </div>
                <div id="importHistoryBlock" class="prInnerTop">
                {*include file="users/videogallery/template.import.history.tpl"*}
            </div>
        </div>

</div>

<script type="text/javascript" src="/js/PhotoGalleryPhotosListApplication.js" ></script>
{literal}
    <script type="text/javascript">
        $(function(){ PGPLApplication.init(); });
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
    <div class="bd" id="previewPanelContent" class="prTCenter">
        <img style="cursor:pointer;" onclick="PGPLApplication.hidePreviewPanel(); return false;" src="" id="previewPanelImg" />
    </div>
    <div class="prInner">
    {t var="button_01"}Close{/t}
   {linkbutton name=$button_01 link="#" onclick="PGPLApplication.hidePreviewPanel(); return false;"}
   </div>
</div>
<div id="deleteCommentPanel" style="visibility:hidden; display:none;">
    <div class="hd">
        <div class='tl'></div>
            <span id="deleteCommentPanelTitle">{t}Delete Comment{/t}</span>
        <div class='tr'></div>
    </div>
    <div class="bd" id="deleteCommentPanelContent">
        <p>{t}Are you sure you want to delete this comment?{/t}</p>
        <div class="prInnerTop prTCenter">
        {t var="button_02"}Delete comment{/t}
             {linkbutton name=$button_02 link="#" onclick="PGPLApplication.showDeleteCommentPanelHandle(); return false;"}
             <span class="prIEVerticalAling prIndentLeftSmall"> {t}or{/t} <a href="#" onclick="PGPLApplication.hideDeleteCommentPanel(); return false;">{t}Cancel{/t}</a></span>
        </div>
    </div>
</div>
<div id="editPhotoPanel" style="visibility:hidden; display:none;">
    <div class="hd">
        <div class='tl'></div>
            <span id="editPhotoPanelTitle">{t}Edit Video{/t}</span>
        <div class='tr'></div>
    </div>
    <div class="bd" id="editPhotoPanelContent">
    </div>
</div>
<div id="deletePhotoPanel" style="visibility:hidden; display:none;">
    <div class="hd">
        <div class='tl'></div>
            <span id="deletePhotoPanelTitle">{t}Delete Video{/t}</span>
        <div class='tr'></div>
    </div>
    <div class="bd" id="deletePhotoPanelContent">
        <p>{t}Are you sure you want to delete this video?{/t}</p>
        <div class="prInnerTop prTCenter">
        {t var="button_03"}Delete Video{/t}
            {linkbutton name=$button_03 link="#" onclick="PGPLApplication.showDeletePhotoPanelHandle(); return false;"}
            <span class="prIEVerticalAling prIndentLeftSmall"> {t}or{/t} <a href="#" onclick="PGPLApplication.hideDeletePhotoPanel(); return false;">{t}Cancel{/t}</a></span>
        </div>
    </div>
</div>
<div id="unsharePanel" title="{t}Unshare Collection{/t}" style="visibility:hidden; display:none;">
    <div id="unsharePanelContent">
        <p class="prText2 prTCenter">{t}Are you sure you want to unshare this collection?{/t}</p>
        <div class="prInnerTop prTCenter">
        {t var="button_04"}Unshare collection{/t}
            {linkbutton name=$button_04 link="#" onclick="PGPLApplication.showUnsharePanelHandle(); return false;"}
            <span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="PGPLApplication.hideUnSharePanel(); return false;">{t}Cancel{/t}</a></span>
        </div>
    </div>
</div>
{*popup_item*}
