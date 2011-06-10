<div class="themeA">
{include file="content_objects/headline_block_view.tpl"}
<div class="prTCenter">
              
        
	{if $video->getId()}	
		<img width="183" style="cursor:pointer;" src="{$video->getCover()->setWidth(183)->getImage()}" alt="" onclick="xajax_showVideoPopup({$video->getId()});" />
     {else}
   		<img width="183" src="{$video->getCover()->setWidth(183)->getImage()}" alt="{$video->getTitle()|escape:html}" title="{$video->getTitle()|escape:html}" />
     {/if}   
        
        
</div>
{*

<div id="VM_tinyMCE_{$cloneId}" style="width:157px; overflow-x:auto; font-size:12px;" class="li-cru">
	{$Content|strip_script}
</div>
*}

{if $video->getId()}
    <div id="aj_info_{$cloneId}">
        {include file="content_objects/ddMyVideoContentBlock/narrow_info.tpl"}
    </div>
{/if}
   




</div>