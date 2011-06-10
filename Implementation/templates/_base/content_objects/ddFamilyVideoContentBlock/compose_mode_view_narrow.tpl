<div class="themeA prCOCentrino">
    <img width="150" src="{$video->getCover()->setWidth(150)->getImage()}" alt="{$video->getTitle()|escape:html}" title="{$video->getTitle()|escape:html}" />  
	{if $video->getId()}
		<div class="prTLeft">                                  
	        <h3 class="prWithoutInnerTop">{$video->getTitle()|escape:html}</h3>
	        {$video->getCreateDate()|user_date_format:$user->getTimezone()|date_locale:'DATE_MEDIUM'}{t} by {/t}<a href="{$video->getCreator()->getUserPath('profile')}">{$video->getCreator()->getLogin()|escape:html}</a><br />
	        {$video->getDescription()|strip_tags}{if $video->getDescription()}<br />{/if}
	    </div> 
		<div class="prTLeft prInnerSmallTop">																				
			<a href="{$CurrentGroup->getGroupPath()}videogalleryView/id/{$video->getId()}/">{t}Full Video{/t}</a>
			<a href="{$CurrentGroup->getGroupPath()}videogalleryView/id/{$video->getId()}/">{$video->getCommentsCount()} {t}Comments{/t}</a>  {if $video->getSource() == 'own'}|  {$video->getViewsCount()} {t}views{/t}{/if}
		</div>
	{/if}
</div>