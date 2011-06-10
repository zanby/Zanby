<div id="{$share_id}">
<table class="prForm">
	<col width="75%" />
	<col width="25%" />
	<tr>
		<td>{if $share_id[0]=='u'}{t}{tparam value=$name|escape}My friend %s{/t}{else}{t}{tparam value=$name|escape}The %s group{/t}{/if}</td>
		<td><a href="#" onclick="xajax_list_{$action}_unshare('{$share_id}'); return false;">{t}unshare{/t}</a></td>
	</tr>
</table>
</div>

