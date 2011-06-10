<div class="themeA">
{include file="content_objects/headline_block_view.tpl"}

<div class="prInner prClr3">
	<table class="prForm" cellspacing="0" cellpadding="0">
		<col width="100%" />
		<tbody>
			<tr style="display:{if $hide[0]}none{else}table-row{/if};">
				<td>
					<strong style="margin-bottom: 0px;">{t}Username:{/t}</strong>
					{$userInfo->getLogin()|escape:'html'}
				</td>
			</tr>
			<tr style="display:{if $hide[1]}none{else}table-row{/if};">
				<td>
					<strong style="margin-bottom: 0px;">{t}Age:{/t}</strong>
					{$userInfo->getAge()|escape:'html'}
				</td>
			</tr>
			<tr style="display:{if $hide[2]}none{else}table-row{/if};">
				<td>
					<strong style="margin-bottom: 0px;">{t}Gender:{/t}</strong>
					{$userInfo->getGender()|escape:'html'|capitalize}
				</td>
			</tr>
			<tr style="display:{if $hide[3]}none{else}table-row{/if};">
				<td>
					<strong style="margin-bottom: 0px;">{t}Real Name:{/t}</strong>
					{if $userInfo->getRealname()}{$userInfo->getRealname()|escape:'html'}{else}-{/if}
				</td>
			</tr>
			<tr style="display:{if $hide[4]}none{else}table-row{/if};">
				<td>
					<strong style="margin-bottom: 0px;">{t}Location:{/t}</strong>
					{if $userInfo->getCountry()->id==1 || $userInfo->getCountry()->id==38}
						{$userInfo->getCity()->name|escape:'html'},&nbsp;{$userInfo->getState()->name|escape:'html'}
					{else}
						{$userInfo->getCity()->name|escape:'html'},&nbsp;{$userInfo->getCountry()->name|escape:'html'}
					{/if}
				</td>
			</tr>
		</tbody>
	</table>
</div>
</div>
