{if $currentTab == 'city' && $city->id}
    {assign var="categoryLocation" value="city/`$city->id`/"}
    {assign var="locationString" value="`$city->name`"}
    {assign var="allCities" value=""}
{elseif $currentTab == 'state' && $state->id}
    {assign var="categoryLocation" value="state/`$state->id`/"}
    {assign var="locationString" value="`$state->name`"}
    {assign var="allCities" value="`$_url`/index/view/allcities/`$categoryLocation`"}
{elseif $currentTab == 'country' && $country->id}
    {assign var="categoryLocation" value="country/`$country->id`/"}
    {assign var="locationString" value="`$country->name`"}
    {assign var="allCities" value="`$_url`/index/view/allstates/`$categoryLocation`"}
{else}
    {assign var="categoryLocation" value="world/1/"}
    {assign var="locationString" value="World"}
    {assign var="allCities" value="`$_url`/index/view/allcities/`$categoryLocation`"}
{/if}
<div class="prSubContentLeft">
      <!--h4>Search</h4-->
      {form from=$form}
      {if $currentTab == 'city' && $city->id}
        {form_hidden name="country" value=$country->id}
        {form_hidden name="state" value=$state->id}
        {form_hidden name="city" value=$city->id}
      {elseif $currentTab == 'state' && $state->id}
        {form_hidden name="country" value=$country->id}
        {form_hidden name="state" value=$state->id}
      {elseif $currentTab == 'country' && $country->id}
        {form_hidden name="country" value=$country->id}
      {/if}
      {/form}
      <div class="prIndentTop">
        {Widget_GlobalSearch tags=$allGroupTags}{/Widget_GlobalSearch}
      </div>

      {if $currentTab == 'world'}
      <h4 class="prLanding">{t}Top Countries{/t}</h4>
        {if $topCountries}
          <p>
            {foreach item=c key=k name=countries from=$topCountries}
              <a href="{$BASE_URL}/{$LOCALE}/search/groups/preset/new/keywords/country:'{$c.country_name}'/">{$c.country_name}</a>{if !$smarty.foreach.countries.last}, {/if}
            {/foreach}
          </p>
        {/if}
      {*<a href="{$_url}/index/view/allcountries/">{t}All Countries{/t}</a>*}
      {/if}
      {if $currentTab == 'world' || $currentTab == 'country' || $currentTab == 'state'}
        <h4 class="prLanding">{t}{tparam value=$locationString}Top %s Cities{/t}</h4>
        {if $topCities}
          <p>
            {foreach item=c key=k name=cities from=$topCities}
              <a href="{$BASE_URL}/{$LOCALE}/search/groups/preset/new/keywords/city:'{$c.city_name}'/">{$c.city_name}</a>{if !$smarty.foreach.cities.last}, {/if}
            {/foreach}
         </p>
        {/if}
     {* <a href="{$allCities}">{t}{tparam value=$locationString}All %s Cities{/t}</a> *}
      {/if}
      <h4 class="prLanding">{t}Group Categories{/t}</h4>
      <table class="prFullWidth" cellpadding="0" cellspacing="0" border="0">
        <tr>
          {if $categories}
            {if count($categories)>3}
                {foreach key=id item=c name='category' from=$categories}
                    {if $smarty.foreach.category.iteration % (ceil(count($categories)/3)) == 1}<td valign="top">{/if}
                    <a href="{$BASE_URL}/{$LOCALE}/search/groups/preset/new/keywords/category:'{$c}'/">{$c}</a><br />
                    {if $smarty.foreach.category.iteration % (ceil(count($categories)/3)) == 0}</td>{/if}
                {/foreach}
            {else}
                {foreach key=id item=c name='category' from=$categories}
                    <td valign="top"><a href="{$BASE_URL}/{$LOCALE}/search/groups/preset/new/keywords/category:'{$c}'/">{$c}</a>&nbsp;&nbsp;</td>
                {/foreach}
            {/if}
          {/if}
        </tr>
      </table>
</div>
<div class="prSubContentRight">
	<div class="prTRight">
  		<a href="{$BASE_URL}/{$LOCALE}/newgroup/"><img alt="Start Group" src="{$AppTheme->images}/buttons/startGroup.gif" /></a>
  	</div>
</div>