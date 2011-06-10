{*popup_item*}
{$paging}
<table cellpadding="0" cellspacing="0" width="100%" class="prResult prIndentBottom">
	<col width="15%" />
	<col width="20%" />
	<col width="55%" />
	<col width="10%" />
	<tr>
    	<th>Revisions</th>
    	<th>Last Modified</th>
    	<th>Comments</th>
    	<th>&nbsp;</th>
    </tr>
    {foreach from=$listRevisions item=revision name='revisions'}
	<tr>
    	<td>{t}Revisions{/t} {$revision->getRevisionNumber()}</td>
    	<td>{$revision->getRevisionDate()|user_date_format:$user->getTimezone()|date_locale:'DATETIME_SHORT'}</td>
    	<td>{$revision->getRevisionDescription()|escape}</td>
    	<td>
            {if $currentRevision != $revision->getRevisionId()}
            <a href="#" onclick="DocumentApplication.revertRevision({$revision->getRevisionId()}, {$page})">{t}Revert{/t}</a>
            {else}&nbsp;{/if}
        </td>

    </tr>
    {foreachelse}
	<tr>
    	<td class="prTCenter" colspan="4">{t}No revisions{/t}</td>
    </tr>
    {/foreach}
</table>
{$paging}
{t var="in_button"}Close{/t}
{linkbutton name=$in_button link="#" onclick="popup_window.close(); return false;"}
{*popup_item*}