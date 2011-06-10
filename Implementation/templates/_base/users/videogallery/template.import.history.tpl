{*popup_item*}
{*ajax*}
{if $importHistory}
    <p>
    {if $importHistory.action_type == 'save_video'}
        {t}{tparam value=$importHistory.related_gallery->getTitle()|escape:html}{tparam value=$importHistory.action_date|user_date_format:$user->getTimezone()}You saved this video as '%s' on %s{/t}
    {elseif $importHistory.action_type == 'merge_video'}
        {t}{tparam value=$importHistory.related_gallery->getTitle()|escape:html}{tparam value=$importHistory.action_date|user_date_format:$user->getTimezone()}You added this video to '%s' on %s{/t}
    {elseif $importHistory.action_type == 'save_gallery'}
        {t}{tparam value=$importHistory.related_gallery->getTitle()|escape:html}{tparam value=$importHistory.action_date|user_date_format:$user->getTimezone()}You saved this collection as '%s' on %s{/t}
    {elseif $importHistory.action_type == 'merge_gallery'}
        {t}{tparam value=$importHistory.related_gallery->getTitle()|escape:html}{tparam value=$importHistory.action_date|user_date_format:$user->getTimezone()}You merged this collection with '%s' on %s{/t}
    {elseif $importHistory.action_type == 'watch_gallery'}
        {t}Watching{/t}
    {/if}
    </p>
{/if}
{*popup_item*}