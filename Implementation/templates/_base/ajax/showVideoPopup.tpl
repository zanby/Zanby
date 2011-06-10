{literal}
<style>
#showVideoPopup_mask {
    opacity: 1;
    background: transparent url(/theme/zays/images/decorators/gray-trunc.png) repeat;
    _background-image: none;
    _background-color: #ccc;
}
</style>
{/literal}
<div class="bd" id="showVideoPopup_objectContainer" align="center" style="padding:5px; margin:0; float: none; display: block;  overflow: auto;">
    
    {if $video->getId()}        
   			
            <div id="showVideoPopup_scriptContainer"></div>
            <div id="myAlternativeContent_showVideoPopup">
                <a href="http://www.adobe.com/go/getflashplayer">
                    <img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
                </a>
            </div>
    
    {else}
        <img src="{$video->getCover()->setWidth(400)->getImage()}" alt="{$video->getTitle()}" title="{$video->getTitle()}" />
    {/if}
    
</div>

<div class="prCOCentrino prIndent">
        {if $video->getId()}
            <div onclick="if (document.getElementById('showVideoPopup_objectContainer') && document.getElementById('zanbyPlayer_showVideoPopup')) document.getElementById('showVideoPopup_objectContainer').removeChild(document.getElementById('zanbyPlayer_showVideoPopup'));popup_window.close(); return false;" onMouseOver="this.className = 'co-button co-btn-active'" onMouseOut="this.className = 'co-button'"><a class="prButton" href="#null"><span>{t}Close{/t}</span></a></div>
        {else}
            <div onclick="popup_window.close(); return false;" onMouseOver="this.className = 'co-button co-btn-active'" onMouseOut="this.className = 'co-button'"><a class="prButton" href="#null"><span>{t}Close{/t}</span></a></div>
        {/if}
</div>
