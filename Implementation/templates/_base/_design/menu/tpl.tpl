    {tab template="tabs1" active="listssearch"}
        {tabitem link="$_url/$LOCALE/lists/" name="lists"}List Index{/tabitem}
        {tabitem link="$_url/$LOCALE/listssearch/" name="listssearch"}Search and Browse Lists{/tabitem}
        {tabitem link="$_url/$LOCALE/listssearch/" name="listssearch2"}Search2{/tabitem}
        {tabitem link="$_url/$LOCALE/listssearch/" name="listssearch3"}Search3{/tabitem}
    {/tab}
    <br />
    {tab template="tabs2" active="listssearch"}
        {tabitem link="$_url/$LOCALE/lists/" name="lists"}List Index{/tabitem}
        {tabitem link="$_url/$LOCALE/listssearch/" name="listssearch"}Search and Browse Lists{/tabitem}
        {tabitem link="$_url/$LOCALE/listssearch/" name="listssearch2"}Search2{/tabitem}
        {tabitem link="$_url/$LOCALE/listssearch/" name="listssearch3"}Search3{/tabitem}
    {/tab}
    <br />
    {tab template="tabs1" active="type0"}
        {foreach from=$typesTabs item=t key=key}
            {tabitem link=`$t.url` name=type`$key`}{$t.title}{/tabitem}
        {/foreach}
    {/tab}
