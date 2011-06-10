<div class="prClr3">
<table width="100%" border="0">
	<tr>
		<td colspan="2">{t}Required:{/t}</td>
	</tr>
	<tr>
		<td>{t}Venue name:{/t}</td><td>{form_text name="event[venue_name]" id="event[venue_name]" value=$venue->name|escape:html} </td>
	</tr>
	<tr>
		<td>{t}Venue type:{/t}</td><td>
		{form_select name="event[venue_type1]" options=$type1_values selected=$venue->getCategoryId()} </td>
	</tr>
	<tr>
		<td>{t}Street address:{/t}</td><td>
			{form_text name="event[venue_street_address1]" value=$venue->getAddress1()|escape:html} </td>
	</tr>
	<tr>
		<td>{t}Street address 2:{/t}</td><td>
			{form_text name="event[venue_street_address2]" value=$venue->getAaddress2()|escape:html} </td>
	</tr>
	<tr>
		<td>{t}Country:{/t}</td><td>
			{form_select id="countryId" name="event[venue_countryId]" selected="$event.venue_countryId" options=$countries onchange="xajax_changeCountry(this.options[this.selectedIndex].value);" style="width:209px;"}
			 </td>
	</tr>
	<tr>
		<td>{t}State:{/t}</td><td>
			{form_select id="stateId" name="event[venue_stateId]" selected="$event.venue_stateId" options=$states onchange="xajax_changeState(this.options[this.selectedIndex].value);" style="width:209px;"}
			 </td>
	</tr>
	<tr>
		<td>{t}City:{/t}</td><td>
			{form_select id="cityId" name="event[venue_cityId]" selected="$event.venue_cityId" options=$cities style="width:209px;"}
			 </td>
	</tr>
	<tr>
		<td>{t}Zip code:{/t}</td><td>
		<div class="reg-last-name-inpt" id="zipSelect" style="display:none">{form_select id=zipId name="event[venue_zipcode]" selected="$event.venue_zipCode" options=$zipCodes style="width:209px;"}</div>
		<div class="reg-last-name-inpt" id="zipText" style="display:none">{form_text id=zipId1 name="event[venue_zipcode1]" value="$zipCode1" style="width:200px;"}</div>

		{if $countryId == 1}
		<script>document.getElementById("zipSelect").style.display = "";</script>
		{else}
		<script>document.getElementById("zipText").style.display = "";</script>
		{/if}
	 </td>
	</tr>

	<tr>
		<td>{t}Phone:{/t}</td><td>{form_text name="event[venue_phone]" value=$venue->getPhone()|escape:html} {t}Please include area code.{/t}</td>
	</tr>

	<tr>
		<td>{t}Website:{/t}</td><td>{form_text name="event[venue_website]" value=$venue->getWebsite()|escape:html}</td>
	</tr>

	<tr>
		<td>{t}Description:{/t}</td><td>{t}750 Characters Max{/t}<br>{form_textarea name="event[venue_description]" value=$venue->getDescription()|escape:html}</td>
	</tr>

	<tr>
		<td>{t}Tags:{/t}</td><td>{form_text name="event[venue_tags]" value=$event.venue_tags}</td>
	</tr>
    {if HTTP_CONTEXT == 'zccf' || HTTP_CONTEXT == 'zccf-alt' || HTTP_CONTEXT == 'zccf-base'}
	<tr>
		<td>&nbsp;</td><td><div class="prTip">{t}Tags are a way to group your venues and are separated by spaces. Use quotes around multi-word tags like "Public LIbrary"{/t}</div></td>
	</tr>
    {/if}

	<tr>
		<td>{t}Privacy:{/t}</td><td>{t}This venue is{/t}{form_select name="event[venue_private]" options=$venue_privacy selected = $venue->getPrivate()}</td>
	</tr>

	<tr>
		<td>{t}Save venue:{/t}</td><td>
		{form_radio name="event[save_venue]" value=1 checked=$event.save_venue|default:"1"} {t}Save venue as:{/t}<br> {form_text name="event[new_venue_name]" value=""}<br>
		{form_radio name="event[save_venue]" value=0 checked=$event.save_venue}{t}Do not save venue{/t}<br>
		</td>
	</tr>
</table>
</div>