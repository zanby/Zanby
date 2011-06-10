<div class="themeA">
    {include file="content_objects/headline_block_view.tpl"}
    
    {if $gallery_show_as_icons}
    <ul>
        {foreach from=$thumbnails item=current name=gallery}
        {assign var="myIteration" value=$smarty.foreach.gallery.iteration-1}
        <li class="prFloatLeft prIndentLeftSmall" id="ddGroupPhotos_{$cloneId}_{$myIteration}">
            {if $current}
                <a href="{$currentGroup->getGroupPath('galleryView/id')|cat:$current->getId()}/"><img class="prGrayBorder" src="{$current->setWidth(35)->setHeight(35)->getImage()}" alt="" /></a>
            {/if}
        </li>
        {/foreach}
    </ul>
    {else}
    <div class="prCOCentrino">
    {foreach item=current name=gallery from=$gallery_hash}
        {assign var="myIteration" value=$smarty.foreach.gallery.iteration-1}
        <div class="prFloatLeft prIndent">
            {if $current && $current->getId()}
            <!-- tags -->
            {*<div> {if !$current->getPhotos()->getLastPhoto()->getTagsList()}&nbsp;{/if}
                {foreach from=$current->getPhotos()->getLastPhoto()->getTagsList() item=tag name="tags"}
                {if $smarty.foreach.tags.iteration < 3}
                <a href="{$BASE_URL}/{$LOCALE}/search/photos/preset/new/keywords/{$tag->getPreparedTagName()}/">{$tag->getPreparedTagName()|escape:html}</a>
                {if $smarty.foreach.tags.iteration < 2 && !$smarty.foreach.tags.last},&nbsp;{/if}
                {/if}
                {/foreach} </div>*}

            <!-- photo -->
            <div>
                {if $current && $current->getPhotos()->getLastPhoto()->getId()}
                    <img class="prGrayBorder" id="ddGroupPhotosG_{$cloneId}_{$myIteration}" src="{$current->getPhotos()->getLastPhoto()->setWidth(119)->setHeight(89)->getImage()}"  />
                {else}
                    {t}Empty Slot{/t}
                {/if}
            </div>
            <!-- title -->
            <div style="overflow: hidden; width:118px; height:35px;">
                <a href="{$currentGroup->getGroupPath('galleryView/id')|cat:$current->getPhotos()->getLastPhoto()->getId()}/" title="{$current->getPhotos()->getLastPhoto()->getTitle()}"> {$current->getPhotos()->getLastPhoto()->getTitle()}</a>
            </div>
            <input type="hidden" name="gallery_{$div_id}_{$smarty.foreach.gallery.iteration}" id="gallery_{$div_id}_{$smarty.foreach.gallery.iteration}" value="{if $thumbs[$smarty.foreach.gallery.iteration]}{$gallery_ids[$smarty.foreach.gallery.iteration]}{/if}">
            {else}
                <img title="" src="{$AppTheme->images}/decorators/imgFake.gif"/> <br /> {t}No More Galleries{/t}
            {/if}
            </div>
        {/foreach} </div>
    {/if} </div>
