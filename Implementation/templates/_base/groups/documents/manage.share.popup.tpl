{*popup_item*}
<div id="shareFilePanel">
<div><a href="javascript:void(0)" onclick="DocumentApplication.shareDocument('{$document->getId()}'); return false;">{t}Return{/t}</a></div>
		<div class="prPopupHeight"><table class="prResult"> 
        	<col width="80%" />
        	<col width="20%" /> 
        	{foreach item=g from=$sharedGroupsList key=groupId}
            	{if $Warecorp_Document_AccessManager->canUnshareDocument($document, $CurrentGroup, $user->getId())}
                    <tr>
                        <td>
                            {assign var="checkId" value="family_"|cat:$g->getId()}
                            {if $groupId eq $checkId}
                                {$familySharedWithAlias[$checkId]|escape:"html"}
                            {else}
                                {$g->getName()|escape:"html"}
                            {/if}
                        </td>
                        <td><a class="prFloatLeft" href="javascript:void(0)" onclick="DocumentApplication.manageSharing('{$document->getId()}', 'group', '{if $groupId eq $checkId}{$checkId}{else}{$g->getId()}{/if}'); return false;">{t}Unshare{/t}</a></td>
                    </tr>     
            	{/if}
            {/foreach}
            {foreach item=u from=$sharedFriendsList}
            	{if $Warecorp_Document_AccessManager->canUnshareDocument($document, $CurrentGroup, $user->getId())}
                    <tr>
                        <td>
                        {t}{tparam value=$u->getLogin()|escape:"html"}Shared with %s{/t}                        
                        </td>
                    	<td><a class="prFloatLeft" href="javascript:void(0)" onclick="DocumentApplication.manageSharing('{$document->getId()}', 'user', '{$u->getId()}'); return false;">{t}Unshare{/t}</a></td>
                    </tr>   
            	{/if}
            {/foreach} 
		</table></div>
		<div><a href="javascript:void(0)" onclick="DocumentApplication.shareDocument('{$document->getId()}'); return false;">{t}Return{/t}</a></div>
</div>
{*popup_item*}