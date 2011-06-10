{if $action == "findfriends"}
	{t var="title"}My Friends{/t}
{else}
	{t var="title"}My Messages{/t}
{/if}

{if $error==0}
    {if $invited}
        {t}{tparam value=$SITE_NAME_AS_STRING}We have sent an invitation to %s on your behalf.<br />
        Your contacts will receive your invitation soon.{/t}
    {else}
        {t}Import completed.{/t}
    {/if}
{elseif $error==1}
    {t} Error: Incorrect import source type.{/t}
{elseif $error==2}
    {t}You haven't any imported contacts. {/t}
{/if}
