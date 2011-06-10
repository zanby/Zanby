<!-- user content -->
{*popup_item*}
{form from=$form id="editPhotoForm" onSubmit=$JsApplication|cat:".showEditPhotoPanelHandle(); return false;"}
{form_errors_summary}
<table class="prForm">
	<col width="25%" />
	<col width="75%" />
	<tr>
		<td class="prTRight"><label for="photoTitle">{t}Title :{/t} </label></td>
		<td>
			{form_hidden name="gallery_id" value=$gallery->getId()}
			{form_hidden name="photo_id" value=$photo->getId()}
			{form_hidden name="JsApplication" value=$JsApplication}
			{form_text name="title" id="photoTitle" value=$photo->getTitle()|escape:html}
		</td>
	</tr>
	<tr>
		<td class="prTRight"><label for="photoDescription">{t}Description :{/t} </label></td>
		<td>{form_textarea name="description" id="photoDescription" rows=5 value=$photo->getDescription()}</td>
	</tr>
	<tr>
		<td class="prTRight"><label for="photoTags">{t}Tags :{/t}	</label></td>
		<td>{form_text name="tags" id="photoTags" value=$photoTags|escape:"html"}</td>
	</tr>
    {if HTTP_CONTEXT == 'zccf' || HTTP_CONTEXT == 'zccf-alt' || HTTP_CONTEXT == 'zccf-base'}
	<tr>
		<td class="prTRight">&nbsp;</td>
		<td><div class="prTip">{t}Tags are a way to group your photos and are separated by spaces. Use quotes around multi-word tags like "Public LIbrary"{/t}</div></td>
	</tr>
    {/if}
	<tr>
		<td class="prInnerTop prTCenter" colspan="2">
		<a class="prButton" href="#null" onclick="{$JsApplication}.showEditPhotoPanelHandle(); return false;"><span>{t}Save Changes{/t}</span></a>			
		<span class="prIndentLeftSmall">{t}or{/t} <a href="#null" onclick="{$JsApplication}.hideEditPhotoPanel(); return false;"><span>{t}Cancel{/t}</span></a></span>			
		</td>
	</tr>
</table>
{/form}
<!-- /user content -->
{*popup_item*}
