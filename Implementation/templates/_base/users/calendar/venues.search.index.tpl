<h4>{t}Find a Venue{/t}</h4>
<div>{t}{tparam value=$SITE_NAME_AS_STRING}This searches public venues created by other %s users.<br /> NOTE:  Venue information has not been verified.  Please review details carefully.{/t}</div>
<div class="prInner prClr3">
<!-- search form -->
    <input type="hidden" id="find_venue" name="find_venue" value="1" />
    <input type="hidden" id="find_page" name="find_page"  value="1" />
    <table cellspacing="0" cellpadding="0" border="0" class="prForm">
      <tbody>
      	<tr>
			<td>
				<label>{t}Keyword{/t}</label><br /> {t}or Tag separated by comma{/t}
				<div class="prInnerSmallTop">
					<input type="text" id="find_keywords" name="find_keywords" value="{if $aSearches.find_keywords}{$aSearches.find_keywords}{/if}" onfocus="addInspectButtons()" />
            	</div>
			</td>
			<td>
					<label><strong>{t}Where{/t}</strong></label><br /> {t}City, State, Country{/t}
				<div class="prInnerSmallTop">	
					<input type="text" id="find_where" name="find_where" value="{if $aSearches.find_where}{$aSearches.find_where}{/if}" onfocus="addInspectButtons()" />
            	</div>
			</td>
		</tr>
		<tr>
			<td>
				<label>{t}Created By{/t}</label><br /> {t}Your friends, etc.{/t}
				<div class="prInnerSmallTop">		
					<select id="find_createdBy" name="find_createdBy">
					   {foreach from=$aCreatedBy key = k item = v}
						   <option value="{$k}" {if $k == $aSearches.find_createdBy}selected="selected"{/if} >{$v}</option>
					   {/foreach}
					</select>
				</div>	
			</td>
			<td> 
				<label>{t}Venue Type{/t}</label><br /> {t}Bar, Park, etc{/t}
				<div class="prInnerSmallTop">	
					<select id="find_category" name="find_category">
					{foreach from=$aVenueCategories key = k item = v}
						<option value="{$k}" {if $k == $aSearches.find_category }selected="selected"{/if}>{$v}</option>
					{/foreach}
					</select>
				</div>	
			</td>
		</tr>
    </tbody></table>
	<div class="prTRight prInnerTop">
		{t var='button'}Find Venues{/t}
		{linkbutton color="blue" name=$button onclick="xajax_findaVenue(getFindSearches()); return false;"}
	</div>
<!-- /search form -->
</div>

<!--  JS functions placed in action.event.create.tpl, action.event.edit.tpl and action.event.copy.tpl -->

    {include file="users/calendar/venues.search.result.tpl"}
