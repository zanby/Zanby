<div class="themeA">
    <div class="prInnerTop prCOCentrino">  
	    {if $video->getId()}
	        {if $video->getSource() == 'nonvideo'}
	            <a href="{$CurrentGroup->getGroupPath()}videogalleryView/id/{$video->getId()}/">
	                <img width="385" src="{$video->getCover()->setWidth(385)->getImage()}" alt="" /></a>
	        {else}
	            {show_video video=$video user = $user width=385 height=305 id=$cloneId}
	        {/if}
	    {else}
	        <img width="385" src="{$video->getCover()->setWidth(385)->getImage()}" alt="{$video->getTitle()|escape:html}" title="{$video->getTitle()|escape:html}" /> 
	    {/if} 
    </div>
    {*        
    {include file="content_objects/headline_block_view.tpl"}
    <div id="VM_tinyMCE_{$cloneId}" style="width:407px; overflow-x:auto; font-size:12px;" class="li-cru"> {$Content|strip_script} </div>
    <br />
    *}
    {if $video->getId()}
        <div id="aj_info_{$cloneId}"> {include file="content_objects/ddFamilyVideoContentBlock/wide_info.tpl"} </div>
    {/if} 
</div>