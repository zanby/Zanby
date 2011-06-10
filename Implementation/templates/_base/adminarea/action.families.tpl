<div class="prTLeft prIndentTop">
    {form id="sForm" from=$form}
        <table>
        <col width="9%" />
        <col width="20%" />
        <col width="71%" />
            <tbody>
                <tr>
                    <td class="prTLeft">
                        <label for="keyword" >{t}Keyword:{/t} </label>
                    </td>
                    <td class="prTLeft">
                        {form_text id="keyword" name="keyword" value=$keyword|escape:"html"}
                    </td>
                    <td class="prTLeft">
                        {t var="in_submit"}Search{/t}{form_submit value=$in_submit name="searchForm"}
                    </td>
                </tr>
            </tbody>
        </table>
    {/form}
</div>

<h3 class="prTLeft prIndentTop">Groups on site_name:</h3>

<!-- result begin -->
<div class="prTLeft prIndentTop">
{$paging}
</div>

<table cellspacing="0" cellpadding="0" class="prResult">
    <col width="5%" />
    <col width="18%" />
    <col width="20%" />
    <col width="15%" />
    <col width="15%" />
    <col width="10%" />
    <col width="17%" />
    <thead>
        <tr>
            <th>&#160;</th>
            <th class="prTLeft">
                <div {if $order == 'name'} class="prRActive prRActive-{if $direction == 'asc'}top{else}bottom{/if}"{/if}>
                <a class="{if $order == 'name'}freeClass{else}freeClass{/if}" href="{$admin->getAdminPath('families/order/name')}{$search}/direction/{if $direction=='asc'}desc/{else}asc/{/if}">{t}Group Name{/t}</a>
                </div>
            </th>
            <th class="prTLeft">{t}Location{/t}</th>
            <th class="prTLeft">{t}Owner{/t}</th>
            <th class="prTLeft">
                <div {if $order == 'creation_date'} class="prRActive prRActive-{if $direction == 'asc'}top{else}bottom{/if}"{/if}>
                <a class="{if $order == 'creation_date'}freeClass{else}freeClass{/if}" href="{$admin->getAdminPath('families/order/creation_date')}{$search}/direction/{if $direction=='asc'}desc/{else}asc/{/if}">{t}Founded{/t}</a>
                </div>
            </th>
            <th class="prTLeft">
                <div {if $order == 'members'} class="prRActive prRActive-{if $direction == 'asc'}top{else}bottom{/if}"{/if}>
                <a class="{if $order == 'members'}freeClass{else}freeClass{/if}" href="{$admin->getAdminPath('families/order/members')}{$search}/direction/{if $direction=='asc'}desc/{else}asc/{/if}">{t}Member groups{/t}</a>
                </div>
            </th>
            <!--th class="prTLeft">{t}Group Type{/t}</th-->
            <th class="prTLeft">{t}Who can join?{/t}</th>
        </tr>
    </thead>
    {foreach item=group from=$groupsList}
    <tr>
        <td class="prTLeft"><img src="{$group->getAvatar()->setWidth(37)->setHeight(37)->setBorder(1)->getImage()}" border="0"></td>
        <td class="prTLeft" style="text-align: center;"><a style="font-size: 1em;" href="{$admin->getAdminPath('families/id/')}{$group->getId()}/">{$group->getName()|escape|wordwrap:13:"\n":true}({$group->getId()})</a></td>
        <td class="prTLeft">{$group->getCity()->name},{$group->getState()->name}</td>
        <td class="prTLeft">{assign var="host" value=$group->getHost()}{if $host->getId()}<a href="{$admin->getAdminPath('members/id/')}{$host->getId()}">{$host->getLogin()|escape}</a>{/if}</td>
        <td class="prTLeft">{$group->getCreateDate()|date_locale:'DATE_MEDIUM'}</td>
        <td class="prTLeft">{$group->getGroupsInFamilyCount()}</td>
        {if $group->getJoinMode() == 0}
            <td class="prTLeft">{t}Anyone may join{/t}</td>
        {elseif $group->getJoinMode() == 1}
            <td class="prTLeft">{t}Contact Host to request membership{/t}</td>
        {elseif $group->getJoinMode() == 2}
            <td class="prTLeft">{t}Join with Code{/t}</td>
        {/if}
    </tr>
    {foreachelse}
    <tr>
        <td colspan="7">{t}&nbsp;There are no group families in search results<br />
        &nbsp;Use the right utility to search again.{/t}<br />
        </td>
    </tr>
    {/foreach}
</table>

<div class="prTLeft prIndentTop">
{$paging}
</div>
<!-- result end -->
