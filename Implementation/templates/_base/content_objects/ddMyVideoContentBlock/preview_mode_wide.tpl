<div class="themeA">
	{include file="content_objects/headline_block_view.tpl"}
    
    <div class="prTCenter">
    {if $video->getId()} 
    		<script type="text/javascript" src="{$BASE_URL}/UptakeVideoPlayer/swfobject-2.0.js"></script>       
            <script type="text/javascript">
                var flashvars = {$smarty.ldelim}width:340, height:305, usefullscreen:false, file:"{$video->getViewSrc()}", image:"{$video->getCover()->getSrc()}_orig.jpg", title:"{$video->getTitle()|escape:html}", viewCounterFunc:"xajax_viewCounter", viewCounterParam: {$video->getId()} {$smarty.rdelim};
                var params = {$smarty.ldelim}allowscriptaccess: "always", wmode:"transparent", allowfullscreen:true{$smarty.rdelim};
				var attributes = {$smarty.ldelim}wmode:"transparent", allowfullscreen:true{$smarty.rdelim};
                attributes.id = "zanbyPlayer_{$cloneId}";
                swfobject.embedSWF("{$video->getViewerSrc()}", "myAlternativeContent_{$cloneId}", "340", "305", "8.0.0", false, flashvars, params, attributes);
            </script>
            <div id="myAlternativeContent_{$cloneId}">
                <a href="http://www.adobe.com/go/getflashplayer">
                    <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
                </a>
            </div>
    
    {else}
    	<img src="{$video->getCover()->setWidth(340)->getImage()}" alt="{$video->getTitle()|escape:html}" title="{$video->getTitle()|escape:html}" />
    {/if}
    
    </div>

           
    {* 
    <div id="VM_tinyMCE_{$cloneId}" style="width:407px; overflow-x:auto; font-size:12px;" class="li-cru">
        {$Content|strip_script}
    </div>
    <br />
    *}
    
    {if $video->getId()}
        <div id="aj_info_{$cloneId}">
            {include file="content_objects/ddMyVideoContentBlock/wide_info.tpl"}
        </div>
    {/if}
    
                        
 </div>