{*popup_item*}
<!-- user content -->
<p>{t}{tparam value=$video->getTitle()|escape:html}Add %s to My Videos{/t}</p>
<div id="errors">
</div>

<table class="prForm">
	<tr>
		<td>
			<input type="radio" name="addPhotoMode" id="addPhotoMode1" value="1"/><label for="addPhotoMode1"> {t}Add video to the following collection{/t}
			<div class="prIndentTopSmall prInnerSmall6">
			<select name="addPhotoGalleryExist" id="addPhotoGalleryExist">
				{foreach from=$galleries item=g}
				<option value="{$g->getId()}">{$g->getTitle()|escape:html}</option>
				{/foreach}
			</select>
			</div>
		</td>
	</tr>
	<tr>
		<td><input type="radio" name="addPhotoMode" id="addPhotoMode2" value="2" />
		<label for="addPhotoMode2"> {t}Save video as new collection{/t}</label>
		<div class="prIndentTopSmall">
		<input type="text" name="addPhotoGalleryNew" id="addPhotoGalleryNew" />
		</div>
		</td>
	</tr>
	<tr>
		<td class="prTCenter">
		<a class="prButton" href="#null" onClick="{$JsApplication}.showAddPhotoHandle({$gallery->getId()}, {$video->getId()})"><span>{t}Add Video{/t}</span></a>
			<span class="prIndentLeftSmall"> {t}or{/t} <a id="btnCancel1" href="#null" onClick="{$JsApplication}.hideAddPanel(); return false;"><span>{t}Cancel{/t}</span></a></span>
		</td>
	</tr>
</table>

<!-- /user content -->
{*popup_item*}