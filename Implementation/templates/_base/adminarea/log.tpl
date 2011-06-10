{if USER_LOG}
<div class="prTLeft">
    <a href="{$admin->getAdminPath('log')}">{t}Admin Log Info{/t}</a> | <a href="{$admin->getAdminPath('userlog')}">{t}User Log Info{/t}</a>
</div>
{/if}
<div class="prTLeft prIndentTop">
	{form id="sForm" name="sForm" from=$form}
		<table>
			<col width="50%" />
			<col width="40%" />
			<col width="10%" />
			<tbody>
				<tr>
					<td class="prTLeft">
						<label for="date">{t}Date (yyyy-mm-dd):{/t} </label>{form_text id="date" name="date" value=$dateFilter|escape:"html"}        
					</td>
					<td class="prTRight">
						<label for="adminname">{t}Admin's name:{/t} </label>{form_text id="adminname" name="adminname" value=$nameFilter|escape:"html"}        
					</td>
					<td class="prTCenter">
						{t var="in_submit"}Apply{/t}{form_submit value=$in_submit name="searchForm"}
					</td>
				</tr>
			</tbody>
		</table>
	{/form}
</div>
<!-- result begin -->
<div class="prTLeft prIndentTop">
{$paging}
</div>
<table cellspacing="0" cellpadding="0" class="prResult">
		<col width="20%" />
		<col width="65%" />
		<col width="15%" />
	  	<thead><tr>
		<th class="prTLeft">{t}Action{/t}</th>
		<th class="prTLeft">{t}Message{/t}</th>
		<th class="prTLeft">{t}Change date/time{/t}</th>
		</tr></thead>
		<tbody>
			<tr><td colspan="3"></td></tr>
			{assign var=cvet value=""} {foreach item=log from=$logList}
			<tr>
				{if $cvet=="znBG1"}
					{assign var=cvet value=""}
				{else}
					{assign var=cvet value="znBG1"}
				{/if}
				<td class="{$cvet} znTLeft">
					{$log.action}
				</td>
				<td class="{$cvet} znTLeft">
					{$log.message} 
					
				</td>
				<td class="{$cvet} znTLeft">
					{$log.change_time|date_locale:'DATETIME_SHORT'}
				</td>
			</tr>
		{/foreach}
		</tbody>
</table>
<div class="prTLeft prIndentTop">
{$paging}
</div>
<!-- result end -->