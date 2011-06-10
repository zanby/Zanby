{*popup_item*}
<!-- user content -->
{form from=$form id="editPhotoForm"}
{form_errors_summary}
<table class="prForm">
    <col width="20%" />
    <col width="80%" />
    <tr>
        <td class="prTRight">{t}Title :{/t} </td>
        <td>
            {form_hidden name="gallery_id" value=$gallery->getId()}
            {form_hidden name="video_id" value=$video->getId()}
            {form_hidden name="JsApplication" value=$JsApplication}
            {form_text name="title" id="videoTitle" value=$video->getTitle()|escape:html}
        </td>
    </tr>
    <tr>
        <td class="prTRight">{t}Description :{/t} </td>
        <td>{form_textarea name="description" id="videoDescription" rows=5 value=$video->getDescription()|escape:html}</td>
    </tr>
    <tr>
        <td class="prTRight">{t}Tags :{/t} </td>
        <td>{form_text name="tags" id="videoTags" value=$videoTags|escape:"html"}</td>
    </tr>
    {if HTTP_CONTEXT == 'zccf' || HTTP_CONTEXT == 'zccf-alt' || HTTP_CONTEXT == 'zccf-base'}
	<tr>
		<td class="prTRight">&nbsp;</td>
		<td><div class="prTip">{t}Tags are a way to group your videos and are separated by spaces. Use quotes around multi-word tags like "Public LIbrary"{/t}</div></td>
	</tr>
    {/if}
    {if $video->getSource() != 'own'}
    <tr>
        <td class="prTRight">{t}Source{/t}</td>
          <td>
          {form_select id="source" name="source" options=$sourceEnum->getEnumAsArray(1) selected=$sourceEnum->translate($video->getSource())}
          </td>
    </tr>
    <tr>
        <td class="prTRight">{t}Embeded object{/t}</td>
          <td>
          {form_textarea name="customSrc" id="customSrc" value="$customSrc|escape:html"}
          <p class="prTip">{t}Leave blank to keep current{/t}</p>
          </td>
    </tr>
    <tr>
        <td class="prTRight">{t}Thumbnail{/t}</td>
          <td>{form_text name="customSrcImg" id="customSrcImg" value=$video->getCustomSrcImg()|escape:"html"}</td>
    </tr>
    {/if}
    <tr>
        <td></td>
        <td>
        <div class="prInnerSmallTop">
            <a class="prButton" href="#null" onclick="tinyMCE.execCommand( 'mceRemoveControl', true, 'videoDescription');{$JsApplication}.showEditPhotoPanelHandle(); return false;"><span>{t}Save Changes{/t}</span></a>
            <span class="prIndentLeftSmall"><a class="prButton" href="#null" onclick="tinyMCE.execCommand( 'mceRemoveControl', true, 'videoDescription');{$JsApplication}.hideEditPhotoPanel(); return false;"><span>{t}Cancel{/t}</span></a></span>
        </div>
        </td>
    </tr>
</table>
{/form}
<!-- /user content -->
{*popup_item*}
