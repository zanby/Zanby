<!-- -->
	<div class="prIndentBottom">
        <label>{t}Select Saved Venue:{/t}</label>
		<div class="prInnerTop">
        <select name="event[saved_venues_list]" class="" onchange="xajax_chooseSavedWWVenue(this.options[this.selectedIndex].value)">
            {foreach from=$venuesWorldwideList key = k item = v}
                <option value="{$k}">{$v|strip_tags}</option>';
            {/foreach}
        </select>
		</div>
    </div>
    {t}OR{/t}
    <div class="prIndentTop">
	{t var='button'}Add New Venue{/t}
	{linkbutton color="blue" name=$button onclick="xajax_addNewWWVenue(); return false;"}</div>
<!-- / -->