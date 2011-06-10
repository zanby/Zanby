<link type="text/css" rel="stylesheet" href="{$AppTheme->css}/yui-autocomplete.css">
<script type="text/javascript" src="/js/yui/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="/js/yui/connection/connection-min.js"></script>
<script type="text/javascript" src="/js/yui/autocomplete/autocomplete-min.js"></script>
<script type="text/javascript" src="{$AppTheme->common->js}/modules/adminarea/group.details.js"></script>
{literal}
    <script>
        var cfgGDApplication = null;
        if ( !cfgGDApplication ) {
        	cfgGDApplication = function () {
                return {
                    urlOnChangeHost  : '{/literal}{$BASE_URL}/{$LOCALE}/adminarea/groups{literal}',
                    urlOnAddCoHost  : '{/literal}{$BASE_URL}/{$LOCALE}/adminarea/groups{literal}',
                    urlOnRemoveCoHost  : '{/literal}{$BASE_URL}/{$LOCALE}/adminarea/groups{literal}',
                   	urlOnDeleteGroup  : '{/literal}{$BASE_URL}/{$LOCALE}/adminarea/groups{literal}'
                }
            }();
        };
    </script>
{/literal}

{literal}
<style>
	.yui-ac-bd { height: 150px; overflow:auto; text-align:left;}
	#acCity .yui-ac-content .yui-ac-bd { background-color:#FFFFFF; text-align:left; }
	#acZip .yui-ac-content .yui-ac-bd { background-color:#FFFFFF; text-align:left; }
    #acNewHost .yui-ac-content {margin-top:20px;}
    #acNewHost .yui-ac-content .yui-ac-bd { background-color:#FFFFFF; text-align:left; }
    #acNewCoHost .yui-ac-content {margin-top:20px;}
    #acNewCoHost .yui-ac-content .yui-ac-bd { background-color:#FFFFFF; text-align:left; }
</style>
{/literal}

{tab template="admin_subtabs" active='group_details'}
	{tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/groups/id/`$groupID`/" name="group_details"}{t}Group details{/t}{/tabitem}
	{tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/groupMembers/id/`$groupID`/" name="group_members"}{t}Group members{/t}{/tabitem}
	{tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/groupFamilyMembership/id/`$groupID`/" name="group_family_membership"}{t}Group Family membership{/t}{/tabitem}
{/tab}

<div class="prDropBoxInner"> 
    {form from=$form id="gdForm" name="gdForm"}
    {form_hidden name="groupID" id="groupID" value=$group->getId()}
	{form_errors_summary width="300px"}
	<div class="prTLeft prText5 prIndentBottom">{t}Fields marked with asterisk <span class="prMarkRequired">*</span> are required.{/t}</div>
	<table cellpadding="0" cellspacing="0" border="0" class="prForm">
		<col width="30%" />
		<col width="40%" />
		<col width="30%" />
		<tr>
			<td class="prTRight"><label>{t}Group ID:{/t}</label></td>
			<td class="prTLeft">{$group->getId()|escape:"html"}</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight"><label>{t}Group host:{/t}</label></td>
			<td class="prTLeft">{if $group->getHost()}<a href="{$BASE_URL}/{$LOCALE}/adminarea/members/id/{$group->getHost()->getId()}/">{$group->getHost()->getLogin()|escape:html}</a>{else}{t}No Host{/t}{/if} <a href="#lnkChangeHost" class="prInnerLeft">{t}Change Host{/t}</a></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight"><span class="prMarkRequired">*</span>
				<label>{t}Group Name:{/t}</label></td>
			<td class="prTLeft"> {form_text name="gname" value=$group->getName()|escape:"html"} </td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight">
				<span class="prMarkRequired">*</span>
				<label>{t}Group Address:{/t}</label>
			</td>
			<td class="prTLeft"> {form_text id="gemail" name="gemail" value=$group->getPath()}</td>
			<td class="prTLeft">@{$DOMAIN_FOR_GROUP_EMAIL}</td>
		</tr>
		<tr>
			<td class="prTRight"><span class="prMarkRequired">*</span>
				<label>{t}Group Category:{/t}</label></td>
			<td class="prTLeft"> {form_select name="categoryId" selected=$group->getCategoryId() options=$categories} </td>
			<td>&nbsp;</td>
		</tr>
    
        {* LOCATION *}				
        <tr>
            <td class="prTRight"><span class="prMarkRequired">*</span>
                <label>{t}Country:{/t}</label></td>
            <td class="prTLeft"> {form_select id="countryId" name="countryId" selected=$countryId options=$countries onchange="xajax_detectCountry(this.options[this.selectedIndex].value);"} </td>
            <td>&nbsp;</td>
        </tr>
		<tr id="LocationTrZip" {if !$countryId || ($countryId != 1 && $countryId != 38)} style="display:none;"{/if}>		
            <td class="prTRight"><span class="prMarkRequired">*</span><label for="zipId">{t}Zip code:{/t}</label></td>
		    <td>
                <div class=" yui-skin-sam">
	                <div class="yui-ac"> {form_text name="zipcode" id="zipId"  value=$zipStr|escape:"html"}
	                    <div id="acZip"></div>
	                </div>
		        </div>
	        </td>
		    <td class="prTip">&nbsp;</td>
		</tr>
		<tr id="LocationTrCity" {if !$countryId || $countryId == 1 || $countryId == 38} style="display:none;"{/if}>		
			<td class="prTRight">
	            <span class="prMarkRequired">*</span><label for="city">{t}City:{/t}</label>
	        </td>
	        <td>
	            <div class=" yui-skin-sam">
	                <div class="yui-ac"> {form_text name="city" id="city"  value=$cityStr|escape:"html"}
	                    <div id="acCity"></div>
	                </div>
		        </div>
	        </td>
	        <td class="prTip"></td>
		</tr>		
		{* LOCATION *}
		
		
		
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		{*
		<tr>
			<td class="prTRight"><label>{t}Group Headline:{/t}</label></td>
			<td class="prTLeft">{form_text name="" }</td>
			<td class="prTLeft prText5">{t}100 characters available{/t}</td>
		</tr>
		*}
		<tr>
			<td class="prTRight"><span class="prMarkRequired">*</span>
				<label>{t}Description:{/t}</label></td>
			<td class="prTLeft"> {form_textarea name="description" value=$group->getDescription()|escape:"html" style="height:10em;"} </td>
			<td>&nbsp;</td>
		</tr>				
		<tr>
			<td class="prTRight"><label>{t}What are members called?{/t}</label></td>
			<td class="prTLeft"> {form_text name="membersName" value=$group->getMembersName()|escape:"html"} </td>
			<td class="prTLeft prText5">{t}100 characters available{/t}</td>
		</tr>
		<tr>
			<td class="prTRight"><span class="prMarkRequired">*</span>
				<label>{t}Tags:{/t}</label></td>
			<td class="prTLeft"> {form_text name="tags" value=$tags|escape:"html"} </td>
			<td>&nbsp;</td>
		</tr>
        {if HTTP_CONTEXT == 'zccf' || HTTP_CONTEXT == 'zccf-alt' || HTTP_CONTEXT == 'zccf-base'}
		<tr>
			<td class="prTRight">&nbsp;</td>
			<td class="prTLeft" colspan=2><div class="prTip">{t}Tags are a way to group your groups and are separated by spaces. Use quotes around multi-word tags like "Public LIbrary"{/t}</div></td>
		</tr>
        {/if}
		<tr>
			<td class="prTRight"><span class="prMarkRequired">*</span>
				<label>{t}Who can join?{/t}</label></td>
			<td class="prTLeft"> {form_radio id="h1" name="hjoin" value="0" checked=$group->getJoinMode()|default:"0"}
				<label for="h1">{t}Anyone{/t}</label>
				<br />
				{form_radio id="h2" name="hjoin" value="1" checked=$group->getJoinMode()}
				<label for="h2">{t}Only those I approve{/t}</label>
				<br />
				{form_radio id="h3" name="hjoin" value="2" checked=$group->getJoinMode()}
				<label for="h3">{t}Only those with a following code:{/t}</label>
				<div> {form_text name="jcode" value=$group->getJoinCode()|escape:"html"} </div></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight"><span class="prMarkRequired">*</span>
				<label>{t}Set Content Visibility:{/t}</label></td>
			<td class="prTLeft"> {form_radio id="g1" name="gtype" value="0" checked=$group->getIsPrivate()|default:"0"}
				<label for="g1">{t}Public{/t}</label><br/>
				{form_radio id="g2" name="gtype" value="1" checked=$group->getIsPrivate()}
				<label for="g2">{t}Private &ndash; Members Only*{/t}</label>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight"><span class="prMarkRequired">*</span>
				<label>{t}Notify host  about new members:{/t}</label></td>
			<td class="prTLeft">{form_checkbox id="" name="" value="0"}</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td></td>
			<td class="prTLeft"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td class="prTLeft">{t var="in_submit"}Save Changes{/t}{form_submit name="form_save" value=$in_submit}</td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<div class="prTLeft prIndentTop"></div>
	{/form} </div>
	
	<div class="prDropBoxInner prIndentTop">
		<h4 class="prTLeft">Delete the group</h4>
		<table cellpadding="0" cellspacing="0" border="0" class="prForm">
			<col width="30%" />
			<col width="40%" />
			<col width="30%" />
			<tr>
				<td class="prTLeft"><label>{t}This will completely delete the group from Zanby{/t}</label></td>
				<td class="prTLeft">
				    {if !$group->getGroupUID() }
				    {t var="in_button_01"}Delete the group{/t}
				    {linkbutton style="" name=$in_button_01 link=# id="lnkOnDeleteGroup"}
				    {else}
				    {t}Current group is system group. You can not delete current group.{/t}
				    {/if}
			    </td>
				<td>&nbsp;</td>
			</tr>
		</table>
	</div>
	
	<div class="prDropBoxInner prIndentTop">
		<table cellpadding="0" cellspacing="0" border="0" class="prForm">
			<col width="30%" />
			<col width="40%" />
			<col width="30%" />
			<tr>
				<td class="prTRight"><label>{t}Group host:{/t}</label></td>
				<td class="prTLeft">{if $group->getHost()}<a href="{$BASE_URL}/{$LOCALE}/adminarea/members/id/{$group->getHost()->getId()}/">{$group->getHost()->getLogin()|escape:html}</a>{else}{t}No Host{/t}{/if} <a href="#" class="prInnerLeft" id="lnkChangeHost" name="lnkChangeHost">{t}Change Host{/t}</a></td>
				<td>&nbsp;</td>
			</tr>
            <tr id="plhChangeHost" style="display:none;">
                <td class="prTRight">&nbsp;</td>
                <td class="prTLeft">
	                <div class=" yui-skin-sam">
	                    <div class="yui-ac">{form_text id="newHost" name="newHost" class="prFloatLeft prPerWidth31 prIndentRight" }
	                        <div id="acNewHost"></div>
	                    </div>
	                </div>
                    {t var="in_button_02"}Change Host{/t}{linkbutton style="" name=$in_button_02 link=# id="lnkOnChangeHostSubmit"}
                    {t var="in_button_02"}Cancel{/t}{linkbutton style="" name=$in_button_02 link=# id="lnkOnChangeHostCancel"}
                    <div class="prFormErrors" id="plhChangeHostErrors" style="display:none;">
                        <h1>Error Message:</h1>
                        <div class="prIndentBottom" id="plhChangeHostErrorsMessage"></div>
                    </div>
                </td>
                <td>&nbsp;</td>
            </tr>
            
            {if $coowners}
            {foreach from=$coowners item=co name='cohosts'}
			<tr id="plhCoHost_{$co->getId()}">
				<td class="prTRight">{if $smarty.foreach.cohosts.first }<label>{t}Group co-hosts:{/t}</label>{else}&nbsp;{/if}</td>
				<td class="prTLeft"><a href="#">{$co->getLogin()|escape:html}</a> <a href="#" class="prInnerLeft" onclick="GDApplication.onRemoveCoHost({$co->getId()}); return false;">Remove</a></td>
				<td>&nbsp;</td>
			</tr>
            {/foreach}
            {/if}
			<tr>
				<td class="prTRight"><label>{t}Add new co-host:{/t}</label></td>
				<td class="prTLeft">
                    <div class=" yui-skin-sam">
                        <div class="yui-ac">{form_text name="newCoHost" id="newCoHost" class="prFloatLeft prPerWidth31 prIndentRight" }
                            <div id="acNewCoHost"></div>
                        </div>
                    </div>
				    {t var="in_button_02"} Add {/t}{linkbutton style="" name=$in_button_02 link=# id="lnkOnAddCoHostSubmit"}
                    <div class="prFormErrors" id="plhAddCoHostErrors" style="display:none;">
                        <h1>Error Message:</h1>
                        <div class="prIndentBottom" id="plhAddCoHostErrorsMessage"></div>
                    </div>
                </td>
				<td>&nbsp;</td>
			</tr>
		</table>
	</div>
	
	<!-- Popups -->
	<div id="confirmDeletePanel" style="display:none;" title="Delete Group">
	    <div>
	        <form name="confirmDeleteForm" action="" method="post" id="confirmDeleteForm">
	        <input type="hidden" name="revision" id="revertrevision_revision" />
	        <input type="hidden" name="page" id="revertrevision_page" value="" />
	        <input type="hidden" name="folder_id" id="revertrevision_folder_id" value="" />
	        <input type="hidden" name="owner_id" id="revertrevision_owner_id" value="" />
	        <table class="prForm">
	            <tr>
	                <td><p>{t}Are you sure you want to delete group?{/t}</p></td>
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
	        </form>
	    </div>
	</div>
	<!-- Popups -->