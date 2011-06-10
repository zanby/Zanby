<div class="prInner">
<p class="prInnerTop">{t}Please use the form below to contact us. Thank you for your interest.{/t}</p>

	{form from=$form id="contactUs"}
	<div class="prInnerTop">
	{form_errors_summary}
	</div>
	<table class="prForm">
		<col width="28%" />
		<col width="50%" />
		<col width="22%" />
		<tr>
			<th colspan="3">
			<p class="prInnerSmallTop">{t}Fields marked with asterisk <span class="prMarkRequired">*</span> are required.{/t}</p>
			</th>
		</tr>
		<tr>
			<td class="prTRight"><span class="prMarkRequired">*</span> <label for="first_name">{t}First Name:{/t}</label></td>
			<td>
				{form_text maxlength="100" name="first_name" id="first_name" size="60" value=$firstName|escape:"html"}
			</td>
			<td></td>
		</tr>
		<tr>
			<td class="prTRight"><span class="prMarkRequired">*</span> <label for="last_name">{t}Last Name:{/t}</label></td>
			<td>
				{form_text maxlength="100" name="last_name" id="last_name" size="60" value=$lastName|escape:"html"}
			</td>
			<td></td>
		</tr>
		<tr>
			<td class="prTRight"><span class="prMarkRequired">*</span> <label for="email_address">{t}Email Address:{/t}</label></td>
			<td>
				{form_text maxlength="100" name="email" id="email_address" size="60" value=$email|escape:"html"}
				<p class="prTip">{t}Never sold, never spammed!{/t} <a href="/{$LOCALE}/info/privacy/">{t}Privacy Policy{/t}</a></p>
			</td>
			<td></td>
		</tr>

		<tr>
			<td class="prTRight"><label for="company">{t}Company Name:{/t}</label></td>
			<td>
				{form_text maxlength="100" name="company" id="company" size="60" value=$company|escape:"html"}
			</td>
			<td></td>
		</tr>
		<tr>
			<td class="prTRight"><label for="countryId">{t}Country:{/t}</label></td>
			<td>
				{form_select name="country" options=$countries id="countryId" onchange="xajax_changeCountry(this.options[this.selectedIndex].value);" selected=$newuser.country}
			</td>
			<td></td>
		</tr>
		<tr>
			<td class="prTRight"><label for="stateId">{t}State / Province:{/t}</label></td>
			<td>
			   {form_select id="stateId" name="state" selected=$newuser.state options=$states onchange="xajax_changeState(this.options[this.selectedIndex].value);" selected=$newuser.state}
			</td>
			<td></td>
		</tr>
		<tr>
			<td class="prTRight"><label for="cityId">{t}City:{/t}</label></td>
			<td>
				{form_select id="cityId" name="city" selected=$newuser.city options=$cities  selected=$newuser.city}
			</td>
			<td></td>
		</tr>
		<tr>
			<td class="prTRight"><label for="phone">{t}Phone:{/t}</label></td>
			<td>
				{form_text maxlength="50" name="phone" id="phone" value=$phone|escape:"html"}
			</td>
			<td></td>
		</tr>
		<tr>
			<td class="prTRight"><label for="topic">{t}Please Select a Topic:{/t}</label></td>
			<td>
				{form_select id="topic" name="topic" options=$topics selected=$newuser.topic}
			</td>
			<td></td>
		</tr>
		<tr>
			<td class="prTRight"><span class="prMarkRequired">*</span> <label for="message">{t}Message:{/t}</label></td>
			<td>
				{form_textarea name="message" id="message" class="textarea" value=$message|escape:"html"}
			</td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td>
				{t var="in_submit"}Submit{/t}
				{form_submit value=$in_submit}
			</td>
			<td></td>
		</tr>
	</table>

	{/form}
</div>
