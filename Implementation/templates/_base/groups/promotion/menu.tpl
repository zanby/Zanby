    {if !$menu}
        {t}Invite Groups{/t}
    {else}
        <a href="{$CurrentGroup->getGroupPath('invite1')}">{t}Invite Groups{/t}</a>
    {/if}
|    
    {if $menu == 'draft'}
        {t}Draft Invitations{/t}
    {else}
        <a href="{$CurrentGroup->getGroupPath('invitelist')}folder/draft/">{t}Draft Invitations{/t}</a>
    {/if}
|    
    {if $menu == 'sent'}
        {t}Sent Invitations{/t}
    {else}
        <a href="{$CurrentGroup->getGroupPath('invitelist')}folder/sent/">{t}Sent Invitations{/t}</a>
    {/if}