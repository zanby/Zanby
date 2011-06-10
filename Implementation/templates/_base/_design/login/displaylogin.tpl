<script type="text/javascript">YAHOO.namespace("example.container");</script> 

{if $userExist}
    <a {if $dl.href} href="{$dl.href}" {else} href="#null" {/if}
    {if $dl.onclick} onclick="{$dl.onclick}" {/if} 
    {if $dl.style} style="{$dl.style}" {/if}   
{else}
    <span 
    {if $dl.style} style="color:#666666;{$dl.style}" {else} style="color:#666666;" {/if}
{/if}
{if $dl.class} class="{$dl.class}" {/if}
{if $dl.id} id="{$dl.id}" {/if} 
{if $dl.name} name="{$dl.name}" {/if}
{if $userExist}
    >{$dl.user->getLogin()|wordwrap:25:"\n":true|escape} </a>
{else}
    {popup text="<span class='TooltipContent' style='height:15px;color:#3F3F3F;'>User deleted</span>" width="0" height="0" fgcolor=#FFFFFF}>{$dl.user->getLogin()|wordwrap:25:"\n":true|escape}</span>
<!--    <script>
        YAHOO.example.container.{$dl.id}X = new YAHOO.widget.Tooltip("{$dl.id}X", {$smarty.ldelim}hidedelay:100, width:200, context:"{$dl.id}", text:"User deleted"{$smarty.rdelim});
    </script> -->   
{/if}
