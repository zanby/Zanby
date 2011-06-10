<div class="prTLeft">                                                  
     <h3><a href="{$CurrentGroup->getGroupPath()}videogalleryView/id/{$video->getId()}/">{$video->getTitle()|escape:html}</a></h3>
         {$video->getCreateDate()|user_date_format:$user->getTimezone()|date_locale:'DATE_MEDIUM'}{t} by {/t}<a href="{$video->getCreator()->getUserPath('profile')}">{$video->getCreator()->getLogin()|escape:html}</a>
	<div class="prIndentTopSmall">	
		{$video->getDescription()|strip_tags}{if $video->getDescription()}<br />{/if}<a href="{$CurrentGroup->getGroupPath()}videogalleryView/id/{$video->getId()}/">{t}Full Video{/t}</a>
		<a href="{$CurrentGroup->getGroupPath()}videogalleryView/id/{$video->getId()}/">{$video->getCommentsCount()} {t}Comments{/t}</a>  {if $video->getSource() == 'own'}|  {$video->getViewsCount()} {t}views{/t}{/if}
	</div>
</div>