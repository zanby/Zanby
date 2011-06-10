<div class="znbFooterCorner">
    <div class="znbFooterCenterContainer">
        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td>
<h1>{t}OBSOLETE TEMPLATE{/t}</h1>
                    <ul class="znbBottomMenu">
                        <li><a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/">{t}Home{/t}</a></li>
                        <!--<li><a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/groups/index/">Groups</a></li>-->
                        <li><a href="{$user->getUserPath()}">{t}Members{/t}</a></li>
                        <li><a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/info/tour/">{t}Tour{/t}</a></li>
                        {if !$user->isAuthenticated()}
                            <li><a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/info/contactus/">{t}Advertise With Us{/t}</a></li>
                        {else}    
                            <li class="znbLastItem"><a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/info/contactus/">{t}Advertise With Us{/t}</a></li>
                        {/if}
                        {if !$user->isAuthenticated()}
                            <li><a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/registration/index/">{t}Register{/t}</a></li>
                            <li><a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/users/login/">{t}Log In{/t}</a></li>
                        {/if}
                        <!-- <li class="znbLastItem"><a href="#null">Support</a></li>-->
                    </ul>
                </td>
            </tr>
        </table>
        <div class="znbClear"><span /></div>
        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td>
                    <ul class="znbBottomMenu">
                        <li><a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/info/contactus/">{t}Contact Us{/t}</a></li>
                        <li><a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/info/about/">{t}About Us{/t}</a></li>
                        <li><a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/info/copyright/">{t}Copyright{/t}</a></li>
                        <li class="znbLastItem"><a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/info/privacy/">{t}Privacy Policy{/t}</a></li>
                    </ul>
                </td>
            </tr>
        </table>
        <div class="znbClear"><span /></div>
    </div>
    <div class="znbCopyright"><a href="http://{$BASE_HTTP_HOST}">{copyright}</a></div>
    <div class="znbClear"><span /></div>
</div>
