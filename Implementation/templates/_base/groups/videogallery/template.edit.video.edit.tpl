<div class="prFloatLeft prInnerSmall"> <img height="100" width="100" src='{$video->getCover()->setWidth(100)->setHeight(100)->getImage()}'> </div>
{form from=$form id="editPhotoForm"|cat:$video->getId()}
    {form_errors_summary}
<table class="prForm">
    <tr>
        <td class="prTRight"><label>{t}Title:{/t}</label></td>
        <td> {form_hidden name="gallery_id" value=$gallery->getId()}
            {form_hidden name="video_id" value=$video->getId()}
            {form_text name="title" id="videoTitle" value=$video->getTitle()|escape:html} </td>
    </tr>
    <tr>
        <td class="prTRight"><label>{t}Description:{/t}</label></td>
        <td>{form_textarea name="description" id="videoDescription"|cat:$video->getId() value=$video->getDescription()|escape:html} </td>
    </tr>
    <tr>
        <td class="prTRight"><label>{t}Tags:{/t}</label></td>
        <td>{form_text name="tags" id="videoTags" value=$video->setForceDbTags()->getVideoTags()|escape:"html"}</td>
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
        <td> {form_select id="source" name="source" options=$sourceEnum->getEnumAsArray(1) selected=$sourceEnum->translate($video->getSource())} </td>
    </tr>
    <tr>
        <td class="prTRight"><label>{t}Embeded object{/t}</label></td>
        <td> {form_textarea name="customSrc" id="customSrc" value="$customSrc"}
            <p class="prTip">{t}Leave blank to keep current{/t}</p></td>
    </tr>
    <tr>
        <td class="prTRight"><label>{t}Thumbnail{/t}</label></td>
        <td>{form_text name="customSrcImg" id="customSrcImg" value=$video->getCustomSrcImg()|escape:"html"}</td>
    </tr>
    {/if}
    <tr>
        <td></td>
        <td><a class="prButton" href="#null" onClick="tinyMCE.execCommand( 'mceRemoveControl', true, 'videoDescription{$video->getId()}');PGEApplication.editPhotoHandle({$video->getId()}); return false;"><span>{t}Save Changes{/t}</span></a> {t}or{/t} <a class="prButton" href="#null" onClick="tinyMCE.execCommand( 'mceRemoveControl', true, 'videoDescription{$video->getId()}'); xajax_cancel_edit_photo({$gallery->getId()}, {$video->getId()}); return false;"><span>{t}Cancel{/t}</span></a> </td>
    </tr>
</table>
{/form}
