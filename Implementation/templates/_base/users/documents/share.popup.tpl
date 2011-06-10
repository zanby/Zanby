{*popup_item*}
<div id="shareFilePanel">
		<table class="prForm">
            <tr>
                <td>
					<span class="prMarkRequired">{t}Note:
                    Only groups which you host, or in which the
                    host has granted you permission to upload documents will appear
                    in this field. if you do not see the desired group, you must contact
                    the host to get permission to share your document{/t}</span>
                </td>
            </tr>   
            {if $groupsList}  
                <tr>
                    <td>
                        <label for="new_file">{t}Select a group to share this document:{/t}</label>
                        <div id="fields_table">
                            <select name='group_id' id='sharefile_group_id' class='prMiddleFormItem'>
                                {foreach item=g from=$groupsList key=groupId}
                                    {assign var="checkId" value="family_"|cat:$g->getId()}
                                    <option value="{if $groupId eq $checkId}{$checkId}{else}{$g->getId()}{/if}">
                                        {if $groupId eq $checkId}
                                            {$familyNotSharedWithAlias[$checkId]|escape:"html"}
                                        {else}
                                            {$g->getName()|escape:"html"}
                                        {/if}
                                    </option>
                                {/foreach}
                            </select>
                        </div>
                    </td>
                </tr>     
                <tr>
                    <td>
					{t var='in_button'}Share Document{/t}
					{linkbutton id="btnShareDocumentToGroup" name=$in_button link="javascript:void(0)"}
					</td>
                </tr>
            {else}  
            	<tr>
                	<td>{t}<span class="prMarkRequired">Note: There are no groups for share this document{/t}</span></td>
                </tr>  
            {/if}
            {if $friendsList}
                <tr>
                    <td>
                        <label for="new_file">{t}Select a friend to share this document:{/t}</label>
                        <div id="fields_table">
                            <select name='friend_id' id='sharefile_friend_id' class='prMiddleFormItem'>
                                {foreach key=id item=name from=$friendsList}
                                    <option value="{$id}">{$name|escape:"html"}</option>
                                {/foreach}
                            </select>
                        </div>
                    </td>
                </tr>     
                <tr>
                    <td>
					{t var='button_01'}Share Document{/t}
					{linkbutton id="btnShareDocumentToUser" name=$button_01 link="javascript:void(0)"}</td>
                </tr>
            {else}     
                <tr>
                    <td>{t}<span class="prMarkRequired">Note: There are no friends for share this document{/t}</span></td>
                </tr>     
            {/if}
            {if $sharedGroupsList || $sharedFriendsList }
            <tr>
                <td>{t}This document is shared to group(s) or friend(s).{/t} <a href="javascript:void(0)" id="lnkManageSharing">{t}Manage Sharing{/t}</a></td>
            </tr>     
            {/if}
            <tr>
                <td></td>
            </tr>     
		</table>
</div>
{*popup_item*}