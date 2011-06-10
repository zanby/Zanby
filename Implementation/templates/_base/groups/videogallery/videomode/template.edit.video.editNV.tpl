<div class="prClr2">
    <div class="prFloatLeft">
        <div class="prInnerSmall"> <img height="100" width="100" src='{$video->getCover()->setWidth(100)->setHeight(100)->getImage()}'> </div>
        {if $AccessManager->canShareGallery($gallery, $currentGroup, $user)} <a href="#null" onclick="PGEApplication.showShareMenu(this, '{$video->getGalleryId()}'); return false;">{t}Share{/t}</a>&#160;
        {/if}
        {if $AccessManager->canDeleteGallery($gallery, $currentGroup, $user)} <a href="#null" onclick="PGEApplication.showDeletePhotoPanel({$video->getGalleryId()}, {$video->getId()}); return false;">{t}Delete{/t}</a> {/if} </div>
    <div class="prFloatLeft"> {form from=$form id="editPhotoForm"|cat:$video->getId() onsubmit="tinyMCE.execCommand( 'mceRemoveControl', true, 'videoDescription"|cat:$video->getId()|cat:"');PGEApplication.editPhotoHandle("|cat:$video->getId()|cat:"); return false;"}
        {form_errors_summary}
        <table class="prForm">
s			<tr>
                <td class="prTRight"><label>{t}Title:{/t}</label></td>
                <td> {form_hidden name="gallery_id" value=$gallery->getId()}
                    {form_hidden name="video_id" value=$video->getId()}
                    {form_text name="title" id="videoTitle" value=$video->getTitle()|escape:html} </td>
            </tr>
            <tr>
                <td class="prTRight"><label for="videoDescription">{t}Description:{/t}</label></td>
                <td>{form_textarea name="description" id="videoDescription"|cat:$video->getId()value=$video->getDescription()|escape:html} </td>
            </tr>
            <tr>
                <td class="prTRight"><label for="videoTags">{t}Tags:{/t}</label></td>
                <td>{form_text name="tags" id="videoTags" value=$video->setForceDbTags()->getVideoTags()|escape:"html"}</td>
            </tr>
            {if HTTP_CONTEXT == 'zccf' || HTTP_CONTEXT == 'zccf-alt' || HTTP_CONTEXT == 'zccf-base'}
            <tr>
                <td class="prTRight">&nbsp;</td>
                <td><div class="prTip">{t}Tags are a way to group your videos and are separated by spaces. Use quotes around multi-word tags like "Public LIbrary"{/t}</div></td>
            </tr>
            {/if}
            <tr>
                <td class="prTRight"><label for="customSrcImg">{t}Thumbnail{/t}</label></td>
                <td> {form_file id="customSrcImg" name="customSrcImg" size="87"} </td>
            </tr>
            <tr>
                <td class="prTRight"><label for="isPrivate1">{t}Privacy:{/t}</label></td>
                <td> {form_radio name="isPrivate" id="isPrivate1" value="0" checked=$gallery->getPrivate()}
                    <label for="isPrivate1"> {t}Public{/t}</label>
                    {form_radio name="isPrivate" id="isPrivate2" value="1" checked=$gallery->getPrivate()}
                    <label for="isPrivate2"> {t}Private{/t}</label>
                </td>
            </tr>
            <tr>
                <td></td>
                <td class="prTCenter"><a class="prButton" href="#null" onClick="tinyMCE.execCommand( 'mceRemoveControl', true, 'videoDescription{$video->getId()}');PGEApplication.editPhotoHandle({$video->getId()}); return false;"><span>{t}Save Changes{/t}</span></a> {t}or{/t} <a href="#null" onClick="tinyMCE.execCommand( 'mceRemoveControl', true, 'videoDescription{$video->getId()}'); xajax_cancel_edit_photo({$gallery->getId()}, {$video->getId()}); return false;"><span>{t}Cancel{/t}</span></a> </td>
            </tr>
        </table>
        {/form} </div>
    <script>
              tinyMCE.execCommand('mceAddControl', true, 'videoDescription{$video->getId()}');
          </script>
</div>
