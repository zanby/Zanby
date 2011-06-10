{literal}
    <script src = "/js/yui/yahoo/yahoo.js" ></script>
    <script src = "/js/yui/event/event.js" ></script>
	<script src = "/js/yui/dom/dom.js" ></script>
	<script src = "/js/yui/animation/animation.js" ></script>
	<script src="/js/discussion/topic.js"></script>
    <script src="/js/discussion/bbcode.js"></script>
{/literal}
{if $keywords}<h2>{t}{tparam value=$SITE_NAME_AS_STRING} %s Discussions About {/t} {$keywords|wordwrap:25:"\n":true|escape}</h2>{/if}
{if $searchTitle}<h2>{$searchTitle|escape}</h2>{/if}
{if $results}
	{$paging}
	<table class="prResult" cellspacing="0" cellpadding="0">
		{foreach from=$results name='results' item=post}
			<tr{if ($smarty.foreach.results.iteration % 2) != 0} class="prEvenBg"{else} class="prOddBg"{/if}>
			   {view_factory entity='discussion' view='globalsearch' object=$post user=$user}
			</tr>
		{/foreach}
	</table>
	<div class="prIndentTop">
		{$paging}
	</div>
{else}
	<p class="prIndentBottom prText2">{t}There are no discussions in search results{/t}</p>
	<p>{t}Use the right utility to search again.{/t}</p>
	{t}If you have a special question, please email{/t} <a href="{$BASE_URL}/{$LOCALE}/info/contactus/">{t}Contact Us.{/t}</a>
{/if}