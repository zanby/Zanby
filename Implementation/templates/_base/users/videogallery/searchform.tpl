<h2>{t}Search Videos{/t}</h2> 
{form from=$form}
{form_hidden name="preset" value="new"}
<table class="prForm">
<col width="30%" />
<col width="30%" /> 
  <tr>
    <td>
        <label for="keywords">{t}<strong>Keyword</strong>
        or Tag separated by comma{/t}</label>
        <div class="prIndentTopSmall">
        {form_text id="keywords" name="keywords" value=$keywords|escape class="prInnerSmallTop"}
        </div>
    </td>
    <td>
        <label for="whouploaded">{t}Uploaded by Who{/t}</label>
        <div class="prIndentTopSmall">
        {form_select name="whouploaded" options=$whoUploadedList selected = $whoUploaded}
		{t var="in_button_02"}Search{/t}
        {linkbutton name=$in_button_02 onclick="document.forms['search_videos'].submit(); return false;"}
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
	<col width="30%" />
	<col width="30%" /> 
		<tr>
			<td><label for="searchName">{t}Remember search as:{/t}</label>
				<div class="prIndentTopSmall">
				{form_text id="searchName" name="search_name" maxlength="15"}
				</div>
			</td>
			<td><br />
			{t var="in_button_01"}Remember{/t}
				{linkbutton name=$in_button_01 onclick="document.forms['formRemember'].submit(); return false;"}
			</td>
		</tr>
	</table>
{/if}
{if $savedSearches}
    {form_hidden id="searchtodel" name="searchtodel" value=""}
    <h3 class="prInnerTop">{t}Saved Video Searches:{/t}</h3>
    <div class="prInnerTop">
      {foreach item=s key=key from=$savedSearches name=savedSearches}      
        <a href="{$user->getUserPath('videossearch')}saved/{$key}/"{if !$smarty.foreach.savedsearches.first} class="prIndentBottom10"{/if}>{if $s}{$s|escape:"html"}{else}{t}noname{/t}{/if}</a> 
        <a href="javascript:void(0);" onclick="xajax_searchdelete('{$key}'); return false;" >&nbsp;</a>
      {/foreach}
    </div>             
{/if}  
{/form}