<h4>{$header}</h4>
<input type="hidden" name="ww_form_submit" id="ww_form_submit" value="1" />

    {if $formErrors1 }

		<div class="prFormErrors">
			<h1>{t}Error Message:{/t}</h1>
			{foreach from=$formErrors1 item = e}
				<div class="prIndentBottom">{$e}</div>
			{/foreach}
        </div>       
    {/if}

	<h4>{t}Fields marked with an asterisk <span class="prMarkRequired">* </span> are required.{/t}</h4>
<div class="prClr3">
<table class="prForm">

    <col width="30%"/>
    <col width="40%"/>
	<col width="30%"/>
    <tbody>
        <tr>
            <td class="prTRight">
                <label for="ww_venue_name"><span class="prMarkRequired">* </span>{t}Venue Name:{/t}</label>
			</td>
            <td>
                <input type="text" name="ww_venue_name" id="ww_venue_name" value="{$aData.ww_venue_name|escape:html}"/>
            </td>
			<td>&#160;</td>
        </tr>
        <tr>

            <td class="prTRight">
                <label for="ww_venue_category"><span class="prMarkRequired">*</span>{t}Venue Type:{/t}</label></td>

            <td>
                <select name="ww_venue_category" id="ww_venue_category">
                    {foreach from=$categories key = k item = v}
                        <option value="{$k}"{if $k == $aData.ww_venue_category} selected="selected" {/if}>{$v}</option>
                    {/foreach}
                </select>
            </td>
			<td>&#160;</td>
        </tr>
        <tr>
            <td class="prTRight">
                <label for="ww_venue_phone">{t}Phone:{/t}</label></td>
            <td>

                <input type="text" class="" name="ww_venue_phone" id="ww_venue_phone" value="{$aData.ww_venue_phone|escape:html}" />
                
            </td>
			<td><div class="prTip">{t}Please include area code{/t} </div></td>
        </tr>
        <tr>
            <td class="prTRight">
                <label for="ww_venue_website">{t}Website:{/t}</label> </td>
            <td>
                <input type="text" name="ww_venue_website" id="ww_venue_website" value="{$aData.ww_venue_website|escape:html}" />
            </td>
			<td>&#160;</td>
        </tr>
        <tr>
            <td class="prTRight">
                <label for="ww_venue_description">{t}Description:{/t}</label> </td>
            <td>

                <textarea class="" name="ww_venue_description" id="ww_venue_description">{$aData.ww_venue_description|escape:html}</textarea>
                
            </td>
			<td><div class="prTip">{t}750 characters max{/t}</div></td>
        </tr>
        <tr>
            <td class="prTRight"><label for="ww_venue_tags">{t}Tags:{/t}</label></td>
            <td><input type="text" name="ww_venue_tags" id="ww_venue_tags" value="{$aData.ww_venue_tags}" /></td>
			<td>&#160;</td>
        </tr>
        {if HTTP_CONTEXT == 'zccf' || HTTP_CONTEXT == 'zccf-alt' || HTTP_CONTEXT == 'zccf-base'}
        <tr>
            <td class="prTRight">&nbsp;</td>
            <td colspan=2><div class="prTip">{t}Tags are a way to group your venues and are separated by spaces. Use quotes around multi-word tags like "Public LIbrary"{/t}</div></td>
        </tr>
        {/if}
        <tr>

            <td class="prTRight">
                <label for="ww_venue_private"><span class="prMarkRequired">* </span>{t}Privacy:{/t}</label>
			</td>
            <td>
                <span>{t}This Venue is{/t}</span> 
                <select name="ww_venue_private" id="ww_venue_private">
                {foreach from=$privacy key = k item = v}
                    <option value="{$k}" {if $k == $aData.ww_venue_private}selected="selected" {/if}>{$v}</option>
                {/foreach}
                </select>
            </td>
			<td>&#160;</td>
        </tr>
        <tr>
            <td class="prTRight"></td>               
            <td>

            {if $mode == "add"}
                <span class="prIndentLeft">
				{t var='button_01'}Save New Venue{/t}
				{linkbutton color="blue" name=$button_01 onclick="xajax_addNewWWVenue(grubWWVenuesForm()); return false;"}</span>
                <span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="xajax_setWWVenue(); return false;">{t}Cancel{/t}</a></span>
            {elseif $mode == "edit"}
			<span>
			{t var='button_02'}Save Changes{/t}
			{linkbutton color="blue" name=$button_02 onclick="xajax_editWWVenue('"|cat:$aData.venueId|cat:"', '"|cat:$fromWhat|cat:"', grubWWVenuesForm()); return false;"}</span>
			<span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="
                {if $fromWhat == 'wa'}
                    xajax_chooseSavedWWVenue();
                {elseif $fromWhat == 'ws'}
                    xajax_loadSavedWWVenues(getWWSearches());            
                {else}
                    xajax_chooseSavedWWVenue();
                {/if}
				 return false;">{t}Cancel{/t}</a></span>
            {/if}
            </td>
			<td>&#160;</td>
        </tr>
    </tbody>
</table>
</div>