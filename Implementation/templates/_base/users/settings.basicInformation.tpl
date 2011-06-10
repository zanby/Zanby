{if $visibility_details == "basicinformation"}
	<script>xajax_settings_basicInformation_show();</script>
{else}	
    {if $visibility == true}
        <!-- form begin -->
        {form from=$form onsubmit="xajax_settings_basicInformation_save(xajax.getFormValues('biForm')); return false;" id="biForm" name="biForm"}
		{t}<span class="prText5">{t}Fields marked with asterisk <span class="prMarkRequired">*</span> are required.{/t}</span>{/t}
		{form_errors_summary}
            <table class="prForm">
                <col width="30%" />
                <col width="40%" />
                <col width="30%" />
                <thead>
                    <tr><th colspan="3"></th></tr>                   
                </thead>
                <tbody>
                    <tr>
                        <td class="prTRight"><span class="prMarkRequired">{t}*{/t}</span> <label for="firstname">{t}First Name:{/t}</label></td>
                        <td>{form_text name="firstname" value=$edituser->getFirstname()|escape:"html"}</td>
                        <td class="prTip"></td>
                    </tr>
                    <tr>
                        <td class="prTRight"><span class="prMarkRequired">{t}*{/t}</span> <label for="lastname">{t}Last Name:{/t}</label></td>
                        <td>{form_text name="lastname" value=$edituser->getLastname()|escape:"html"}</td>
                        <td class="prTip"></td>
                    </tr>
                    <tr>
                        <td class="prTRight"><span class="prMarkRequired">{t}*{/t}</span> <label for="countryId">{t}Country:{/t}</label></td>
                        <td>
                            {form_select id="countryId" name="countryId" options=$countries onchange="xajax_detectCountry(this.options[this.selectedIndex].value);" selected=$countryId}
                        </td>
                        <td class="prTip"></td>
                    </tr>
                    
                    <!-- START : ZIP -->
                    <tr id="LocationTrZip" {if !$countryId || ($countryId != 1 && $countryId != 38)}style="display:none;"{/if}>
                        <td class="prTRight"><span class="prMarkRequired">{t}*{/t}</span> <label for="zipId">{t}Zip code:{/t}</label></td>
                        <td>
                            {form_text name="zipcode" id="zipId"  value=$zipcode|escape:"html" onblur="xajax_zipcodeavailable(this.value, document.getElementById('countryId').options[document.getElementById('countryId').selectedIndex].value); return false;"}
                        </td>
                        <td class="prTip">
                            <span id="zipcodeavailable">
                            {if $countryId && ($countryId == 1 || $countryId == 38) }
                                {if $strRECOGNIZEDZip }
                                    <label style="color:green;">{t}RECOGNIZED{/t}</label>
                                {elseif $zipcode}
                                    <label class="prMarkRequired">{t}UNRECOGNIZED{/t}</label>
                                {/if}
                            {/if}
                            </span>
                        </td>
                    </tr>
                    <!-- END : ZIP -->
                    <!-- START : CITY -->
                    <tr id="LocationTrCity" {if !$countryId || $countryId == 1 || $countryId == 38}style="display:none;"{/if}>
                        <td class="prTRight"><span class="prMarkRequired">{t}*{/t}</span> <label for="city">{t}City:{/t}</label></td>
                        <td>
                            {form_text name="city" id="city"  value=$city|escape:"html" onblur="cityavailable(this.value, document.getElementById('countryId').options[document.getElementById('countryId').selectedIndex].value); return false;"}
                            {form_hidden name="cityQuerySelected" id="cityQuerySelected" value=$cityQuerySelected|escape:"html"}
                            {form_hidden name="cityAliasSelected" id="cityAliasSelected" value=$cityAliasSelected|escape:"html"}
                        </td>
                        <td class="prTip">
                            <span id="cityavailable">
                                {if $countryId && $countryId != 1 && $countryId != 38}
                                    {if $strRECOGNIZEDCity != ''}
                                        <label style="color:green;">{t}{tparam value=$strRECOGNIZEDCity|escape:"html"}RECOGNIZED : %s{/t}</label>
                                    {elseif $city}
                                        <label style="color:red;">{t}UNRECOGNIZED{/t}</label>
                                    {/if}
                                {/if}
                            </span>
                        </td>
                    </tr>
                    <tr id="cityavailableResults" {if !$lstCities}style="display:none;"{/if}>
                        <td class="prTRight">{t}Did you mean :{/t} </td>
                        <td id="cityavailableResultsMessage" colspan="2">
                            {if $lstCities}
                                {foreach from=$lstCities item=alias}
                                    <label><a id="cityObject{$alias->id}" href="#null" onclick="chooseAlias({$alias->id}, '{$city|escape:html}')">{$alias->name|escape:html}, {$alias->getState()->name|escape:html}, {$alias->getState()->getCountry()->name|escape:html}</a></label><br>
                                {/foreach}
                            {else}
                                &nbsp;
                            {/if}
                        </td>
                    </tr>
                    <tr id="cityavailableChoose" {if !$needapprovecity}style="display:none;"{/if}>
                        <td class="prTRight">&nbsp;</td>
                        <td>{form_checkbox name="city_correct" id="city_correct" value="1" checked=$city_correct onclick="chooseCustomCity();"} <label for="city_correct">{t}City I entered is correct{/t}</label></td>
                        <td class="prTip">&nbsp;</td>
                    </tr>
                    <!-- END : CITY -->
                    
                    <tr>
                        <td class="prTRight"><label for="timezone">{t}Timezone:{/t}</label></td>
                        <td>
                            {form_select name="timezone" selected=$edituser->getTimezone() options=$time_zones}
                        </td>
                        <td class="prTip"></td>
                    </tr>
                    <tr>
                        <td class="prTRight"><label for="userlocale">{t}Locale:{/t}</label></td>
                        <td>
                            {form_select name="userlocale" selected=$edituser->getLocale() options=$user_locales} {*$edituser->getUserLocale()*}
                        </td>
                        <td class="prTip"></td>
                    </tr>
                    <tr>
                        <td class="prTRight"><label for="gender">{t}Gender:{/t}</label></td>
                        <td>
                            {form_select name="gender" options=$genderArray selected=$edituser->getGender()}
                            <div>{form_checkbox name="is_gender_private" value=1 checked=$edituser->getIsGenderPrivate()}<label for="is_gender_private">{t} Keep my gender private{/t}</label></div>
                        </td>
                        <td class="prTip"></td>
                    </tr>
                    <tr>
                        <td class="prTRight"><label for="birthday_date_Month">{t}Birthday:{/t}</label></td>
                        <td>
                            {form_select_date start_year="-80" end_year="-1" prefix="date_" field_array="birthday" time=$edituser->getBirthday()|default:'0000-00-00' all_empty="" reverse_years=1}
                            <div>{form_checkbox name="is_birthday_private" value=1 checked=$edituser->getIsBirthdayPrivate()}<label for="is_birthday_private">{t} Keep my birthday private{/t}</label></div>
                        </td>
                        <td class="prTip"></td>
                    </tr>
                    <tr>
                        <td class="prTRight"></td>
                        <td>
							{t var='button_01'}Save{/t}
                            {form_submit name="form_save" value=$button_01}
							<span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="TitltPaneAppAccountBasicInformation.hide(); return false;">{t}Cancel{/t}</a></span>
                        </td>
                        <td class="prTip"></td>
                    </tr>
                </tbody>
            </table>
        {/form}
        <!-- form end -->
    {/if}
{/if}
