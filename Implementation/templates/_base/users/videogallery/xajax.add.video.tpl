<!-- user content -->
{*popup_item*}
<p>{t}{tparam value=$video->getTitle()|escape:html}Add %s to My Videos{/t}</p>
<div id="errors">
</div>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td><input type="radio" name="addPhotoMode" id="addPhotoMode1" value="1" /></td>
        <td>{t}Add video to the following collection{/t}</td>
    </tr>
    <tr>
        <td></td>
        <td>
            <select name="addPhotoGalleryExist" id="addPhotoGalleryExist">
                {foreach from=$galleries item=g}
                <option value="{$g->getId()}">{$g->getTitle()|escape:html}</option>
                {/foreach}
            </select>
        </td>
    </tr>
    <tr>
        <td><input type="radio" name="addPhotoMode" id="addPhotoMode2" value="2" /></td>
        <td>{t}Save video as new collection{/t}</td>
    </tr>
    <tr>
        <td></td>
        <td><input type="text" name="addPhotoGalleryNew" id="addPhotoGalleryNew" /></td>
    </tr>
</table>
<div class="prInnerTop">
			<a class="prButton" href="#null" onclick="{$JsApplication}.showAddPhotoHandle({$gallery->getId()}, {$video->getId()})"><span>{t}Add Video{/t}</span></a>
			<span class="prIndentLeftSmall"><a class="prButton" id="btnCancel1" href="#null" onclick="{$JsApplication}.hideAddPanel(); return false;"><span>{t}Cancel{/t}</span></a></span>
</div>
{*popup_item*}
<!-- /user content -->