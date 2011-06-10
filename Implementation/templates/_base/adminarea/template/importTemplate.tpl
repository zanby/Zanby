{form from=$form id="iuForm" name="iuForm" enctype="multipart/form-data"}
<div class="prDropBoxInner"> 
<h3 class="prTLeft">Import {$importOnly|escape}</h3>
		
    {if $importStart} 
    	{if !empty($messages)}
    	    <div> 
        	{foreach item=message from=$messages}
            	{$message}<br/>
            {/foreach}
            </div>
    	{/if}
        {if !empty($errors) || $mailsrvDown}
        <div class="prFormErrors">
    		<h1>Errors:</h1>
    		{foreach item=error key=ekey from=$errors}
            	<div class="prIndentBottom">{$error}</div>
            {/foreach}
            {if $mailsrvDown}
            	<div class="prIndentBottom">Mailserver error on updating. Imported data may be updated partialy.</div>
            {/if}
        </div>
        {elseif $importedCount == 0}
            <div class="prFormErrors">
            	<h1>Incorrect import file.</h1>
            	<div class="prIndentBottom">{$importOnly} message is not found.</div>
        	</div>
        {/if}
        <br/>
    {/if}
	
    {form_errors_summary}
	<table width="100%" cellpadding="0" cellspacing="0" border="0" class="prForm">
		<col width="35%" />
		<col width="30%" />
		<col width="35%" />
		<tr>
			<td class="prTRight"><label for="import_file">{t}Upload import file:{/t}</label></td>
			<td class="prTLeft"><input id="import_file" name="import_file" type="file"/>
			</td>
			<td>&nbsp;</td>
		</tr>
	</table>
	<div class="prTCenter prIndentTop">{t var="in_submit"}Upload{/t}{form_submit name="form_upload" value=$in_submit}</div>
</div>
{/form}
