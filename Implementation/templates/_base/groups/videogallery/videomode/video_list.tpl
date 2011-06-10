<script type="text/javascript" src="/js/tinymceBlog/tiny_mce.js"></script>
{literal}
<script type="text/javascript">
    var TMbuttons = {
        buttons1:"cut,copy,paste,pastetext,|,formatselect,bold,italic,underline,forecolor",
        buttons2:",fontselect,fontsizeselect,|,link,unlink,image,|,removeformat,code",
        buttons3:""
    };
</script>
{/literal}
{* <script type="text/javascript" src="/js/tinymceStory/settings.js"></script> *}
    <!-- NEW CONTEN -->
    {assign var='IsShared' value=$gallery->isShared($CurrentGroup)}
    <a href="{$currentGroup->getGroupPath('videos')}">{t}Back to Videos{/t}</a>
    <div class="prMediaContent">
    <!-- left column -->
        <div class="prMediaContentLeft">
             <h2>{$video->getTitle()|longwordsimp:40|escape:"html"}</h2>


            <div class="prInnerTop prTCenter">
              {if $video->getSource() != 'nonvideo'}
                  {show_video video=$video user = $user}
              {else}
                <img width="300" title="" alt="" src="{$video->getCover()->getImage($user)}" />
              {/if}

            </div>
            <div class="prImpList prInnerBottom">
                {$video->getDescription()}
            </div>

            <!-- comment begin -->
             {if $AccessManager->canViewCommentsGallery($gallery, $CurrentGroup, $user)}
            <div id="commentListContent">
            {include file="groups/videogallery/template.comments.list.tpl"}
            </div>
            {/if}
            <!-- comment end -->


        </div>
        <!-- right column -->
        <div class="prMediaContentRight">

            <span>{t}{tparam value=$video->getCreateDate()|user_date_format:$user->getTimezone()}Posted</span> %s{/t}<br/>
            <span>{t}by{/t}</span> <strong>{$video->getCreator()->getLogin()|escape:"html"}</strong>



            {if $isShared}
            {if $AccessManager->canUnShareGallery($gallery, $CurrentGroup, $user)}
            <div class="prInnerTop"><a href="#null" onclick="PGPLApplication.showUnsharePanel('{$gallery->getId()}'); return false;">{t}Unshare{/t}</a>
            </div>
            {/if}
            {/if}


            {if $AccessManager->canShareGallery($gallery, $CurrentGroup, $user)}
            <div class="prInnerTop prClr">
                <a href="#" onclick="PGPLApplication.showShareMenu(this, '{$gallery->getId()}', null); return false;">{t}Share{/t}</a>
            </div>
            {/if}
            {if $AccessManager->canEditGallery($gallery, $CurrentGroup, $user)}
            <div class="prInnerTop">
                <a href="#null" onclick="PGPLApplication.showEditPhotoPanel('{$gallery->getId()}', '{$video->getId()}'); return false;">
                    {t}Edit Video{/t}
                </a>
            </div>
            {/if}
            {if $AccessManager->canEditGallery($gallery, $CurrentGroup, $user)}
                <div class="prInnerTop">
                    <a href="#null" onclick="PGPLApplication.showDeletePhotoPanel('{$gallery->getId()}', '{$video->getId()}', 300, 80); return false;">{t}Delete Video{/t}</a>
                </div>
            {/if}
            {if $AccessManager->canCopyGallery($gallery, $CurrentGroup, $user)}
            <div class="prInnerTop prClr">
                {if $video->getCreatorId() == $user->getId()}
                <a href="#null" onclick="xajax_add_photo_do({$gallery->getId()}, {$video->getId()}, {literal}{mode:2,galleryName:{/literal}'{$gallery->getTitle()|escape:html}'{literal}}{/literal}); return false;">
                    {t}Add to My Videos{/t}
                </a>
                {/if}
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

            <div class="prInnerTop">
            <label>{t}Video Tags:{/t}</label>
                <div class="prIndentTopSmall">
                {if $tags}
                    <ul>
                        {foreach item=g from=$tags}
                            <li class="prIndentTopSmall">
                            <a href="{$BASE_URL}/{$LOCALE}/search/videos/preset/new/keywords/{$g->getPreparedTagName()|escape:html}/">{$g->getPreparedTagName()|escape:"html"}</a></li>
                        {/foreach}
                    </ul>
                {else}
                    <div class="prInnerSmallTop">
                        {t}No Tags{/t}
                    </div>
                {/if}
                </div>
            </div>

            <!-- videos -->
            <div id="tmbPanel" class="prInnerTop">
                <strong>{t}Recent Videos{/t}</strong>
                <div id="videoList">
                <div id="videoListDiv" class="prIndentTopSmall prClr2">
                    {foreach item=p name='videos' from=$videosList}
                    {if $AccessManager->canViewGallery($p->getGallery(), $CurrentGroup, $user)}
                        <div class="prTCenter prInnerSmallTop">
                            <a href="{$CurrentGroup->getGroupPath('videogalleryView')}id/{$p->getId()}/">
                                <img alt="" height="80" width="80" src="{$p->getCover()->setWidth(50)->setHeight(50)->getImage($user)}" />
                            </a>
                        </div>
                    {/if}
                    {/foreach}
                    <div id="addVideoList2"></div>
                </div>
                </div>
            </div>
            <!-- /videos -->
            <div id="importHistoryBlock" class="prInnerTop">
            {include file="groups/videogallery/template.import.history.tpl"}
            </div>
        </div>
    </div>



<script type="text/javascript" src="/js/PhotoGalleryPhotosListApplication.js" ></script>
<script type="text/javascript" src="/js/dynamicDiv.js" ></script>
{literal}
    <script type="text/javascript">
        $(function(){ PGPLApplication.init(); });
    </script>
{/literal}
<script type="text/javascript">
    var videoList = new DynamicDiv('videoList');
    videoList.galleryId = {$gallery->getId()};
    videoList.currentPage = 1;
    videoList.Listen();
</script>
<div id="shareMenuTarget" style="visibility:hidden; display:none;"></div>
<div id="shareMenuPanel" style="visibility:hidden; display:none;">
    <div class="hd">
        <div class='tl'></div>
            <span id="shareMenuPanelTitle">{t}Message{/t}</span>
        <div class='tr'></div>
    </div>
    <div id="shareMenuPanelContent"></div>
</div>
<div id="addMenuTarget" style="visibility:hidden; display:none;"></div>
<div id="addMenuPanel" style="visibility:hidden; display:none;">
    <div class="hd">
        <div class='tl'></div>
            <span id="addMenuPanelTitle">{t}Message{/t}</span>
        <div class='tr'></div>
    </div>
    <div id="addMenuPanelContent"></div>
</div>
<div id="previewPanel" title="{t}Preview{/t}" style="visibility:hidden; display:none;">
    <div id="previewPanelContent" class="prTCenter">
        <img style="cursor:pointer;" onclick="PGPLApplication.hidePreviewPanel(); return false;" src="" id="previewPanelImg" />
    </div>
    <div class="prInner">
   {linkbutton name="Close" link="#" onclick="PGPLApplication.hidePreviewPanel(); return false;"}
   </div>
</div>
<div id="deleteCommentPanel" title="{t}Delete Comment{/t}" style="visibility:hidden; display:none;">
    <div id="deleteCommentPanelContent">
        <p class="prText2 prTCenter">{t}Are you sure you want to delete this comment?{/t}</p>
           <div class="prInnerTop prTCenter">
             {t var="in_button"}Delete comment{/t}
             {linkbutton name=$in_button link="#" onclick="PGPLApplication.showDeleteCommentPanelHandle(); return false;"}
             <span class="prIEVerticalAling prIndentLeftSmall">{t}or{/t}<a class="prIndentLeftSmall" href="#" onclick="PGPLApplication.hideDeleteCommentPanel(); return false;"}>{t}Cancel{/t}</a></span>
        </div>
    </div>
</div>
<div id="editPhotoPanel" style="visibility:hidden; display:none;">
    <div class="hd">
        <div class='tl'></div>
            <span id="editPhotoPanelTitle">{t}Edit Video{/t}</span>
        <div class='tr'></div>
    </div>
    <div id="editPhotoPanelContent">

    </div>
</div>
<div id="deletePhotoPanel" style="visibility:hidden; display:none;">
    <div id="deletePhotoPanelContent">
        <p class="prTCenter prText2">{t}Are you sure you want to delete this video?{/t}</p>
           <div class="prInnerTop prTCenter">
             {t var="in_button_2"}Delete Video{/t}
             {linkbutton name=$in_button_2 link="#" onclick="PGPLApplication.showDeletePhotoPanelHandle(); return false;"}
             <span class="prIEVerticalAling prIndentLeftSmall"> {t}or{/t}<a class="prInnerSmallLeft" href="#" onclick="PGPLApplication.hideDeletePhotoPanel(); return false;">{t}Cancel{/t}</a></span>
        </div>
    </div>
</div>
<div id="unsharePanel" title="{t}Unshare Video{/t}" style="visibility:hidden; display:none;">
    <div id="unsharePanelContent">
        <p class="prText2 prTCenter">{t}Are you sure you want to unshare this video?{/t}</p>
          <div class="prInnerTop prTCenter">
            {t var="in_button_3"}Unshare Video{/t}
            {linkbutton name=$in_button_3 link="#" onclick="PGPLApplication.showUnsharePanelHandle(); return false;"}
            <span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="PGPLApplication.hideUnSharePanel(); return false;">{t}Cancel{/t}</a></span>
        </div>
    </div>
</div>

