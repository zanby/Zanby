<!-- form container -->

            	<h4>{t}Please Choose one of the following:{/t}</h4>
                <input type="hidden" id="venue_type" value="{$formParams.venue_type}">
                <div>               
                {form_radio id="vt_1" name="event_venue_type" value='no' checked=$formParams.venue_type onclick="changevenueto('no')"}
                <label for="vt_1"> {t}This event has no set  venue.{/t}</label><br />
				</div>
                <div class="prIndentTopSmall">               
                {form_radio id="vt_2" name="event_venue_type" value='worldwide' checked=$formParams.venue_type onclick="xajax_chooseSavedWWVenue(); return true;"}
                <label for="vt_2"> {t}This event is worldwide.{/t}</label><br />
				</div>
                <div class="prIndentTopSmall">               
                {form_radio id="vt_3" name="event_venue_type" value='simple' checked=$formParams.venue_type onclick="xajax_chooseSavedVenue(); return true;"}
                <label for="vt_3"> {t}This event takes place at the following venue.{/t}</label>
                {form_hidden name="event_venue_id" id="venueId" value=$formParams.venueId}
				</div>

{literal}
<!-- /form container -->

<!-- script -->
<script type="text/javascript">
	function setVenueId(id){
		document.getElementById('venueId').value = id;
	}

    function grubVenuesForm(){
        var venue = new Array();

        venue['venue_name'] = document.getElementById('venue_name').value;
        venue['venue_category'] = document.getElementById('venue_category').value;
        venue['venue_street_address1'] = document.getElementById('venue_street_address1').value;
        venue['venue_street_address2'] = document.getElementById('venue_street_address2').value;
        venue['venue_countryId'] = document.getElementById('countryId').value;
        venue['venue_stateId'] = document.getElementById('stateId').value;
        venue['venue_cityId'] = document.getElementById('cityId').value;
        venue['venue_zipcode1'] = document.getElementById('venue_zipcode1').value;
        venue['venue_phone'] = document.getElementById('venue_phone').value;
        venue['venue_website'] = document.getElementById('venue_website').value;
        venue['venue_description'] = document.getElementById('venue_description').value;
        venue['venue_tags'] = document.getElementById('venue_tags').value;
        venue['venue_private'] = document.getElementById('venue_private').value;
        venue['venue_active'] = document.getElementById('venue_active').value;

        return venue;
    }

    function grubWWVenuesForm(){
        var venue = new Array();

        venue['ww_venue_name'] = document.getElementById('ww_venue_name').value;
        venue['ww_venue_category'] = document.getElementById('ww_venue_category').value;
        venue['ww_venue_phone'] = document.getElementById('ww_venue_phone').value;
        venue['ww_venue_website'] = document.getElementById('ww_venue_website').value;
        venue['ww_venue_description'] = document.getElementById('ww_venue_description').value;
        venue['ww_venue_tags'] = document.getElementById('ww_venue_tags').value;
        venue['ww_venue_private'] = document.getElementById('ww_venue_private').value;

        return venue;
    }
</script>
{/literal}
<!-- /script -->

<!-- no venue block -->
<div id="no_venue_block" {if $formPArams.venue_type != 'no'} style="display:none" {/if}>
    <h4>{t}no venue for this event{/t}</h4>
</div>
<!-- /no venue block -->

<div id="add_show">
    
    <!-- worldwide venue -->
    <div id="worldwide_venue_block" class="prSubNavBlock" {if $formParams.venue_type != 'worldwide'} style="display:none" {/if}>
        {include file="users/calendar/ww.venues.tabs.tpl" active='add'}
        <div class="prSubNavContBlock" id="worldwide_venue_body">
            {if $formParams.venueId && $formParams.venue_type == "worldwide"}
                {include file="users/calendar/ww.venues.view.tpl"}
            {else}
                {include file= "users/calendar/ww.venues.index.tpl"}
            {/if}
        </div>
    </div>
    <!-- /worldwide venue -->
    
    <!-- simple venue block -->
    <div id="simple_venue_block" class="prSubNavBlock" {if $formParams.venue_type != 'simple'} style="display:none" {/if}>
        {include file="users/calendar/venues.tabs.tpl"  active='add'}
        <div class="prSubNavContBlock" id="simple_venue_body">
            {if $formParams.venueId && $formParams.venue_type == "simple"}
                {include file="users/calendar/venues.view.tpl"}
            {else}
                {include file= "users/calendar/venues.index.tpl"}
            {/if}
        </div>
    </div>
    <!-- /simple venue block -->
    
    <!-- saved worldwide -->
    <div id="saved_worldwide_venue_block" class="prSubNavBlock" style="display:none">
        {include file="users/calendar/ww.venues.tabs.tpl"  active='saved'}
        <div class="prSubNavContBlock" id="saved_worldwide_venue_body">
            {include file="users/calendar/ww.venues.saved.index.tpl"}
        </div>
        <input type="hidden" id="wl" name="wl" value="{if $aWWSearches.l}{$aWWSearches.l}{else}all{/if}" />
        <input type="hidden" id="wc" name="wc" value="{if $aWWSearches.c}{$aWWSearches.c}{else}0{/if}" />
        <input type="hidden" id="wp" name="wp" value="{if $aWWSearches.p}{$aWWSearches.p}{else}1{/if}" />
    </div>
    <!-- /saved worldwide -->
    
    <!-- saved simple -->
    <div id="saved_simple_venue_block" class="prSubNavBlock" style="display:none">
        {include file="users/calendar/venues.tabs.tpl"  active='saved'}
        <div class="prSubNavContBlock" id="saved_simple_venue_body">
            {include file="users/calendar/venues.saved.index.tpl"}
        </div>
        <input type="hidden" id="l" name="l" value="{if $aSearches.l}{$aSearches.l}{else}all{/if}" />
        <input type="hidden" id="c" name="c" value="{if $aSearches.c}{$aSearches.c}{else}0{/if}" />
        <input type="hidden" id="p" name="p" value="{if $aSearches.p}{$aSearches.p}{else}1{/if}" />
    </div>
    <!-- /saved simple -->
    
    <!-- find simple -->
    <div id="find_simple_venue_block" class="prSubNavBlock" style="display:none">
        {include file="users/calendar/venues.tabs.tpl"  active='find'}
        <div class="prSubNavContBlock" id="find_simple_venue_body">
            {include file="users/calendar/venues.search.index.tpl"}
        </div>
    </div>
    <!-- /find simple -->
</div>