{form from=$form class="search_form"}
{form_hidden name="new" value="1"}
<table class="prForm">
	<col width="50%" />
	<col width="50%" /> 		    
	<tr>
		<td>
		<label for="keywords">{t}Keyword <span>or Tag separated by comma</span>{/t}</label>
		<div class="prIndentTopSmall">
		{form_text id="keywords" name="keywords" value=$keywords|escape class="prIndentTop"}
		</div>
		</td>            
		<td>
			<label for="listType">{t}List Type{/t}</label>
			<div class="prIndentTopSmall">
			{form_select id="listType" name="type" options=$listTypesAssoc selected = $type}
			{t var='button'}Search{/t}
			{linkbutton name=$button onclick="document.forms['search_list'].submit(); return false;"}
			</div>					
		</td>            
	</tr>
</table>
{/form}