<script type="text/javascript">
    var params = [];
    {foreach from=$params item=item key=key}
        params["{$key}"] = "{$item|escape}";
    {foreachelse}
        params['garbage'] = "garbage";
    {/foreach}
</script>
{literal}
    <script>
        var cfgSearchApplication = null;
        if ( !cfgSearchApplication ) {
            cfgSearchApplication = function () {
                return {
                    hEventAddToMy : '{/literal}{$BASE_URL}/{$LOCALE}/search/eventAddToMy/{literal}'
                }
            }();
        };
    </script>
{/literal}
<script type="text/javascript" src="{$AppTheme->common->js}/modules/search/init.js"></script>

{if $keywords}<h2>{t}{tparam value=$SITE_NAME_AS_STRING} %s Events About {/t} {$keywords|wordwrap:25:"\n":true|escape}</h2>{/if}
{if $searchTitle}<h2>{$searchTitle|escape}</h2>{/if}
{assign var="activeMenuItem" value="list"}
{if $eventsList}
    {$paging}
    <table cellspacing="0" cellpadding="0" border="0" class="prResult">
      <col width="12%"/>
      <col width="35%"/>
      <col width="20%"/>
      <col width="15%"/>
        <thead>
            <tr>
                <th colspan="2"><div {if $order==title}class="prRActive {if $direction==asc}prRActive-top{else}prRActive-bottom{/if}"{/if}><a href="{$_url}{if $filter && $filterid}/filter/{$filter|escape}/filterid/{$filterid|escape}{/if}/order/title/direction/{if $order==title && $direction=='asc'}desc{else}asc{/if}/page/1/" class="{if $order==title}Color1{else} Color2{/if}">What</a></div></th>
                <th><div {if $order==venue}class="prRActive {if $direction==asc}prRActive-top{else}prRActive-bottom{/if}"{/if}><a href="{$_url}{if $filter && $filterid}/filter/{$filter|escape}/filterid/{$filterid|escape}{/if}/order/venue/direction/{if $order==venue && $direction=='asc'}desc{else}asc{/if}/page/1/" class="{if $order==venue}Color1{else} Color2{/if}">Where</a></div></th>
                <th colspan="3"><div {if $order==date}class="prRActive {if $direction==asc}prRActive-top{else}prRActive-bottom{/if}"{/if}><a href="{$_url}{if $filter && $filterid}/filter/{$filter|escape}/filterid/{$filterid|escape}{/if}/order/date/direction/{if $order==date && $direction=='asc'}desc{else}asc{/if}/page/1/" class="{if $order==date}Color1{else} Color2{/if}">When</a></div></th>
            </tr>
        </thead>
        <tbody>
            {foreach item=e from=$eventsList name='events'}
                <tr {if ($smarty.foreach.events.iteration % 2) == 0} class="prEvenBg"{else} class="prOddBg"{/if}>
                    {assign var='eventDate' value=$e->convertTZ($e->getDtstart(), $currentTimezone)}
                    {assign var='userAttendee' value=$e->getAttendee()->findAttendee($user)}
                    {view_factory entity='event' view='globalsearch' object=$e user=$user currentTimezone=$currentTimezone eventDate=$eventDate|date_format:"year/%Y/month/%m/day/%d/" currentUser=$currentUser Warecorp_Venue_AccessManager=$Warecorp_Venue_AccessManager Warecorp_Group_Factory=$Warecorp_Group_Factory even=$smarty.foreach.events.iteration num=0}
                </tr>
            {/foreach}
      </tbody>
    </table>
    <div class="prIndentTop">
        {$paging}
    </div>
{else}
    <p class="prIndentBottom prText2">{t}There are no events in search results{/t}</p>
    <p>{t}Use the right utility to search again.{/t}</p>
    {t}If you have a special question, please email{/t} <a href="{$BASE_URL}/{$LOCALE}/info/contactus/">{t}Contact Us.{/t}</a>
{/if}
