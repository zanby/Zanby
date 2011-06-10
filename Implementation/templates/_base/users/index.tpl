<script type="text/javascript" src="{$JS_URL}/yui/yahoo/yahoo.js"></script>
<script type="text/javascript" src="{$JS_URL}/yui/event/event.js"></script>
<script type="text/javascript" src="{$JS_URL}/yui/dom/dom.js" ></script>
<script type="text/javascript" src="{$JS_URL}/yui/container/container.js"></script>
<script type="text/javascript">YAHOO.namespace("example.container");</script>

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
    {assign var="allCities" value="`$_url`/index/view/allcountries/`$categoryLocation`"}
{/if}

    <div class="prSubContentLeft">
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
                {Widget_GlobalSearch tags=$allUserTags}{/Widget_GlobalSearch}
              </div>


             {if $currentTab == 'world'}
              <h4 class="prLanding">{t}Top Countries{/t}</h4>
                {if $topCountries}
                  <p>
                    {foreach item=c key=k name=countries from=$topCountries}
                      <a href="{$BASE_URL}/{$LOCALE}/search/members/preset/new/keywords/country:'{$c.country_name}'/">{$c.country_name}</a>{if !$smarty.foreach.countries.last}, {/if}
                    {/foreach}
                  </p>
                {/if}
                <p><a href="{$_url}/index/view/allcountries/">{t}All Countries{/t}</a></p>
              {/if}
              {if $currentTab == 'world' || $currentTab == 'country' || $currentTab == 'state'}
                <h4 class="prLanding">Top {$locationString} Cities</h4>
                {if $topCities}
                  <p>
                    {foreach item=c key=k name=cities from=$topCities}
                      <a href="{$BASE_URL}/{$LOCALE}/search/members/preset/new/keywords/city:'{$c.city_name}'/">{$c.city_name}</a>{if !$smarty.foreach.cities.last}, {/if}
                    {/foreach}
                  </p>
                {/if}
              {/if}
              <h4 class="prLanding">{t}Group Categories{/t}</h4>
              <table class="prFullWidth" cellpadding="0" cellspacing="0" border="0">
                <tr>
                  {if $categories}
                    {if count($categories)>3}
                        {foreach key=id item=c name='category' from=$categories}
                            {if $smarty.foreach.category.iteration % (ceil(count($categories)/3)) == 1}
                                <td valign="top" width="33%">
                            {/if}
                            <a href="{$BASE_URL}/{$LOCALE}/search/members/preset/new/keywords/category:'{$c}'">{$c}</a><br />
                            {if $smarty.foreach.category.iteration % (ceil(count($categories)/3)) == 0}
                                </td>
                            {/if}
                        {/foreach}
                    {else}
                        {foreach key=id item=c name='category' from=$categories}
                            <td valign="top"><a href="{$BASE_URL}/{$LOCALE}/search/members/preset/new/keywords/category:'{$c}" >{$c}</a>&nbsp;&nbsp;</td>
                        {/foreach}
                    {/if}
                  {/if}
                </tr>
              </table>
    </div>

<div class="prSubContentRight">

        <h2>{t}Most Recent Members{/t}</h2>
        {if $isAuthenticated }
            {if $recentCityUsers}
            <h4 class="prLanding">{$city->name}</h4>
            <div class="">
                {foreach item=u name='user' from=$recentCityUsers}
                <a href="{$u->getUserPath('profile')}" id="img_{$u->getId()}"><img src="{$u->getAvatar()->setWidth(28)->setHeight(28)->setBorder(1)->getImage()}" title="" alt="" width="28" height="28" /></a>
                {capture name="tagscapture"}{foreach name=tags item=t from=$u->getTagsList()}{$t->name|escape:html}{if !$smarty.foreach.tags.last}, {/if}{/foreach}{/capture}
                <script>
                YAHOO.example.container.img_{$u->getId()}X = new YAHOO.widget.Tooltip("img_{$u->getId()}X", {$smarty.ldelim}hidedelay:100, width:200, context:"img_{$u->getId()}", text:"<b>{$u->getLogin()|escape:html}</b> from <b>{$u->getCity()->name}</b> <b>{$u->getState()->name}</b><br>Joined on {$u->getRegisterDate()|date_locale:'DATE_MEDIUM'}<br>{if $smarty.capture.tagscapture} With the tags: {$smarty.capture.tagscapture}{/if}"{$smarty.rdelim});
                </script>
                {/foreach}
               {* <br />
                <a href="{$_url}/search/preset/city/id/{$city->id}/">{t}{tparam value=$city->name}All members in %s{/t}</a> *}
            </div>
            {/if}
            {if $recentStateUsers}
            <h4 class="prLanding">{$state->name}</h4>
            <div class="">
                {foreach item=u name='user' from=$recentStateUsers}
                <a href="{$u->getUserPath('profile')}" id="img_{$u->getId()}"><img src="{$u->getAvatar()->setWidth(28)->setHeight(28)->setBorder(1)->getImage()}" title="" alt="" width="28" height="28" /></a>
                {capture name="tagscapture"}{foreach name=tags item=t from=$u->getTagsList()}{$t->name|escape:html}{if !$smarty.foreach.tags.last}, {/if}{/foreach}{/capture}
                <script>
                YAHOO.example.container.img_{$u->getId()}X = new YAHOO.widget.Tooltip("img_{$u->getId()}X", {$smarty.ldelim}hidedelay:100, width:200, context:"img_{$u->getId()}", text:"<b>{$u->getLogin()|escape:html}</b> from <b>{$u->getCity()->name}</b> <b>{$u->getState()->name}</b><br>Joined on {$u->getRegisterDate()|date_locale:'DATE_MEDIUM'}<br>{if $smarty.capture.tagscapture} With the tags: {$smarty.capture.tagscapture}{/if}"{$smarty.rdelim});
                </script>
                {/foreach}
                {* <br />
                <a href="{$_url}/search/preset/state/id/{$state->id}/">{t}{tparam value=$state->name}All members in %s{/t}</a> *}
            </div>
            {/if}
            {if $recentCountryUsers}
            <h4 class="prLanding">{$country->name}</h4>
            <div class="">
                {foreach item=u name='user' from=$recentCountryUsers}
                <a href="{$u->getUserPath('profile')}" id="img_{$u->getId()}"><img src="{$u->getAvatar()->setWidth(28)->setHeight(28)->setBorder(1)->getImage()}" title="" alt="" width="28" height="28" /></a>
                {capture name="tagscapture"}{foreach name=tags item=t from=$u->getTagsList()}{$t->name|escape:html}{if !$smarty.foreach.tags.last}, {/if}{/foreach}{/capture}
                <script>
                YAHOO.example.container.img_{$u->getId()}X = new YAHOO.widget.Tooltip("img_{$u->getId()}X", {$smarty.ldelim}hidedelay:100, width:200, context:"img_{$u->getId()}", text:"<b>{$u->getLogin()|escape:html}</b> from <b>{$u->getCity()->name}</b> <b>{$u->getState()->name}</b><br>Joined on {$u->getRegisterDate()|date_locale:'DATE_MEDIUM'}<br>{if $smarty.capture.tagscapture} With the tags: {$smarty.capture.tagscapture}{/if}"{$smarty.rdelim});
                </script>
                {/foreach}
                {*<br />
                <a href="{$_url}/search/preset/country/id/{$country->id}/">{t}{tparam value=$country->name}All members in %s {/t}</a> *}
            </div>
            {/if}
        {/if}
        {if $recentWorldUsers}
        {if $isAuthenticated }
            <h4 class="prLanding">{t}The World{/t}</h4>
        {/if}
        <div class="">
            {foreach item=u name='user' from=$recentWorldUsers}
            <a href="{$u->getUserPath('profile')}" id="img_{$u->getId()}"><img src="{$u->getAvatar()->setWidth(28)->setHeight(28)->setBorder(1)->getImage()}" title="" alt="" width="28" height="28" /></a>
            {capture name="tagscapture"}{foreach name=tags item=t from=$u->getTagsList()}{$t->name|escape:html}{if !$smarty.foreach.tags.last}, {/if}{/foreach}{/capture}
            <script>
            YAHOO.example.container.img_{$u->getId()}X = new YAHOO.widget.Tooltip("img_{$u->getId()}X", {$smarty.ldelim}hidedelay:100, width:200, context:"img_{$u->getId()}", text:"<b>{$u->getLogin()|escape:html}</b> from <b>{$u->getCity()->name}</b> <b>{$u->getState()->name}</b><br>Joined on {$u->getRegisterDate()|date_locale:'DATE_MEDIUM'}<br>{if $smarty.capture.tagscapture} With the tags: {$smarty.capture.tagscapture}{/if}"{$smarty.rdelim});
            </script>
            {/foreach}
            {*<br />
            <a href="{$_url}/search/preset/new/">{t}All members{/t}</a> *}
        </div>
        {/if}
    {if IMPLEMENTATION_TYPE == 'EIA' && $objGlobalGroup}
            <div class="prIndentTop"><a href="{$objGlobalGroup->getGroupPath('familymembers.all')}">All Members</a></div>
    {/if}
</div>
