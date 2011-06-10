{if $rssUrl}<a href="{$rssUrl}">&nbsp;</a>{/if}
<div>
{$paging}
<table cellpadding="0" cellspacing="0" border="0" class="prResult">
{if $usersList}
  <col width="22%" />
  <col width="29%" />
  <col width="21%" />
  <col width="28%" />
<tr>
      <th nowrap="nowrap"><div {if $order==name}class="prRActive {if $direction==asc}prRActive-top{else}prRActive-bottom{/if}"{/if}><a href="{$_url}{if $filter && $filterid}/filter/{$filter|escape}/filterid/{$filterid|escape}{/if}/order/name/direction/{if $order==name && $direction=='asc'}desc{else}asc{/if}/page/1/">{t}Name{/t}</a></div></th>
      <th nowrap="nowrap"><div {if $order==joined}class="prRActive {if $direction==asc}prRActive-top{else}prRActive-bottom{/if}"{/if}><a href="{$_url}{if $filter && $filterid}/filter/{$filter|escape}/filterid/{$filterid|escape}{/if}/order/joined/direction/{if $order==joined && $direction=='asc'}desc{else}asc{/if}/page/1/">{t}Date Joined{/t}</a></div></th>
      <th nowrap="nowrap"><div {if $order==laston}class="prRActive {if $direction==asc}prRActive-top{else}prRActive-bottom{/if}"{/if}><a href="{$_url}{if $filter && $filterid}/filter/{$filter|escape}/filterid/{$filterid|escape}{/if}/order/laston/direction/{if $order==laston && $direction=='asc'}desc{else}asc{/if}/page/1/">{t}Last On{/t}</a></div></th>
      <th nowrap="nowrap" colspan="2">{if $user->getId()}<div {if $order==proximityme}class="prRActive {if $direction==asc}prRActive-top{else}prRActive-bottom{/if}"{/if}><a href="{$_url}{if $filter && $filterid}/filter/{$filter|escape}/filterid/{$filterid|escape}{/if}/order/proximityme/direction/{if $order==proximityme && $direction=='asc'}desc{else}asc{/if}/page/1/">{t}WomanProximity to me{/t}</a></div>{else}&nbsp;{/if}</th>
</tr>
  {foreach item=u from=$usersList}
  <tr>
    <td colspan="2" class="prVTop">{if $u->getPrivacy()->getSrViewProfilePhoto()}<img src="{$u->getAvatar()->setWidth(37)->setHeight(37)->setBorder(1)->getImage()}" title="" width="37" height="37" class="prFloatLeft prInnerSmallTop" />{else}<img src="{$AppTheme->images}/decorators/fakeImage.gif" class="prFloatLeft prInnerSmallTop" />{/if} <a href="{$u->getUserPath('profile')}">{$u->getLogin()|escape:html|wordwrap:25:"<br />\n":true}</a>
      <div class="prInnerSmallTop prClr2" style="white-space: nowrap;">{if !$u->getIsBirthdayPrivate()}{t}{tparam value=$u->getAge()}%s Yr old{/t} {/if}{if !$u->getIsGenderPrivate()}{if $u->getGender()==male}{t}Man{/t}{elseif $u->getGender()==female}{t}Woman{/t}{/if}{/if}</div>
      <div class="prInnerSmallTop"><a href="{$_url}/preset/city/id/{$u->getCity()->id}/">{$u->getCity()->name}</a>, <a href="{$_url}/preset/state/id/{$u->getState()->id}/">{$u->getState()->name}</a></div>
      <div class="prInnerSmallTop">{t}{tparam value=$u->getRegisterDate()|date_locale:'DATE_MEDIUM'}Member since %s{/t}</div>
    </td>
    <td class="prVTop">{if $u->getLastOnline()=='Online'}<div>{t}online{/t}</div>{else}{$u->getLastOnline()}{/if}</td>
    <td class="prVTop">
      {if $u->getPrivacy()->getSrViewSendMessage()}
        {if $user->isAuthenticated()}
        <div class="prInnerSmallTop"><a href="#null" onclick="xajax_sendMessage({$u->getId()}); return false;">{t}Send Message{/t}</a></div>
        {else}
        <div class="prInnerSmallTop"><a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/users/login/">{t}Send Message{/t}</a></div>
        {/if}
      {/if}
      {if $u->getId() !== $user->getId()}
        {if $u->getPrivacy()->getSrViewAddToFriend()}
          {if in_array($u->getId(), $friends)}
            {t}Already Friend{/t}
          {else}
            <div class="prInnerSmallTop"><a href="#null" onclick="xajax_addToFriends({$u->getId()}); return false;">{t}Add to Friends{/t}</a></div>
          {/if}
        {/if}
      {/if}
      {if $u->getPrivacy()->getSrViewMyFriends()}<div class="prInnerSmallTop"><a href="{$u->getUserPath('friends')}">{t}View Friends{/t}</a></div>{/if}
    </td>
  </tr>
  {/foreach}
{else}
  <tr>
    <td colspan="5">
        &nbsp;{t}There are no members in search results{/t}<br />
        &nbsp;{t}Use the right utility to search again.{/t}<br />
        &nbsp;{t}{tparam value=$BASE_URL}{tparam value=$LOCALE}If you have a special question, please email <a href="%s/%s/info/contactus/">Contact Us.{/t}</a>
    </td>
  </tr>
{/if}
</table>
{$paging}
</div>
{include file="users/search.form.tpl"}
