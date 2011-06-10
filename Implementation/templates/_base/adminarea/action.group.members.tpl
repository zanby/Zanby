{literal}
    <script>
        var cfgGMembersApplication = null;
        if ( !cfgGMembersApplication ) {
        	cfgGMembersApplication = function () {
                return {
                    urlOnDeleteChecked  : '{/literal}{$BASE_URL}/{$LOCALE}/adminarea/groupMembers/id/{$groupID}/{literal}',
                }
            }();
        };
    </script>
{/literal}
<script type="text/javascript" src="{$AppTheme->common->js}/modules/adminarea/group.members.js"></script>

{tab template="admin_subtabs" active='group_members'}
	{tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/groups/id/`$groupID`/" name="group_details"}{t}Group details{/t}{/tabitem}
	{tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/groupMembers/id/`$groupID`/" name="group_members"}{t}Group members{/t}{/tabitem}
	{tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/groupFamilyMembership/id/`$groupID`/" name="group_family_membership"}{t}Group Family membership{/t}{/tabitem}
{/tab}

<div class="prDropBoxInner">
	<h3 class="prTLeft">"{$objGroup->getName()|escape}" members</h3>

    <div class="prTLeft prIndentTop">
    {$paging}
    </div>
    
	<div class="prTLeft prIndentTopLarge">
		<div class="prFloatLeft prIndentRight prInnerSmallTop">
            <a href="#" id="checkAll">Select All</a> | <a href="#" id="checkNone">None</a>
		</div>
		<div class="prFloatLeft prIndentLeftLarge">
			{t var="in_button"}Detach from the group{/t}{linkbutton style="margin-right: 5px;" name=$in_button link=# id="deleteChecked"}
		</div>
	</div>

	<table class="prResult" cellspacing="0" cellpadding="0">
	    <col width="5%" />
	    <col width="20%" />
	    <col width="15%" />
	    <col width="15%" />
	    <col width="15%" />
	    <col width="15%" />
	    <col width="15%" />
		<thead>
			<tr>
                <th>&nbsp;</th>
                <th class="prTLeft">
                    <div {if $order == 'login'} class="prRActive prRActive-{if $direction == 'asc'}top{else}bottom{/if}"{/if}>
                        <a class="{if $order == 'login'}freeClass{else}freeClass{/if}" href="{$BASE_URL}/{$LOCALE}/adminarea/groupMembers/id/{$groupID}/order/login{$search}/direction/{if $direction=='asc'}desc/{else}asc/{/if}">{t}Name{/t}</a>
                    </div>
			  </th>
			  <th class="prTLeft">{t}Location{/t}</th>
			  <th class="prTLeft">
			      <div {if $order == 'register_date'} class="prRActive prRActive-{if $direction == 'asc'}top{else}bottom{/if}"{/if}>
			      <a class="{if $order == 'register_date'}freeClass{else}freeClass{/if}" href="{$BASE_URL}/{$LOCALE}/adminarea/groupMembers/id/{$groupID}/order/register_date{$search}/direction/{if $direction=='asc'}desc/{else}asc/{/if}">{t}Registration Date{/t}</a>
			      </div>
			  </th>
			  <th class="prTLeft">
			      <div {if $order == 'last_access'} class="prRActive prRActive-{if $direction == 'asc'}top{else}bottom{/if}"{/if}>
			      <a class="{if $order == 'last_access'}freeClass{else}freeClass{/if}" href="{$BASE_URL}/{$LOCALE}/adminarea/groupMembers/id/{$groupID}/order/last_access{$search}/direction/{if $direction=='asc'}desc/{else}asc/{/if}">{t}Last On{/t}</a>
			      </div>
			  </th>
			  <th class="prTLeft">{t}User / Admin{/t}</th>
			  <th class="prTLeft">{t}Status / Login as{/t}</th>
			</tr>
		</thead>
		<tbody>
			{foreach item=u from=$membersList}
			<tr>
				<td>
					<input type="checkbox" value="{$u->getId()}"  class="item-checkbox"{*DON't REMOVE THIS CLASS, IT USED TO JS*} />
				</td>
				<td class="prTLeft">
					<img class="prFloatLeft" src="{$u->getAvatar()->setWidth(37)->setHeight(37)->setBorder(1)->getImage()}" title="" width="37" height="37" />
					<a href="{$admin->getAdminPath('members/id/')}{$u->getId()}/">{$u->getLogin()|escape:html|wordwrap:15:"<br />\n":true}({$u->getId()})</a><br />
					<div>{$u->getAge()} Yr old {if $u->getGender()==male}Man{elseif $u->getGender()==female}Women{/if}</div>
				</td>
				<td class="prTLeft">{$u->getCity()->name} {$u->getState()->name}</td>
				<td class="prTLeft">{$u->getRegisterDate()|date_locale:'DATE_MEDIUM'}</td>
				<td class="prTLeft">{if $u->getLastOnline()=='Online'}<div>online</div>{else}{$u->getLastOnline()}{/if}</td>
				<td class="prTLeft">
					{if $objGroup->getMembers()->isHost($u->getId())}HOST
					{elseif $objGroup->getMembers()->isCoHost($u->getId())}CO-HOST
					{else}Member{/if}
				</td>
				<td class="prTLeft">{if $u->getStatus() == 'active'}<a href="{$admin->getAdminPath('loginas/id/')}{$u->getId()}/" >{t}Login as this user{/t}</a>{else}{$u->getStatus()}{/if}</td>
			</tr>
		    {foreachelse}
		    <tr>
                <td colspan='7'>{t}There are no members in current group.{/t}</td>
		    </tr>
			{/foreach}
		</tbody>
	</table>
	
	<div class="prTLeft prIndentTop">
	{$paging}
	</div>
</div>

<!-- Popups -->
<div id="infoPanel" style="visibility:hidden; display:none;">
    <p class="prText2 prTCenter" id="infoPanelContent"></p>
</div>

<div id="confirmDeletePanel" style="display:none;" title="Detach Member(s)">
    <div>
        <table class="prForm">
            <tr>
                <td><p>{t}{tparam value=$objGroup->getName()|escape}Are you sure you want to detach choosed user(s) from the group  "%s"?{/t}</p></td>
            </tr>
            <tr>
                <td class="prTCenter">
                    <span>
                    {t var='button_18'}Detach Member(s){/t}
                    {linkbutton id="btnConfirmDeleteFormSubmit" name=$button_18}
                    </span> <span class="prIEVerticalAling">{t}or{/t} <a href="#" id="btnConfirmDeleteFormCancel" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
                </td>
            </tr>
        </table>
    </div>
</div>
<!-- Popups -->