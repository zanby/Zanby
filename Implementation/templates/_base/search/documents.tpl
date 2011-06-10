{literal}
	<script>
        var cfgSearchApplication = null;
        if ( !cfgSearchApplication ) {
            cfgSearchApplication = function () {
                return {
                    hDocumentAddToMy : '{/literal}{$BASE_URL}/{$LOCALE}/search/documentAddToMy/{literal}'
                }
            }();
        };
    </script>
{/literal}
<script type="text/javascript" src="{$AppTheme->common->js}/modules/search/init.js"></script>

{if $keywords_gs}<h2>{t}{tparam value=$SITE_NAME_AS_STRING} %s Documents About {/t} {$keywords_gs|wordwrap:25:"\n":true|escape}</h2>{/if}
{if $searchTitle}<h2>{$searchTitle|escape}</h2>{/if}
{if $rssUrl}
{assign var='thisrss' value=0}{/if}
{if $documents}
	{$paging}
    <table cellpadding="0" cellspacing="0" border="0" class="prResult">
		<col width="5%" />
		<col width="30%" />
		<col width="15%" />
		<col width="15%" />
		<col width="6%" />
		<col width="19%" />
		<tr>
		<th colspan="3">
			<div {if $order=='name'} class="prRActive{if $direction==asc} prRActive-top{else} prRActive-bottom{/if}"{/if}>
				<a href="/{$LOCALE}/search/documents/preset/new/order/name/direction/{if $order=='name' && $direction=='desc'}asc{else}desc{/if}/page/1/">{t}Document{/t}</a>
			</div>
		</th>
		<th colspan="3">
			<div {if $order=='date'} class="prRActive{if $direction==asc} prRActive-top{else} prRActive-bottom{/if}"{/if}>
				<a href="/{$LOCALE}/search/documents/preset/new/order/date/direction/{if $order=='date' && $direction=='desc'}asc{else}desc{/if}/page/1/">{t}Created{/t}</a>
			</div>
		</th>
	</tr>
		{foreach item=doc from=$documents name='documents'}
			<tr{if ($smarty.foreach.documents.iteration % 2) != 0} class="prEvenBg"{else} class="prOddBg"{/if}>
				{view_factory entity='document' view='globalsearch' object=$doc user=$user}
			</tr>
		{/foreach}
	</table>
    <div class="prIndentTop">{$paging}</div>
{else}
	<p class="prIndentBottom prText2">{t}There are no documents in search results{/t}</p>
	<p>{t}Use the right utility to search again.{/t}</p>
	{t}If you have a special question, please email{/t} <a href="{$BASE_URL}/{$LOCALE}/info/contactus/">{t}Contact Us.{/t}</a>
{/if}