{if $keywords}<h2>{t}{tparam value=$SITE_NAME_AS_STRING} %s Members About {/t} {$keywords|wordwrap:30:"\n":true|escape}</h2>{/if}
{if $searchTitle}<h2>{$searchTitle|escape}</h2>{/if}
{if $rssUrl}<a href="{$rssUrl}">&nbsp;</a>{/if}
{if $usersList}
{$paging}
<table cellpadding="0" cellspacing="0" border="0" class="prResult">
  <col width="5%" />
  <col width="35%" />
  <col width="15%" />
  <col width="15%" />
  <col width="12%" />
  <col width="18%" />
<tr>
	  <th nowrap="nowrap" colspan="2">
	  <div {if $order==name}class="prRActive {if $direction==asc}prRActive-top{else}prRActive-bottom{/if}"{/if}>
	  <a href="{$_url}{if $filter && $filterid}/filter/{$filter|escape}/filterid/{$filterid|escape}{/if}/order/name/direction/{if $order==name && $direction=='asc'}desc{else}asc{/if}/page/1/">{t}Name{/t}</a></div></th>
	  <th nowrap="nowrap"><div {if $order==laston}class="prRActive {if $direction==asc}prRActive-top{else}prRActive-bottom{/if}"{/if}>
	  <a href="{$_url}{if $filter && $filterid}/filter/{$filter|escape}/filterid/{$filterid|escape}{/if}/order/laston/direction/{if $order==laston && $direction=='asc'}desc{else}asc{/if}/page/1/">{t}Last On{/t}</a></div></th>
	  <th nowrap="nowrap" colspan="3">&#160;</th>
</tr>
  {foreach item=u from=$usersList name='members'}
  <tr{if ($smarty.foreach.members.iteration % 2) == 0} class="prEvenBg"{else} class="prOddBg"{/if}>
	{view_factory entity='user' view='globalsearch' object=$u}
</tr>
  {/foreach}

</table>
<div class="prIndentTop">
	{$paging}
</div>
{else}
	<p class="prIndentBottom prText2">{t}There are no members in search results{/t}</p>
	<p>{t}Use the right utility to search again.{/t}</p>
	{t}{tparam value=$BASE_URL}{tparam value=$LOCALE}If you have a special question, please email <a href="%s/%s/info/contactus/">Contact Us.{/t}</a>
{/if}