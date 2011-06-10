	<a href="{$user->getUserPath('addressbook')}">{t}Back to Addressbook{/t}</a>
	<h2>{t}{tparam value=$locationText}Address Book &ndash; %s{/t}</h2>
<div class="prAddressBookContent">
	<div class='prAddressBookLeft'>
		{form from=$form}
		<table class="prForm">
		<col width="42%" />
		<col width="55%" />
		<col width="3%" />
		 
		<thead>
			<tr><th colspan="2">{form_errors_summary}</th></tr>
		</thead>
		<tbody>
			<tr style="display:{$addZanbyBlock};">
				<td class="prTRight"><label for="username">{t}{tparam value=$SITE_NAME_AS_STRING}%s User:{/t}</label></td>
				<td>{form_text name="username" value=$items.username|escape:"html"}</td>
				<td></td>
			</tr>
			<tr style="display:{$addZanbyBlock};">
				<td></td>
				<td class="prTRight">
				{t var='button_01'}Add User{/t}
				{form_submit value=$button_01 name="addZanby"}</td>
				<td></td>
			</tr>                
			<tr>
				<td class="prTRight"><label for="firstName">{t}First Name:{/t}</label></td>
				<td>{form_text name="firstName" value=$items.firstName|escape:"html"}</td>
				<td></td>
			</tr>                
			<tr>
				<td class="prTRight"><label for="lastName">{t}Last Name:{/t}</label></td>
				<td>{form_text name="lastName" value=$items.lastName|escape:"html"}</td>
				<td></td>
			</tr>
			<tr>
				<td class="prTRight"><label for="email">{t}Email Address:{/t}</label></td>
				<td>{form_text name="email" value=$items.email|escape:"html"}</td>
				<td></td>
			</tr>
		  <tr>
			<td class="prTRight"><label for="email2">{t}Seconadry Email Address:{/t}</label></td>
			<td>{form_text name="email2" value=$items.email2|escape:"html"}</td>
			<td></td>
		  </tr>        
		  <tr>
			<td class="prTip" colspan="3">&nbsp;</td>
		  </tr>              
		  <tr>
			<td class="prTRight"><label for="phoneHome">{t}Home Phone:{/t}</label></td>
			<td>{form_text name="phoneHome" value=$items.phoneHome|escape:"html"}</td>
			<td></td>
		  </tr>                                   
		  <tr>
			<td class="prTRight"><label for="phoneBusiness">{t}Business Phone:{/t}</label></td>
			<td>{form_text name="phoneBusiness" value=$items.phoneBusiness|escape:"html"}</td>
			<td></td>
		  </tr>                           
		  <tr>
			<td class="prTRight"><label for="phoneMobile">{t}Mobile Phone:{/t}</label></td>
			<td>{form_text name="phoneMobile" value=$items.phoneMobile|escape:"html"}</td>
			<td></td>
		  </tr>    
		  <tr>
			<td class="prTip" colspan="3">&nbsp;</td>
		  </tr>              
		  <tr>
			<td class="prTRight"><label for="country">{t}Country:{/t}</label></td>
			<td>{form_select id="countryId" name="country" options=$countries onchange="xajax_changeCountry(this.options[this.selectedIndex].value);" selected=$items.country}</td>
			<td></td>
		  </tr>                     
		  <tr>
			<td class="prTRight"><label for="state">{t}State\Province:{/t}</label></td>
			<td>{form_select id="stateId" name="state" selected=$items.state options=$states onchange="xajax_changeState(this.options[this.selectedIndex].value);" selected=$items.state}</td>
			<td></td>
		  </tr>
		  <tr>
			<td class="prTRight"><label for="city">{t}City:{/t}</label></td>
			<td>{form_select id="cityId" name="city" selected=$items.city options=$cities selected=$items.city}</td>
			<td></td>
		  </tr>
		  <tr>
			<td class="prTRight"><label for="zip">{t}Zip code/Postal code:{/t}</label></td>
			<td>{form_text name="zip" value=$items.zip|escape:"html"}</td>
			<td></td>
		  </tr>
		  <tr>
			<td class="prTRight"><label for="street">{t}Street:{/t}</label></td>
			<td>{form_text name="street" value=$items.street|escape:"html"}</td>
			<td></td>
		  </tr>                
		  <tr>
			<td class="prTip" colspan="3">&nbsp;</td>
		  </tr>              
		  <tr>
			<td class="prTRight"><label for="notes">{t}Notes:{/t}</label></td>
			<td>{form_textarea name="notes" value=$items.notes|escape:"html" style="height: 8em !important;"}</td>
			<td></td>
		  </tr>                                                                     
			<tr>
				<td></td>
				<td class="prTRight">
				{t var='button_02'}{tparam value=$ButtonName}%s Contact{/t}
				{form_submit value=$button_02 name="add"}</td>
				<td></td>
			</tr>
		</tbody>
		</table>
		{/form}
	</div>
	<div class='prAddressBookRight'>
		{include file="users/addressbook/mailinglist.tpl"}
	</div>
	<!-- right column end -->
</div>