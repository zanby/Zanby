<table width="100%" border="0">
  <tr>
    <td valign="top"><table width="400">
        <tr>
          <td>{$SITE_NAME_AS_STRING} {t}Tour{/t} </td>
        </tr>
        <tr>
          <td>
		  {if $currentTab == "city"}
		  <b>{$city->name}</b>
		  {else}
		  <a href="/{$LOCALE}/groups/index/view/city/">{$city->name}</a> 
		  {/if}:: 
 		  {if $currentTab == "state"}
		  <b>{$state->name}</b>
		  {else}
		  <a href="/{$LOCALE}/groups/index/view/state/">{$state->name}</a> 
		  {/if}:: 
  		  {if $currentTab == "country"}
		  <b>{$country->name}</b>
		  {else}		  
		  <a href="/{$LOCALE}/groups/index/view/country/">{$country->name}</a>
		  {/if} :: 
  		  {if $currentTab == "world"}
		  <b>{t}World{/t}</b>
		  {else}
		  <a href="/{$LOCALE}/groups/index/view/world/">{t}World{/t}</a>
		  {/if}
		  
		  </td>
        </tr>
        <tr>
          <td> {t}Search:{/t} <br>
            {t}Enter keyword or Zip code:{/t}
            <input type="text">
            <input type="button" value="{t}Search{/t}" onclick="document.location='/{$LOCALE}/groups/search/';">
          </td>
        </tr>
        <tr>
          <td>
		  
		          {foreach item=m key=k name=gtags from=$allGroupTags}
                    <a href="/{$LOCALE}/groups/search/" class="
					{if $m > 85}tag5
					{elseif $m> 70}tag4
					{elseif $m> 55}tag3
					{elseif $m> 40}tag2
					{elseif $m> 25}tag1
					{else}tag0{/if}
					">{$k}</a>
					
					
					{if !$smarty.foreach.gtags.last}, {/if}
                {/foreach}
		  
		  </td>
        </tr>
	   <tr>
          {if $currentTab != "city"}
		  
			  {if $currentTab == "state"}
		  
			  <td>{t}{tparam value=$state->name}Top %s cities: {/t}<br><br>
			  {t}TOP:{/t}
			  {if $topCities}
			  	{foreach item=c key=k name=cities from=$topCities}
					<a href="/{$LOCALE}/groups/browse/city/{$c.city_id}/">{$c.city_name}</a> {if !$smarty.foreach.cities.last}, {/if}
				{/foreach}
			  
			  {/if}
			  {t}:TOP{/t}<br><br>
			  <a href="/{$LOCALE}/groups/index/view/allcities/state/{$state->id}/">{t}{tparam value=$state->name}All %s Cities{/t}</a></td>
			  {/if}
			  
			  {if $currentTab == "country"}
		  
			  <td>{t}{tparam value=$country->name}Top %s states: {/t}<br><br>
			  <br>
			  {if $topCities}
			  	{foreach item=s key=k name=states from=$topStates}
					<a href="/{$LOCALE}/groups/search/">{$s.state_name}</a> {if !$smarty.foreach.states.last}, {/if}
				{/foreach}
			  {/if}
			  <br>
			  <a href="/{$LOCALE}/groups/index/view/allstates/country/{$country->id}/">{t}{tparam value=$country->name}All %s States{/t}</a><br><br>

			  {t}{tparam value=$country->name}Top %s cities:{/t} <br>
			  <br>
			  {if $topCities}
			  	{foreach item=c key=k name=cities from=$topCities}
					<a href="/{$LOCALE}/groups/browse/city/{$c.city_id}/">{$c.city_name}</a> {if !$smarty.foreach.cities.last}, {/if}
				{/foreach}
			  {/if}
				<br>

			  <a href="/{$LOCALE}/groups/index/view/allstates/country/{$country->id}/">{t}{tparam value=$country->name}All %s Cities{/t}</a>
			  
			  </td>
			  {/if}
			  
			  {if $currentTab == "world"}
		  
			  <td>{t}Top Countries:{/t} <br><br>
			  {if $topCountries}
			  	{foreach item=c key=k name=countries from=$topCountries}
					<a href="/{$LOCALE}/groups/search/">{$c.country_name}</a> {if !$smarty.foreach.countries.last}, {/if}
				{/foreach}
			  {/if}
			  
			  <br><br>
			  <a href="/{$LOCALE}/groups/index/view/allcountries/">{t}All Countries{/t}</a><br><br>

			  {t}Top World cities:{/t} <br><br>
			  			  {if $topCities}
			  	{foreach item=c key=k name=cities from=$topCities}
					<a href="/{$LOCALE}/groups/browse/city/{$c.city_id}/">{$c.city_name}</a> {if !$smarty.foreach.cities.last}, {/if}
				{/foreach}
			  {/if}
			  <br><br>
			  <a href="/{$LOCALE}/groups/index/view/allcountries/">{t}All World Cities{/t}</a>
			  
			  </td>
			  {/if}
			  
		  {/if}
        </tr>
		<tr>
          <td>
		  {t}Groups Categories:{/t}

<table border="1">
<tr>
			{foreach item=c name='category' from=$categories}
		 {if $smarty.foreach.category.iteration % 7 == 1}<td>{/if}	


			<a href="/{$LOCALE}/groups/search/">{$c->name}</a> <br>
					 {if $smarty.foreach.category.iteration % 7 == 0}</td>{/if}	
			{/foreach}
	</tr>		
			</table>
		  </td>
        </tr>
      </table></td>
    <td><table width="100" border="0">
        <tr>
          <td><a href="/{$LOCALE}/newgroup/step1/">{t}Start a group{/t}</a><br />
            <a href="/{$LOCALE}/newfamilygroup/step0/">{t}Start a Group Family{/t}</a> </td>
        </tr>
        <tr>
          <td>{t}Group Families: {/t}<br />
            <br />
			{foreach item=g name='family' from=$lastFamily}
			
			<a href="{$g->getPath()}{$LOCALE}/summary/">{$g->getName()}</a><br>
			
			{/foreach}
			
            <br />
            <br />
            <a href="/{$LOCALE}/groups/search/">{t}Browse Group Families{/t}</a><br>
            <a href="/{$LOCALE}/newfamilygroup/step0/">{t}Start a group Family{/t}</a> 
        </tr>
      </table></td>
  </tr>
</table>
