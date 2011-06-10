<div>
    <div id="photos_area_{$cloneId}"> {foreach name=gallery from=$gallery_hash item=current}
        {assign var="myIteration" value=$smarty.foreach.gallery.iteration-1}
        <div class="prFloatLeft prIndent"> {if !($smarty.foreach.gallery.first && $smarty.foreach.gallery.last)}
            <div>
                <a href="#" onclick="removeDDMyPhotosSlot('{$cloneId}', {$myIteration});return false;"><img src="{$AppTheme->images}/buttons/btnCloseGal.gif" alt="" /></a>
            </div>
            {/if}
            <div id="gallery_{$cloneId}_{$myIteration}" style="cursor:pointer;" onclick="if (document.getElementById('photo_type_select_{$cloneId}').selectedIndex) {$smarty.ldelim}return false;{$smarty.rdelim} else xajax_select_gallery('{$cloneId}', {$myIteration});"> {if $current && $current->getId()} <img src="{$current->getPhotos()->getLastPhoto()->setWidth(119)->setHeight(89)->getImage()}" alt="" title="" /> {else} <img title="" src="{$AppTheme->images}/decorators/imgFake.gif"/>
                <br />
                {if !$gallery_type}{t}Select Gallery{/t}{else}{t}No More Galleries{/t}{/if}
                {/if}
            </div>
        </div>
        {/foreach}
        <div class="prClearer"></div>
    </div>
</div>
