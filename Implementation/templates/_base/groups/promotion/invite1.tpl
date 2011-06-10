<!-- tabs2 slave area begin -->
<h2>{t}Invite Groups to join family (Step 1 of 2){/t}</h2>
<div class="prDropBox prDropBoxInner">
	<div class="prDropHeader">
		<h2>{t}Step 1: Select groups to invite{/t}</h2>
	</div>
	<div class="prHeaderHelper">{t}{tparam value=$CurrentGroup->getGroupPath('invitelist')}This tool will search all groups not currently a member of your family<br />
		OR <a href="%sfolder/sent/">groups you have already invited</a>.{/t}</div>
	<div class="prIndentTop"> {include file="groups/promotion/search.form.tpl"} </div>
</div>
{*<h3>{t}Top Family Tags{/t}</h3>
{foreach item=m key=k from=$topTags}
				{if $m>80} <a style="font-size:1.4em" href="{$CurrentGroup->getGroupPath('invitesearch/preset/tag/tname')}{$k|escape:html}/">{$k|escape:html}</a> {elseif $m>60} <a style="font-size:1.2em" href="/{$LOCALE}/invitesearch/preset/tag/tname/{$k|escape:html}/">{$k|escape:html}</a> {elseif $m>40} <a href="/{$LOCALE}/invitesearch/preset/tag/tname/{$k|escape:html}/">{$k|escape:html}</a> {elseif $m>20} <a style="font-size:0.9em" href="/{$LOCALE}/invitesearch/preset/tag/tname/{$k|escape:html}/">{$k|escape:html}</a> {else} <a style="font-size:0.8em" href="/{$LOCALE}/invitesearch/preset/tag/tname/{$k|escape:html}/">{$k|escape:html}</a> {/if} 
			{/foreach}
            *}
<h3>{t}Top Countries{/t}</h3>
{if $topCountries}
								  
{foreach item=c key=k name=countries from=$topCountries} 
<a href="{$CurrentGroup->getGroupPath('invitesearch/preset/country/id')}{$c.country_id}/">{$c.country_name}</a>{if !$smarty.foreach.countries.last}, {/if}
{/foreach}
									
				{/if}
<h3>{t}Top World Cities{/t}</h3>
{if $topCities}
{foreach item=c key=k name=cities from=$topCities}
<a href="{$CurrentGroup->getGroupPath('invitesearch/preset/city/id')}{$c.city_id}/">{$c.city_name}</a>{if !$smarty.foreach.cities.last}, {/if}
{/foreach}
{/if}
<h3>{t}Group Categories{/t}</h3>
<div class="prClr2">
	<table cellspacing="0" cellpadding="0" border="0" class="prFullWidth">
		<col width="33%" />
		<col width="33%" />
		<col width="33%" />
		<tr> {if $allCategories}
			{if count($allCategories)>3}
			{foreach key=id item=c name='category' from=$allCategories}
			{if $smarty.foreach.category.iteration % (ceil(count($allCategories)/3)) == 1}
			<td valign="top"><ul class="prFloatLeft">
					{/if}
					<li><a href="{$CurrentGroup->getGroupPath('invitesearch/preset/category/id')}{$id}/{$categoryLocation}">{$c}</a></li>
					{if $smarty.foreach.category.iteration % (ceil(count($allCategories)/3)) == 0}
				</ul></td>
			{/if}
			{/foreach}
			{else}
			{foreach key=id item=c name='category' from=$allCategories}
			<td><ul class="prFloatLeft">
					<li><a href="{$CurrentGroup->getGroupPath('invitesearch/preset/category/id')}{$id}/{$categoryLocation}">{$c}</a></li>
				</ul></td>
			{/foreach}
			{/if}
			{/if} </tr>
	</table>
</div>
