<link type="text/css" rel="stylesheet" href="{$AppTheme->css}/yui-autocomplete.css">
<script type="text/javascript" src="/js/yui/yahoo-dom-event/yahoo-dom-event.js"></script> 
<script type="text/javascript" src="/js/yui/connection/connection-min.js"></script> 
<script type="text/javascript" src="/js/yui/autocomplete/autocomplete-min.js"></script> 
{literal}
<style>
.yui-ac-bd {
	height: 150px; 
	overflow:auto;
	overflow-x:hidden;
}
#acCity .yui-ac-content .yui-ac-bd {
    background-color:#FFFFFF;
}
#acZip .yui-ac-content .yui-ac-bd {
    background-color:#FFFFFF;
}
</style> 
<script type="text/javascript">
	/**
	*
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
	function GroupSettingsGroupDetails_over() {
		document.getElementById("GroupSettingsGroupDetailsTitle").style.textDecoration = "underline";
		document.getElementById("GroupSettingsGroupDetailsImage").src = "{/literal}{$AppTheme->images}{literal}/decorators/gsett_arrow.gif";

	}
	function GroupSettingsGroupDetails_out() {
		document.getElementById("GroupSettingsGroupDetailsTitle").style.textDecoration = "none";
		document.getElementById("GroupSettingsGroupDetailsImage").src = "{/literal}{$AppTheme->images}{literal}/decorators/gsett_arrow_off.gif";
	}
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
	<script>xajax_privileges_group_details_show('{$groupId}');</script>
{else}
	{if $visibility == true }
		{form from=$form onsubmit="xajax_privileges_familygroup_details_save(xajax.getFormValues('fdForm')); return false;" id="fdForm" name="fdForm"}
		{form_errors_summary width="90%"}
		{form_hidden name="groupId" id="groupId" value=$currentGroup->getId()}
			{form_errors_summary}
			<table class="prForm">
				<col width="30%" />
				<col width="40%" />
				<col width="30%" />						
				<tr>
					<td class="prTRight"><span class="prMarkRequired">*</span> <label for="gname">{t}Group Name{/t}</label></td>							
					<td>
						 {form_text name="gname" value=$currentGroup->getName()|escape:"html"}						
					</td>
					<td></td>		
				</tr>
				<tr>
					<td class="prTRight"><span class="prMarkRequired">*</span> <label for="gemail">{t}Group Address{/t}</label></td>							
					<td>
						 {form_text name="gemail" value=$currentGroup->getPath()|escape:"html" dir="rtl"}						
					</td>		
					<td>@{$DOMAIN_FOR_GROUP_EMAIL}</td>
				</tr>					
				<tr>
					<td class="prTRight"><span class="prMarkRequired">*</span> <label for="categoryId">{t}Group Category{/t}</label></td>	
					<td>
						 {form_select name="categoryId" selected=$currentGroup->getCategoryId() options=$categories}
					</td>
					<td></td>
				</tr>		
				<tr>
					<td class="prTRight"><span class="prMarkRequired">*</span> <label for="description">{t}Description{/t}</label></td>	
					<td>
						<div class="prTip prIndentBottomSmall">
							{t}2000 Characters Avaible{/t}    		
						</div>
						<div>
							{form_textarea name="description" value=$currentGroup->getDescription()|escape:"html" style="height:140px;"}							
						</div>						
					</td>
					<td class="prTip">
						<br />
						{t}The first few words of your description will appear in a search result.<br />
						The full description will appear in your group profile.{/t}
					</td>
				</tr>					
				<tr>
					<td class="prTRight">
						<label for="tags">{t}Tags{/t}</label>    	
					</td>
					<td>
						{form_text name="tags" value=$tags|escape:"html"}    	
					</td>
					<td class="prTip">
						{t}Enter the top five key words that describe your group{/t}   
					</td>
				</tr>					
				<tr>
					<td class="prTRight">
						<label>{t}Company{/t}</label> 
					</td>
					<td>
						{form_text name="company" value=$currentGroup->getCompany()|escape:"html"}    	
					</td>
					<td></td>
				</tr>
				<tr>
					<td class="prTRight"><label for="position">{t}Position{/t}</label> 
					</td>
					<td>
						{form_text name="position" value=$currentGroup->getPosition()|escape:"html"}    	
					</td>
					<td></td>						
				</tr>					
				<tr>
					<td class="prTRight"><span class="prMarkRequired">*</span> <label for="address1">{t}Address1{/t}</label></td>		
					<td>
						{form_text name="address1" value=$currentGroup->getAddress1()|escape:"html"}    	
					</td>
					<td></td>						
				</tr>					
				<tr>
					<td class="prTRight"><label for="address2">{t}Address2{/t}</label></td>		
					<td>
						{form_text name="address2" value=$currentGroup->getAddress2()|escape:"html"}    	
					</td>
					<td></td>						
				</tr>					
				<tr>
					<td class="prTRight"><span class="prMarkRequired">*</span> <label for="countryId">{t}Country{/t}</label></td>		
					<td>
						{form_select id="countryId" name="countryId" selected=$countryId options=$countries onchange="xajax_detectCountry(this.options[this.selectedIndex].value);"}
					</td>
					<td></td>
				</tr>
				
				<tr id="LocationTrZip" {if !$countryId || ($countryId != 1 && $countryId != 38)}style="display:none;"{/if}>
					<td class="prTRight"><span class="prMarkRequired">*</span> <label for="zipId">{t}Zip code:{/t}</label></td>
					<td>
						<div class="yui-skin-sam">
							<div class="yui-ac">
								{form_text name="zipcode" id="zipId"  value=$zipStr|escape:"html"}
								<div id="acZip"></div>
							</div>
						</div>
					</td>
					<td></td>
				</tr>
				<tr id="LocationTrCity" {if !$countryId || $countryId == 1 || $countryId == 38}style="display:none;"{/if}>
					<td class="prTRight"><span class="prMarkRequired">*</span> <label for="city">{t}City:{/t}</label></td>
					<td>
						<div class="yui-skin-sam">
							<div class="yui-ac">
								{form_text name="city" id="city"  value=$cityStr|escape:"html"}
								<div id="acCity"></div>
							</div>
						</div>
					</td>
					<td></td>
				</tr>						
                <tr>
                    <td class="prTRight"><span class="prMarkRequired">*</span> <label>{t}Who Can Join?{/t}</label></td>
                    <td colspan="2">                            
                        {form_radio id="h1" name="hjoin" value="0" checked=$currentGroup->getJoinMode()|default:"0"}<label for="h1">{t}Anyone{/t}</label>
                        <div class="prIndentTopSmall">
                        {form_radio id="h2" name="hjoin" value="1" checked=$currentGroup->getJoinMode()}<label for="h2">{t}Only those I approve{/t}</label>
                        </div>
                        <div class="prIndentTopSmall">
                        {form_radio id="h3" name="hjoin" value="2" checked=$currentGroup->getJoinMode()}<label for="h3">{t}Only those with a following code:{/t}</label>
                        </div>
                   </td>
                   <td></td>
                </tr>
				<tr>
					<td></td>		
					<td>
						{form_text name="jcode" value=$currentGroup->getJoinCode()|escape:"html"}												
					</td>
					<td class="prTip">
						{t}You may choose a word or number as your code. It will
						be included in the invitation you send to the people
						you invite to join your group.{/t}    	
					</td>
				</tr>
				<tr>
				   <td></td>
				   <td colspan="2">
				   		{t var="in_submit"}Save{/t}
						{form_submit name="form_save" value=$in_submit}
					</td>
					<td></td>
				</tr>
			</table>
		{/form}
	{/if}
{/if}