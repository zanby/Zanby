{strip}
<link type="text/css" rel="stylesheet" href="{$AppTheme->css}/yui-autocomplete.css">
<script type="text/javascript" src="/js/yui/yahoo-dom-event/yahoo-dom-event.js"></script> 
<script type="text/javascript" src="/js/yui/connection/connection-min.js"></script> 
<script type="text/javascript" src="/js/yui/autocomplete/autocomplete-min.js"></script> 
<script type="text/javascript" src="/js/autocomplete_xajax_datasource.js"></script> 
<script type="text/javascript" src="/js/manage_checkboxes.js"></script>
<script type="text/javascript">
{literal}
    var m_fCallBackToAutocomplete;
    var m_oParent;
    var m_sQuery;
    
    function doQueryXajax(fCallBack, oParent, sQuery)
    {
        m_fCallBackToAutocomplete = fCallBack;
        m_oParent = oParent;
        m_sQuery = sQuery;
        xajax_privacy_bu_ac_logins(decodeURIComponent(sQuery), "autocompleteCallback");
    }
                                
    function autocompleteCallback(sResponse)
    {
        if (sResponse.constructor != Array)
        {
            sResponse = new Array();
        }
        m_fCallBackToAutocomplete(m_sQuery, sResponse, m_oParent);
    }
    var myDataSource = new YAHOO.widget.DS_XAJAX();
{/literal}
</script>
        {TitlePane id='communicationPreferences'}
            {TitlePane_Title}{t}Communication Preferences{/t}{/TitlePane_Title}
            {TitlePane_Note}{t}Determine who can contact you{/t}{/TitlePane_Note}
            {TitlePane_Toggle type='show'}{t}Show{/t}{/TitlePane_Toggle}
            {TitlePane_Toggle type='hide'}{t}Hide{/t}{/TitlePane_Toggle}
            {TitlePane_ToggleCallback type='show' request_type='ajax'}xajax_privacy_cp_show();{/TitlePane_ToggleCallback}
            {TitlePane_ToggleCallback type='hide' request_type='ajax'}xajax_privacy_cp_hide();{/TitlePane_ToggleCallback}
            {TitlePane_Content}
                {include file="users/privacy.communicationPreferences.tpl"}
            {/TitlePane_Content}
        {/TitlePane}
        {TitlePane id='contentVisibility'}
            {TitlePane_Title}{t}Content Visibility{/t}{/TitlePane_Title}
            {TitlePane_Note}{t}Determine who can see your profile and associated content{/t}{/TitlePane_Note}
            {TitlePane_Toggle type='show'}{t}Show{/t}{/TitlePane_Toggle}
            {TitlePane_Toggle type='hide'}{t}Hide{/t}{/TitlePane_Toggle}
            {TitlePane_ToggleCallback type='show' request_type='ajax'}xajax_privacy_cv_show();{/TitlePane_ToggleCallback}
            {TitlePane_ToggleCallback type='hide' request_type='ajax'}xajax_privacy_cv_hide();{/TitlePane_ToggleCallback}
            {TitlePane_Content}
                {include file="users/privacy.contentVisibility.tpl"}
            {/TitlePane_Content}
        {/TitlePane}
		{TitlePane id='searchResultSettings'}
            {TitlePane_Title}{t}Search Result Settings{/t}{/TitlePane_Title}
            {TitlePane_Note}{t}Determine who can find you and what they see{/t}{/TitlePane_Note}
            {TitlePane_Toggle type='show'}{t}Show{/t}{/TitlePane_Toggle}
            {TitlePane_Toggle type='hide'}{t}Hide{/t}{/TitlePane_Toggle}
            {TitlePane_ToggleCallback type='show' request_type='ajax'}xajax_privacy_sr_show();{/TitlePane_ToggleCallback}
            {TitlePane_ToggleCallback type='hide' request_type='ajax'}xajax_privacy_sr_hide();{/TitlePane_ToggleCallback}
            {TitlePane_Content}
                {include file="users/privacy.searchResultSettings.tpl"}
            {/TitlePane_Content}
        {/TitlePane}
		{TitlePane id='blockUsers'}
            {TitlePane_Title}{t}Block Users{/t}{/TitlePane_Title}
            {TitlePane_Note}{t}Block the following users{/t}{/TitlePane_Note}
            {TitlePane_Toggle type='show'}{t}Show{/t}{/TitlePane_Toggle}
            {TitlePane_Toggle type='hide'}{t}Hide{/t}{/TitlePane_Toggle}
            {TitlePane_ToggleCallback type='show' request_type='ajax'}xajax_privacy_bu_show();{/TitlePane_ToggleCallback}
            {TitlePane_ToggleCallback type='hide' request_type='ajax'}xajax_privacy_bu_hide();{/TitlePane_ToggleCallback}
            {TitlePane_Content}
                {include file="users/privacy.blockUsers.tpl"}
            {/TitlePane_Content}
        {/TitlePane} 
{/strip}