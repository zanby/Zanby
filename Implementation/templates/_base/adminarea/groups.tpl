{literal}
    <script>
        var cfgDGListApplication = null;
        if ( !cfgDGListApplication ) {
        	cfgDGListApplication = function () {
                return {
                    urlOnDeleteChecked  : '{/literal}{$BASE_URL}/{$LOCALE}/adminarea/groups{literal}',
                    urlOnJoinChecked  : '{/literal}{$BASE_URL}/{$LOCALE}/adminarea/groups{literal}'
                }
            }();
        };
    </script>
{/literal}
<script type="text/javascript" src="{$AppTheme->common->js}/modules/adminarea/group.list.js"></script>
<div class="prTLeft prIndentTop">
    {form id="sForm" from=$form}
        <table>
	        <col width="9%" />
	        <col width="20%" />
	        <col width="20%" />
	        <col width="51%" />
            <tbody>
                <tr>
                    <td class="prTLeft"><label for="keyword" >{t}Keyword:{/t} </label></td>
                    <td class="prTLeft">{form_text id="keyword" name="keyword" value=$keyword|escape:"html"}</td>
                    <td class="prTLeft">{t var="in_submit"}Search{/t}{form_submit value=$in_submit name="searchForm"}</td>
                    <td class="prTLeft">{t var="in_button"}Import groups{/t}{linkbutton style="float:right; margin-top:7px;" name=$in_button link=$admin->getAdminPath('importgroups/')}</td>
                </tr>
            </tbody>
        </table>
    {/form}
</div>

<h3 class="prTLeft prIndentTop">Groups on {$SITE_NAME_AS_STRING}:</h3>

<!-- result begin -->
<div class="prTLeft prIndentTop">
{$paging}
</div>

<div class="prTLeft prIndentTopLarge">
    <div class="prFloatLeft prIndentRight prInnerSmallTop">
        <a href="#" id="checkAll">Select All</a> | <a href="#" id="checkNone">None</a>
    </div>
    <div class="prFloatLeft prIndentLeftLarge">
        {t var="in_button_02"}Delete{/t}{linkbutton style="margin-right: 5px;" name=$in_button_02 link=# id="deleteChecked"}&nbsp;
        {t var="in_button_03"}Join family{/t}{linkbutton style="" name=$in_button_03 link=# id="joinChecked"}
    </div>
</div>

<table cellspacing="0" cellpadding="0" class="prResult">
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
                <a class="{if $order == 'name'}freeClass{else}freeClass{/if}" href="{$admin->getAdminPath('groups/order/name')}{$search}/direction/{if $direction=='asc'}desc/{else}asc/{/if}">{t}Group Name{/t}</a>
                </div>
            </th>
            <th class="prTLeft">{t}Location{/t}</th>
            <th class="prTLeft">{t}Host{/t}</th>
            <th class="prTLeft">
                <div {if $order == 'creation_date'} class="prRActive prRActive-{if $direction == 'asc'}top{else}bottom{/if}"{/if}>
                <a class="{if $order == 'creation_date'}freeClass{else}freeClass{/if}" href="{$admin->getAdminPath('groups/order/creation_date')}{$search}/direction/{if $direction=='asc'}desc/{else}asc/{/if}">{t}Founded{/t}</a>
                </div>
            </th>
            <th class="prTLeft">{t}Members{/t}</th>
            <!--th class="prTLeft">{t}Group Type{/t}</th-->
            <th class="prTLeft">{t}Who can join?{/t}</th>
        </tr>
    </thead>
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
    <tr>
        <td colspan="8">{t}&nbsp;There are no groups in search results<br />
        &nbsp;Use the right utility to search again.{/t}<br />
        </td>
    </tr>
    {/foreach} 
</table>

<div class="prTLeft prIndentTop">
{$paging}
</div>
<!-- result end -->

<!-- Popups -->
<div id="infoPanel" style="visibility:hidden; display:none;">
    <p class="prText2 prTCenter" id="infoPanelContent"></p>
</div>

<div id="confirmDeletePanel" style="display:none;" title="Delete Group(s)">
    <div>
        <table class="prForm">
            <tr>
                <td><p>{t}Are you sure you want to delete choosed group(s)?{/t}</p></td>
            </tr>
            <tr>
                <td class="prTCenter">
                    <span>
                    {t var='button_18'}Delete Group(s){/t}
                    {linkbutton id="btnConfirmDeleteFormSubmit" name=$button_18}
                    </span> <span class="prIEVerticalAling">{t}or{/t} <a href="#" id="btnConfirmDeleteFormCancel" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
                </td>
            </tr>
        </table>
    </div>
</div>

<div id="joinFamilyPanel" style="display:none;" title="Attach Group(s) to the Family">
    <div>
        <table class="prForm">
            <tr>
                <td>
	                <label for="edit_weblink">{t}Choose Family{/t}</label>
	                <div id="fields_table">
	                   <select name="family_id" id="joinFamilyFormFamilyId">
	                       {foreach from=$families item='f' key='key'}
	                       <option value='{$key}'>{$f}</option>
	                       {/foreach}
	                   </select>
	                </div>
                </td>
            </tr>
            <tr>
                <td class="prTCenter">
                    <span>
                    {t var='button_18'}Attach to Family{/t}
                    {linkbutton id="btnJoinFamilyFormSubmit" name=$button_18}
                    </span> <span class="prIEVerticalAling">{t}or{/t} <a href="#" id="btnJoinFamilyFormCancel" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
                </td>
            </tr>
        </table>
    </div>
</div>

<!-- Popups -->
