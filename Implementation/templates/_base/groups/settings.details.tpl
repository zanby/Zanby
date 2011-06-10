<link type="text/css" rel="stylesheet" href="{$AppTheme->css}/yui-autocomplete.css">
<script type="text/javascript" src="/js/yui/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="/js/yui/connection/connection-min.js"></script>
<script type="text/javascript" src="/js/yui/autocomplete/autocomplete-min.js"></script>

{literal}
<style>
.yui-ac-bd {
	height: 150px; 
	overflow:auto;
}
#acCity .yui-ac-content .yui-ac-bd {
    background-color:#FFFFFF;
}
#acZip .yui-ac-content .yui-ac-bd {
    background-color:#FFFFFF;
}
</style>
<script type="text/javascript">
	/*
	*/
    var m_city_fCallBackToAutocomplete;
    var m_city_oParent;
    var m_city_sQuery;
	YAHOO.widget.DS_XAJAX_CITY = function() { this._init(); };
	YAHOO.widget.DS_XAJAX_CITY.prototype = new YAHOO.widget.DataSource();
	YAHOO.widget.DS_XAJAX_CITY.prototype.doQuery = function(oCallbackFn, sQuery, oParent) { doQueryXajaxCity(oCallbackFn, oParent, sQuery); return; }
    function doQueryXajaxCity(fCallBack, oParent, sQuery)
    {
        m_city_fCallBackToAutocomplete 	= fCallBack;
        m_city_oParent 					= oParent;
        m_city_sQuery 					= sQuery;
		var country = YAHOO.util.Dom.get('countryId');
		country = country.options[country.selectedIndex].value;
        xajax_autoCompleteCity(country, decodeURIComponent(sQuery), "autocompleteCallbackCity");
    }                           
    function autocompleteCallbackCity(sResponse)  {
        if (sResponse.constructor != Array) sResponse = new Array();
        m_city_fCallBackToAutocomplete(m_city_sQuery, sResponse, m_city_oParent);
    }
	var itemCitySelectHandler = function(sType, aArgs) {
		YAHOO.util.Dom.get('acCity').style.display = 'none';
	};
	var containerCityCollapseEvent = function(oSelf) {
		YAHOO.util.Dom.get('acCity').style.display = '';
	}
    var myDataSourceCity = new YAHOO.widget.DS_XAJAX_CITY();
	function initCityAutocomplete() {
		var cityAutoComp = new YAHOO.widget.AutoComplete("city", "acCity", myDataSourceCity);
		cityAutoComp.maxResultsDisplayed 	= 1000;
		cityAutoComp.minQueryLength 		= 1; 
        cityAutoComp.animSpeed				= 0.01;
		cityAutoComp.itemSelectEvent.subscribe(itemCitySelectHandler);
		cityAutoComp.containerCollapseEvent.subscribe(containerCityCollapseEvent);
	}
	/**
	*
	*/
    var m_zip_fCallBackToAutocomplete;
    var m_zip_oParent;
    var m_zip_sQuery;
	YAHOO.widget.DS_XAJAX_ZIP = function() { this._init(); };
	YAHOO.widget.DS_XAJAX_ZIP.prototype = new YAHOO.widget.DataSource();
	YAHOO.widget.DS_XAJAX_ZIP.prototype.doQuery = function(oCallbackFn, sQuery, oParent) { doQueryXajaxZip(oCallbackFn, oParent, sQuery); return; }
    function doQueryXajaxZip(fCallBack, oParent, sQuery)
    {
        m_zip_fCallBackToAutocomplete 	= fCallBack;
        m_zip_oParent 					= oParent;
        m_zip_sQuery 					= sQuery;
		var country = YAHOO.util.Dom.get('countryId');
		country = country.options[country.selectedIndex].value;
        xajax_autoCompleteZip(country, decodeURIComponent(sQuery), "autocompleteCallbackZip");
    }                           
    function autocompleteCallbackZip(sResponse)  {
        if (sResponse.constructor != Array) sResponse = new Array();
        m_zip_fCallBackToAutocomplete(m_zip_sQuery, sResponse, m_zip_oParent);
    }
	var itemZipSelectHandler = function(sType, aArgs) {
		YAHOO.util.Dom.get('acZip').style.display = 'none';
	};
	var containerZipCollapseEvent = function(oSelf) {
		YAHOO.util.Dom.get('acZip').style.display = '';
	}
    var myDataSourceZip = new YAHOO.widget.DS_XAJAX_ZIP();
	function initZipAutocomplete() {
		var zipAutoComp = new YAHOO.widget.AutoComplete("zipId", "acZip", myDataSourceZip);
		zipAutoComp.maxResultsDisplayed 	= 1000;
		zipAutoComp.minQueryLength 			= 3; 
        zipAutoComp.animSpeed				= 0.01;
		zipAutoComp.itemSelectEvent.subscribe(itemZipSelectHandler);
		zipAutoComp.containerCollapseEvent.subscribe(containerZipCollapseEvent);
	}
</script>
{/literal}

{literal}
<script language="javascript">
	function showGroupDetailsFormErrors (errors) {
		if ( errors != "" ) {
			document.getElementById("GroupDetailsErrorsTD").innerHTML = errors;
			document.getElementById("GroupDetailsErrorsTR").style.display = "";
			document.getElementById("GroupSettingsGroupDetailsAnchor").focus();
		}
	}
	function hideGroupDetailsFormErrors () {
		document.getElementById("GroupDetailsErrorsTR").style.display = "none";
	}
	function redirectGroupDetailsFormErrors(url) {
		document.location.href = url;
	}
	</script>
{/literal}

{if $visibility_details == "groupdetails"}
<script>xajax_privileges_group_details_show('{$currentGroup->getId()}');</script>
{else}
    {if $visibility == true }
		{form from=$form onsubmit="xajax_privileges_group_details_save(xajax.getFormValues('dForm')); return false;" id="dForm" name="dForm"}	
		{form_hidden name="groupId" id="groupId" value=$currentGroup->getId()}
		{form_errors_summary}
<table class="prForm">
	<col width="30%" />
	<col width="40%" />
	<col width="30%" />
	<tbody>
		<tr>
			<td colspan="2" class="prText5 prTRight"><span class="prMarkRequired">*</span> {t}Required Fields{/t}</td>
			<td></td>
		</tr>
		<tr>
			<td class="prTRight"><span class="prMarkRequired">*</span>
				<label for="categoryId">{t}Group Category:{/t}</label></td>
			<td> {form_select name="categoryId" selected=$currentGroup->getCategoryId() options=$categories} </td>
			<td class="prTip"></td>
		</tr>
		<tr>
			<td class="prTRight"><span class="prMarkRequired">*</span>
				<label for="countryId">{t}Country:{/t}</label></td>
			<td> {form_select id="countryId" name="countryId" selected=$countryId options=$countries onchange="xajax_detectCountry(this.options[this.selectedIndex].value);"} </td>
			<td class="prTip"></td>
		</tr>
	{* {t}CHANGES FOR LOCATIONS START{/t} *}
	<tr id="LocationTrZip" {if !$countryId || ($countryId != 1 && $countryId != 38)} style="display:none;"{/if}>
	
	<td class="prTRight"><span class="prMarkRequired">*</span>
			<label for="zipId">{t}Zip code:{/t}</label></td>
		<td><div class=" yui-skin-sam">
				<div class="yui-ac"> {form_text name="zipcode" id="zipId"  value=$zipStr|escape:"html"}
					<div id="acZip"></div>
				</div>
			</div></td>
		<td class="prTip"></td>
	</tr>
	<tr id="LocationTrCity" {if !$countryId || $countryId == 1 || $countryId == 38} style="display:none;"{/if}>
	
	<td class="prTRight"><span class="prMarkRequired">*</span>
			<label for="city">{t}City:{/t}</label></td>
		<td><div class=" yui-skin-sam">
				<div class="yui-ac"> {form_text name="city" id="city"  value=$cityStr|escape:"html"}
					<div id="acCity"></div>
				</div>
			</div></td>
		<td class="prTip"></td>
	</tr>
	{* {t}CHANGES FOR LOCATIONS END{/t} *}
	<tr>
		<td class="prTRight"><span class="prMarkRequired">*</span>
			<label for="gname">{t}Group Name:{/t}</label></td>
		<td> {form_text name="gname" value=$currentGroup->getName()|escape:"html" } </td>
		<td class="prTip"></td>
	</tr>
	<tr>
		<td class="prTRight"><label for="membersName">{t}What are members called?{/t}</label></td>
		<td> {form_text name="membersName" value=$currentGroup->getMembersName()|escape:"html" } </td>
		<td class="prTip">{t}100 characters available{/t}</td>
	</tr>
	<tr>
		<td class="prTRight"><span class="prMarkRequired">*</span>
			<label for="gemail">{t}Group Address:{/t}</label>
		</td>
		<td colspan="2" class="prTip prText2"> {t}		
			Note: When you change your group's address, you're changing the address your subscribers will use to send email and the web address people will use to access your group. People who visit the old address will not reach your group.{/t} </td>
	</tr>
	<tr>
		<td></td>
		<td> {form_text id="gemail" name="gemail" value=$currentGroup->getPath() dir="rtl"} </td>
		<td>@{$DOMAIN_FOR_GROUP_EMAIL}</td>
	</tr>
	<tr>
		<td></td>
		<td colspan="2"><div class="prTip">
				<p>{t}
					Name may contain letters, numbers, - or _, 60 Characters Max{/t} </p>
				<p class="prIndentTopSmall">{t}
					The group address is used for the group web address, host email account and group discussions.{/t} </p>
				<p class="prIndentTopSmall">{t}{tparam value=$BASE_HTTP_HOST}<span class="prText2">Web Address:</span>  http://%s/en/group/groupsaddressname{/t}</p>
				<p class="prIndentTopSmall">{t}{tparam value=$DOMAIN_FOR_GROUP_EMAIL}<span class="prText2">Group Discussions Email:</span> groupsaddressname@%s{/t} </p>
				<p class="prIndentTopSmall">{t}{tparam value=$DOMAIN_FOR_GROUP_EMAIL} <span class="prText2">Host Email Address:</span> groupsaddressname_host@%s{/t} </p>
			</div></td>
	</tr>
	<tr>
		<td class="prTRight"><span class="prMarkRequired">*</span>
			<label for="description">{t}Description:{/t}</label></td>
		<td> {form_textarea name="description" value=$currentGroup->getDescription()|escape:"html" } </td>
		<td class="prTip"></td>
	</tr>
	<tr>
		<td></td>
		<td colspan="2"><div class="prTip">{t}2000 characters available<br/>
				The first few words of your description will appear in a search result. 
				The full description will appear in your group profile.{/t} </div></td>
	</tr>
	<tr>
		<td class="prTRight"><label for="tags">{t}Tags:{/t}</label></td>
		<td>{form_text name="tags" value=$tags|escape:"html"}
			<div class="prTip prIndentTopSmall">{t}Enter the top five keywords that describe your group{/t}</div></td>
		<td class="prTip"></td>
	</tr>
	<tr>
		<td class="prTRight"><span class="prMarkRequired">*</span>
			<label>{t}Who can join?{/t}</label></td>
		<td> {form_radio id="h1" name="hjoin" value="0" checked=$currentGroup->getJoinMode()|default:"0"}
			<label for="h1">{t}Anyone{/t}</label>
			<div class="prIndentTopSmall"> {form_radio id="h2" name="hjoin" value="1" checked=$currentGroup->getJoinMode()}
				<label for="h2">{t}Only those I approve{/t}</label>
			</div>
			<div class="prIndentTopSmall"> {form_radio id="h3" name="hjoin" value="2" checked=$currentGroup->getJoinMode()}
				<label for="h3">{t}Only those with a following code:{/t}</label>
			</div>
			<div class="prIndentTopSmall"> {form_text name="jcode" value=$currentGroup->getJoinCode()|escape:"html" } </div></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td colspan="2"><div class="prTip">{t}
				You can choose a word or number as your code. It will be included in the inventation you send to the people you invite to join your group.{/t}</div></td>
	</tr>
	<tr>
		<td class="prTRight"><span class="prMarkRequired">*</span>
			<label>{t}Set Content Visibility:{/t}</label></td>
		<td> {form_radio id="g1" name="gtype" value="0" checked=$currentGroup->getIsPrivate()|default:"0"}
			<label for="g1">{t}Public{/t}</label>
			{form_radio id="g2" name="gtype" value="1" checked=$currentGroup->getIsPrivate()}
			<label for="g2">{t}Private &ndash; Members Only*{/t}</label>
		</td>
		<td></td>
	</tr>
	<tr>
		<td class="prTRight"><span class="prMarkRequired">*</span>
			<label>{t}Notify me about new members:{/t}</label></td>
		<td> {form_radio id="nt1" name="jnotify" value="0" checked=$currentGroup->getJoinNotifyMode()|default:"0"}
			<label for="nt1">{t}Never{/t}</label>
			{form_radio id="nt2" name="jnotify" value="1" checked=$currentGroup->getJoinNotifyMode()}
			<label for="nt2">{t}Always{/t}</label>
		</td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td colspan="2"><div class="prTip">{t} <span class="prMarkRequired">*</span> If your group is private, its summary, event calendar, documents, photos, lists, and messages will be visible to members only. Private group will not appear in searches.{/t} </div></td>
	</tr>
	<tr>
		<td></td>
		<td><div class="prIndentTop"> {t var="in_submit"}Save Changes{/t}{form_submit name="form_save" value=$in_submit} </div></td>
	</tr>
</table>
{/form}	
	{/if}
{/if}