{literal}
	<script>
        var cfgSearchApplication = null;
        if ( !cfgSearchApplication ) {
            cfgSearchApplication = function () {
                return {
                    hPhotoAddToMy : '{/literal}{$BASE_URL}/{$LOCALE}/search/photoAddToMy/{literal}',
                    hVideoAddToMy : '{/literal}{$BASE_URL}/{$LOCALE}/search/videoAddToMy/{literal}',
                    hEventAddToMy : '{/literal}{$BASE_URL}/{$LOCALE}/search/eventAddToMy/{literal}',
                    hDocumentAddToMy : '{/literal}{$BASE_URL}/{$LOCALE}/search/documentAddToMy/{literal}',
                    hListAddToMy : '{/literal}{$BASE_URL}/{$LOCALE}/search/listAddToMy/{literal}'
                }
            }();
        };
    </script>
{/literal}
<script type="text/javascript" src="{$AppTheme->common->js}/modules/search/init.js"></script>

{if $keywords}<h2>{t}{tparam value=$SITE_NAME_AS_STRING} %s All Results About {/t} {$keywords|wordwrap:25:"\n":true|escape}</h2>{/if}
{if $searchTitle}<h2>{$searchTitle|escape}</h2>{/if}
{if $rssUrl}
{assign var='thisrss' value=0}{/if}
{if $groupsList}
{$paging}
	<table cellpadding="0" cellspacing="0" border="0" class="prResult">
		<col width="5%" />
		<col width="30%" />
		<col width="15%" />
		<col width="15%" />
		<col width="6%" />
		<col width="19%" />
		{foreach item=group from=$groupsList name='allres'}
			<tr{if ($smarty.foreach.allres.iteration % 2) != 0} class="prEvenBg"{else} class="prOddBg"{/if}>
				{if $group->EntityTypeName == 'group'}
					{view_factory entity='group' view='globalsearch' object=$group}
				{elseif $group->EntityTypeName == 'family'}
					{view_factory entity='group' view='globalsearch' object=$group}
				{elseif $group->EntityTypeName == 'user'}
				   {view_factory entity='user' view='globalsearch' object=$group}
				{elseif $group->EntityTypeName == 'list'}
					{view_factory entity='list' view='globalsearch' object=$group}
				{elseif $group->EntityTypeName == 'video'}
					{view_factory entity='video' view='allresults' object=$group}
				{elseif $group->EntityTypeName == 'photo'}
					{view_factory entity='photo' view='allresults' object=$group}
				{elseif $group->EntityTypeName == 'document'}
					{view_factory entity='document' view='globalsearch' object=$group}
				{elseif $group->EntityTypeName == 'event'}
					{view_factory entity='event' view='globalsearch' object=$group user=$user even=$smarty.foreach.allres.iteration num=1}
				{else}
					{view_factory entity='discussion' view='globalsearch' object=$group user=$user}
				{/if}
			</tr>
		{/foreach}
	</table>
	<div class="prIndentTop">
		{$paging}
	</div>
{else}
	<p class="prIndentBottom prText2">{t}There are no search results{/t}</p>
	<p>{t}Use the right utility to search again.{/t}</p>
	{t}{tparam value=$BASE_URL}{tparam value=$LOCALE}If you have a special question, please email <a href="%s/%s/info/contactus/">Contact Us.{/t}</a>
{/if}