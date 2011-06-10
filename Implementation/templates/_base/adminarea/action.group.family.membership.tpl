{tab template="admin_subtabs" active='group_family_membership'}
    {tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/groups/id/`$groupID`/" name="group_details"}{t}Group details{/t}{/tabitem}
    {tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/groupMembers/id/`$groupID`/" name="group_members"}{t}Group members{/t}{/tabitem}
    {tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/groupFamilyMembership/id/`$groupID`/" name="group_family_membership"}{t}Group Family membership{/t}{/tabitem}
{/tab}


{form from=$form}
<div class="prDropBoxInner">
	<h3 class="prTLeft">"{$group->getName()|escape}"</h3>
	
	<table cellpadding="0" cellspacing="0" border="0" class="prForm">
		<col width="30%" />
		<col width="25%" />
		<col width="45%" />
		<tr>
			<td class="prTRight"><label>{t}Attach Group to the Family:{/t}</label></td>
			<td class="prTLeft">{form_select name="familySelect" options=$families}</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight">&nbsp;</label></td>
			<td class="prTLeft">{t var="button_1"}Attach to Family{/t}
		        {linkbutton name=$button_1 link="javascript:void(0)" onclick="xajax_groupAddToFamily("|cat:$group->getId()|cat:", document.getElementById('familySelect').value); return false;"}</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight"><label class="prInnerTop">{t}Member of the families:{/t}</label></td>
			<td class="prTLeft" colspan="2">
				<table cellpadding="0" cellspacing="0" border="0" class="prForm">
					<col width="25%" />
					<col width="75%" />
					{foreach from=$familyList item=family}
					<tr>
						<td>{$family->getName()|escape}</td>
						<td><a href="javascript:void(0)" onclick="xajax_groupRemoveFromFamily({$group->getId()}, {$family->getId()}); return false;">detach from this family</a></td>
					</tr>
					{foreachelse}
                    <tr>
                        <td colspan=2>There are no families for current group.</td>
                    </tr>					
					{/foreach}
				</table>
			</td>
		</tr>
	</table>
	
</div>
{/form}