<div class="prInner themeA" id="light_{$cloneId}">
    <table class="prForm" cellspacing="0" cellpadding="0">
        <col width="93%" />
        <col width="7%" />
        <tbody>
            <tr id="pddiv_0_{$cloneId}" style="display:{if $hide[0]}none{else}table-row{/if};">
                <td>
                    <strong>{t}Username:{/t}</strong> {$userInfo->getLogin()|escape:'html'} </td>
                <td>
                    <span>
                    <a class="prCOHeaderClose"  onclick="profile_element_hide(0,1,'{$cloneId}');return false;" href="#null" title="remove section">&nbsp;</a>
                    </span>
                </td>
            </tr>
            <tr id="pddiv_1_{$cloneId}" style="display:{if $hide[1]}none{else}table-row{/if};">
                <td>
                    <strong>{t}Age:{/t}</strong> {$userInfo->getAge()|escape:'html'} </td>
                <td>
                    <span>
                    <a class="prCOHeaderClose"  onclick="profile_element_hide(1,1,'{$cloneId}');return false;" href="#null" title="remove section">&nbsp;</a>
                    </span>
                </td>
            </tr>
            <tr id="pddiv_2_{$cloneId}" style="display:{if $hide[2]}none{else}table-row{/if};">
                <td>
                    <strong>{t}Gender:{/t}</strong>
                    <select id="{$cloneId}_gender" name="gender" value="{$userInfo->getGender()|escape:html}" onclick="makeProfileChanges('{$cloneId}');return false;" >
                        <option value="male" {if $userInfo->getGender() == male}selected="selected"{/if}>{t}Male{/t}</option>
                        <option value="female" {if $userInfo->getGender() == female}selected="selected"{/if}>{t}Female{/t}</option>
                        <option value="unselected" {if $userInfo->getGender() == unselected}selected="selected"{/if}> </option>
                    </select>
                </td>
                <td>
                    <span>
                    <a class="prCOHeaderClose"  onclick="profile_element_hide(2,1,'{$cloneId}');return false;" href="#null" title="remove section">&nbsp;</a>
                    </span>
                </td>
            </tr>
            <tr id="pddiv_3_{$cloneId}" style="display:{if $hide[3]}none{else}table-row{/if};">
                <td>
                    <strong>{t}Real Name:{/t}</strong>
                    <input type="text" id="{$cloneId}_realname" name="realname" value="{$userInfo->getRealname()|escape:html}" onclick="makeProfileChanges('{$cloneId}');return false;" />
                </td>
                <td>
                    <span>
                    <a class="prCOHeaderClose"  onclick="profile_element_hide(3,1,'{$cloneId}');return false;" href="#null" title="remove section">&nbsp;</a>
                    </span>
                </td>
            </tr>
            <tr id="pddiv_4_{$cloneId}" style="display:{if $hide[4]}none{else}table-row{/if};">
                <td>
                    <strong>{t}Location:{/t}</strong> {if $userInfo->getCountry()->id==1 || $userInfo->getCountry()->id==38}
                    {$userInfo->getCity()->name|escape:'html'},&nbsp;{$userInfo->getState()->name|escape:'html'}
                    {else}
                    {$userInfo->getCity()->name|escape:'html'},&nbsp;{$userInfo->getCountry()->name|escape:'html'}
                    {/if} </td>
                <td>
                    <span>
                    <a class="prCOHeaderClose"  onclick="profile_element_hide(4,1,'{$cloneId}');return false;" href="#null" title="remove section">&nbsp;</a>
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
</div>
