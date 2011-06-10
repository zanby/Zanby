{literal}
	<script>
        var cfgSearchApplication = null;
        if ( !cfgSearchApplication ) {
            cfgSearchApplication = function () {
                return {
                    hPhotoAddToMy : '{/literal}{$BASE_URL}/{$LOCALE}/search/photoAddToMy/{literal}'
                }
            }();
        };
    </script>
{/literal}
<script type="text/javascript" src="{$AppTheme->common->js}/modules/search/init.js"></script>

{if $keywords}<h2>{t}{tparam value=$SITE_NAME_AS_STRING} %s Photos About {/t} {$keywords|wordwrap:25:"\n":true|escape}</h2>{/if}
{if $searchTitle}<h2>{$searchTitle|escape}</h2>{/if}
{if $photosList}
{$paging}
<div class="prIndentTop prClr">
	{foreach from=$photosList name=photos item=photo key=id}
		{view_factory entity='photo' view='globalsearch' object=$photo currentUser=$currentOwner user=$user gallery=$gallery}
	{/foreach}
</div>
<div class="prIndentTop">{$paging}</div>
{else}
	<p class="prIndentBottom prText2">{t}There are no photos in search results{/t}</p>
	<p>{t}Use the right utility to search again.{/t}</p>
	{t}If you have a special question, please email{/t} <a href="{$BASE_URL}/{$LOCALE}/info/contactus/">{t}Contact Us.{/t}</a>
{/if}