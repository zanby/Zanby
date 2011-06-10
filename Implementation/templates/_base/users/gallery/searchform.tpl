<h2>{t}Search Photos{/t}</h2> 
{form from=$form}
{form_hidden name="preset" value="new"}
<table class="prForm">
<col width="50%" />
<col width="50%" /> 
  <tr>
    <td>
        <label for="keywords"><strong>{t}Keyword{/t}</strong></label>
        {t}or Tag separated by comma{/t}
        <div class="prIndentTopSmall">
        {form_text id="keywords" name="keywords" value=$keywords|escape class="prInnerSmallTop"}
        </div>
    </td>
    <td>
        <label for="whouploaded">{t}Uploaded by Who{/t}</label>
        <div class="prIndentTopSmall">
        {form_select name="whouploaded" options=$whoUploadedList selected = $whoUploaded}
		{t var='button_01'}Search{/t}
        {linkbutton name=$button_01 onclick="document.forms['search_photos'].submit(); return false;"}
        </div>    
      
    </td>
  </tr>
</table>
{/form}
{form from=$formRemember id="formRemember"}
{if $isResultPage}
    {form_hidden name=page value=$page|escape}
    {form_hidden name=order value=$order|escape}
    {form_hidden name=direction value=$direction|escape}
    {form_hidden name=filter value=$filter|escape}
	 
	<table class="prForm">
	<col width="50%" />
	<col width="50%" /> 
  	<tr>
    	<td>                    
        <label for="searchName">{t}Remember search as:{/t}</label>
		<div class="prIndentTopSmall">
		{form_text id="searchName" name="search_name" maxlength="15"}
		</div>
    	</td>
		<td><br />
		{t var='button_02'}Remember{/t}
		{linkbutton name=$button_02 onclick="document.forms['formRemember'].submit(); return false;"}
		</td>
	</tr>
	</table>
	
{/if}
{if $savedSearches}
    {form_hidden id="searchtodel" name="searchtodel" value=""}
    <h3>{t}Saved Photo Searches:{/t}</h3>
    <div class="prInnerTop">
      {foreach item=s key=key from=$savedSearches name=savedSearches}
        <a href="{$user->getUserPath('photossearch')}saved/{$key}/"{if !$smarty.foreach.savedSearches.first} class="prInnerLeft"{/if}>{if $s}{$s|escape:"html"}{else}{t}noname{/t}{/if}</a> 
        <a href="javascript:void(0);" onclick="xajax_searchdelete('{$key}'); return false;" >&nbsp;</a>
      {/foreach}
    </div>             
{/if}  
{/form}
