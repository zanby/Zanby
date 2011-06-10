<div class="themeA">
{include file="content_objects/headline_block_view.tpl"}

{if $gallery_show_as_icons}	
<div class="prCO-section-inner">
<ul class="prCO-thumbnails">  
	{foreach from=$thumbnails item=current name=gallery}
    {assign var="myIteration" value=$smarty.foreach.gallery.iteration-1}
     <li id="ddMyVideos_{$cloneId}_{$myIteration}"><a href="{$currentUser->getUserPath('videoGalleryView/id')|cat:$current->getId()}/"><img src="{$current->getCover()->setWidth(35)->setHeight(35)->getImage()}" alt="" /></a></li>
		<script type="text/javascript">YAHOO.example.container.ttdocs_{$cloneId}_{$current->id} = new YAHOO.widget.Tooltip("ttdocs_{$cloneId}_{$current->getId()}", {$smarty.ldelim} hidedelay:100, width:"250px" ,context:"ddMyVideos_{$cloneId}_{$myIteration}", text:"<b>{$current->getTitle()|escape:'html'}</b><br>{$current->getDescription()|escape:'html'}<br>Shared by {$current->getCreator()->getLogin()|escape:'html'} on {$current->getCreateDate()|user_date_format:$user->getTimezone()|date_locale:'DATETIME'} {$TIMEZONE}"{$smarty.rdelim});
  				</script>
  	{/foreach}
</ul>
</div>
{else}
  {foreach item=current name=gallery from=$gallery_hash}
  {assign var="myIteration" value=$smarty.foreach.gallery.iteration-1}
  {if $current->getId()}

  <div style="float:left; font-size:11px; height:130px; padding:8px;">
	{if $currentUser->getId() == $user->getId()}	
    	{assign var=currVideo value=$current->getVideos()->getLastVideo()}
	{else}
    	{assign var=currVideo value=$current->getVideos()->getRandomVideo()}
	{/if}	

	{if !$currVideo->getTagsList()}&nbsp;{/if}
    {foreach from=$currVideo->getTagsList() item=tag name="tags"}
		{if $smarty.foreach.tags.iteration < 3}
			<a href="{$currentUser->getUserPath('videossearch/preset/tag/id')|cat:$tag->id}/">{$tag->getPreparedTagName()|escape:html}</a>
			{if $smarty.foreach.tags.iteration < 2},&nbsp;{/if}
		{/if}
	{/foreach}
   
    <div id="ddMyVideosG_{$cloneId}_{$myIteration}">
    	{if $currVideo->getId()}
        	<a href="{$currentUser->getUserPath('videoGalleryView/id')|cat:$currVideo->getId()}/"><img alt="" title="" src="{$currVideo->getCover()->setWidth(119)->setHeight(89)->getImage()}" /></a>
         {else}&nbsp;{/if} 
   </div>
    <div style="overflow: hidden; width:118px; height:26px;">
        <a href="{$currentUser->getUserPath('videoGalleryView/id')|cat:$currVideo->getId()}/">{$currVideo->getTitle()}</a>
    </div>

  </div>
 
 
 
				<script type="text/javascript">
                            YAHOO.example.container.ttdocs_{$cloneId}_{$myIteration} = new YAHOO.widget.Tooltip("ttdocs_{$cloneId}_{$myIteration}", {$smarty.ldelim} hidedelay:100, width:"250px" ,context:"ddMyVideosG_{$cloneId}_{$myIteration}", text:"<b>{$current->getTitle()|escape:'html'}</b><br>{$current->getDescription()|escape:'html'}<br>Shared by {$current->getCreator()->getLogin()|escape:'html'} on {$current->getCreateDate()|user_date_format:$user->getTimezone()|date_locale:'DATETIME'} {$TIMEZONE}"{$smarty.rdelim});
              </script>
  		{/if}
	{/foreach}
{/if}
</div>
