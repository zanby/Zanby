{*popup_item*}
{if $importHistory}
	<p>
	{if $importHistory.action_type == 'save_photo'}
		{t}{tparam value=$importHistory.related_gallery->getTitle()|escape:html}You saved  this Photo as '%s' on{/t} {$importHistory.action_date|user_date_format:$user->getTimezone()}
	{elseif $importHistory.action_type == 'merge_photo'}
		{t}{tparam value=$importHistory.related_gallery->getTitle()|escape:html}You added  this  Photo to '%s' on{/t} {$importHistory.action_date|user_date_format:$user->getTimezone()}
	{elseif $importHistory.action_type == 'save_gallery'}
		{t}{tparam value=$importHistory.related_gallery->getTitle()|escape:html}You saved this Gallery as '%s' on {/t} {$importHistory.action_date|user_date_format:$user->getTimezone()}
	{elseif $importHistory.action_type == 'merge_gallery'}
		{t}{tparam value=$importHistory.related_gallery->getTitle()|escape:html}You merged this  gallery with '%s' on {/t} {$importHistory.action_date|user_date_format:$user->getTimezone()}
	{elseif $importHistory.action_type == 'watch_gallery'}
		{t}Watching{/t}
	{/if}
	</p>
{/if}
{*popup_item*}