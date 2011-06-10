{*popup_item*} {*probably AJAX*}
{if $importHistory}
<p> {if $importHistory.action_type == 'save_video'}
	{t}{tparam value=$importHistory.related_gallery->getTitle()|escape:html}You saved  this video as '%s' on{/t} {$importHistory.action_date|user_date_format:$user->getTimezone()}
	{elseif $importHistory.action_type == 'merge_video'}
	{t}{tparam value=$importHistory.related_gallery->getTitle()|escape:html}You added  this  video to '%s' on {/t}{$importHistory.action_date|user_date_format:$user->getTimezone()}
	{elseif $importHistory.action_type == 'save_gallery'}
	{t}{tparam value=$importHistory.related_gallery->getTitle()|escape:html}You saved this collection as '%s' on{/t} {$importHistory.action_date|user_date_format:$user->getTimezone()}
	{elseif $importHistory.action_type == 'merge_gallery'}
	{t}{tparam value=$importHistory.related_gallery->getTitle()|escape:html}You merged this collection with '%s' on{/t} {$importHistory.action_date|user_date_format:$user->getTimezone()}
	{elseif $importHistory.action_type == 'watch_gallery'}
	{t}Watching{/t}
	{/if} </p>
{/if} 
{*popup_item*}