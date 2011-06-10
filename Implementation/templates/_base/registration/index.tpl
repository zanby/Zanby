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

{if $FACEBOOK_USED}
{literal}
    <script type="text/javascript">//<![CDATA[ 
		FBCfg.url_do_signup = '{/literal}{$BASE_URL}/{$LOCALE}/facebook/processregistration/{literal}';
    //]]></script>
{/literal}
{/if}
{form from=$form}
	<table class="prForm">
		<col width="26%" />
		<col width="39%" />
		<col width="35%" />
		<thead>
			<tr><th colspan="3">
				<div class="prFormMessage prMarkRequired">
					{t}{tparam value=$SITE_NAME_AS_STRING}Already have %s Account?{/t} <a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/users/login/">{t}Log In{/t}</a>.
				</div>
			</th></tr>
			{if $FACEBOOK_USED}
			<tr><th colspan="3">
				{if !$facebookId}
				    <div class="prIndentTop">{t}Please, fill the form below Or{/t} <a href="javascript:void(0);" onclick="FBApplication.do_signup();">{t}Sign Up with Your Facebook account{/t}</a></div>
				{else}
				    <div class="prIndentTop"><b>{t}Your Facebook profile has been retrieved and used to fill in the information on this page.{/t}</b></div>
				    <div class="prIndentTop">{t}Please verify this information, and fill in any missing fields.{/t}</div>
				{/if}			
			</th></tr>
			{/if}
			<tr><th colspan="3">{t}Fields marked with asterisk <span class="prMarkRequired">*</span> are required.{/t}</th></tr>
			<tr><th colspan="3">
				{form_errors_summary}
			</th></tr>
		</thead>
		<tbody>
			<tr>
				<td class="prTRight"><span class="prMarkRequired">*</span> <label for="firstname">{t}First Name:{/t}</label></td>
				<td>{form_text name="firstname" value=$newuser.firstname|escape:"html"}</td>
				<td class="prTip"></td>
			</tr>
			<tr>
				<td class="prTRight"><span class="prMarkRequired">*</span> <label for="lastname">{t}Last Name:{/t}</label></td>
				<td>{form_text name="lastname" value=$newuser.lastname|escape:"html"}{form_hidden name="gender" value="unselected"}{form_hidden name="is_gender_private" value="1"}</td>
				<td class="prTip"></td>
			</tr>
			<tr>
				<td class="prTRight"><span class="prMarkRequired">*</span> <label for="countryId">{t}Country:{/t}</label></td>
				<td>
					{form_select id="countryId" name="countryId" options=$countries onchange="xajax_detectCountry(this.options[this.selectedIndex].value);" selected=$newuser.countryId}
				</td>
				<td class="prTip"></td>
			</tr>
                  
                  <!-- START : ZIP -->
			<tr id="LocationTrZip"{if !$newuser.countryId || ($newuser.countryId != 1 && $newuser.countryId != 38)} style="display:none;"{/if}>
				<td class="prTRight"><span class="prMarkRequired">*</span> <label for="zipId">{t}Zip code:{/t}</label></td>
				<td>
					{form_text name="zipcode" id="zipId"  value=$newuser.zipcode|escape:"html" onblur="xajax_zipcodeavailable(this.value, document.getElementById('countryId').options[document.getElementById('countryId').selectedIndex].value); return false;"}
				</td>
				<td class="prTip">
                          <span id="zipcodeavailable" style="padding-left: 3px;">
                          {if $newuser.countryId && ($newuser.countryId == 1 || $newuser.countryId == 38) }
                              {if $strRECOGNIZEDZip }
                                  <label style="color:green;">{t}RECOGNIZED{/t}</label>
                              {elseif $newuser.zipcode}
                                  <label style="color:red;">{t}UNRECOGNIZED{/t}</label>
                              {/if}
                       {/if}
                          </span>
				</td>
			</tr>
                  <!-- END : ZIP -->
                  
                  <!-- START : CITY -->
			<tr id="LocationTrCity"{if !$newuser.countryId || $newuser.countryId == 1 || $newuser.countryId == 38} style="display:none;"{/if}>
				<td class="prTRight"><span class="prMarkRequired">*</span> <label for="countryId">{t}City:{/t}</label></td>
				<td>
					{form_text name="city" id="city"  value=$newuser.city|escape:"html" onblur="cityavailable(this.value, document.getElementById('countryId').options[document.getElementById('countryId').selectedIndex].value); return false;"}
                          {form_hidden name="cityQuerySelected" id="cityQuerySelected" value=$cityQuerySelected|escape:"html"}
                          {form_hidden name="cityAliasSelected" id="cityAliasSelected" value=$cityAliasSelected|escape:"html"}
				</td>
				<td class="prTip">
                          <span id="cityavailable" style="padding-left: 3px;">
                              {if $newuser.countryId && $newuser.countryId != 1 && $newuser.countryId != 38}
                                  {if $strRECOGNIZEDCity != ''}
                                      <label style="color:green;">{t}RECOGNIZED :{/t} {$strRECOGNIZEDCity|escape:"html"}</label>
                                  {elseif $newuser.city}
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
                                  <label><a id="cityObject{$alias->id}" href="#null" onclick="chooseAlias({$alias->id}, '{$newuser.city|escape:html}')">{$alias->name|escape:html}, {$alias->getState()->name|escape:html}, {$alias->getState()->getCountry()->name|escape:html}</a></label><br>
                              {/foreach}
                          {else}
                              &nbsp;
                          {/if}
                      </td>
			</tr>
			<tr id="cityavailableChoose" {if !$needApproveCity}style="display:none;"{/if}>
				<td class="prTRight">&nbsp;</td>
				<td>{form_checkbox name="city_correct" id="city_correct" value="1" checked=$city_correct onclick="chooseCustomCity();"} <label for="city_correct">{t}City I entered is correct{/t}</label></td>
				<td class="prTip">&nbsp;</td>
			</tr>
                  <!-- END : CITY -->
                  
			<tr>
				<td class="prTRight">&nbsp;</td>
				<td>&nbsp;</td>
				<td class="prTip">&nbsp;</td>
			</tr>

			
			<tr>
				<td class="prTRight"><span class="prMarkRequired">*</span> <label for="login">{t}User Name:{/t}</label></td>
				<td>{form_text name="login" value=$newuser.login|escape:"html" onblur="xajax_loginavailable(this.value); return false;"}</td>
				<td class="prTip">
					<span id="loginavailable" style="padding-left: 3px;"></span> 
                    <script type="text/javascript">
						{literal}YAHOO.util.Event.onDOMReady(function() { xajax_loginavailable('{/literal}{$newuser.login|escape:"html"}{literal}');});{/literal}
                    </script>
				</td>
			</tr>
			<tr>
				<td class="prTRight"><span class="prMarkRequired">*</span> <label for="pass">{t}Password:{/t}</label></td>
				<td>
					{assign var="temp_url" value="http://$BASE_HTTP_HOST/$LOCALE/info/strength/"}
					{form_password id="pass" name="pass" value=$newuser.pass|escape:"html"}
				</td>
				<td class="prTip">{t}Six characters or more.{/t}</td>
			</tr>
			<tr>
				<td class="prTRight"><span class="prMarkRequired">*</span> <label for="pass_confirm">{t}Confirm Password:{/t}</label></td>
				<td>
					{form_password name="pass_confirm" value=$newuser.pass_confirm|escape:"html"}
				</td>
				<td class="prTip"></td>
			</tr>
			<tr>
				<td class="prTRight"><span class="prMarkRequired">*</span> <label for="email">{t}Email Address:{/t}</label></td>
				<td>
					{form_text name="email" value=$newuser.email|escape:"html"}
				</td>
				<td class="prTip">
					{t}Never sold, never spammed!{/t}<br />
					<a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/info/privacy/">{t}Privacy Policy{/t}</a>
				</td>
			</tr>
			{if $REGISTRATION_CAPTCHA !='off'}
			    {if !($FACEBOOK_USED && $facebookId) }
				<tr>
					<td></td>
					<td colspan="2"><h3>{t}Verify Your Registration{/t}</h3></td>
				</tr>
				<tr>
					<td class="prTRight"></td>
					<td><img src="/{$verifyImage}" alt="Captcha" /></td>
					<td class="prTip"></td>
				</tr>
				<tr>
					<td class="prTRight"><span class="prMarkRequired">*</span> <label for="verify_code">{t}Enter the code shown:{/t}</label></td>
					<td>
						{form_text name="verify_code"} <a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/info/captcha/">{t}More info{/t}</a>
					</td>
					<td class="prTip">{t}{tparam value=$SITE_NAME_AS_STRING}This helps %s prevent automated registration.{/t}</td>
				</tr>
			    {/if}
			{/if}
			<tr>
				<td class="prTRight"></td>
				<td>
					{form_checkbox name="agree" id="agree" value="1" checked=$agree}<label for="agree"> {t}{tparam value=$BASE_HTTP_HOST}{tparam value=$LOCALE}I agree to the <a href="http://%s/%s/info/terms/" target="_blank">Terms of Service</a>{/t}</label>
				</td>
				<td class="prTip"></td>
			</tr>
                  <tr>
                      <td class="prTRight"></td>
                      <td>
                          {form_checkbox name="age_agree" id="age_agree" value="1" checked=$age_agree}<label for="age_agree"> {t}I am over 18 years of age{/t}</label>
                      </td>
                      <td class="prTip"></td>
                  </tr>
			<tr>
				<td class="prTRight"></td>
				<td>
					{t var='button'}Register{/t}
					{form_submit name="form_register" value=$button }
				</td>
				<td class="prTip"></td>
			</tr>
		</tbody>
	</table>
{/form}

