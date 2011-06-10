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

{tab template="admin_subtabs" active='family_details'}
	{tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/families/id/`$groupID`/" name="family_details"}{t}Family details{/t}{/tabitem}
	{tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/familyMembers/id/`$groupID`/" name="family_members"}{t}Group Family  Members{/t}{/tabitem}
{/tab}

<div class="prDropBoxInner">
	<h3 class="prTLeft">FamilyName details:</h3>
	{form from=$editFamilyForm id="gdForm" name="gdForm"}
	{form_hidden name="groupID" id="groupID" value=$group->getId()}
	{form_errors_summary width="300px"}

	<table cellpadding="0" cellspacing="0" border="0" class="prForm">
		<col width="30%" />
		<col width="40%" />
		<col width="30%" />
		<tr>
			<td class="prTRight"><label>{t}Group family  ID:{/t}</label></td>
			<td class="prTLeft">{$group->getId()}</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight"><label>{t}Family owner:{/t}</label></td>
			<td class="prTLeft">{if $group->getHost()}<a href="{$BASE_URL}/{$LOCALE}/adminarea/members/id/{$group->getHost()->getId()}/">{$group->getHost()->getLogin()|escape:html}</a>{else}{t}No Host{/t}{/if} <a href="#lnkChangeHost" class="prInnerLeft">{t}Change Host{/t}</a></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight"><span class="prMarkRequired">*</span>
				<label>{t}Family Name:{/t}</label></td>
			<td class="prTLeft">{form_text id="gname" name="gname" value=$group->getName()}</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight">
				<span class="prMarkRequired">*</span>
				<label>{t}Family address:{/t}</label>
			</td>
			<td class="prTLeft"> {form_text id="gemail" name="gemail" value=$group->getPath()}</td>
			<td class="prTLeft">@{$DOMAIN_FOR_GROUP_EMAIL}</td>
		</tr>
		<tr>
			<td class="prTRight"><span class="prMarkRequired">*</span>
				<label>{t}Family category:{/t}</label></td>
			<td class="prTLeft"> {form_select name="categoryId" selected=$group->getCategoryId() options=$categories} </td>
			<td>&nbsp;</td>
		</tr>
		
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		
		<tr>
			<td class="prTRight"><label>{t}Family Headline:{/t}</label></td>
			<td class="prTLeft">{form_text name="gheadline" id="gheadline" value=$group->getHeadline() }</td>
			<td class="prTLeft prText5">{t}100 characters available{/t}</td>
		</tr>
		<tr>
			<td class="prTRight"><span class="prMarkRequired">*</span> <label>{t}Description:{/t}</label></td>
			<td class="prTLeft"> {form_textarea name="description" value=$group->getDescription()|escape:"html" style="height:10em;"} </td>
			<td class="prTLeft prText5">{t}2000 characters available{/t}</td>
		</tr>
		<tr>
			<td class="prTRight">
				<label for="tags">{t}Tags:{/t}</label></td>
			<td class="prTLeft"> {form_text name="tags" id="tags" value=$tags|escape:"html"} </td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight">
				<label for="company">{t}Company:{/t}</label></td>
			<td class="prTLeft">{form_text name="company" id="company" value=$group->getCompany()|escape}</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight">
				<label for="position">{t}Position:{/t}</label></td>
			<td class="prTLeft">{form_text name="position" id="position" value=$group->getPosition()|escape}</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight"><span class="prMarkRequired">*</span>
				<label for="address1">{t} Address1:{/t}</label></td>
			<td class="prTLeft">{form_text name="address1" value="address1" value=$group->getAddress1()|escape}</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="prTRight">
				<label for="address2">{t} Address2:{/t}</label></td>
			<td class="prTLeft">{form_text name="address2" id="address2" value=$group->getAddress2()|escape}</td>
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
	{/form} 
</div>
	
	<div class="prDropBoxInner prIndentTop">
		<table cellpadding="0" cellspacing="0" border="0" class="prForm">
			<col width="30%" />
			<col width="40%" />
			<col width="30%" />
			<tr>
				<td class="prTRight"><label>{t}Group family OWNER:{/t}</label></td>
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
                <td class="prTRight">{if $smarty.foreach.cohosts.first }<label>{t}Group family co-owners:{/t}</label>{else}&nbsp;{/if}</td>
                <td class="prTLeft"><a href="#">{$co->getLogin()|escape:html}</a> <a href="#" class="prInnerLeft" onclick="GDApplication.onRemoveCoHost({$co->getId()}); return false;">Remove</a></td>
                <td>&nbsp;</td>
            </tr>
            {/foreach}
            {/if}

            <tr>
                <td class="prTRight"><label>{t}Add new co-owner:{/t}</label></td>
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
