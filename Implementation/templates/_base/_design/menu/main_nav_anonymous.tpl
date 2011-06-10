<!-- before active tab there should be no border -->
<ul class="znMainMenu">

    <!-- Home -->
    {if $bodyContent == "index/index.tpl" || $bodyContent == "index/index_anonymous.tpl"}
        <li class="znCurrent">
            <a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/"><span>{t}Home{/t}</span></a>
        </li>
    {else}
        {if $MOD_NAME == "users"}
            <li class="znNoBorder">
                <a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/"><span>{t}Home{/t}</span></a>
            </li>
        {else}
            <li class="znNoBorder">
                <a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/"><span>{t}Home{/t}</span></a>
            </li>
        {/if}
    {/if}
    
    
    <!-- Groups -->
    {if $MOD_NAME == "groups"}
        <li class="znCurrent">
            <a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/groups/index/"><span>{t}Groups{/t}</span></a>
        </li>
    {else}
        {if $bodyContent == "index/index.tpl" || $bodyContent == "index/index_anonymous.tpl"}
            <li class="znNoBorder">
                <a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/groups/index/"><span>{t}Groups{/t}</span></a>
            </li>
        {else}
            <li>
                <a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/groups/index/"><span>{t}Groups{/t}</span></a>
            </li>
        {/if}
    {/if}

    
    <!-- Users -->
    {if $MOD_NAME == "users" && $ACTION_NAME != "login"}
        <li  class="znCurrent">
            <a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/users/"><span>{t}Members{/t}</span></a>
        </li>
    {else}
		{if $MOD_NAME == "groups"}
	        <li class="znNoBorder">
	            <a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/users/"><span>{t}Members{/t}</span></a>
	        </li>
		{else}
			<li>
	            <a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/users/"><span>{t}Members{/t}</span></a>
	        </li>
		{/if}
    {/if}
    
	{if $MOD_NAME == "info" && $ACTION_NAME == "support"}
		<li class="znCurrent">
			<a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/info/support/"><span>{t}Support{/t}</span></a>
		</li>
	{else}
		{if $MOD_NAME == "users" && $ACTION_NAME != "login"}
		<li class="znNoBorder">
			<a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/info/support/"><span>{t}Support{/t}</span></a>
		</li>
		{else}
		<li>
			<a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/info/support/"><span>{t}Support{/t}</span></a>
		</li>
		{/if}
	{/if}
</ul>