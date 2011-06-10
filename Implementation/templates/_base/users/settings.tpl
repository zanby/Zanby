{literal}
<script type="text/javascript">
    function cityavailable(city, country) {
        if ( document.getElementById('cityQuerySelected').value != city ) {
            document.getElementById('cityavailable').innerHTML = '<label style="color:red;">SEARCH...</label>';
            document.getElementById('cityavailableResults').style.display = 'none';
            document.getElementById('cityavailableChoose').style.display = 'none';            
            document.getElementById('cityAliasSelected').value = '';
            document.getElementById('city_correct').checked = false;
            xajax_cityavailable(city, country);
        }
    }
    function chooseAlias(alias, query)
    {
        document.getElementById('cityAliasSelected').value = alias;
        xajax_citychoosealias(alias, query);
    }
    function chooseCustomCity()
    {
        var query = document.getElementById('city').value;
        var country = document.getElementById('countryId').options[document.getElementById('countryId').selectedIndex].value;
        document.getElementById('cityAliasSelected').value = '';
        xajax_citychoosecustom(query, country, document.getElementById('city_correct').checked);
    }
</script>
{/literal}
    {strip}
        {TitlePane id='AccountBasicInformation'}
            {TitlePane_Title}{t}Basic Information{/t}{/TitlePane_Title}
            {TitlePane_Note}{t}Name, Location, Time Zone, Gender{/t}{/TitlePane_Note}
            {TitlePane_Toggle type='show'}{t}Show{/t}{/TitlePane_Toggle}
            {TitlePane_Toggle type='hide'}{t}Hide{/t}{/TitlePane_Toggle}
            {TitlePane_ToggleCallback type='show' request_type='ajax'}xajax_settings_basicInformation_show();{/TitlePane_ToggleCallback}
            {TitlePane_ToggleCallback type='hide' request_type='ajax'}xajax_settings_basicInformation_hide();{/TitlePane_ToggleCallback}
            {TitlePane_Content}
                {include file="users/settings.basicInformation.tpl"}
            {/TitlePane_Content}
        {/TitlePane}

        {TitlePane id='AccountLoginInformation'}
            {TitlePane_Title}{t}Login Information{/t}{/TitlePane_Title}
            {TitlePane_Note}{t}Username, Password, Email of record{/t}{/TitlePane_Note}
            {TitlePane_Toggle type='show'}{t}Show{/t}{/TitlePane_Toggle}
            {TitlePane_Toggle type='hide'}{t}Hide{/t}{/TitlePane_Toggle}
            {TitlePane_ToggleCallback type='show' request_type='ajax'}xajax_settings_loginInformation_show();{/TitlePane_ToggleCallback}
            {TitlePane_ToggleCallback type='hide' request_type='ajax'}xajax_settings_loginInformation_hide();{/TitlePane_ToggleCallback}
            {TitlePane_Content}
                {include file="users/settings.loginInformation.tpl"}
            {/TitlePane_Content}
        {/TitlePane}

        {TitlePane id='AccountCancel'}
            {TitlePane_Title}{t}Cancel Your Account{/t}{/TitlePane_Title}
            {TitlePane_Note}{t}{tparam value=$SITE_NAME_AS_STRING}Permanently Cancel your %s account{/t}{/TitlePane_Note}
            {TitlePane_Toggle type='show'}{t}Show{/t}{/TitlePane_Toggle}
            {TitlePane_Toggle type='hide'}{t}Hide{/t}{/TitlePane_Toggle}
            {TitlePane_ToggleCallback type='show' request_type='ajax'}xajax_settings_accountCancel_show();{/TitlePane_ToggleCallback}
            {TitlePane_ToggleCallback type='hide' request_type='ajax'}xajax_settings_accountCancel_hide();{/TitlePane_ToggleCallback}
            {TitlePane_Content}
                {include file="users/settings.accountCancel.tpl"}
            {/TitlePane_Content}
        {/TitlePane}
		
    {/strip} 