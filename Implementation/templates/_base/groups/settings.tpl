<link type="text/css" rel="stylesheet" href="{$AppTheme->css}/yui-autocomplete.css">
<script type="text/javascript" src="/js/yui/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="/js/yui/connection/connection-min.js"></script>
<script type="text/javascript" src="/js/yui/autocomplete/autocomplete-min.js"></script>
<script type="text/javascript" src="/js/autocomplete_xajax_datasource.js"></script>
{literal}
<script type="text/javascript">
    var m_fCallBackToAutocomplete;
    var m_oParent;
    var m_sQuery;

    function turn_offUserList(elem)
    {
       document.getElementById(elem.name + "_div").style.display="none";
       document.getElementById(elem.name + "_error_div").innerHTML="&nbsp;";
       document.getElementById(elem.name + "_td").className = "prNoBorder";
    }

    function turn_onUserList(elem)
    {
       document.getElementById(elem.name + "_div").style.display="";
       document.getElementById(elem.name + "_td").className = "prNoBorder";
    }

    function setAutoComplete()
    {
	    gpCalendar_myAutoComp = new YAHOO.widget.AutoComplete("text_gpCalendar", "gpCalendar_acLogins", myDataSource);
	    gpEmail_myAutoComp = new YAHOO.widget.AutoComplete("text_gpEmail", "gpEmail_acLogins", myDataSource);
	    gpPhotos_myAutoComp = new YAHOO.widget.AutoComplete("text_gpPhotos", "gpPhotos_acLogins", myDataSource);
	    gpDocuments_myAutoComp = new YAHOO.widget.AutoComplete("text_gpDocuments", "gpDocuments_acLogins", myDataSource);
	    gpLists_myAutoComp = new YAHOO.widget.AutoComplete("text_gpLists", "gpLists_acLogins", myDataSource);
	    gpPolls_myAutoComp = new YAHOO.widget.AutoComplete("text_gpPolls", "gpPolls_acLogins", myDataSource);
	    gpManageMembers_myAutoComp = new YAHOO.widget.AutoComplete("text_gpManageMembers", "gpManageMembers_acLogins", myDataSource);
	    gpManageGroupFamilies_myAutoComp = new YAHOO.widget.AutoComplete("text_gpManageGroupFamilies", "gpManageGroupFamilies_acLogins", myDataSource);
	    gpModifyLayout_myAutoComp = new YAHOO.widget.AutoComplete("text_gpModifyLayout", "gpModifyLayout_acLogins", myDataSource);
    }

    function doQueryXajax(fCallBack, oParent, sQuery)
    {
        m_fCallBackToAutocomplete = fCallBack;
        m_oParent = oParent;
        m_sQuery = sQuery;
        text = new String(oParent);
        xajax_privileges_user_ac_members(decodeURIComponent(sQuery), "autocompleteCallback");
    }

    function autocompleteCallback(sResponse)
    {
        if (sResponse.constructor != Array) sResponse = new Array();
        m_fCallBackToAutocomplete(m_sQuery, sResponse, m_oParent);
    }
    var myDataSource = new YAHOO.widget.DS_XAJAX();
    //var myDataSource1 = new YAHOO.widget.DS_XAJAX();
</script>
{/literal}
<!-- ******************************* -->

    <br />
    {if $visibility_details == "privileges"}{assign var='showContent' value=1}{else}{assign var='showContent' value=0}{/if}
	{TitlePane id='GroupSettingsPrivilegies' showContent=$showContent}
        {TitlePane_Title}{t}Privileges{/t}{/TitlePane_Title}
        {TitlePane_Note}{if $groupType == "simple"}{t}Decide who can use group tools{/t}{elseif $groupType == "family"}{t}Decide who can use Group Family tools{/t}{/if}{/TitlePane_Note}
        {TitlePane_ToggleCallback type='show' request_type='ajax'}xajax_privileges_privileges_show('{$gid}');{/TitlePane_ToggleCallback}
        {TitlePane_ToggleCallback type='hide' request_type='ajax'}xajax_privileges_privileges_hide('{$gid}');{/TitlePane_ToggleCallback}
        {TitlePane_Content}
            {if $groupType == "simple"}
                {include file="groups/settings.privileges.tpl"}
            {elseif $groupType == "family"}
                {include file="groups/settings.privileges.tpl"}
            {/if}
        {/TitlePane_Content}
    {/TitlePane}

    {if !$groupCanBeDeleted}<br />{/if}

    {if $visibility_details == "cohosts"}{assign var='showContent' value=1}{else}{assign var='showContent' value=0}{/if}
	{TitlePane id='GroupSettingsCoHost' showContent=$showContent}
        {TitlePane_Title}{if $groupType == "simple"}{t}Co-Hosts{/t}{elseif $groupType == "family"}{t}Co-Owners{/t}{/if}{/TitlePane_Title}
        {TitlePane_Note} {if $groupType == "simple"}{t}Give members the same privileges as the original host, including the ability to add and delete members, moderate discussions and join group families. Type in the name of one of your group members.{/t}{elseif $groupType == "family"}{t}Give members the same privileges as the original host. Type in the name of one of your group members.{/t}{/if}{/TitlePane_Note}
        {TitlePane_ToggleCallback type='show' request_type='ajax'}xajax_privileges_cohosts_show();{/TitlePane_ToggleCallback}
        {TitlePane_ToggleCallback type='hide' request_type='ajax'}xajax_privileges_cohosts_hide();{/TitlePane_ToggleCallback}
        {TitlePane_Content}
            {if $groupType == "simple"}
                {include file="groups/settings.cohosts.tpl"}
            {elseif $groupType == "family"}
                {include file="groups/settings.familycohosts.tpl"}
            {/if}
        {/TitlePane_Content}
    {/TitlePane}

    {if !$groupCanBeDeleted}<br />{/if}

    {if $visibility_details == "groupdetails"}{assign var='showContent' value=1}{else}{assign var='showContent' value=0}{/if}
	{TitlePane id='GroupSettingsGroupDetails' showContent=$showContent}
        {TitlePane_Title}{if $groupType == "simple"}{t}Group Details{/t}{elseif $groupType == "family"}{t}Family Details{/t}{/if}{/TitlePane_Title}
        {TitlePane_Note} {if $groupType == "simple"}{t}Group name, category, tags, privacy{/t}{elseif $groupType == "family"}{t}Family name, category, tags, privacy{/t}{/if}{/TitlePane_Note}
        {TitlePane_ToggleCallback type='show' request_type='ajax'}xajax_privileges_group_details_show('{$currentGroup->getId()}');{/TitlePane_ToggleCallback}
        {TitlePane_ToggleCallback type='hide' request_type='ajax'}xajax_privileges_group_details_hide('{$currentGroup->getId()}');{/TitlePane_ToggleCallback}
        {TitlePane_Content}
            {if $groupType == "simple"}
                {include file="groups/settings.details.tpl"}
            {elseif $groupType == "family"}
                {include file="groups/settings.familydetails.tpl"}
            {/if}
        {/TitlePane_Content}
    {/TitlePane}

    {if !$groupCanBeDeleted}<br />{/if}

    {if $visibility_details == "resign"}{assign var='showContent' value=1}{else}{assign var='showContent' value=0}{/if}
    {if $groupType == "simple"}
	    {TitlePane id='GroupSettingsResign' showContent=$showContent}
	        {TitlePane_Title}{t}Resign as Host{/t}{/TitlePane_Title}
	        {TitlePane_Note}{t}Resign your position as host and appoint a new host{/t}{/TitlePane_Note}
	        {TitlePane_ToggleCallback type='show' request_type='ajax'}xajax_privileges_resign_show();{/TitlePane_ToggleCallback}
	        {TitlePane_ToggleCallback type='hide' request_type='ajax'}xajax_privileges_resign_hide();{/TitlePane_ToggleCallback}
	        {TitlePane_Content}
	            {include file="groups/settings.resign.tpl"}
	        {/TitlePane_Content}
	    {/TitlePane}
    {else}
	    {if $groupType == "family"}
	        {TitlePane id='GroupSettingsTransfer'}
	            {TitlePane_Title}{t}Transfer Family Account{/t}{/TitlePane_Title}
	            {TitlePane_Note}{t}Transfer Group Family Account to another person{/t}{/TitlePane_Note}
	            {TitlePane_ToggleCallback type='show' request_type='ajax'}xajax_privileges_transfer_show();{/TitlePane_ToggleCallback}
	            {TitlePane_ToggleCallback type='hide' request_type='ajax'}xajax_privileges_transfer_hide('{$gid}');{/TitlePane_ToggleCallback}
	            {TitlePane_Content}
	                {include file="groups/settings.transferaccount.tpl"}
	            {/TitlePane_Content}
	        {/TitlePane}
	    {/if}    
    {/if}

    {if $groupCanBeDeleted}
    {if $visibility_details == "deletegroup"}{assign var='showContent' value=1}{else}{assign var='showContent' value=0}{/if}
	{TitlePane id='GroupSettingsDeleteGroup' showContent=$showContent}
        {TitlePane_Title}{if $groupType == "simple"}{t}Delete Group{/t}{elseif $groupType == "family"}{t}Delete Group Family{/t}{/if}{/TitlePane_Title}
        {TitlePane_Note}{if $groupType == "simple"}{t}Delete group and all group data.{/t}{elseif $groupType == "family"}{t}Delete group family and all group family data{/t}{/if}{/TitlePane_Note}
        {TitlePane_ToggleCallback type='show' request_type='ajax'}xajax_privileges_deletegroup_show();{/TitlePane_ToggleCallback}
        {TitlePane_ToggleCallback type='hide' request_type='ajax'}xajax_privileges_deletegroup_hide('{$gid}');{/TitlePane_ToggleCallback}
        {TitlePane_Content}
            {if $groupType == "simple"}{include file="groups/settings.deletegroup.tpl"}
            {elseif $groupType == "family"}{include file="groups/settings.deletefamilygroup.tpl"}
            {/if}
        {/TitlePane_Content}
    {/TitlePane}
    {/if}
<!-- ******************************* -->
{literal}
<script type="text/javascript">
function confirmConvert()
{
    popup_window.content(YAHOO.util.Dom.get('convertPanel').innerHTML);
    popup_window.title('Please confirm');
    popup_window.width(500);
    popup_window.height(350);
    popup_window.open();
}
</script>
{/literal}