{if $currentUser->getId() == $user->getId()}
{t var="title"}My Events{/t}
{else}{assign var="title" value=$currentUser->getLogin()|escape:"html"|cat:"'s events"}{/if}

{if $currentUser->getId() == $user->getId()}
	{assign var="addLink" value=$currentUser->getUserPath('calendar.event.create')}
{/if}


	{if $viewMode == 'active'}
		{assign var="activeMenuItem" value="list"}
	{else}
		{assign var="activeMenuItem" value="expired"}
	{/if}
          <div class="prInner prClr2">
            <div class="prInner">
              <h2>{t}Search Events{/t}</h2>
              {form class="prInnerTop" from=$form id="search_events"}
              {form_hidden name="preset" value="new"}
              <table class="prForm">
					<tbody>
						<tr>
						  <td><label for="keywords">{t}<span>Keyword</span> or Tag separated by comma{/t}</label>
							{form_text id="keywords" name="keywords" value=$keywords|escape}
						  </td>
						</tr>
						<tr>
						  <td><label for="where">{t}<span>Where </span> &ndash; City, State, Country{/t}</label>
							{form_text id="where" name="where" value=$where|escape}
						  </td>
						</tr>
						<tr>
						  <td><label for="when">{t}<span>When </span> &ndash; any date{/t}</label>
							{form_text id="when" name="when" value=$when|escape}
						  </td>
						</tr>
						<tr>
						  <td>
							<div class="prInnerSmallTop prTRight">
							{t var='button_01'}Search{/t}
							{linkbutton name=$button_01 onclick="document.forms['search_events'].submit(); return false;"}
							</div>
						  </td>
						</tr>
					</tbody>
			  </table>
              {/form}
			  	{if $savedSearches}
					<h3>{t}Saved Searches:{/t}</h3>
					<div class="prInnerTop">
					  {foreach item=s key=key from=$savedSearches name=savedSearches}
					   <a href="{$_url}/calendarsearch/saved/{$key}/" {if !$smarty.foreach.savedSearches.first} class="prInnerLeft"{/if}>{if $s}{$s|escape}{else}{t}noname{/t}{/if}</a> <a href="{$_url}/calendarsearchdel/id/{$key}/"  >&nbsp;</a>
					  {/foreach}
					 </div>
				{/if}
            </div>
          <h3 class="prIndentTop">{t}See events...{/t}</h3>
          <ul class="prClr2">
            <li class="prFloatLeft prInnerRight"><a href="{$_url}/calendarsearch/preset/date/id/today/">{t}Today{/t} </a> </li>
            <li class="prFloatLeft"><a href="{$_url}/calendarsearch/preset/date/id/week/">{t}This Week{/t}</a> </li>
            <li class="prFloatLeft"><a href="{$_url}/calendarsearch/preset/date/id/nweek/">{t}Next Week{/t}</a> </li>
            <li class="prFloatLeft"><a href="{$_url}/calendarsearch/preset/date/id/month/">{t}This month{/t}</a> </li>
            <li class="prFloatLeft"><a href="{$_url}/calendarsearch/preset/date/id/future/">{t}All future{/t}</a> </li>
          </ul>
            {if $categories}
            <h3 class="prIndentTop">{t}Events Categories{/t}</h3>
            <table cellpadding="0" cellspacing="0" border="0">
            <col width="33%" />
            <col width="33%" />
            <col width="34%" />
            <tr>
                    {if (count($categories)+1)>3}
                        {foreach key=id item=c name='category' from=$categories}
                            {if $smarty.foreach.category.first}<td><a href="{$_url}/calendarsearch/preset/worldwide/">{t}Worldwide{/t}</a><br /> {/if}
                            {if ($smarty.foreach.category.iteration+1) % $catOnCol == 1}<td>{/if}
                            <a href="{$_url}/calendarsearch/preset/category/id/{$c.id}/">{$c.name}</a><br />
                            {if ($smarty.foreach.category.iteration+1) % $catOnCol == 0}</td>{/if}
                        {/foreach}
                    {else}
                        {foreach key=id item=c name='category' from=$categories}
                            {if $smarty.foreach.category.first}<td><a href="{$_url}/calendarsearch/preset/worldwide/">{t}Worldwide{/t}</a></td> {/if}
                            <td><a href="{$_url}/calendarsearch/preset/category/id/{$c.id}/">{$c.name}</a></td>
                        {/foreach}
                    {/if}
            </tr>
            </table>
            {/if}
          <h3 class="prIndentTop">{t}Events By Country{/t}</h3>
          <p>
            <a href="{$_url}/calendarsearch/preset/worldwide/">{t}Worldwide{/t}</a>{if count($topCountries)}, {/if}
            {foreach from=$topCountries item=country name=topCountries}
                <a href="{$_url}/calendarsearch/preset/country/id/{$country.country_id}/">{$country.country_name}</a>{if !$smarty.foreach.topCountries.last}, {/if}
            {/foreach}
          </p>
		  <p class="prInnerTop">
          {if $topCountriesExists}
          	<a href="{$_url}/calendarsearchindex/view/allcountries/">{t}All Countries{/t}</a>
          {/if}
		  </p>

          {if $topCitiesExists}
		  <h3 class="prIndentTop">{t}Events In Top World Cities{/t}</h3>
          <p>
            {foreach from=$topCities item=city name=topCities}
                <a href="{$_url}/calendarsearch/preset/city/id/{$city.city_id}/">{$city.city_name}</a>{if !$smarty.foreach.topCities.last}, {/if}
            {/foreach}
          </p>
          <p class="prInnerTop">
		  	<a href="{$_url}/calendarsearchindex/view/allcities/">{t}All World Cities{/t}</a>
		  </p>
          {/if}

            {if $tags}
            <h3 class="prInnerTop">{t}By My Tags{/t}</h3>
            <div class="prInnerTop">
                {foreach from=$tags key=key item=tag name=myTags}
                    <a href="{$_url}/calendarsearch/preset/tag/id/{$key}/">{$tag|escape:"html"}</a>{if !$smarty.foreach.myTags.last} {/if}</li>
                {/foreach}
            </div>
            {/if}

          </div>
          <!-- -->
          <div class="prInnerSmall">
            <!--h1>Calendar</h1-->
            {if $user && $user->getId() !== null}
            <div class="prEvents-calendar prEvents-calendar-small">
        	<div class="prTCenter"><a href="{$user->getUserPath('calendarsearchindex')}year/{$objPrevDate->toString('yyyy')}/month/{$objPrevDate->toString('MM')}/">&laquo;</a>&nbsp;{$objCurrDate->toString('MMMM')}, {$objCurrDate->toString('yyyy')}&nbsp;<a href="{$user->getUserPath('calendarsearchindex')}year/{$objNextDate->toString('yyyy')}/month/{$objNextDate->toString('MM')}/">&raquo;</a></div>
                <!-- / -->
                <!-- -->
                {foreach from=$objYear->getMonths() item=month}
				<div class="prInnerTop">
                <table cellspacing="0" cellpadding="0">
                        <thead>
                            {foreach from=$month->getWeekdaysHeader('FULL') item=wh}
                                <th class="prTCenter">{$wh}</th>
                            {/foreach}
                        </thead>
                        <tbody>
                        {foreach from=$month->getWeeks() key=weekNo item=week}
                            <tr>
                                {foreach from=$week->getDays() item=day}
                                    {assign var='strDate' value=$day->getDateAsString()}
                                    {assign var='tdStyle' value=''}
                                    {if $day->getMonth() != $month->getMonth()}
                                        {if $arrDates[$strDate]}{assign var='tdStyle' value='prPreviewMonth prPreviewMonth-active'}{*   *}
                                        {else}{assign var='tdStyle' value='prPreviewMonth'}{*   *}
                                        {/if}
                                    {else}
                                        {if $strDate < $objDateNow->toString('yyyy-MM-dd')}
                                            {assign var='tdStyle' value='prCurrentMonth-preview'}
                                        {else}
                                            {if $arrDates[$strDate]}
                                                {if $strDate != $objDateNow->toString('yyyy-MM-dd')}{assign var='tdStyle' value='prCurrentMonth-event'}
                                                {else}{assign var='tdStyle' value='prCurrentMonth-event prCurrentMonth-active'}
                                                {/if}
                                            {else}
                                                {if $strDate != $objDateNow->toString('yyyy-MM-dd')}{assign var='tdStyle' value='prCurrentMonth'}
                                                {else}{assign var='tdStyle' value='prCurrentMonth prCurrentMonth-active'}
                                                {/if}
                                            {/if}
                                        {/if}
                                    {/if}
                               <td class="{$tdStyle}">
                                    <div class="prEventsDaySmallBlock">

                                        	<span style="cursor: pointer;" onclick='location.href="{$user->getUserPath('calendarsearchindex')}day/{$day->getDay()}/year/{$objCurrDate->toString('yyyy')}/month/{$objCurrDate->toString('MM')}/";'>{$day->getDay()}</span>
                                    </div>
                               </td>
                            {/foreach}
                            </tr>
                         {/foreach}
                        </tbody>
                    </table>
					</div>
                    {/foreach}
                <!-- / -->
            </div>
            {/if}
            <!-- /CALENDAR -->
            <table cellpadding="0" cellspacing="0" border="0">
              <col width="30%" />
              <col />
              <tr>
                <th colspan="2"><h3 class="prIndentTop">{$daysList.date}:</h3></th>
              </tr>
              <!-- -->
              {if $user && $user->getId() !== null}
              <tr>
                <td colspan="2"><h4 class="prIndentTop"><a href="{$user->getUserPath('calendarsearch/filter/owner/filterid/friends/preset/section')}">{t}My Friend's events{/t}</a></h4></td>
              </tr>
              {foreach item=event name='friends' from=$eventsList.friends}
                    <tr>
                		<td class="prInnerSmall">
                            {if $event->getPictureId()}<img align="left" class="prIndentRight"  src="{$event->getEventPicture()->setWidth(37)->setHeight(37)->getImage($user)}">{else}<img class="prIndentRight" src="{$AppTheme->images}/decorators/fakeImage.gif" align="left" />{/if}
                        </td>
                		<td class="prInnerSmall">
                            <a href="{$event->entityURL()}">{$event->getTitle()}</a>
                            <br> {$event->displayDate('search.index.events', $user, $currentTimezone)} <br>
                            <a href="{$event->getOwner()->getUserPath('profile')}">{$event->getOwner()->getLogin()}</a>
                        </td>
              </tr>
	          {foreachelse}
	                <tr>
                		<td colspan="2">{t}No Friend's events at all{/t}</td></tr>
	          {/foreach}
              {/if}
              <!-- -->
              {if $user && $user->getId() !== null}
              <tr>
                <td colspan="2"><h4 class="prIndentTop"><a href="{$user->getUserPath('calendarsearch/filter/owner/filterid/families/preset/section')}">{t}Group Family events{/t}</a></h4></td>
              </tr>
              {foreach item=event name='families' from=$eventsList.families}
		            <tr>
                		<td class="prInnerSmall">
                            {if $event->getPictureId()}<img align="left" class="prIndentRight"  src="{$event->getEventPicture()->setWidth(37)->setHeight(37)->getImage($user)}">{else}<img class="prIndentRight" src="{$AppTheme->images}/decorators/fakeImage.gif" align="left" />{/if}
                        </td>
                		<td class="prInnerSmall">
                            <a href="{$event->entityURL()}">{$event->getTitle()}</a>
                            <br> {$event->displayDate('search.index.events', $user, $currentTimezone)} <br>
                            <a href="{$event->getOwner()->getGroupPath('summary')}">{$event->getOwner()->getName()}</a>
						</td>
              </tr>
	          {foreachelse}
	                <tr>
                		<td colspan="2">{t}No Family's events at all{/t}</td></tr>
	          {/foreach}
              {/if}
              <!-- -->
              {if $user && $user->getId() !== null}
              <tr>
                <td colspan="2"><h4 class="prIndentTop"><a href="{$user->getUserPath('calendarsearch/filter/owner/filterid/groups/preset/section')}">{t}Group events{/t}</a></h4></td>
              </tr>
              {foreach item=event name='groups' from=$eventsList.groups}
		            <tr>
                		<td class="prInnerSmall">
                            {if $event->getPictureId()}<img class="prIndentRight" align="left"  src="{$event->getEventPicture()->setWidth(37)->setHeight(37)->getImage($user)}">{else}<img class="prIndentRight" src="{$AppTheme->images}/decorators/fakeImage.gif" align="left" />{/if}
                        </td>
                		<td class="prInnerSmall">
                            <a href="{$event->entityURL()}">{$event->getTitle()}</a>
							<br> {$event->displayDate('search.index.events', $user, $currentTimezone)} <br>
                            <a href="{$event->getOwner()->getGroupPath('summary')}">{$event->getOwner()->getName()}</a>
						</td>
              </tr>
	          {foreachelse}
	                <tr>
                		<td colspan="2">{t}No Group's events at all{/t}</td></tr>
	          {/foreach}
              {/if}
              <!-- -->
              {if $user && $user->getId() !== null}
              <tr>
                <td colspan="2">
					<h4 class="prIndentTop"><a href="{$user->getUserPath('calendarsearch/filter/owner/filterid/other/preset/section')}">{t}Other events{/t}</a></h4>
				</td>
              </tr>
              {/if}
					{foreach class="prInnerTop" item=event name='other' from=$eventsList.other}
					<tr>
                		<td class="prInnerSmall">
                  			{if $event->getPictureId()}<img  src="{$event->getEventPicture()->setWidth(37)->setHeight(37)->getImage($user)}">{else}<img src="{$AppTheme->images}/decorators/fakeImage.gif"/>{/if}
                        </td>
						<td class="prInnerSmall">
							{if $event->getOwner()->EntityTypeName == 'user'}
                            	<a href="{$event->entityURL()}">{$event->getTitle()}</a>
                            {else}
								<a href="{$event->entityURL()}">{$event->getTitle()}</a>
                            {/if}
                            <br> {$event->displayDate('search.index.events', $user, $currentTimezone)} <br>
							{if $event->getOwner()->EntityTypeName == 'user'}
                            	<a href="{$event->getOwner()->getUserPath('profile')}">{$event->getOwner()->getLogin()}</a>
                            {else}
								<a href="{$event->getOwner()->getGroupPath('summary')}">{$event->getOwner()->getName()}</a>
                            {/if}
						</td>
              </tr>
	          {foreachelse}
	                <tr>
                		<td colspan="2">
							<div>{t}No events at all{/t}</span>
						</td>
					</tr>
	          {/foreach}
            </table>
  		</div>
	</div>  <!-- new -->
