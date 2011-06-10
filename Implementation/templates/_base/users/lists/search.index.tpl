{if $currentUser->getId() == $user->getId()}
	{t var='title'}My lists{/t}
{else}
	{assign var="title" value=$currentUser->getLogin()|escape:"html"|cat:"'s lists"}
{/if}

{if $Warecorp_List_AccessManager->canCreateLists($currentUser, $user)}
    <div style="margin: 6px -50px 0px 0px; float: right; display: inline; width: 200px;">
	{t var='button_01'}Make List{/t}
        {linkbutton name=$button_01 link=$currentUser->getUserPath('listsadd')}
    </div>
{/if}

        <!-- inner begin -->

<div class="prGrayBorder prInner">
<h2>{t}Search Lists{/t}</h2>
{include file="users/lists/search.form.tpl"}
{if $savedSearches}
<h3 class="prInnerTop">{t}Saved Searches}{/t}</h3>
	<div class="prInnerTop">
	{foreach item=s key=key from=$savedSearches name=savedSearches}
		<a href="{$_url}/listssearch/saved/{$key}/"{if !$smarty.foreach.savedSearches.first} class="prInnerLeft"{/if}>{if $s}{$s|escape:"html"}{else}{t}noname{/t}{/if}</a><a href="{$_url}/listssearchdel/id/{$key}/" >&nbsp;</a>
	{/foreach}
	</div>
{/if}
</div>
 <div>
	<h3>{t}By Type{/t}</h3>
	<ul class="prClr2 prInnerTop">
	{foreach from=$listTypes item=type name=listTypes}
		<li{if $smarty.foreach.listTypes.last}
			class="prFloatLeft"
			{else}
				{if $smarty.foreach.listTypes.first}
				class="prFloatLeft prIndentBottom"
				{else}
				class="prFloatLeft"
				{/if}
			{/if}>
		<a href="{$_url}/listssearch/preset/type/id/{$type.id}/">{$type.title}</a> {*({$type.lists_count})*}
	{/foreach}
	</ul>
  </div>
  <div>
	<h3>{t}By Creator{/t}</h3>
	<ul class="prClr2 prInnerTop">
		<li class="prFloatLeft prIndentBottom"><a href="{$_url}/listssearch/preset/friends/">{t}My Friend's Lists{/t}</a></li>
		<li class="prFloatLeft"><a href="{$_url}/listssearch/preset/groups/">{t}My Groups' Lists{/t}</a></li>
		<li class="prFloatLeft"><a href="{$_url}/listssearch/preset/families/">{t}My Group Families' Lists{/t}</a></li>
	 </ul>
  </div>
  <div>
	<h3>{t}By My Tags{/t}</h3>
	<div class="prInnerTop">
		<div>
		{foreach from=$tags key=key item=tag name=myTags}
			<a href="{$_url}/listssearch/preset/tag/id/{$key}/">{$tag|escape:"html"}</a>{if !$smarty.foreach.myTags.last} {/if}</li>
		{/foreach}
		</div>
	</div>
  </div>
  <div>
	<h3>{t}Lists From Top Countries{/t}</h3>
	<p class="prInnerTop">
		{foreach from=$topCountries item=country name=topCountries}
		<a href="{$_url}/listssearch/preset/country/id/{$country.country_id}/">{$country.country_name}</a>{if !$smarty.foreach.topCountries.last}, {/if}
		{/foreach}
	</p>
	<p class="prInnerTop">
	<a href="{$_url}/listssearch/view/allcountries/">{t}All Countries{/t}</a>
	</p>
  </div>
  <div>
	<h3>{t}Lists From Top World Cities{/t}</h3>
	<p class="prInnerTop">
		{foreach from=$topCities item=country name=topCities}
		<a href="{$_url}/listssearch/preset/city/id/{$country.city_id}/">{$country.city_name}</a>{if !$smarty.foreach.topCities.last}, {/if}
		{/foreach}
	</p>
	<p class="prInnerTop">
		<a href="{$_url}/listssearch/view/countries/">{t}All World Cities{/t}</a>
	</p>
  </div>
 <div>
	<h3>{t}List Categories{/t}</h3>

	<div class="prInnerTop">
		<table class="prFullWidth">
			<col width="33%" />
			<col width="33%" />
			<col width="33%" />
			{if $categories}
			<tr>
				{foreach item=c name='category' from=$categories}
					{assign var="iteration" value=$smarty.foreach.category.iteration}
					{if !(($iteration-1) % 3) && $iteration neq 1}</tr><tr>{/if}
					<td><a href="{$_url}/listssearch/preset/category/id/{$c->id}/">{$c->name}</a></td>
				{/foreach}
				{* Function adds td fields into table recursivly *}
				{print_td_recursive from=$iteration step=3 fill="&nbsp;"}
			</tr>
			{/if}
	  </table>
  </div>
</div>