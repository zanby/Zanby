<div class="themeA">
{include file="content_objects/headline_block_view.tpl"}

<!-- Content Object Profile Detail -->
<table class="prForm prIndent" cellpadding="0" cellspacing="0">
	<col width="28%" />
	<col width="72%" />
	<tbody>
		<tr style="display:{if $hide[0]}none{else}table-row{/if};">
			<td class="prTRight"><strong>{t}Username:{/t}</strong></td>
			<td>{$userInfo->getLogin()|escape:'html'}</td>
		</tr>
		<tr style="display:{if $hide[1]}none{else}table-row{/if};">
			<td class="prTRight"><strong>{t}Age:{/t}</strong></td>
			<td>{$userInfo->getAge()|escape:'html'}</td>
		</tr>
		<tr style="display:{if $hide[2]}none{else}table-row{/if};">
			<td class="prTRight"><strong>{t}Gender:{/t}</strong></td>
			<td>{$userInfo->getGender()|escape:'html'|capitalize}</td>
		</tr>
		<tr style="display:{if $hide[3]}none{else}table-row{/if};">
			<td class="prTRight"><strong>{t}Real Name:{/t}</strong></td>
			<td>{if $userInfo->getRealname()}{$userInfo->getRealname()|escape:'html'}{else}-{/if}</td>
		</tr>
		<tr style="display:{if $hide[4]}none{else}table-row{/if};">
			<td class="prTRight"><strong>{t}Location:{/t}</strong></td>
			<td>{if $userInfo->getCountry()->id==1 || $userInfo->getCountry()->id==38}
					{$userInfo->getCity()->name|escape:'html'},&nbsp;{$userInfo->getState()->name|escape:'html'}
				{else}
					{$userInfo->getCity()->name|escape:'html'},&nbsp;{$userInfo->getCountry()->name|escape:'html'}
				{/if}
			</td>
		</tr>
	</tbody>
</table>
</div>
