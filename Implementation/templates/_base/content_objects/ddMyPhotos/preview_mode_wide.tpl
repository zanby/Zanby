<div class="themeA"> 

    {include file="content_objects/headline_block_view.tpl"}
    
    {if $gallery_show_as_icons}
    
        <ul>
            {foreach from=$thumbnails item=current name=gallery}
            {assign var="myIteration" value=$smarty.foreach.gallery.iteration-1}
            {if $current}
            <li class="prFloatLeft prIndentLeftSmall" id="ddMyPhotos_{$cloneId}_{$myIteration}">
                <a href="{$currentUser->getUserPath('galleryView/id')|cat:$current->getId()}/"><img class="prGrayBorder" src="{$current->setWidth(35)->setHeight(35)->getImage()}" alt="" /></a>
            </li>
            <script type="text/javascript">YAHOO.example.container.ttdocs_{$cloneId}_{$current->id} = new YAHOO.widget.Tooltip("ttdocs_{$cloneId}_{$current->getId()}", {$smarty.ldelim} hidedelay:100, width:"250px" ,context:"ddMyPhotos_{$cloneId}_{$myIteration}", text:"<b>{$current->getTitle()|escape:'html'}</b><br>{$current->getDescription()|escape:'javascript'}<br>Shared by {$current->getCreator()->getLogin()|escape:'html'} on {$current->getCreateDate()|user_date_format:$user->getTimezone()|date_locale:'DATETIME'} {$TIMEZONE}"{$smarty.rdelim});
  				</script>
            {/if}
            {/foreach}
        </ul>
  
    {else}
    <div class="prCOCentrino prClr3">
        {foreach item=current name=gallery from=$gallery_hash}
        {assign var="myIteration" value=$smarty.foreach.gallery.iteration-1}
        <div class="prFloatLeft prIndent"> {if $current && $current->getId()}
            
            
            {if $currentUser->getId() == $user->getId()}	
            {assign var=currPhoto value=$current->getPhotos()->getLastPhoto()}
            {else}
            {assign var=currPhoto value=$current->getPhotos()->getRandomPhoto()}
            {/if}
           {* <!-- tags -->
            <div> {if !$currPhoto->getTagsList()}&nbsp;{/if}
                {foreach from=$currPhoto->getTagsList() item=tag name="tags"}
                
                {if $smarty.foreach.tags.iteration < 3}
                <!--a href="{$currentUser->getUserPath('photossearch/preset/tag/id')|cat:$tag->id}/">{$tag->getPreparedTagName()|escape:html}</a-->
				<a href="{$BASE_URL}/{$LOCALE}/search/photos/preset/new/keywords/{$tag->getPreparedTagName()}/">{$tag->getPreparedTagName()|escape:html}</a>
                {if $smarty.foreach.tags.iteration < 2 && !$smarty.foreach.tags.last},&nbsp;{/if}
                {/if}
                
                {/foreach} 
			</div> *}
            <!-- photo -->
            <div id="ddMyPhotosG_{$cloneId}_{$myIteration}"> {if $currPhoto->getId()}
                <a href="{$currentUser->getUserPath('galleryView/id')|cat:$currPhoto->getId()}/"><img class="prGrayBorder" alt="" title="" src="{$currPhoto->setWidth(119)->setHeight(89)->getImage($user)}" /></a>
                {else}&nbsp;{/if} 
			</div>
            <!-- title -->
            <div style="overflow: hidden; width:118px; height:35px;">
                <a href="{$currentUser->getUserPath('galleryView/id')|cat:$currPhoto->getId()}/" title="{$currPhoto->getTitle()}">{$currPhoto->getTitle()}</a>
            </div>
            <script type="text/javascript">
                            YAHOO.example.container.ttdocs_{$cloneId}_{$myIteration} = new YAHOO.widget.Tooltip("ttdocs_{$cloneId}_{$myIteration}", {$smarty.ldelim} hidedelay:100, width:"250px" ,context:"ddMyPhotosG_{$cloneId}_{$myIteration}", text:"<b>{$current->getTitle()|escape:'html'}</b><br>{$current->getDescription()|escape:'javascript'}<br>Shared by {$current->getCreator()->getLogin()|escape:'html'} on {$current->getCreateDate()|user_date_format:$user->getTimezone()|date_locale:'DATETIME'} {$TIMEZONE}"{$smarty.rdelim});
              </script>
            {/if} 
		</div>
        {/foreach} 
	</div>
{/if} 
</div>
