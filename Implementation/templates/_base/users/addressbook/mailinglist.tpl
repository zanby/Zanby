<h3>{t}Your Mailing Lists{/t}</h3>
<table cellspacing="0" cellpadding="0">
	{foreach item=contact from=$contactLists}
	<tr>
	  <td><a href="{$contact->url}">{$contact->displayName|escape}</a></td>
	</tr>
	{/foreach}
</table>