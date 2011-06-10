{if $order}
    {assign var="orderPath" value='order/'|cat:$order|cat:'/direction/'|cat:$direction|cat:'/'}
{else}
    {assign var="orderPath" value=''}
{/if}
{if $membersList->getCount() > 0}

    <!-- result begin -->
    <div class="prIndentBottomSmall">
        <div class="prIndentTopSmall">{$paging}</div>
    </div>
    <table cellspacing="0" cellpadding="0" class="prResult">
        <col width="10%" />
        <col width="20%" />
        <col width="20%" />
        <col width="30%" />
        <thead><tr>
            <th colspan="2"><div {if $order == 'name'} class="prRActive{if $direction == 'asc'} prRActive-top{else} prRActive-bottom{/if}"{/if}><a href="{$CurrentGroup->getGroupPath('familymembers.all')}order/name/direction/{if $direction == 'asc'}desc{else}asc{/if}/page/1">{t}Name{/t}</a></div></th>
            <th><div {if $order == 'laston'} class="prRActive{if $direction == 'asc'} prRActive-top{else} prRActive-bottom{/if}"{/if}><a href="{$CurrentGroup->getGroupPath('familymembers.all')}order/laston/direction/{if $direction == 'asc'}desc{else}asc{/if}/page/1">{t}Last On{/t}</a></div></th>
            <th>&#160;</th>
            <th>&#160;</th>
        </tr></thead>
        <tbody>
        {foreach item=m name=members from=$membersList->getList()}
        <tr {if ($smarty.foreach.members.iteration % 2) == 0} class="prEvenBg"{else} class="prOddBg"{/if}>
        {view_factory entity='family' view='members' object=$m}
        </tr>
        {/foreach}
        </tbody>
    </table>
    <div class="prIndentTopSmall">
        <div class="prIndentTopSmall">{$paging}</div>
    </div>
{else}
    <div class="prFormMessage prMarkRequired">
        {t}No members{/t}
    </div>
{/if}
