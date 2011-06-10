{literal}
	<script>
        var cfgSearchApplication = null;
        if ( !cfgSearchApplication ) {
            cfgSearchApplication = function () {
                return {
                    hListAddToMy : '{/literal}{$BASE_URL}/{$LOCALE}/search/listAddToMy/{literal}'
                }
            }();
        };
    </script>
{/literal}
<script type="text/javascript" src="{$AppTheme->common->js}/modules/search/init.js"></script>

{if $keywords}<h2>{t}{tparam value=$SITE_NAME_AS_STRING} %s Lists About {/t} {$keywords|wordwrap:25:"\n":true|escape}</h2>{/if}
{if $searchTitle}<h2>{$searchTitle|escape}</h2>{/if}
{if $listsList}
   	{$paging}
	<table cellpadding="0" cellspacing="0" border="0"  class="prResult">
	  <col width="5%"/>
	  <col width="50%"/>
	  <col width="15%"/>
	  {*<col width="15%"/>*}
	  <col width="15%"/>
	  <col width="15%"/>
	  <thead>
		  <tr>
			<th colspan="2"><div {if $order==title}class="{if $direction==asc}prRActive-top{else}prRActive-bottom{/if}"{/if}><a href="{$_url}{if $filter}/filter/{$filter|escape}{/if}/preset/new/order/title/direction/{if $order==title && $direction=='asc'}desc{else}asc{/if}/page/1/"> {t}Title{/t}</a></div> </th>
			{*<th><div {if $order==author}class="{if $direction==asc}prRActive-top{else}prRActive-bottom{/if}"{/if}><a href="{$_url}{if $filter}/filter/{$filter|escape}{/if}/preset/new/order/author/direction/{if $order==author && $direction=='asc'}desc{else}asc{/if}/page/1/">{t}Author{/t}</a></div></th> *}
			<th><div {if $order==created}class="{if $direction==asc}prRActive-top{else}prRActive-bottom{/if}"{/if}><a href="{$_url}{if $filter}/filter/{$filter|escape}{/if}/preset/new/order/created/direction/{if $order==created && $direction=='asc'}desc{else}asc{/if}/page/1/">{t}Created{/t}</a></div></th>
			<th><div {if $order==items}class="{if $direction==asc}prRActive-top{else}prRActive-bottom{/if}"{/if}><a href="{$_url}{if $filter}/filter/{$filter|escape}{/if}/preset/new/order/items/direction/{if $order==items && $direction=='desc'}asc{else}desc{/if}/page/1/">{t}Items{/t}</a></div></th>
			<th>&#160;</th>
		  </tr>
	  </thead>
	  {foreach item=l from=$listsList name=listsList}
		  <tr{if ($smarty.foreach.listsList.iteration % 2) == 0} class="prEvenBg"{else} class="prOddBg"{/if}>
			{view_factory entity='list' view='globalsearch' object=$l user=$user}
		  </tr>
	  {/foreach}
	  </tbody>
	</table>
	<div class="prIndentTop">
		{$paging}
	</div>
{else}
	<p class="prIndentBottom prText2">{t}There are no lists in search results{/t}</p>
	<p>{t}Use the right utility to search again.{/t}</p>
	{t}If you have a special question, please email{/t} <a href="{$BASE_URL}/{$LOCALE}/info/contactus/">{t}Contact Us.{/t}</a>
{/if}