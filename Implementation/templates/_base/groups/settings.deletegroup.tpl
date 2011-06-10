{if $visibility_details == "deletegroup"}<script>xajax_privileges_deletegroup_show();</script>
{else}
    {if $visibility == true}			
        <p class="prIndentTop">
        {t}Delete the group. Remove the files, group relationships, forum posts, landing pages and data structure for your group.{/t}
        </p>
        <div class="prTRight prIndentTop">
			{t var="in_button"}Delete Group{/t}
            {linkbutton name=$in_button onclick="xajax_privileges_deletegroupstep1(); return false;"}
        </div>
    {/if}
{/if}
