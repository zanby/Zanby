{literal}
	<script>
        var cfgSearchApplication = null;
        if ( !cfgSearchApplication ) {
            cfgSearchApplication = function () {
                return {
                    hVideoAddToMy : '{/literal}{$BASE_URL}/{$LOCALE}/search/videoAddToMy/{literal}'
                }
            }();
        };
    </script>
{/literal}
<script type="text/javascript" src="{$AppTheme->common->js}/modules/search/init.js"></script>

{if $keywords}<h2>{t}{tparam value=$SITE_NAME_AS_STRING} %s Videos About {/t} {$keywords|wordwrap:25:"\n":true|escape}</h2>{/if}
{if $searchTitle}<h2>{$searchTitle|escape}</h2>{/if}
{if $videosList}
<!-- search result begin -->
{$paging}
<div class="prIndentTop prClr">
	{foreach from=$videosList name=videos item=video key=id}
		{view_factory entity='video' view='globalsearch' object=$video currentOwner=$currentUser user=$user}
	{/foreach}
</div>
<div class="prIndentTop">
	{$paging}
</div>
{else}
	<p class="prIndentBottom prText2">{t}There are no videos in search results{/t}</p>
	<p>{t}Use the right utility to search again.{/t}</p>
	{t}If you have a special question, please email{/t} <a href="{$BASE_URL}/{$LOCALE}/info/contactus/">{t}Contact Us.{/t}</a>
{/if}