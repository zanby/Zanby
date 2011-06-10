
<div class="prFloatRight">
	<!-- vertical spacer -->
	{t var="in_button"}Start a group{/t}
	{linkbutton name=$in_button link="$BASE_URL/$LOCALE/newgroup/"}
	<!--<a href="{$BASE_URL}/{$LOCALE}/newgroup/">&nbsp;</a>-->
	<div>{t var="in_button_2"}Start a group family{/t}{linkbutton name=$in_button_2 link="$BASE_URL/$LOCALE/newfamilygroup/"}</div>
	<!--<a href="{$BASE_URL}/{$LOCALE}/newfamilygroup/">&nbsp;</a>-->  
	<div class="prGrayBorder prInnerSmall">
		<h3>{t}Sort Results or Search again{/t}</h3>   
   
    	{form from=$form}
    	{form_hidden name="preset" value="new"}
		<table class="prForm">			
			<tr>
				<td><label for="keywrd">{t}Keyword or Zip Code:{/t}</label>			  	
				{form_text id=keywrd name="keywords" value=$keywords|escape class="prIndentTopSmall"}
			  	</td>
			</tr>
			<tr>
			  	<td><label for="countryId">{t}Country:{/t}</label>
				{form_select name="country" id="countryId" onchange="xajax_search_onchange_country(this.options[this.selectedIndex].value);" selected=$country options=$countries  class="prIndentTopSmall"}
			  	</td>
			</tr>
			<tr>
			  	<td><label for="stateId">{t}State/province:{/t}</label>
				{form_select name="state" id="stateId" onchange="xajax_search_onchange_state(this.options[this.selectedIndex].value);" selected=$state options=$states class="prIndentTopSmall"}
			  	</td>
			</tr>
			<tr>
			  	<td><label for="cityId">{t}City:{/t}</label>
				{form_select name="city" id="cityId" selected=$city options=$cities class="prIndentTopSmall"}
			  	</td>
			</tr>
			<tr>
			  	<td><label for="grcat">{t}Group Category:{/t}</label>
				{form_select id=grcat name="category" selected=$category options=$allCategories class="prIndentTopSmall"}
			  	</td>
			</tr>
			<tr>
				<td class="prTRight">
				{t var="in_button_3"}Update{/t}
				{linkbutton name=$in_button_3 onclick="document.forms['search_group'].submit(); return false;"}
			  	</td>			  
			</tr>
		</table>
      {/form}
     
    
   
    {if $topTags}
    
    <h3 class="prInnerSmall16">{t}Top Group Tags{/t}</h3>   
    <div class="prInnerTop">      
        {foreach item=m key=k from=$topTags}
            {if $m>80}
                 <a style="font-size:1.4em" href="{$_actionUrl}/preset/tag/tname/{$k|escape:html}/">{$k|escape:html}</a>
            {elseif $m>60}
                 <a style="font-size:1.2em" href="{$_actionUrl}/preset/tag/tname/{$k|escape:html}/">{$k|escape:html}</a>
            {elseif $m>40}
                 <a style="font-size:1em" href="{$_actionUrl}/preset/tag/tname/{$k|escape:html}/">{$k|escape:html}</a>
            {elseif $m>20}
                 <a style="font-size:0.9em" href="{$_actionUrl}/preset/tag/tname/{$k|escape:html}/">{$k|escape:html}</a>
            {else}
                 <a style="font-size:0.8em" href="{$_actionUrl}/preset/tag/tname/{$k|escape:html}/">{$k|escape:html}</a>
            {/if}
        {/foreach}      
    </div>
    {/if}
    
  </div>  
</div>
<!-- right column end -->    