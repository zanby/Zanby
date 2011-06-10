<div class="prFloatRight"> 
  <div class="prGrayBorder prInner">
    <h4>{t}Sort Results or Search again{/t}</h4>
    <div class="prInnerSmallTop">
    {form from=$form class="search_form"}
    {form_hidden name="preset" value="new"}
       <div class="prInnerTop">
        	<label for="keywrd" />{t}Keyword or Zip code:{/t}</label> 
			{form_text name="keywords" value=$keywords|escape id="keywrd"}
		</div>	
		<div class="prInnerTop prInnerLeft">
			<div>Gender:</div>
			{form_radio name="gender" id="gender_a" value="0" checked=$gender|default:"0"} <label for="gender_a">{t}All{/t}</label><br />
			{form_radio name="gender" id="gender_m" value="male" checked=$gender} <label for="gender_m">{t}Men{/t}</label><br />
			{form_radio name="gender" id="gender_w" value="female" checked=$gender} <label for="gender_w">{t}Women{/t}</label>
		</div>	
		<div class="prInnerTop prInnerLeft">
            <label>{t}Age:{/t}</label>
            {form_select name="age_from" id="age_from" selected="$age_from" options=$ageFrom slass="prFloatLeft"} - 
            {form_select name="age_to" id="age_to" selected="$age_to" options=$ageTo slass="prFloatLeft"}
		</div>	
     	<div class="prInnerTop">
            <label for="country">{t}Country:{/t}</label><br />
            {form_select name="country" id="countryId" onchange="xajax_search_onchange_country(this.options[this.selectedIndex].value);" selected=$country options=$countries}
     	</div>
		<div class="prInnerTop">
            <label for="stateId"> {t}State/Province:{/t}</label><br />
            {form_select name="state" id="stateId" onchange="xajax_search_onchange_state(this.options[this.selectedIndex].value);" selected=$state options=$states}
        </div>
		<div class="prInnerTop">
            <label for="cityId">{t}City:{/t}</label><br />
            {form_select name="city" id="cityId" selected=$city options=$cities}
		</div>
		<div class="prInnerTop">
            <label for="grcat">{t}Group Category:{/t}</label><br />
            {form_select name="category" id="grcat" selected=$category options=$categories}          
            <div class="prInnerSmallTop">{form_checkbox name="photo_only" value="1" checked=$photo_only id="photo_only"}<label for="photo_only">{t}Photo Only{/t}</label><div>
        </div>
        <div class="prTRight">
			{t var='button_01'}Update{/t}
          {linkbutton float=right name=$button_01 onclick="document.forms['search_user'].submit(); return false;"}
		</div>  
    {/form}
    </div>
    {if $topTags}
    <h4 class="prInnerTop">{t}Top Member Tags{/t}</h4>
    <div>
        {foreach item=m key=k from=$topTags}
            {if $m>80}
                 <a style="color:#FF6600; font-size:1.4em" href="{$_url}/preset/tag/tname/{$k|escape:html}/">{$k|escape:html}</a>
            {elseif $m>60}
                 <a style="color:#00B9DD; font-size:1.1em" href="{$_url}/preset/tag/tname/{$k|escape:html}/">{$k|escape:html}</a>
            {elseif $m>40}
                 <a style="color:#336666; font-size:1em" href="{$_url}/preset/tag/tname/{$k|escape:html}/">{$k|escape:html}</a>
            {elseif $m>20}
                 <a style="color:#000000; font-size:0.9em" href="{$_url}/preset/tag/tname/{$k|escape:html}/">{$k|escape:html}</a>
            {else}
                 <a style="color:#666666; font-size:0.8em; text-decoration:none" href="{$_url}/preset/tag/tname/{$k|escape:html}/">{$k|escape:html}</a>
            {/if}
        {/foreach}
    </div>
    {/if}              
    {if $user->getId()}
    <div class="prInnerSmallTop"> <a href="{$_url}/preset/mytags/">{t}Members with your{/t}</a> <a href="{$_url}/preset/mytags/" class="prMarkRequired">{t}tags{/t}</a></div>
    <div class="prInnerSmallTop"> <a href="{$_url}/preset/friendstag/">{t}Your friend's{/t}</a> <a href="{$_url}/preset/friendstag/" class="prMarkRequired">{t}tags{/t}</a></div>
    {/if}
  </div>
</div>