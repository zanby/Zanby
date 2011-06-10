<div class="prDropBoxInner">
<a href="{$admin->getAdminPath('userActivityLogsCsv/month/')}{$currMonth}">{t}Download logs for current month{/t}</a><br/>
<a href="{$admin->getAdminPath('userActivityLogsCsv/month/')}{$previousMonth}">{t}Download logs for previous month{/t}</a><br/><br/>
{if $backupedLogs}
<h3>{t}Logs for other monthes{/t}</h3>
{foreach from=$backupedLogs item=month}
<a href="{$admin->getAdminPath('userActivityLogsCsv/month/')}{$month}">{$month}</a><br/>
{/foreach}
{/if}
</div>