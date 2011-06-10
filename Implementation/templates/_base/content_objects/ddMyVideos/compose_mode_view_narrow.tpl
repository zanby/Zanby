<div class="themeA">
{include file="content_objects/headline_block_view.tpl"}

{if $gallery_show_as_icons}	
<div class="prCO-section-inner">
<ul class="prCO-thumbnails">     
	{foreach from=$thumbnails item=current name=gallery}
    {assign var="myIteration" value=$smarty.foreach.gallery.iteration-1}
     <li id="ddMyVideos_{$cloneId}_{$myIteration}"><a href="{$currentUser->getUserPath('videoGalleryView/id')|cat:$current->getId()}/"><img src="{$current->getCover()->setWidth(35)->setHeight(35)->getImage()}" alt="" /></a></li>
  	{/foreach}
</ul>
</div>
{else}

<div>
	{foreach item=current name=gallery from=$gallery_hash}
	{assign var="myIteration" value=$smarty.foreach.gallery.iteration-1}
    	<div style="float:left; font-size:11px; height:130px; padding:8px;">
		{if $current->getId()}			
	
			<!-- tags -->
			<div>
            	{if !$current->getVideos()->getLastVideo()->getTagsList()}&nbsp;{/if}
				{foreach from=$current->getVideos()->getLastVideo()->getTagsList() item=tag name="tags"}
				{if $smarty.foreach.tags.iteration < 3}
					<a href="{$currentUser->getUserPath('videossearch/preset/tag/id')|cat:$tag->id}/">{$tag->getPreparedTagName()|escape:html}</a>
					{if $smarty.foreach.tags.iteration < 2},&nbsp;{/if}
				{/if}
				{/foreach}
	 		</div>
	 
			<!-- video -->	 
			<div class="display-three-block1-frame">
   				{if $current->getVideos()->getLastVideo()->getId()}
					  <img  id="ddMyVideosG_{$cloneId}_{$myIteration}" src="{$current->getVideos()->getLastVideo()->getCover()->setWidth(119)->setHeight(89)->getImage()}"  />
				{else}{t}Empty Slot{/t}{/if}
	  		</div>
			
			<!-- title -->
			<div style="overflow: hidden; width:118px; height:26px;">
				<a href="{$currentUser->getUserPath('videoGalleryView/id')|cat:$current->getVideos()->getLastVideo()->getId()}/">
				{$current->getVideos()->getLastVideo()->getTitle()}</a>
			</div>
	  		
			
			<input type="hidden" name="gallery_{$div_id}_{$smarty.foreach.gallery.iteration}" id="gallery_{$div_id}_{$smarty.foreach.gallery.iteration}" value="{if $thumbs[$smarty.foreach.gallery.iteration]}{$gallery_ids[$smarty.foreach.gallery.iteration]}{/if}">
	  
	  {else}
		<div>{t}No Galleries{/t}</div>
	{/if}
	  
		</div>
	
	{/foreach}

</div>

{/if}
</div>