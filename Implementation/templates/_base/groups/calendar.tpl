
{assign var="GroupName" value=$CurrentGroup->getName()}

    {tab template="tabs2" active="events"}
        {tabitem link="$_url/$LOCALE/summary/" name="summary"}{t}Summary{/t}{/tabitem}
        {tabitem link="$_url/$LOCALE/members/" name="members"}{t}Members{/t}{/tabitem}
        {tabitem link="$_url/$LOCALE/discussion/" name="discussion"}{t}{t}Discussions{/t}{/tabitem}
        {tabitem link="$_url/$LOCALE/photos/" name="photos"}Photos{/t}{/tabitem}
        {tabitem link="$_url/$LOCALE/lists/" name="lists"}{t}Lists{/t}{/tabitem}
        {tabitem link="$_url/$LOCALE/calendar/" name="events"}{t}Events{/t}{/tabitem}
        {tabitem link="$_url/$LOCALE/tags/" name="tags"}{t}Tags{/t}{/tabitem}
        {tabitem link="$_url/$LOCALE/documents/" name="documents"}{t}Documents{/t}{/tabitem}
        {tabitem link="$_url/$LOCALE/settings/" name="settings"}{t}Tools{/t}{/tabitem}
    {/tab} 

  
