{literal}
    <script>
        var cfgMListApplication = null;
        if ( !cfgMListApplication ) {
        	cfgMListApplication = function () {
                return {
                	urlOnActivateChecked  : '{/literal}{$BASE_URL}/{$LOCALE}/adminarea/members{literal}',
               		urlOnBlockChecked  : '{/literal}{$BASE_URL}/{$LOCALE}/adminarea/members{literal}',
                    urlOnDeleteChecked  : '{/literal}{$BASE_URL}/{$LOCALE}/adminarea/members{literal}'
                    
                }
            }();
        };
    </script>
{/literal}
<script type="text/javascript" src="{$AppTheme->common->js}/modules/adminarea/members.list.js"></script>

<div class="prTLeft prIndentTop">
	{form id="sForm" from=$form}
		<table class="">
		<col width="9%" />
		<col width="20%" />
		<col width="20%" />
		<col width="51%" />
		<tbody>		
		<tr>
		    <td class="prTLeft">
		        <label for="keyword" >{t}Keyword:{/t} </label>
            </td>
            <td class="prTLeft">
                {form_text id="keyword" name="keyword" value=$keyword|escape:"html" } 
		    </td>
		    <td class="prTLeft">
		        {t var="in_submit"}Search{/t}{form_submit value=$in_submit name="searchForm"}
		    </td>
		    <td class="prTLeft"> 
            {t var="in_button"}Import members{/t}{linkbutton style="float:right; margin-top:7px;" name=$in_button link=$admin->getAdminPath('importmembers/')}
		     <!--<a href="{$admin->getAdminPath('newmember/')}">new member</a>-->&nbsp;
		    </td>
		</tr>
		</tbody>		
		</table>
	{/form}
</div>

<h3 class="prTLeft prIndentTop">Members of {$SITE_NAME_AS_STRING}:</h3>

<!-- result begin -->
<div class="prTLeft prIndentTop">
{$paging}
</div>

<div class="prTLeft prIndentTopLarge">
	<div class="prFloatLeft prIndentRight prInnerSmallTop">
	   <a href="#" id="checkAll">Select All</a> | <a href="#" id="checkNone">None</a>
	</div>
	<div class="prFloatLeft prIndentLeftLarge">
		{t var="in_button_02"}Activate{/t}{linkbutton style="margin-right: 5px;" name=$in_button_02 link=# id="lnkOnActivateChecked"}&nbsp;
		{t var="in_button_03"}Block{/t}{linkbutton style="" name=$in_button_03 link=# id="lnkOnBlockChecked"}&nbsp;
		{t var="in_button_04"}Delete{/t}{linkbutton style="" name=$in_button_04 link=# id="lnkOnDeleteChecked"}
	</div>
</div>

<table cellspacing="0" cellpadding="0" class="prResult">
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
	    	<a class="{if $order == 'login'}freeClass{else}freeClass{/if}" href="{$admin->getAdminPath('members/order/login')}{$search}/direction/{if $direction=='asc'}desc/{else}asc/{/if}">{t}Name{/t}</a>
	    	</div>
	    </th>
		<th class="prTLeft">
			{t}Location{/t}
		</th>
	    <th class="prTLeft">
	    	<div {if $order == 'register_date'} class="prRActive prRActive-{if $direction == 'asc'}top{else}bottom{/if}"{/if}>
	    	<a class="{if $order == 'register_date'}freeClass{else}freeClass{/if}" href="{$admin->getAdminPath('members/order/register_date')}{$search}/direction/{if $direction=='asc'}desc/{else}asc/{/if}">{t}Registration Date{/t}</a>
	    	</div>
	    </th>
	    <th class="prTLeft">
	    	<div {if $order == 'last_access'} class="prRActive prRActive-{if $direction == 'asc'}top{else}bottom{/if}"{/if}>
	    	<a class="{if $order == 'last_access'}freeClass{else}freeClass{/if}" href="{$admin->getAdminPath('members/order/last_access')}{$search}/direction/{if $direction=='asc'}desc/{else}asc/{/if}">{t}Last On{/t}</a>
	    	</div>
	    </th>
		<th class="prTLeft">{t}User / Admin{/t}</th>
	    <th class="prTLeft">{t}Status / Login as{/t}</th>
	  </tr>
	  </thead>
	{foreach item=u from=$membersList}
	  <tr>
	  	<td><input type="checkbox" value="{$u->getId()}" class="item-checkbox"{*DON't REMOVE THIS CLASS, IT USED TO JS*} /></td>
	    <td class="prTLeft">
		    <img class="prFloatLeft" src="{$u->getAvatar()->setWidth(37)->setHeight(37)->setBorder(1)->getImage()}" title="" width="37" height="37" />
		    <a href="{$admin->getAdminPath('members/id/')}{$u->getId()}/">{$u->getLogin()|escape:html|wordwrap:15:"<br />\n":true}({$u->getId()})</a><br />
		    <div>{$u->getAge()} Yr old {if $u->getGender()==male}Man{elseif $u->getGender()==female}Women{/if}</div>
	    </td>
		<td class="prTLeft">{$u->getCity()->name}, {$u->getState()->name}</td>
	    <td class="prTLeft">{$u->getRegisterDate()|date_locale:'DATE_MEDIUM'}</td>
	    <td class="prTLeft">{if $u->getLastOnline()=='Online'}<div>online</div>{else}{$u->getLastOnline()}{/if}</td>
		<td class="prTLeft"></td>
	    <td class="prTLeft">{if $u->getStatus() == 'active'}<a href="{$admin->getAdminPath('loginas/id/')}{$u->getId()}/" >{t}Login as this user{/t}</a>{else}{$u->getStatus()}{/if}</td>
	  </tr>
	{foreachelse}
      <tr>
        <td colspan="7">
            {t}&nbsp;There are no members in search results<br />
            &nbsp;Use the right utility to search again.<br />{/t}
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

<div id="confirmDeletePanel" style="display:none;" title="Delete Members(s)">
    <div>
        <table class="prForm">
            <tr>
                <td><p>{t}Are you sure you want to delete choosed members(s)?{/t}</p></td>
            </tr>
            <tr>
                <td class="prTCenter">
                    <span>
                    {t var='button_18'}Delete Members(s){/t}
                    {linkbutton id="btnConfirmDeleteFormSubmit" name=$button_18}
                    </span> <span class="prIEVerticalAling">{t}or{/t} <a href="#" id="btnConfirmDeleteFormCancel" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
                </td>
            </tr>
        </table>
    </div>
</div>
<!-- Popups -->