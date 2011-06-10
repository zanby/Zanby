
{assign var="login" value=$currentUser->getLogin()}

	{*profilemenu color=$menuColor active="lists"*}
    <table cellpadding="4" cellspacing="0" width="100%" border="0">
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" width="100%" border="0">
                    <tr>
                        <td width="75%">
                            <table cellpadding="4" cellspacing="0" width="100%" border="0" bgcolor="#DDDDDD">
                                <tr>
                                    <td>
                                        {if $currentUser->getId() == $user->getId()}
                                            You have {$listsCount|default:0} lists
                                        {else}
                                            {$currentUser->getLogin()|escape:"html"} has {$listsCount|default:0} {t}lists{/t}
                                        {/if}
                                    </td>
                                    <td align="right">{$paging}</td>
                                </tr>
                            </table>
                        </td>
                        <td align="right">
						{t var='button_01'}Add List{/t}
						{linkbutton name=$button_01}</td>
                    </tr>
                </table>
            </td>
        </tr>
        {foreach item=l name='lists' from=$listsList}
        <tr>
            <td valign="top"></td>
        </tr>
        {if !$smarty.foreach.lists.last}
        <tr>
            <td style="border-bottom:1px solid #DDDDDD" height="1"><img src="{$AppTheme->images}/decorators/px.gif" width="100%" height="1"></td>
        </tr>
        {/if}
        {foreachelse}
        <tr>
            <td align="center" style="padding-top:30px; padding-bottom:30px;">{t}No Lists{/t}</td>
        </tr>
        {/foreach}
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" width="100%" border="0">
                    <tr>
                        <td>
                            <table cellpadding="4" cellspacing="0" width="100%" border="0" bgcolor="#DDDDDD">
                                <tr>
                                    <td>
                                        {if $currentUser->getId() == $user->getId()}
                                            {t}You have{/t} {$listsCount|default:0} lists
                                        {else}
                                            {$currentUser->getLogin()|escape:"html"} {t}has{/t} {$listsCount|default:0} {t}lists{/t}
                                        {/if}
                                    </td>
                                    <td align="right">{$paging}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
