<script src="/js/upload_fields.js" type="text/javascript"></script>
<script type="text/javascript" src="/js/SWFUpload/swfupload.js"></script>
<script type="text/javascript" src="/js/SWFUpload/handlers.js"></script>
<script src="/js/SWFUpload/swfuploadcode.js" type="text/javascript"></script>
<script type="text/javascript" src="/js/SWFUpload/swfupload.graceful_degradation.js"></script>    
{literal}
<script>    
    function setSWFUploadParams()
		{
			setUploadURL("{/literal}{$group->getGroupPath('avatarupload/swf/1')}{literal}");
			setPostParams({{/literal}"SWFUploadID" : '{$SWFUploadID}', "group" : '{$group->getId()}'{literal}});
			setFileTypes("{/literal}{$IMAGES_EXT}{literal}", "{/literal}Images Files ({$IMAGES_EXT}){literal}");
			setFileSizeLimit("{/literal}{$AVATARS_SIZE_LIMIT/1024}{literal}");
			setQueuedLimit({/literal}{$avatarsLeft}{literal}); 
		}
    YAHOO.util.Event.onDOMReady(turnOnSWFUpload);
</script>
{/literal}
	{form from=$form name="auForm" id="auForm" enctype="multipart/form-data"}
	{form_hidden id="upload_type" name="upload_type" value="upload"} 
	{form_hidden name="do" value="upload"}
        <h3>{t}Upload images.{/t}</h3>
        <p>{t}Press "Browse" to locate the picture you would like to display with your group.{/t}</p>
		<div id="swferror" class="prFormErrors" style="display:none;"></div>
			{form_errors_summary}
    <div id="fields_table" _style="display:none;">
		<table class="prForm"> 
			<col width="20%" />
			<col width="50%" />
			<col width="30%" />
			{if $avatarsLeft >=5}
				{assign var=loopvalue value=5}
			{else}
				{assign var=loopvalue value=$avatarsLeft}
			{/if}
			{section name=files loop=$loopvalue}
			<tr>
				<td></td>
				<td>{form_file name="img_"|cat:$smarty.section.files.index_next size="45"}</td>
				<td></td>
			</tr>
			{/section}
			{section name=files loop=20 start=$loopvalue}
			<tr title="file_field" style="display:none;">
				<td></td>
				<td>{form_file name="img_"|cat:$smarty.section.files.index_next size="45"}	</td>
				<td></td>
			</tr>
			{/section}
			<tr id="more_avatars_link" {if !($avatarsLeft-$loopvalue)} style="display:none;"{/if}>
				<td></td>
				<td><a href="#" onclick="show_more_advanced({$avatarsLeft}); return false;">{t}+ add another picture{/t}</a></td>
				<td></td>
			</tr>
		</table>
	</div>
	{include file="groups/avatar/swfupload.tpl"}
	<table class="prForm">
		<col width="10%" />
		<col width="90%" />
		<tr>
			<td></td> 
			<td>
				<div class="prFloatLeft">{t var="in_button"}Upload Profile Photos{/t}{linkbutton name=$in_button onclick="uploadandsubmit(function()"|cat:$smarty.ldelim|cat:"document.auForm.submit();"|cat:$smarty.rdelim|cat:"); return false;"}</div>
				<div class="prFloatLeft prIndentLeft">{t}or{/t}&nbsp;<a id="btnCancel1" href="#" onclick="cancelupload(function() {$smarty.ldelim}location.href='{$group->getGroupPath('avatars')}';{$smarty.rdelim}); return false;">{t}Cancel{/t}</a> {t}and go back to profile photos list.{/t}</div>
			</td>
		</tr>
	</table>
{/form}