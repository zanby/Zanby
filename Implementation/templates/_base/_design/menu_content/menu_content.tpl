{if !$user->isAuthenticated()}
    <table width="215" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <img src="{$AppTheme->images}/decorators/right_top.gif" border="0">
            </td>
        </tr>
        <tr>
            <td background="{$AppTheme->images}/decorators/right_center.gif" align="center">
                <input type="image" src="{$AppTheme->images}/decorators/right_register_button.gif" onclick="document.location='/{$LOCALE}/registration/index/';">
            </td>
        </tr>
        <tr>
            <td>
                <img src="{$AppTheme->images}/decorators/right_bottom.gif" border="0">
            </td>
        </tr>
    </table>
{else}
    
    <table width="215" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0">
                    {foreach name="groups_f" item=g from=$user->getGroups()->getList()}
						{if $CurrentGroup && $CurrentGroup->getId() == $g->getId() }
                            <tr>
                                <td style="padding-top: 10px; padding-bottom: 10px;">
                                    {contentblock width="100%"}
                                        <table width="100%" cellpadding="0" cellspacing="3" border="0" bgcolor="#EEF4E5">
                                            <tr>
                                                <td rowspan="2">
                                                    <img src="{$g->getAvatar()->getSmall()}" width="48" height="48" border="0">
                                                </td>
                                                <td>
                                                    <b>{$g->getName()|escape:"html"}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    {t}Group details display at left{/t}
                                                </td>
                                            </tr>
                                        </table>
                                    {/contentblock}
                                </td>
                            </tr>
                        {else}
                            <tr>
                                <td style="padding:10px; font-size:12px;">
								<table width="100%" border="0">
								<tr><td>
                                   <div style="padding-bottom:7px;"><a href="{$g->getPath()}{$LOCALE}/summary/"><b>{$g->getName()|escape:"html"}</b></a></div>
                                   <div style="font-size:11px;"><a href="{$g->getPath()}{$LOCALE}/members/"><b>{$g->getMembers()->setMembersStatus('approved')->getCount()} {t}Members{/t}</b></a> - <a href="{$g->getPath()}{$LOCALE}/messages/"><b>{t}Messages{/t}</b></a><div>
                                   <div style="font-size:11px;"><a href="">{t}Set meeting{/t}</a> {if $g->getGroupType() == "family"}{t}(Family){/t}{/if}</div>
								   </td><td>

								   <img src="{$g->getAvatar()->getSmall()}" width="48" height="48" border="0">

								   </td></tr>
								   </table>
                                </td>
                            </tr>
                        {/if}
                        {if !$smarty.foreach.groups_f.last}
                        <tr>
                            <td style="border-bottom: 1px solid #E6F1D3; "><img src="{$AppTheme->images}/decorators/px.gif" width="100%" height="1"></td>
                        </tr>
                        {/if}
                    {/foreach}
                </table>
            </td>
        </tr>
    </table>
{/if}