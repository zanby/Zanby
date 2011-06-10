<div class="prClr2">
    <div class="prFloatLeft prPerWidth15">
        <div class="prGrayBorder prInnerSmall">
            <img id="i{$video->getId()}"  height="100" width="100" src='{$video->getCover()->setWidth(100)->setHeight(100)->getImage()}' />
        </div>
    {if $AccessManager->canShareGallery($gallery, $currentUser, $user)}
        <a href="#null" onclick="PGEApplication.showShareMenu(this, '{$video->getGalleryId()}', null); return false;">{t}Share{/t}</a>&#160;
    {/if}
    {if $AccessManager->canDeleteGallery($gallery, $currentUser, $user)}
        <a href="#null" onclick="PGEApplication.showDeletePhotoPanel({$video->getGalleryId()}, {$video->getId()}); return false;">{t}Delete{/t}</a>
    {/if}
        <br />
    {if $video->isExistRawVideo() && $AccessManager->canDeleteRawVideo($video, $currentUser, $user)}
        <a href="{$video->getRawVideoSrc()}">{t}Download raw video{/t}</a>
        <!--<a href="#null" onclick="PGEApplication.showDeleteRawVideoPanel();return false;">Delete raw video</a>-->
    {/if}
    </div>
    <div class="prFloatLeft prMediaEditBlock">
        <div>
        {form from=$form id="editPhotoForm"|cat:$video->getId() onsubmit="tinyMCE.execCommand( 'mceRemoveControl', true, 'videoDescription"|cat:$video->getId()|cat:"');PGEApplication.editPhotoHandle("|cat:$video->getId()|cat:"); return false;"}
        {form_errors_summary}
              <table class="prForm">
                <tr>
                    <td class="prTRight"><label>{t}Title:{/t}</label></td>
                    <td>
                      {form_hidden name="gallery_id" value=$gallery->getId()}
                      {form_hidden name="video_id" value=$video->getId()}
                      {form_text name="title" id="videoTitle" value=$video->getTitle()|escape:html class="prTinyMceMedia"}
                    </td>
                </tr>
                <tr>
                    <td class="prTRight"><label>{t}Description:{/t}</label></td>
                    <td>{form_textarea name="description" id="videoDescription"|cat:$video->getId() value=$video->getDescription()|escape:html class="prTinyMceWidth"}</td>
                </tr>
                <tr>
                    <td class="prTRight"><label>{t}Tags:{/t}</label></td>
                    <td>{form_text name="tags" id="videoTags" value=$video->setForceDbTags()->getVideoTags()|escape:"html" class="prTinyMceMedia"}</td>
                </tr>
                {if HTTP_CONTEXT == 'zccf' || HTTP_CONTEXT == 'zccf-alt' || HTTP_CONTEXT == 'zccf-base'}
                <tr>
                    <td class="prTRight">&nbsp;</td>
                    <td><div class="prTip">{t}Tags are a way to group your videos and are separated by spaces. Use quotes around multi-word tags like "Public LIbrary"{/t}</div></td>
                </tr>
                {/if}
                {if $video->getSource() != 'own'}
                <tr>
                    <td class="prTRight"><label>{t}Source{/t}</label></td>
                      <td>
                      {form_select id="source" name="source" options=$sourceEnum->getEnumAsArray(1) selected=$sourceEnum->translate($video->getSource()) class="prTinyMceMedia"}
                      </td>
                </tr>
                <tr>
                    <td class="prTRight"><label>{t}Embeded<br />object{/t}</label></td>
                      <td>
                      {form_textarea name="customSrc" id="customSrc" value=$customSrc|escape:"html" class="prTinyMceMedia"}
                      <p class="prTip">{t}Leave blank to keep current{/t}</p>
                      </td>
                </tr>
                <tr>
                    <td class="prTRight"><label>{t}Thumbnail{/t}</label></td>
                      <td>{form_text name="customSrcImg" id="customSrcImg" value=$video->getCustomSrcImg()|escape:"html" class="prTinyMceMedia"}</td>
                </tr>
                {/if}
                <tr>
                    <td class="prTRight"><label>{t}Privacy:{/t}</label></td>
                    <td>
                      {form_radio name="isPrivate" id="isPrivate1" value="0" checked=$gallery->getPrivate()}<label for="isPrivate1"> {t}Public{/t}</label>
                      {form_radio name="isPrivate" id="isPrivate2" value="1" checked=$gallery->getPrivate()}<label for="isPrivate2">  {t}Private{/t}</label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>

                    <a class="prButton" href="#null" onclick="tinyMCE.execCommand( 'mceRemoveControl', true, 'videoDescription{$video->getId()}');PGEApplication.editPhotoHandle({$video->getId()}); return false;"><span>{t}Save Changes{/t}</span></a> {t}or{/t} <a href="#null" onclick="tinyMCE.execCommand( 'mceRemoveControl', true, 'videoDescription{$video->getId()}');xajax_cancel_edit_photo({$gallery->getId()}, {$video->getId()}); return false;"><span>{t}Cancel{/t}</span></a>
                    </td>
                </tr>
              </table>
          {/form}
          </div>
          <script>
              tinyMCE.execCommand('mceAddControl', true, 'videoDescription{$video->getId()}');
          </script>

    </div>
</div>
<div id="deleteRawVideoPanel" style="visibility:hidden; display:none;">
    <div class="hd">
        <div class='tl'></div>
            <span id="deleteRawVideoPanelTitle">{t}Delete Raw Video{/t}</span>
        <div class='tr'></div>
    </div>
    <div class="bd" id="deleteRawVideoPanelContent">
        <p>{t}Are you sure you want to delete raw video?{/t}</p>
          <div class="prInnerSmallTop prTCenter">
          {t var="in_button"}Delete Video{/t}
            {linkbutton name=$in_button link=$currentUser->getUserPath('videogalleryDeleteRawVideo')|cat:"id/"|cat:$video->getId()|cat:"/"}
            <span class="prIndentLeftSmall">
            <span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="PGEApplication.hideDeleteRawVideoPanel();return false;">{t}Cancel{/t}</a></span>
            </span>
        </div>
    </div>
</div>
