<div class="prSubContentLeft">

	{$paging}
	{if $families}
	<table cellpadding="0" cellspacing="0" border="0" class="prFullWidth prIndentTop">
		<col width="7%" />
		<col width="42%" />
		<col width="50%" />
		{foreach item=group from=$families}
            <tr>
                <td rowspan="2" class="prVTop"><img src="{$group->getAvatar()->setWidth(37)->setHeight(37)->setBorder(1)->getImage()}" title='' /></td>
                <td><a href="{$group->getGroupPath('summary')}" class="prLink2">{$group->getName()|escape:'html'}</a> | {t}{tparam value=$group->getGroupsInFamilyCount()}%s Groups{/t}</td>
                <td>{assign var=groupUID value=$group->getGroupUID()}{if $groupUID && $SSO && $SSO.$groupUID}{assign var=currentSSO value=$SSO.$groupUID}{if $currentSSO.allow}<span class="prTBold">{t}Also available at:{/t} </span><a href="http://{$currentSSO.host}" class="prLink3">http://{$currentSSO.host}</a>{/if}{/if}</td>
            </tr>
            <tr>
                <td colspan="2" class="prInnerBottom">{$group->getDescription()|escape:'html'}</td>
            </tr>
		{/foreach}
	</table>
	{/if}
    <div class="prIndentTop">{$paging}</div>    
</div>
<div class="prSubContentRight">
	<div class="prTRight">
		<a href="{$BASE_URL}/{$LOCALE}/newfamilygroup/"><img src="{$AppTheme->images}/buttons/startGroupFamily.gif" /></a>
	</div>
</div>
