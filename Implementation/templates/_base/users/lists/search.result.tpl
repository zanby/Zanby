{if $Warecorp_List_AccessManager->canManageLists($currentUser, $user)}
			{assign var="addLink" value=$currentUser->getUserPath('listsadd')}
{/if}

        <!-- inner begin -->

       <div class="prInner">
          <div class="prGrayBorder prInner prIndentBottom">
            <h2>{t}Search Lists{/t}</h2>
            {include file="users/lists/search.form.tpl"}

            {form from=$formRemember id="formRemember"}
            {form_hidden name=page value=$page|escape}
            {form_hidden name=order value=$order|escape}
            {form_hidden name=direction value=$direction|escape}
            {form_hidden name=filter value=$filter|escape}

			<table class="prForm">
				<col width="50%" />
				<col width="50%" />
            	<tr>
					<td><label for="searchName">{t}Remember search as:{/t}</label>
					<div class="prIndentTopSmall">
					{form_text id="searchName" name="search_name" maxlength="15"}
					</div>
					</td>
					<td><br />
					{t var='button_01'}Remember{/t}
					{linkbutton name=$button_01 onclick="document.forms['formRemember'].submit(); return false;"}</td>
				</tr>
			</table>

            {/form}
            {if $savedSearches}
			<h3 class="prInnerTop">{t}Saved Searches{/t}</h3>
            <div class="prInnerTop">
              {foreach item=s key=key from=$savedSearches name=savedSearches}
               <a href="{$_url}/listssearch/saved/{$key|escape}/" {if !$smarty.foreach.savedSearches.first} class="prInnerLeft"{/if}>{if $s}{$s|escape}{else}{t}noname{/t}{/if}</a> <a href="{$_url}/listssearchdel/id/{$key|escape}{if $preset}/preset/{$preset|escape}{if $id}/preset_id/{$id|escape}{/if}/{else}{if $filter}/filter/{$filter|escape}{/if}{if $order}/order/{$order|escape}{/if}{if $direction}/direction/{$direction|escape}{/if}{if $page}/page/{$page|escape}{/if}/{/if}" >&nbsp;</a>
              {/foreach}
			 </div>
            {/if}

          </div>
          {if $keywords}<h3>{t}{tparam value=$SITE_NAME_AS_STRING} %s Lists About {/t}<strong>{$keywords|escape|wordwrap:25:"\n":true}</strong></h3>{/if}
          {if $searchTitle}<h3>{$searchTitle|escape}</h3>{/if}
	</div>
          <!-- menu -->
		  <div>
			{tab template="tabs1" active="tab_$filter"}
                {foreach from=$typesTabs name="typesofTabs" key=key item=tab}
					{if $smarty.foreach.typesofTabs.last}
                    {tabitem link=$tab.url name="tab_$key" last="last"}{$tab.title}{/tabitem}
					{else}
						{if $smarty.foreach.typesofTabs.first}
						{tabitem link=$tab.url name="tab_$key" first="first"}{$tab.title}{/tabitem}
						{else}
						{tabitem link=$tab.url name="tab_$key"}{$tab.title}{/tabitem}
						{/if}
					{/if}
                {/foreach}
            {/tab}
		</div>
		<div class="prInner">
            <!-- menu end -->
            {if $listsList}
            <h3 class="prIndentBottom">{t}{tparam value=$typesTabs.$filter.title}%s Lists{/t}</h3>

            <!-- search result begin -->
            <div class="prGrayBorder">
				<div>
            	{$paging}
				</div>

				<table class="prResult">
				  <col width="38%"/>
				  <col width="24%"/>
				  <col width="12%"/>
				  <col width="10%"/>
				  <col width="18%"/>
				  <thead>
				  <tr>
					<th><div {if $order==title}class="prRActive {if $direction==asc}prRActive-top{else}prRActive-bottom{/if}"{/if}><a href="{$_url}/listssearch{if $filter}/filter/{$filter|escape}{/if}/order/title/direction/{if $order==title && $direction=='asc'}desc{else}asc{/if}/page/1/"> {t}Title{/t}</a></div> </th>
					<th><div {if $order==created}class="prRActive {if $direction==asc}prRActive-top{else}prRActive-bottom{/if}"{/if}><a href="{$_url}/listssearch{if $filter}/filter/{$filter|escape}{/if}/order/created/direction/{if $order==created && $direction=='asc'}desc{else}asc{/if}/page/1/">{t}Date Created{/t}</a></div></th>
					<th><div {if $order==author}class="prRActive {if $direction==asc}prRActive-top{else}prRActive-bottom{/if}"{/if}><a href="{$_url}/listssearch{if $filter}/filter/{$filter|escape}{/if}/order/author/direction/{if $order==author && $direction=='asc'}desc{else}asc{/if}/page/1/">{t}Author{/t}</a></div></th>
					<th><div {if $order==items}class="prRActive {if $direction==asc}prRActive-top{else}prRActive-bottom{/if}"{/if}><a href="{$_url}/listssearch{if $filter}/filter/{$filter|escape}{/if}/order/items/direction/{if $order==items && $direction=='desc'}asc{else}desc{/if}/page/1/">{t}Items{/t}</a></div></th>
					<th></th>
				  </tr>
				  </thead>
				  {foreach item=l from=$listsList name=listsList}
				  <tr>
					<td class="prVTop">
					{if $l->getOwnerType() == 'user'}
						<a href="{$l->getOwner()->getUserPath('listsview')}listid/{$l->getId()}/">{$l->getTitle()|escape|wordwrap:14:"\n":true}</a>
					{elseif $l->getOwnerType() == 'group'}
						<span>{$l->getOwner()->getName()|escape|wordwrap:15:"\n":true}</span>
						<a href="{$l->getOwner()->getGroupPath('listsview')}listid/{$l->getId()}/">{$l->getTitle()|escape|wordwrap:14:"\n":true}</a>
					{/if}
					<p class="prInnerSmallTop">{$l->getDescription()|escape|wordwrap:16:"\n":true}{if strlen($l->getDescription())>100}<a href="{$l->getListPath()}">{t}MORE{/t}</a>{/if}</p>
					</td>
					<td class="prVTop">{$l->getCreationDate()|user_date_format:$user->getTimezone()|date_locale:'DATETIME'} {$TIMEZONE}</td>
					<td class="prVTop"><a href="{$l->getCreator()->getUserPath('profile')}">{$l->getCreator()->getLogin()|escape}</a></td>
					<td class="prVTop"><strong>{$l->getRecordsCount()}</strong></td>
					<td class="prTCenter prVTop">
						{if $l->getOwnerType() == 'user' && $user->getId() == $l->getOwner()->getId()}
							<div>{t}My List{/t}</div>

							{if $Warecorp_List_AccessManager->canShareList($l, $currentUser, $user)}
							<a href="#" onclick="xajax_list_share_popup_show({$l->getId()}); return false;">{t}Share this List{/t}</a>
							{/if}
						{else}
							<a href="#" onclick="xajax_list_add_popup_show({$l->getId()}); return false;" >{t}Add To My Lists{/t}</a><br />
						{/if}
					</td>
				  </tr>
				  {/foreach}
				  </tbody>
				</table>
				<div>
            	{$paging}
				</div>
            </div>
            {else}

            <div class="prInner">
            
                &nbsp;{t}There are no lists in search results{/t}<br />
                &nbsp;{t}Use the above utility to search again.{/t}<br />
                &nbsp;{t}If you have a special question, please email{/t} <a href="{$BASE_URL}/{$LOCALE}/info/contactus/">{t}Contact Us.{/t}</a>

            </div>
            {/if}
            <!-- search result end -->


      </div>
        <!-- inner end -->