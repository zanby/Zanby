{if USER_LOG}
<div class="prTLeft">
    <a href="{$admin->getAdminPath('log')}">{t}Admin Log Info{/t}</a> | <a href="{$admin->getAdminPath('userlog')}">{t}User Logs Info{/t}</a>
</div>
{/if}
<div class="prTCenter prIndentTop">
	{form id="sForm" name="sForm" from=$form}
    <div >
        <label for="date">{t}Period:{/t} </label>
        <select name="period" id="period" onchange="$('#sForm').submit()">
            <option value="1" {if $period == 1}selected="selected"{/if}>{t}Today{/t}</option>
            <option value="2" {if $period == 2}selected="selected"{/if}>{t}7 Days{/t}</option>
            <option value="3" {if $period == 3}selected="selected"{/if}>{t}30 Days{/t}</option>
        </select>
        {t var="in_submit"}Apply{/t}{form_submit value=$in_submit name="searchForm"}
    </div>
	{/form}
</div>
<div class="prTCenter prIndentTop">
    {t}Number of successful logins{/t}: {$successCount}<br />
    {t}Number of failed logins{/t}: {$failureCount}<br />
    {t}Successfull logins percentage{/t}: {$percentage}<br />
    {t}Number of password recovery requests{/t}: {$restoreCount} <br />
    <a href="{$admin->getAdminPath('userlog/export/1')}">{t}Log info for last 3 months{/t}</a>
</div>