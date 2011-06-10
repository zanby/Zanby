{*popup_item*}
<div>
    <div id="errors">
    </div>
    {if $denidedPhotos}
        <div class="prInnerLeft prInnerSmallTop prTCenter">
            {t}Next Photos will not be added to yours because of age requirement{/t}
            {foreach from=$denidedPhotos item=photoName}
                {$photoName}<br />
            {/foreach}
        </div>
    {/if}
    {if $noPhotos}
        <div class="prInnerLeft prInnerSmallTop prTCenter">
            {t}Sorry no allowed photos to add to yours{/t}
        </div>
    {else}
        <div class="prInnerTop">
            <input type="radio" name="addGalleryMode" id="addGalleryMode1" value="1" />
            <label for="addGalleryMode1"> {t}Merge this gallery with an existing gallery{/t}</label>
        </div>
        <div class="prInnerLeft prInnerSmallTop">
            <select name="addGalleryExist" id="addGalleryExist" class="prLargeFormItem">
                {foreach from=$galleries item=g}
                <option value="{$g->getId()}">{$g->getTitle()|escape:html}</option>
                {/foreach}
            </select>
        </div>
        <div class="prInnerTop">	
            <input type="radio" name="addGalleryMode" id="addGalleryMode2" value="2" />
            <label for="addGalleryMode2"> {t}Save as new gallery{/t}</label>
        </div>
        <div class="prInnerLeft prInnerSmallTop">
            <input class="prLargeFormItem" type="text" name="addGalleryNew" id="addGalleryNew" maxlength="100" />
        </div>
        <div class="prInnerTop">
            <input type="radio" name="addGalleryMode" id="addGalleryMode3" value="3" />
            <label for="addGalleryMode3"> {t}Watch gallery{/t}</label>
        </div>
    {/if}
    <div class="prInnerTop prTCenter">
        {if !$noPhotos}
            <a class="prButton" href="#null" onclick="{$JsApplication}.showAddGalleryHandle({$gallery->getId()}, {$photo->getId()})"><span>{t}Upload photos{/t}</span></a> {t}or{/t} 
        {/if}
       <a href="#null" onclick="{$JsApplication}.hideAddPanel(); return false;"><span>{t}Cancel{/t}</span></a> 
    </div>
</div>
{*popup_item*}