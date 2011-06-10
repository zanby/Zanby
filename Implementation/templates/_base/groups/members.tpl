<!--NOT USED-->
        {tab template="tabs2"}
            {tabitem link=$CurrentGroup->getGroupPath('members')|cat:'mode/approved/' active=$mode_approved}{t}Members{/t}{/tabitem}
            {tabitem link=$CurrentGroup->getGroupPath('members')|cat:'mode/pending/' active=$mode_pending}{t}Pending Members{/t}{/tabitem}
        {/tab}
        <br />
        <span class="rss"><a href="/rss/members/"><img src="/img/discussion/rss.gif" alt="" width="14" height="15" border="0"/></a><a href="/rss/members/">{t}RSS Feed{/t}</a></span>
        {if $mode_pending}    
         {include file="groups/membersPending.tpl"}
        {else}
         {include file="groups/membersApproved.tpl"}
        {/if}
        {$paging}