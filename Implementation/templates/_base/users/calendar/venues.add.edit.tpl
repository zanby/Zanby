<h3>{$header}</h3>
<input type="hidden" name="form_submit" id="form_submit" value="1" />
    {if $formErrors1 }

     <div class="prFormErrors">
		<h1>{t}Error Message:{/t}</h1>
        {foreach from=$formErrors1 item = e}
            <div class="prIndentBottom">{$e}</div>
        {/foreach}
     </div>
    {/if}

	<h4>{t}Fields marked with an asterisk{/t} <span class="prMarkRequired">*</span> {t}are required.{/t}</h4>
<div class="prClr3">
<table class="prForm">

    <col width="30%"/>
    <col width="40%"/>
	<col width="30%"/>
    <tbody>
        <tr>

            <td class="prTRight">
                <label for="venue_name"><span class="prMarkRequired">* </span>{t}Venue Name:{/t}</label></td>

            <td>
                <input type="text" name="venue_name" id="venue_name" value="{$aData.venue_name|escape:html}"/>
            </td>
			<td></td>
        </tr>
        <tr>

            <td class="prTRight">
                <label for="venue_category"><span class="prMarkRequired">* </span>{t}Venue Type:{/t}</label></td>

            <td>
                <select name="venue_category" id="venue_category">
                    {foreach from=$categories key = k item = v}
                        <option value="{$k}" {if $k == $aData.venue_category}selected="selected" {/if}>{$v}</option>
                    {/foreach}
                </select>
            </td>
			<td></td>
        </tr>
        <tr>

            <td class="prTRight">
                <label for="venue_street_address1"><span class="prMarkRequired">* </span>{t}Street Address:{/t}</label></td>

            <td>
                <input type="text" name="venue_street_address1" id="venue_street_address1" value="{$aData.venue_street_address1|escape:html}" />
            </td>
			<td></td>
        </tr>
        <tr style="display:none;">
            <td class="prTRight">
                <label>{t}Street Address 2:{/t}</label></td>
            <td>
                <input type="text" name="venue_street_address2" id="venue_street_address2" value="{$aData.venue_street_address2|escape:html}" />
            </td>
			<td></td>
        </tr>
        <tr>

            <td class="prTRight">
                <label for="countryId"><span class="prMarkRequired">* </span>{t}Country:{/t}</label></td>

            <td>
                <select id="countryId" name="venue_countryId" onchange="xajax_changeCountry(this.options[this.selectedIndex].value);">
                {foreach from=$countries key = k item = v}
                    <option value="{$k}"  {if $k == $aData.venue_countryId}selected="selected" {/if}>{$v}</option>
                {/foreach}
                </select>
            </td>
			<td></td>
        </tr>
        <tr>

            <td class="prTRight">
                <label for="stateId"><span class="prMarkRequired">*</span>{t}State:{/t}</label></td>

            <td>
                <select id="stateId" name="venue_stateId" onchange="xajax_changeState(this.options[this.selectedIndex].value);">
                {foreach from=$states key = k item = v}
                    <option value="{$k}" {if $k == $aData.venue_stateId}selected="selected" {/if}>{$v}</option>
                {/foreach}
                </select>
            </td>
			<td></td>
        </tr>
        <tr>

            <td class="prTRight">
                <label for="cityId"><span class="prMarkRequired">*</span>{t}City:{/t}</label></td>

            <td>
                <select  id="cityId" name="venue_cityId">
                {foreach from=$cities key = k item = v}
                    <option value="{$k}" {if $k == $aData.venue_cityId}selected="selected" {/if}>{$v}</option>
                {/foreach}
                </select>
            </td>
			<td></td>
        </tr>
        <tr>
            <td class="prTRight">
                <label for="venue_zipcode1">{t}Zip/Postal Code:{/t}</label>
            <td>
                <input type="text" id="venue_zipcode1" name="venue_zipcode1" value="{$aData.venue_zipcode1}" />
            </td>
			<td></td>
        </tr>
        <tr>
            <td class="prTRight">
                <label for="venue_phone">{t}Phone:{/t}</label>
            <td>
                <input type="text" name="venue_phone" id="venue_phone" value="{$aData.venue_phone|escape:html}" />

            </td>
			<td class="prTip">{t}Please include area code{/t}</td>
        </tr>
        <tr>
            <td class="prTRight">
                <label for="venue_website">{t}Website:{/t}</label>
            <td>
                <input type="text" name="venue_website" id="venue_website" value="{$aData.venue_website|escape:html}" />
            </td>
			<td></td>
        </tr>
        <tr>
            <td class="prTRight">
                <label for="venue_description">{t}Description:{/t}</label>
            <td>
                <textarea style="height: 80px;" name="venue_description" id="venue_description">{$aData.venue_description|escape:html}</textarea>
            </td>
			<td class="prTip"><span class="prMarkRequired">750</span> {t}characters max{/t}</td>
        </tr>
        <tr>
            <td class="prTRight"><label for="venue_tags">{t}Tags:{/t}</label></td>
            <td><input type="text" name="venue_tags" id="venue_tags" value="{$aData.venue_tags}" /></td>
			<td></td>
        </tr>
        {if HTTP_CONTEXT == 'zccf' || HTTP_CONTEXT == 'zccf-alt' || HTTP_CONTEXT == 'zccf-base'}
        <tr>
            <td class="prTRight">&nbsp;</td>
            <td colspan=2><div class="prTip">{t}Tags are a way to group your venues and are separated by spaces. Use quotes around multi-word tags like "Public LIbrary"{/t}</div></td>
        </tr>
        {/if}
        <tr>
            <td class="prTRight">
                <label for="venue_private"><span class="prMarkRequired">* </span>{t}Privacy:{/t}</label>
            <td>
                <span>{t}This Venue is{/t}</span> 
                <select name="venue_private" id="venue_private">
                {foreach from=$privacy key = k item = v}                
                    <option value="{$k}" {if $k == $aData.venue_private}selected="selected" {/if}>{$v}</option>
                {/foreach}
                </select>
            </td>
			<td></td>
        </tr>
        <tr>
            <td class="prTRight"></td>
            <td>

				{if $mode == "add"}
					{t var='button_01'}Save New Venue{/t}
					{linkbutton onclick="xajax_addNewVenue(grubVenuesForm()); return false;" name=$button_01}

					<span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="xajax_setVenue(); return false;">{t}Cancel{/t}</a></span>

				{elseif $mode == "edit"}  
					<span class="prIndentLeft">
					{t var='button_02'}Save Changes{/t}
					{linkbutton onclick="xajax_editVenue(`$aData.venueId`,'`$fromWhat`',grubVenuesForm()); return false;" name=$button_02} </span>            
					<span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="
					{if $fromWhat == 'a'}
						xajax_chooseSavedVenue();
					{elseif $fromWhat == 's'}
						xajax_loadSavedVenues(getSearches());
					{elseif $fromWhat == 'f'}
						xajax_findaVenue(getFindSearches());
					{else}			
						xajax_chooseSavedVenue()
					{/if}
					return false;">{t}Cancel{/t}</a></span>
				{/if}
            </td>
			<td></td>
        </tr>
    </tbody>
</table>
</div>