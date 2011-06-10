{*popup_item*}
<script type="text/javascript" src="/js/tinymceBlog/tiny_mce.js"></script>
{literal}
<script>
    var TMbuttons = {
        buttons1:"cut,copy,paste,pastetext,|,formatselect,bold,italic,underline,forecolor",
        buttons2:",fontselect,fontsizeselect,|,link,unlink,image,|,removeformat,code",
        buttons3:""
    };
</script>
{/literal}
{* <script type="text/javascript" src="/js/tinymceStory/settings.js"></script> *}
{assign var='IsShared' value=$gallery->isShared($currentUser)}
<!---BUUU--->

<a href="{$currentUser->getUserPath('videos')}">{t}Back to Videos{/t}</a>

    <div class="prMediaContent">
    <!-- left column -->
        <div class="prMediaContentLeft">

               <h3>{$video->getTitle()|longwordsimp:40|escape:"html"}</h3>
            <div class="prInnerTop prTCenter">
                {if $video->getSource() != 'nonvideo'}
                    {show_video video=$video user = $user}
                {else}
                    <img width="300" title="" alt="" src="{$video->getCover()->getImage($user)}" class="prGrayBorder" />
                {/if}
            </div>
            <div class="prImpList prInnerBottom">
                {$video->getDescription()}
            </div>

            <!-- comment begin -->
            <div id="commentListContent">
                {include file="users/videogallery/template.comments.list.tpl"}
            </div>
            <!-- comment end -->
        </div>

        <!-- right column -->
        <div class="prMediaContentRight">
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
                    <a href="#" onclick="PGPLApplication.showShareMenu(this, '{$gallery->getId()}', null); return false;">{t}Share{/t}</a>
                </div>
                {/if}
                {if $AccessManager->canEditGallery($gallery, $currentUser, $user)}
                <div class="prInnerTop">
                    <a href="#null" onclick="PGPLApplication.showEditPhotoPanel('{$gallery->getId()}', '{$video->getId()}'); return false;">
                        {t}Edit Video{/t}
                    </a>
                </div>
                {/if}
                {if $AccessManager->canEditGallery($gallery, $currentUser, $user)}
                <div class="prInnerTop">
                    <a href="#null" onclick="PGPLApplication.showDeletePhotoPanel('{$gallery->getId()}', '{$video->getId()}', 300, 80); return false;">{t}Delete Video{/t}</a>
                </div>
                {/if}
                {if $AccessManager->canCopyGallery($gallery, $currentUser, $user)}
                <div class="prInnerTop prClr">
                    <a href="#null" onclick="xajax_add_photo_do({$gallery->getId()}, {$video->getId()}, {literal}{mode:2,galleryName:{/literal}'{$gallery->getTitle()|escape:html}'{literal}}{/literal}); return false;">
                        {t}Add to My Videos{/t}
                    </a>
                </div>
                {/if}
                {if $isShared}
                {if $AccessManager->canUnShareGallery($gallery, $currentUser, $user)}
                <div class="prInnerTop"><a href="#null" onclick="PGPLApplication.showUnsharePanel('{$gallery->getId()}'); return false;">{t}Unshare{/t}</a>
                </div>
                {/if}
                {/if}

                <div class="prInnerTop">
                    <strong>{t}Video Tags:{/t}</strong>
                    <p class="prInnerSmallTop">
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
                    </p>
                </div>

                <!-- photos -->

                <div id="tmbPanel" class="prInnerTop">
                    <strong>{t}Recent Videos{/t}</strong>
                    <div id="videoList" curpos="0" maxpos="114" pageincrement="0" increment="10">
                    <div id="videoListDiv" class="prInnerSmallTop prClr2">
                        {foreach item=p name='videos' from=$videosList}
                             <div class="prTCenter"><a href="{$currentUser->getUserPath('videogalleryView')}id/{$p->getId()}/"><img  height="80" width="80" src="{$p->getCover()->setWidth(50)->setHeight(50)->getImage($user)}" /></a></div>
                        {/foreach}
                        <div id="addVideoList2"></div>
                    </div>
                    </div>
                </div>

                <!-- /photos -->
                <div id="importHistoryBlock" class="prInnerTop">
                {*include file="users/videogallery/template.import.history.tpl"*}
                </div>
        </div>

</div>

<script type="text/javascript" src="/js/PhotoGalleryPhotosListApplication.js"></script>
<script type="text/javascript" src="/js/dynamicDiv.js"></script>
<script type="text/javascript">
{literal}
    $(function(){ PGPLApplication.init(); });
{/literal}
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
    <div class="bd" id="shareMenuPanelContent"></div>
</div>
<div id="addMenuTarget" style="visibility:hidden; display:none;"></div>
<div id="addMenuPanel" style="visibility:hidden; display:none;">
    <div id="addMenuPanelContent"></div>
</div>
<div id="previewPanel" title="{t}Preview{/t}" style="visibility:hidden; display:none;">
    <div id="previewPanelContent" class="prTCenter">
        <img style="cursor:pointer;" onclick="PGPLApplication.hidePreviewPanel(); return false;" src="" id="previewPanelImg" />
    </div>
    <div class="prInner">
    {t var="in_button"}Close{/t}
   {linkbutton name=$in_button link="#" onclick="PGPLApplication.hidePreviewPanel(); return false;"}
   </div>
</div>
<div id="deleteCommentPanel" style="visibility:hidden; display:none;">
    <div id="deleteCommentPanelContent">
        <p>{t}Are you sure you want to delete this comment?{/t}</p>
        <div class="prInnerTop prTCenter">
        {t var="in_button_01"}Delete comment{/t}
             {linkbutton name=$in_button_01 link="#" onclick="PGPLApplication.showDeleteCommentPanelHandle(); return false;"}
            <span class="prIEVerticalAling prIndentLeftSmall"> {t}or{/t} <a href="#" link="#" onclick="PGPLApplication.hideDeleteCommentPanel(); return false;">{t}Cancel{/t}</a></span>
        </div>
    </div>
</div>
<div id="editPhotoPanel" title="{t}Edit Video{/t}" style="visibility:hidden; display:none;">
    <div id="editPhotoPanelContent">
    </div>
</div>
<div id="deletePhotoPanel" title="{t}Delete Video{/t}" style="visibility:hidden; display:none;">
    <div id="deletePhotoPanelContent">
        <p class="prTCenter prText2">{t}Are you sure you want to delete this video?{/t}</p>
        <div class="prInnerTop prTCenter">
        {t var="in_button_02"}Delete Video{/t}
            {linkbutton name=$in_button_02 link="#" onclick="PGPLApplication.showDeletePhotoPanelHandle(); return false;"}
            <span class="prIEVerticalAling prIndentLeftSmall"> {t}or{/t} <a href="#"onclick="PGPLApplication.hideDeletePhotoPanel(); return false;">{t}Cancel{/t}</a></span>
        </div>
    </div>
</div>
<div id="unsharePanel" title="{t}Unshare Collection{/t}" style="visibility:hidden; display:none;">
    <div id="unsharePanelContent">
        <p class="prText2 prTCenter">{t}Are you sure you want to unshare this collection?{/t}</p>
        <div class="prInnerTop prTCenter">
        {t var="in_button_03"}Unshare collection{/t}
            {linkbutton name=$in_button_03 link="#" onclick="PGPLApplication.showUnsharePanelHandle(); return false;"}
            <span class="prIEVerticalAling"><span class="prIndentLeftSmall">{t}or{/t}</span><a class="prIndentLeftSmall" href="#" onclick="PGPLApplication.hideUnSharePanel(); return false;">{t}Cancel{/t}</a></span>
        </div>
    </div>
</div>
{*popup_item*}
