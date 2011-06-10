<div class="themeA" id="light_{$cloneId}">

    
<div class="prCO-section">
    <!-- content section inner -->
    <div class="prCO-section-inner" id="videos_area_{$cloneId}">
        
        
        
      
        
                     {foreach name=gallery from=$gallery_hash item=current}
                {assign var="myIteration" value=$smarty.foreach.gallery.iteration-1}
                     
                     <div style="float:left; padding: 0 0 0 8px; font-size:11px; height:135px;"> 
                    
                    {if !($smarty.foreach.gallery.first && $smarty.foreach.gallery.last)}
                    	<a href="#" onclick="removeDDMyVideosSlot('{$cloneId}', {$myIteration});return false;"><img src="{$AppTheme->images}/decorators/profile-marker.gif" alt=""/></a>
                    {/if}    
                        <div id="gallery_{$cloneId}_{$myIteration}" style="cursor:pointer;" onclick="if (document.getElementById('video_type_select_{$cloneId}').selectedIndex) {$smarty.ldelim}return false;{$smarty.rdelim} else xajax_select_video_gallery('{$cloneId}', {$myIteration});"> 
                        {if $current->getId()}
                            <img src="{$current->getVideos()->getLastVideo()->getCover()->setWidth(119)->setHeight(89)->getImage()}" alt="" title="" />
                  		{else}
                        	<img title="" src="{$AppTheme->images}/decorators/imgFake.gif"/>
                    		<br />{if !$gallery_type}{t}Select Gallery{/t}{else}{t}No More Galleries{/t}{/if}
                    	{/if} 
                  
                  	</div>
            
            	 </div>
              
              
              
              {/foreach}
        		<div class="clear">&nbsp;</div>

    <!-- /content section inner -->
</div>
</div></div>