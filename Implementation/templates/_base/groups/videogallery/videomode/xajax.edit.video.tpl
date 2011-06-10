{*popup_item*}
<!-- user content -->
{form from=$form id="editPhotoForm" onsubmit="tinyMCE.execCommand( 'mceRemoveControl', true, 'videoDescription');"|cat:$JsApplication|cat:".showEditPhotoPanelHandle(); return false;"}
{form_errors_summary}

<table class="prForm">
    <col width="20%" />
    <col width="80%" />
    <tr>
        <td class="prTRight"><label for="videoTitle">{t}Title:{/t}</label></td>
        <td>
            {form_hidden name="gallery_id" value=$gallery->getId()}
            {form_hidden name="video_id" value=$video->getId()}
            {form_hidden name="JsApplication" value=$JsApplication}
            {form_text name="title" id="videoTitle" value=$video->getTitle()|escape:html}
        </td>
    </tr>
    <tr>
        <td class="prTRight"><label for="videoDescription">{t}Description:{/t}</label></td>
        <td>{form_textarea name="description" id="videoDescription" rows=5 value=$video->getDescription()|escape:html}</td>
    </tr>
    <tr>
        <td class="prTRight"><label for="videoTags">{t}Tags:{/t}</label></td>
        <td>{form_text name="tags" id="videoTags" value=$videoTags|escape:"html"}</td>
    </tr>
    {if HTTP_CONTEXT == 'zccf' || HTTP_CONTEXT == 'zccf-alt' || HTTP_CONTEXT == 'zccf-base'}
	<tr>
		<td class="prTRight">&nbsp;</td>
		<td><div class="prTip">{t}Tags are a way to group your videos and are separated by spaces. Use quotes around multi-word tags like "Public LIbrary"{/t}</div></td>
	</tr>
    {/if}
    {if $video->getSource() != 'own'}
    {if $video->getSource() == 'nonvideo'}
        <tr>
            <td class="prTRight"><label for="customSrcImg">{t}Thumbnail{/t}</label></td>
              <td>
                {form_file name="customSrcImg" id="customSrcImg" size="44"}
              </td>
        </tr>
    {else}
    <tr>
        <td class="prTRight"><label for="source">{t}Source{/t}</label></td>
          <td>
          {form_select id="source" name="source" options=$sourceEnum->getEnumAsArray(1) selected=$sourceEnum->translate($video->getSource())}
          </td>
    </tr>
    <tr>
        <td class="prTRight"><label for="customSrc">{t}Embeded object{/t}</label></td>
          <td>
          {form_textarea name="customSrc" id="customSrc" value="$customSrc"}
          <p class="prTip">{t}Leave blank to keep current{/t}</p>
          </td>
    </tr>
    <tr>
        <td class="prTRight"><label for="customSrcImg">{t}Thumbnail{/t}</label></td>
          <td>{form_text name="customSrcImg" id="customSrcImg" value=$video->getCustomSrcImg()|escape:"html"}</td>
    </tr>
    {/if}
    {/if}
    <tr>
        <td class="prTRight"><label>{t}Privacy:{/t}</label></td>
        <td>
          {form_radio name="isPrivate" id="isPrivate1" value="0" checked=$gallery->getPrivate()}<label for="isPrivate1"> {t}Public{/t}</label>
          {form_radio name="isPrivate" id="isPrivate2" value="1" checked=$gallery->getPrivate()}<label for="isPrivate2"> {t}Private{/t}</label>
        </td>
    </tr>
</table>
{/form}
<!-- popup -->
<div class="prInnerTop prTCenter">
            <a class="prButton" href="#null" onClick="tinyMCE.execCommand( 'mceRemoveControl', true, 'videoDescription');{$JsApplication}.showEditPhotoPanelHandle(); return false;"><span>{t}Save Changes{/t}</span></a>
            <span class="prIndentLeftSmall">{t} or {/t}<a class="prInnerSmallLeft" href="#null" onClick="tinyMCE.execCommand( 'mceRemoveControl', true, 'videoDescription');{$JsApplication}.hideEditPhotoPanel(); return false;"><span>{t}Cancel{/t}</span></a></span>
</div>
<!-- /popup -->
<!-- /user content -->
{*popup_item*}
