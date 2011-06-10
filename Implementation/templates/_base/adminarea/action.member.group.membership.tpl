{literal}
    <script>
        var cfgMGListApplication = null;
        if ( !cfgMGListApplication ) {
        	cfgMGListApplication = function () {
                return {
                    urlOnDeleteChecked  : '{/literal}{$BASE_URL}/{$LOCALE}/adminarea/memberGroupMembership/id/{$memberID}/{literal}',
                }
            }();
        };
    </script>
{/literal}
<script type="text/javascript" src="{$AppTheme->common->js}/modules/adminarea/member.groups.list.js"></script>
{tab template="admin_subtabs" active='member_group_membership'}
	{tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/members/id/`$memberID`/" name="member_details"}{t}User details{/t}{/tabitem}
	{tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/memberGroupMembership/id/`$memberID`/" name="member_group_membership"}{t}Group membership{/t}{/tabitem}
{/tab}

<div class="prDropBoxInner">
	<h3 class="prTLeft">User "{$objUser->getLogin()|escape}" is the member of the following groups:</h3>

	<div class="prTLeft prIndentTop">
	{$paging}
	</div>

	<div class="prTLeft prIndentTopLarge">
		<div class="prFloatLeft prIndentRight prInnerSmallTop">
            <a href="#" id="checkAll">Select All</a> | <a href="#" id="checkNone">None</a>
		</div>
		<div class="prFloatLeft prIndentLeftLarge">
			{t var="in_button"}Detach from the group(s){/t}{linkbutton style="margin-right: 5px;" name=$in_button link=# id="lnkOnDeleteChecked"}
		</div>
	</div>

	<table class="prResult" cellspacing="0" cellpadding="0">
	    <col width="3%" />
	    <col width="5%" />
	    <col width="15%" />
	    <col width="20%" />
	    <col width="15%" />
	    <col width="15%" />
	    <col width="10%" />
	    <col width="17%" />
		<thead>
	        <tr>
	            <th>&#160;</th>
	            <th>&#160;</th>
	            <th class="prTLeft">
	                <div {if $order == 'name'} class="prRActive prRActive-{if $direction == 'asc'}top{else}bottom{/if}"{/if}>
	                <a class="{if $order == 'name'}freeClass{else}freeClass{/if}" href="{$BASE_URL}/{$LOCALE}/adminarea/memberGroupMembership/id/{$memberID}/order/name{$search}/direction/{if $direction=='asc'}desc/{else}asc/{/if}">{t}Group Name{/t}</a>
	                </div>
	            </th>
	            <th class="prTLeft">{t}Location{/t}</th>
	            <th class="prTLeft">{t}Host{/t}</th>
	            <th class="prTLeft">
	                <div {if $order == 'creation_date'} class="prRActive prRActive-{if $direction == 'asc'}top{else}bottom{/if}"{/if}>
	                <a class="{if $order == 'creation_date'}freeClass{else}freeClass{/if}" href="{$BASE_URL}/{$LOCALE}/adminarea/memberGroupMembership/id/{$memberID}/order/creation_date{$search}/direction/{if $direction=='asc'}desc/{else}asc/{/if}">{t}Founded{/t}</a>
	                </div>
	            </th>
	            <th class="prTLeft">{t}Members{/t}</th>
	            <!--th class="prTLeft">{t}Group Type{/t}</th-->
	            <th class="prTLeft">{t}Who can join?{/t}</th>
	        </tr>
		</thead>
		<tbody>
            {foreach item=group from=$groupsList}
		    <tr>
		        <td>
		            <input type="checkbox" value="{$group->getId()}" class="item-checkbox"{*DON't REMOVE THIS CLASS, IT USED TO JS*} />
		        </td>
		        <td class="prTLeft"><img src="{$group->getAvatar()->setWidth(37)->setHeight(37)->setBorder(1)->getImage()}" border="0"></td>
		        <td class="prTLeft" style="text-align: center;"><a style="font-size: 1em;" href="{$admin->getAdminPath('groups/id/')}{$group->getId()}/">{$group->getName()|escape|wordwrap:13:"\n":true}({$group->getId()})</a></td>
		        <td class="prTLeft">{$group->getCity()->name},{$group->getState()->name}</td>
		        <td class="prTLeft">{assign var="host" value=$group->getHost()}{if $host->getId()}<a href="{$admin->getAdminPath('members/id/')}{$host->getId()}">{$host->getLogin()|escape}</a>{/if}</td>
		        <td class="prTLeft">{$group->getCreateDate()|date_locale:'DATE_MEDIUM'}</td>
		        <td class="prTLeft">{$group->getMembers()->setMembersStatus('approved')->getCount()}</td>
		        <!--td class="prTLeft">{$group->getGroupType()|escape:"html"}</td-->
		        {if $group->getJoinMode() == 0}
		            <td class="prTLeft">{t}Anyone may join{/t}</td>
		        {elseif $group->getJoinMode() == 1}
		            <td class="prTLeft">{t}Contact Host to request membership{/t}</td>
		        {elseif $group->getJoinMode() == 2}
		            <td class="prTLeft">{t}Join with Code{/t}</td>
		        {/if}
		    </tr>
		    {foreachelse}
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

<div id="confirmDeletePanel" style="display:none;" title="Detach from Group(s)">
    <div>
        <table class="prForm">
            <tr>
                <td><p>{t}Are you sure you want to detach from choosed group(s)?{/t}</p></td>
            </tr>
            <tr>
                <td class="prTCenter">
                    <span>
                    {t var='button_18'}Detach from Group(s){/t}
                    {linkbutton id="btnConfirmDeleteFormSubmit" name=$button_18}
                    </span> <span class="prIEVerticalAling">{t}or{/t} <a href="#" id="btnConfirmDeleteFormCancel" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
                </td>
            </tr>
        </table>
    </div>
</div>
<!-- Popups -->