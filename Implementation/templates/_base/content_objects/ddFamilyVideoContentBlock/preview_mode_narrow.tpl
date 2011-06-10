<div class="themeA">
    <div class="prInnerTop prCOCentrino"> 
        {if $video->getId()}	
            {if $video->getSource() == 'nonvideo'}
                <a href="{$CurrentGroup->getGroupPath()}videogalleryView/id/{$video->getId()}/"><img width="150" src="{$video->getCover()->setWidth(150)->getImage()}" alt="" /></a>
            {else}
                <img width="150" src="{$video->getCover()->setWidth(150)->getImage()}" alt="" onclick="xajax_showVideoPopup({$video->getId()});" />
            {/if}
        {else} 
            <img width="150" src="{$video->getCover()->setWidth(150)->getImage()}" alt="{$video->getTitle()|escape:html}" title="{$video->getTitle()|escape:html}" />
        {/if}
    </div>
    {*
    {include file="content_objects/headline_block_view.tpl"}
    <div id="VM_tinyMCE_{$cloneId}" style="width:157px; overflow-x:auto; font-size:12px;" class="li-cru"> {$Content|strip_script} </div>
    *}
    {if $video->getId()}
        <div id="aj_info_{$cloneId}">
            {include file="content_objects/ddFamilyVideoContentBlock/narrow_info.tpl"}
        </div>
    {/if}
</div>