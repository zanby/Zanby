<script type="text/javascript" src="/js/tinymceBlog/tiny_mce.js"></script>
<script type="text/javascript" src="/js/tinymceStory/settings.js"></script>
<div>
    <!-- NEW CONTEN -->
    {assign var='IsShared' value=$gallery->isShared($CurrentGroup)}
    <div class="prInnerSmall1 prClr2">
        {if IS_GLOBAL_GROUP}
            <h2 class="prInnerBottom">{t}{tparam value=$currentGroup->getName()}Videos on %s{/t}</h2>
        {/if}
<a href="{$CurrentGroup->getGroupPath('videos')}">{t}Back to Video Collections{/t}</a>
        <div class="prFloatRight">
        {if $AccessManager->canCreateGallery($CurrentGroup, $user)}
            {t var="in_button"}Upload Videos{/t}
            {linkbutton name=$in_button link=$CurrentGroup->getGroupPath('videogallerycreate/step/1')}
        {/if}
        </div>
    </div>


    <div class="prClr3 prInner">
    <!-- left column -->
        <div>
            <h2>
                {$gallery->getTitle()|longwordsimp:30|escape:"html"}&#160;
                {if $AccessManager->canEditGallery($gallery, $CurrentGroup, $user)}
                    <span>[ <a href="{$CurrentGroup->getGroupPath('videogalleryedit/gallery')|cat:$gallery->getId()}">
                        {t}Edit{/t}</a> ]
                    </span>
                {/if}
            </h2>
            <div class="prInnerSmallTop">
            {*Gallery Tags:&#160;{$gtags}*}
            </div>

            <h3>{$video->getTitle()|longwordsimp:40|escape:"html"}</h3>


            <div class="prInnerTop prTCenter">

            {if $video->getSource() != 'nonvideo'}
                {show_video video=$video user = $user}
            {else}
                <img title="" alt="" src="{$video->getCover()->getImage($user)}" />
            {/if}


            </div>
            <p>
                {$video->getDescription()}
            </p>

            <!-- comment begin -->
             {if $AccessManager->canViewCommentsGallery($gallery, $CurrentGroup, $user)}
            <div id="commentListContent">
            {include file="groups/videogallery/template.comments.list.tpl"}
            </div>
            {/if}
            <!-- comment end -->
        </div>
        <!-- right column -->
        <div>

            {t}{tparam value=$video->getCreateDate()|user_date_format:$user->getTimezone()}{tparam value=$video->getCreator()->getLogin()|escape:"html"}<span>Posted</span> %s<br/>
            <span>by</span> <strong>%s</strong>{/t}



            {if $isShared}
            {if $AccessManager->canUnShareGallery($gallery, $CurrentGroup, $user)}
            <div class="prInnerTop"><a href="#null" onclick="PGPLApplication.showUnsharePanel('{$gallery->getId()}'); return false;">{t}Unshare{/t}</a>
            </div>
            {/if}
            {/if}


            {if $AccessManager->canShareGallery($gallery, $CurrentGroup, $user)}
            <div class="prInnerTop prClr">
                <a href="#" onclick="PGPLApplication.showShareMenu(this, '{$gallery->getId()}'); return false;">{t}Share{/t}</a>
            </div>
            {/if}
            {if $AccessManager->canEditGallery($gallery, $CurrentGroup, $user)}
            <div class="prInnerTop">
                <a href="#null" onclick="PGPLApplication.showEditPhotoPanel('{$gallery->getId()}', '{$video->getId()}'); return false;">
                    {t}Edit Video{/t}
                </a>
            </div>
            {/if}
            {if !$IsShared}
                {if $AccessManager->canUploadVideos($currentGroup, $user)}
                    {assign var=galCount value=$CurrentGroup->getVideoGalleries()->setSharingMode('own')->setWatchingMode('own')->getCount()}
                    {if $galCount > 1}
                    <div class="prInnerTop">
                        <a href="#null" onclick="PGPLApplication.showMoveToPanel('{$video->getId()}'); return false;">{t}Move to Collection{/t}</a>
                    </div>
                    {/if}
                {/if}
            {/if}
            {if $AccessManager->canEditGallery($gallery, $CurrentGroup, $user)}
                <div class="prInnerTop">
                    <a href="#null" onclick="PGPLApplication.showDeletePhotoPanel('{$gallery->getId()}', '{$video->getId()}'); return false;">{t}Delete Video{/t}</a>
                </div>
            {/if}
            {if $AccessManager->canCopyGallery($gallery, $CurrentGroup, $user)}
            <div class="prInnerTop prClr">
                <a href="#null" onclick="PGPLApplication.showAddVideoMenu(this, '{$gallery->getId()}', '{$video->getId()}'); return false;">
                    {t}Add to My Videos{/t}
                </a>
            </div>
            {/if}
            <div class="prInnerTop">
                <!--<a href="#null">Flag this video as "may offend"?</a>
                -->
            </div>
            {if $IsShared}
                {if $AccessManager->canUnShareGallery($gallery, $CurrentGroup, $user)}
                <div class="prInnerTop"><a href="#null" onclick="PGPLApplication.showUnsharePanel('{$gallery->getId()}'); return false;">{t}Unshare{/t}</a>
                </div>
                {/if}
            {/if}
            {if $AccessManager->canPublishGallery($gallery, $CurrentGroup, $user)}
            <div class="prInnerTop"><a href="#null" onclick="PGPLApplication.showPublishPanel('{$gallery->getId()}'); return false;">{t}Publish To Groups{/t}</a>
            </div>
            {/if}

            <!-- videos -->
            <div id="tmbPanel" class="prInnerTop">
                <div class="prMarkRequired prTCenter">
                {if $tmbCurrentPage != 1}
                    <a href="#null" onclick="xajax_show_tmb_page({$tmbCurrentPage}-1, {$gallery->getId()})">&laquo;</a>
                {/if}
                    <span class="prInnerLeft prInnerRight">
                    {if $tmpCountVideos > $tmbCurrentPage*$tmbOnPage}
                        {$tmbCurrentPage*$tmbOnPage-$tmbOnPage+1} - {t}{tparam value=$tmbCurrentPage*$tmbOnPage}{tparam value=$tmpCountVideos}%s of %s{/t}
                    {else}
                        {$tmbCurrentPage*$tmbOnPage-$tmbOnPage+1} - {t}{tparam value=$tmpCountVideos}{tparam value=$tmpCountVideos}%s of %s{/t}
                    {/if}
                    </span>
                {if $tmbCurrentPage < $tmbCountPage}
                     <a href="#" onclick="xajax_show_tmb_page({$tmbCurrentPage}+1, {$gallery->getId()})">&raquo;</a>
                {/if}
                </div>
                <div class="prIndentTopSmall prClr2">
                    <!-- -->
                    {foreach item=p name='videos' from=$videosList}
                        <div class="prFloatLeft prInnerSmallTop"><a href="{$CurrentGroup->getGroupPath('videogalleryView')}id/{$p->getId()}/page/{$tmbCurrentPage}/"><img height="50" width="50" src="{$p->getCover()->setWidth(50)->setHeight(50)->getImage($user)}" /></a></div>
                    {/foreach}
                    <!-- -->
                </div>
            </div>
            <!-- /videos -->
            <div class="prInnerTop">
            <strong>{t}Video Tags:{/t}</strong>
                <p class="prIndentTopSmall">
                {$ptags}
                </p>
            </div>
            <div id="importHistoryBlock" class="prInnerTop">
            {include file="groups/videogallery/template.import.history.tpl"}
            </div>
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
    {t var="in_button_2"}Close{/t}
   {linkbutton name=$Close link="#" onclick="PGPLApplication.hidePreviewPanel(); return false;"}
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
             {t var="in_button_3"}Delete comment{/t}
             {linkbutton name=$in_button_3 link="#" onclick="PGPLApplication.showDeleteCommentPanelHandle(); return false;"}
             <span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t} <a href="#" onclick="PGPLApplication.hideDeleteCommentPanel(); return false;">{t}Cancel{/t}</a></span>
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
             {t var="in_button_4"}Delete Video{/t}
             {linkbutton name=$in_button_4 link="#" onclick="PGPLApplication.showDeletePhotoPanelHandle(); return false;"}
             <span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t} <a href="#" onclick="PGPLApplication.hideDeletePhotoPanel(); return false;">{t}Cancel{/t}</a></span>
        </div>
    </div>
</div>
<div id="unsharePanel" title="{t}Unshare Collection{/t}" style="visibility:hidden; display:none;">
    <div id="unsharePanelContent">
        <p class="prText2 prTCenter">{t}Are you sure you want to unshare this collection?{/t}</p>
          <div class="prInnerTop prTCenter">
            {t var="in_button_5"}Unshare collection{/t}
            {linkbutton name=$in_button_5 link="#" onclick="PGPLApplication.showUnsharePanelHandle(); return false;"}
            <span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="PGPLApplication.hideUnSharePanel(); return false;">{t}Cancel{/t}</a></span>
        </div>
    </div>
</div>

