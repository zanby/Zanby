<div class="prClr3">
<h3>{t}Find a Venue{/t}</h3>
<p class="prInnerTop">{t}{tparam value=$SITE_NAME_AS_STRING}This searches public venues created by other %s users.{/t}</p>
<p>{t}NOTE:  Venue information has not been verified.  Please review details carefully.{/t}</p>
    <input type="hidden" id="find_venue" name="find_venue" value="1" />
    <input type="hidden" id="find_page" name="find_page"  value="1" />
    <table cellspacing="0" cellpadding="0" border="0" class="prForm">
      <tbody>
      	<tr>
			<td class="">
				<label for="find_keywords">{t}Keyword{/t}</label>
				<p>{t}or Tag separated by comma{/t}</p>
				<div class="prInnerSmallTop">
					<input type="text" id="find_keywords" name="find_keywords" class="" value="{if $aSearches.find_keywords}{$aSearches.find_keywords}{/if}" />
            	</div>
			</td>
			<td class="">
				<label for="find_where">{t}Where{/t}</label>
				<p>{t}City, State, Country{/t}</p>
				<div class="prInnerSmallTop">	
					<input type="text" id="find_where" name="find_where" class="" value="{if $aSearches.find_where}{$aSearches.find_where}{/if}" />
            	</div>
			</td>
		</tr>
		<tr>
			<td>
				<label for="find_createdBy">{t}Created By{/t}</label>
				<p>{t}Your friends, etc.{/t}</p>
				<div class="prInnerSmallTop">		
					<select id="find_createdBy" name="find_createdBy" class="">
					   {foreach from=$aCreatedBy key = k item = v}
						   <option value="{$k}" {if $k == $aSearches.find_createdBy}selected="selected"{/if} >{$v}</option>
					   {/foreach}
					</select>
				</div>	
			</td>
			<td> 
				<label for="find_category">{t}Venue Type{/t}</label>
				<p>{t}Bar, Park, etc{/t}</p>
				<div class="prInnerSmallTop">	
					<select id="find_category" name="find_category" class="">
					{foreach from=$aVenueCategories key = k item = v}
						<option value="{$k}" {if $k == $aSearches.find_category }selected="selected"{/if}>{$v}</option>
					{/foreach}
					</select>
				</div>	
			</td>
		</tr>
    </tbody></table>
</div>
<div class="prTRight prInnerTop">
	{t var="in_button"}Find Venues{/t}
		{linkbutton name=$in_button onclick="xajax_findaVenue(getFindSearches()); return false;"}
</div>

{literal}
        <script type="text/javascript">
            function getFindSearches()
            {
                var search = new Array();
                search['find_keywords']   = document.getElementById('find_keywords').value;
                search['find_category']   = document.getElementById('find_category').value;
                search['find_createdBy']  = document.getElementById('find_createdBy').value;
                search['find_where']      = document.getElementById('find_where').value;
                search['find_page']       = document.getElementById('find_page').value;
                return search;
            }
            
            function setFindSearches(a,v)
            {
                document.getElementById('p').value = 1;
                if (a == 'c') {
                    document.getElementById('l').value = 'all';
                }
                document.getElementById(a).value = v;
            }
        </script>
 {/literal}

    {include file="groups/calendar/venues.search.result.tpl"}