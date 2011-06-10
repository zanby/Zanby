<script type="text/javascript">
    var params = [];
    {foreach from=$params item=item key=key}
        params["{$key}"] ="{$item|escape}";
    {foreachelse}
        params['garbage'] ="garbage";
    {/foreach}
</script>

{if $currentUser->getId() == $user->getId()}{assign var="title" value="My Events"}
{else}{assign var="title" value=$currentUser->getLogin()|escape:"html"|cat:"'s events"}{/if}

{if $currentUser->getId() == $user->getId()}
	{assign var="addLink" value=$currentUser->getUserPath('calendar.event.create')}
{/if}


	{if $viewMode == 'active'}
		{assign var="activeMenuItem" value="list"}
	{else}
		{assign var="activeMenuItem" value="expired"}
	{/if}

	<div class="prInnerSmall prClr2">
        <div>
		  		<div class="prGrayBorder prInner">
           			<h2>{t}Search Events{/t}</h2>

              	{form from=$form id="search_events"}
              	{form_hidden name="preset" value="new"}
              <table class="prForm prInnerTop">
                <tbody><tr>
                  <td><label for="keywords">{t}<strong>Keyword</strong> or Tag separated by comma{/t}</label>
                    {form_text id="keywords" name="keywords" value=$keywords|escape class="prIndentTopSmall"}
                  </td>
                </tr>
                <tr>
                  <td><label for="where">{t}<strong>Where</strong> &ndash; City, State, Country{/t}</label>
                    {form_text id="where" name="where" value=$where|escape class="prIndentTopSmall"}
                  </td>
                </tr>
                <tr>
                  <td><label for="when">{t}<strong>When</strong> &ndash; any date{/t}</label>
                    {form_text id="when" name="when" value=$when|escape class="prIndentTopSmall"}
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
              </tbody></table>
              {/form}

              {form from=$formRemember id="formRemember"}
              {form_hidden name=page value=$page|escape}
              {form_hidden name=order value=$order|escape}
              {form_hidden name=direction value=$direction|escape}
              {form_hidden name=filter value=$filter|escape}
              {form_hidden name=filterid value=$filterid|escape}

              <label for="searchName">{t}Remember search as:{/t}</label>
              <div class="prIndentTopSmall">
				  <div class="prFloatLeft prInnerRight">
				  {form_text id="searchName" name="search_name" maxlength="15" class="prFullWidth"}
				  </div>
				  {t var='button_02'}Remember{/t}
				  {linkbutton  name=$button_02 onclick="document.forms['formRemember'].submit(); return false;"}
			  </div>

              {/form}
			  {if $savedSearches}
					<h3 class="prInnerTop">{t}Saved Searches:{/t}</h3>
					<div class="prInnerTop">
					  {foreach item=s key=key from=$savedSearches name=savedSearches}
					   <a href="{$_url}/calendarsearch/saved/{$key}/" {if !($smarty.foreach.savedSearches.first)} class="prInnerLeft"{/if}>{if $s}{$s|escape}{else}{t}noname{/t}{/if}</a> <a href="{$_url}/calendarsearchdel/id/{$key}/"  >&nbsp;</a>
					  {/foreach}
					 </div>
				{/if}
          </div>
          <!-- search result begin -->
          <div class="prGrayBorder prIndentTop">
            {$paging}

            {if $eventsList}
            <table cellspacing="0" cellpadding="0" border="0" class="prResult prIndentTop">
              <col width="15%"/>
              <col width="60%"/>
              <col width="25%"/>
				<thead>
					<tr>
					  <th><div {if $order==date}class="prRActive {if $direction==asc}prRActive-top{else}prRActive-bottom{/if}"{/if}><a href="{$_url}{if $filter && $filterid}/filter/{$filter|escape}/filterid/{$filterid|escape}{/if}/order/date/direction/{if $order==date && $direction=='asc'}desc{else}asc{/if}/page/1/">{t}Date{/t}</a></div></th>
					  <th><div {if $order==title}class="prRActive {if $direction==asc}prRActive-top{else}prRActive-bottom{/if}"{/if}><a href="{$_url}{if $filter && $filterid}/filter/{$filter|escape}/filterid/{$filterid|escape}{/if}/order/title/direction/{if $order==title && $direction=='asc'}desc{else}asc{/if}/page/1/">{t}Event Title{/t}</a></div></th>
					  <th><div {if $order==venue}class="prRActive {if $direction==asc}prRActive-top{else}prRActive-bottom{/if}"{/if}><a href="{$_url}{if $filter && $filterid}/filter/{$filter|escape}/filterid/{$filterid|escape}{/if}/order/venue/direction/{if $order==venue && $direction=='asc'}desc{else}asc{/if}/page/1/">{t}Venue{/t}</a></div></th>
					</tr>
				</thead>
				<tbody>
                {foreach item=e from=$eventsList}
                {assign var='eventDate' value=$e->convertTZ($e->getDtstart(), $currentTimezone)}
                {if $e->getOwnerType() == 'user'}
                    <tr>
                      <td class="prVTop">
                        {$e->displayDate('search.results', $user, $currentTimezone)}                        
                      </td>
                      <td class="prVTop">
                        <h4><a href="{$e->entityURL()}">{$e->getTitle()|escape|longwords:15:"\n":true}</a></h4>
                        <div class="prClr2 prInnerSmallTop5">
							{if $e->getPictureId()}<img class="image_thumb prFloatLeft" src="{$e->getEventPicture()->setWidth(37)->setHeight(38)->getImage($user)}" />{else}<img src="{$AppTheme->images}/decorators/fakeImage.gif" class="prFloatLeft" />{/if}
							<div>
							   {$e->getDescription()|escape|longwords:15:"\n":true}
							   <a href="{$e->entityURL()}" class="prIndentLeftSmall">{t}More{/t}</a>
							</div>
						</div>
                        <p class="prInnerSmall">
                           {t}Organizer:{/t} {$e->getCreator()->getLogin()|escape|longwords:15:"\n":true}<br />
                           {if $e->getCategories()->getCount()}
                          {t} Event Category:{/t} {foreach from=$e->getCategories()->setFetchMode('object')->getList() item=c name=eventCat}{$c->getCategory()->getName()}{if !$smarty.foreach.eventCat.last}, {/if}{/foreach}
                           {/if}
                        </p>
                      </td>
                      <td class="prVTop">
                        {if $Warecorp_Venue_AccessManager->canViewPrivateVenue($e, $currentUser, $user)}
                        {if $e->getEventVenue()}
                        <a>{$e->getEventVenue()->getName()|escape|longwords:15:"\n":true}</a><br />
                        <p class="prInnerSmallTop">
                            {if $e->getEventVenue()->getType() == 'worldwide'}
                                {$e->getEventVenue()->getCategory()->getName()|escape|longwords:15:"\n":true}
                            {else}
                                {if $e->getEventVenue()->getAddress1()}{$e->getEventVenue()->getAddress1()|escape|longwords:15:"\n":true},{/if}
                                {if $e->getEventVenue()->getCity()->name}{$e->getEventVenue()->getCity()->name|escape|longwords:15:"\n":true},{/if}
                                {if $e->getEventVenue()->getCity()->getState()->code} {$e->getEventVenue()->getCity()->getState()->code|escape|longwords:15:"\n":true}
                                {elseif $e->getEventVenue()->getCity()->getState()->name} {$e->getEventVenue()->getCity()->getState()->name|escape|longwords:15:"\n":true}{/if}
                            {/if}
                        </p>
                        {/if}
                        {/if}
                        {assign var='userAttendee' value=$e->getAttendee()->findAttendee($user)}
                        {if null !== $userAttendee}
							{if $userAttendee->getAnswer() == 'NONE'}
								<a href="#null" onclick="xajax_doAttendeeEvent({$e->getId()}, {$e->getUid()}, 'list', null, null, params); return false;"><img src="{$AppTheme->images}/decorators/event/btnRSVP.gif" /></a>
							{elseif $userAttendee->getAnswer() == 'YES'}
								<a href="#null" onclick="xajax_doAttendeeEvent({$e->getId()}, {$e->getUid()}, 'list', null, null, params); return false;"><img src="{$AppTheme->images}/decorators/event/btnAttending.gif" /></a>
							{elseif $userAttendee->getAnswer() == 'NO'}
								<a href="#null" onclick="xajax_doAttendeeEvent({$e->getId()}, {$e->getUid()}, 'list', null, null, params); return false;"><img src="{$AppTheme->images}/decorators/event/btnNotAttending.gif" /></a>
							{elseif $userAttendee->getAnswer() == 'MAYBE'}
								<a href="#null" onclick="xajax_doAttendeeEvent({$e->getId()}, {$e->getUid()}, 'list', null, null, params); return false;"><img src="{$AppTheme->images}/decorators/event/btnMaybe.gif" /></a>
							{/if}
                        {/if}
                        &nbsp;
                      </td>
                    </tr>
                {else} {*owner group*}
                    <tr>
                      <td class="prVTop">
                        {$e->displayDate('search.results', $user, $currentTimezone)}
                      </td>
                      <td class="prVTop">
                        <h4><a href="{$e->entityURL()}">{$e->getTitle()|escape|longwords:15:"\n":true}</a></h4>
                         <div class="prClr2 prInnerSmallTop5">
							{if $e->getPictureId()}<img class="image_thumb prFloatLeft" src="{$e->getEventPicture()->setWidth(37)->setHeight(38)->getImage($user)}" />{else}<img src="{$AppTheme->images}/decorators/fakeImage.gif" class="prFloatLeft" />{/if}

						  	<div>
							   {$e->getDescription()|escape|longwords:15:"\n":true}
							   <a href="{$e->entityURL()}" class="prIndentLeftSmall">{t}More{/t}</a>
							</div>
						</div>
                        <p class="prInnerSmallTop">
                           Organizer: {$e->getCreator()->getLogin()|escape|longwords:15:"\n":true}<br />
                           Group event : {$e->getOwner()->getName()|escape|longwords:15:"\n":true}<br />
                           {if $e->getCategories()->getCount()}
                           Event Category: {foreach from=$e->getCategories()->setFetchMode('object')->getList() item=c name=eventCat}{$c->getCategory()->getName()}{if !$smarty.foreach.eventCat.last}, {/if}{/foreach}
                           {/if}
                        </p>
                      </td>
                      <td class="prVTop">
                        {if $Warecorp_Venue_AccessManager->canViewPrivateVenue($e, $Warecorp_Group_Factory->loadById($e->getOwnerId()), $user)}
                        {if $e->getEventVenue()}
                        <a>{$e->getEventVenue()->getName()|escape|longwords:15:"\n":true}</a><br />
                        <p class="prInnerSmallTop">
                            {if $e->getEventVenue()->getType() == 'worldwide'}
                                {$e->getEventVenue()->getCategory()->getName()|escape|longwords:15:"\n":true}
                            {else}
                                {if $e->getEventVenue()->getAddress1()}{$e->getEventVenue()->getAddress1()|escape|longwords:15:"\n":true},{/if}
                                {if $e->getEventVenue()->getCity()->name}{$e->getEventVenue()->getCity()->name|escape|longwords:15:"\n":true},{/if}
                                {if $e->getEventVenue()->getCity()->getState()->code} {$e->getEventVenue()->getCity()->getState()->code|escape|longwords:15:"\n":true}
                                {elseif $e->getEventVenue()->getCity()->getState()->name} {$e->getEventVenue()->getCity()->getState()->name|escape|longwords:15:"\n":true}{/if}
                            {/if}
                        </p>
                        {/if}
                        {/if}
                        {assign var='userAttendee' value=$e->getAttendee()->findAttendee($user)}
                        {if null !== $userAttendee}
							{if $userAttendee->getAnswer() == 'NONE'}
								<a href="#null" onclick="xajax_doAttendeeEvent({$e->getId()}, {$e->getUid()}, 'list', null, null, params); return false;"><img src="{$AppTheme->images}/decorators/event/btnRSVP.gif" /></a>
							{elseif $userAttendee->getAnswer() == 'YES'}
								<a href="#null" onclick="xajax_doAttendeeEvent({$e->getId()}, {$e->getUid()}, 'list', null, null, params); return false;"><img src="{$AppTheme->images}/decorators/event/btnAttending.gif" /></a>
							{elseif $userAttendee->getAnswer() == 'NO'}
								<a href="#null" onclick="xajax_doAttendeeEvent({$e->getId()}, {$e->getUid()}, 'list', null, null, params); return false;"><img src="{$AppTheme->images}/decorators/event/btnNotAttending.gif" /></a>
							{elseif $userAttendee->getAnswer() == 'MAYBE'}
								<a href="#null" onclick="xajax_doAttendeeEvent({$e->getId()}, {$e->getUid()}, 'list', null, null, params); return false;"><img src="{$AppTheme->images}/decorators/event/btnMaybe.gif" /></a>
							{/if}
                        {/if}
                        &nbsp;
                      </td>
                    </tr>
                {/if}
                {/foreach}
              </tbody>
            </table>
            {else}
            <table>
                <tr>
                    <td>
                        {t}Your search{/t} - '{if $keywords}{t}Keyword:{/t} {$keywords|escape}{/if}{if $where}{if $keywords}; {/if}{t}Where:{/t} {$where|escape}{/if}{if $when}{if $keywords or $where}; {/if}{t}When:{/t} {$when|escape}{/if}' - {t}did not match any events.{/t}
                    </td>
                </tr>
                <tr>
                  <td style="padding:15px 10px 15px 10px;">
                    <ul>{t}Suggestions:{/t}
                        <li>{t}Make sure all words are spelled correctly{/t}</li>
                        <li>{t}Try different keywords{/t}</li>
                        <li>{t}Try to change the"when" date{/t}</li>
                    </ul>
                  </td>
                </tr>
            </table>
            {/if}

            {$paging}

          </div>
          <!-- search result end -->
	</div>
        <!-- -->
      <div>
          <h2>{t}Filter Results{/t}</h2>
          <p class="prInnerTop"{t}>Within these results{/t}</p>
		  <p class="prInnerTop"><strong>{t}Show only events for...{/t}</strong></p>
                  <a href="{$_url}/filter/date/filterid/today/">{t}Today{/t}</a><br />
                  <a href="{$_url}/filter/date/filterid/weekend/">{t}This weekend{/t}</a><br />
                  <a href="{$_url}/filter/date/filterid/nweekend/">{t}Next weekend{/t}</a><br />
                  <a href="{$_url}/filter/date/filterid/nmonth/">{t}Next Month{/t}</a><br />
                  <a href="{$_url}/filter/date/filterid/n7days/">{t}Next 7 days{/t}</a><br />
                  {*<a href="#null" onclick="alert('This is the prototype of the pop-up calendar :)'); return false;">&#62;&#62;  {t}Pick another day{/t}</a><br />*}
           <p class="prInnerTop"><strong>{t}Show only events for...{/t}</strong></p>
                {* Added by fixing #4241 *}
                {if $user && null !== $user->getId() }
                  <a href="{$_url}/filter/owner/filterid/friends/">{t}My Friends{/t}</a><br />
                  <a href="{$_url}/filter/owner/filterid/groups/">{t}My Groups{/t}</a><br />
                  <a href="{$_url}/filter/owner/filterid/families/">{t}My Group Families{/t}</a><br />
                {/if}
                  <a href="{$_url}/filter/owner/filterid/other/">{t}Other{/t}</a><br />
            {if $categories}
            <p class="prInnerTop"><strong>{t}Show only events from the following categories...{/t}</strong></p>
                  {foreach key=id item=c name='category' from=$categories}
                    <a href="{$_url}{if $order && $direction}/order/{$order|escape}/direction/{$direction}{/if}/filter/category/filterid/{$c.id}/">{$c.name}</a><br />
                  {/foreach}
             {/if}
    </div>
</div>
