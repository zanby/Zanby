{assign var="login" value=$user->getLogin()}
{if $userBookmarkServicesList}
		{form name="bmForm" id="bmForm" from=$form}
		{form_errors_summary}

			<div class="prInner"><strong>{t}Save link to previous page in:{/t}</strong></div>

				<table class="prFullWidth" border="0">
					{foreach item=b name='bookmark' key=key from=$userBookmarkServicesList}		  				
					    {if $smarty.foreach.bookmark.iteration % 2 == 2 || $smarty.foreach.bookmark.iteration % 2 == 1}
					<tr> 
						{/if}
		    			<td>
		    			{form_radio name="bservice"  value=$b->getId() checked=$service}
		    			</td>
    	        		<td>
							<img src="{$AppTheme->images}/decorators/{$b->getIconPath()}"> 
						</td>
           				<td>           				
							{$b->getName()|escape:"html"}						
						</td>		    			
		                {if $smarty.foreach.bookmark.iteration % 2 == 0}
					</tr>
						{/if}
		  			{/foreach}
				</table>
	
			<div class="prInner">{t}{tparam value=$user->getUserPath('bookmarks')}Click <a href="%s">here</a> to modify set of bookmark services account settings.{/t}</div>
			 
			<div class="prInner"><span class="prIndentBottom">{t}URL:{/t}</span> {form_text name="bookmark_url" value=$bookmarkUrl|escape:"html"}</div>
			
			<div class="prInner"><span class="prIndentBottom">{t}Title:{/t}</span> {form_text name="bookmark_title" value=$bookmarkTitle|escape:"html"}</div>
			
			<div class="prInner prTCenter">
			{t var='button_01'}Bookmark It{/t}
			{linkbutton name=$button_01 onclick="xajax_addbookmark(xajax.getFormValues('bmForm')); return false;"}</div>
		 
			 
		{/form}		
	{else}
		<div class="prInner">
		
		<label>{t}Please Select at least one bookmark service in{/t} </label><a href="{$user->getUserPath('bookmarks')}">{t}account settings{/t}</a>.
		
		</div>
	{/if}