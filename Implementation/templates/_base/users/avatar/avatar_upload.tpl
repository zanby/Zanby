<script src="/js/upload_fields.js" type="text/javascript"></script>
<script type="text/javascript" src="/js/SWFUpload/swfupload.js"></script>
<script type="text/javascript" src="/js/SWFUpload/handlers.js"></script>
<script type="text/javascript" src="/js/SWFUpload/swfobject.js"></script> 
<script src="/js/SWFUpload/swfuploadcode.js" type="text/javascript"></script>
<script type="text/javascript" src="/js/SWFUpload/swfupload.graceful_degradation.js"></script>       
{literal}
<script>    
    function setSWFUploadParams()
    {
        setUploadURL("{/literal}{$user->getUserPath('avatarupload/swf/1')}{literal}");
        setPostParams({{/literal}"SWFUploadID" : '{$SWFUploadID}', "user" : '{$user->getId()}'{literal}});
        setFileTypes("{/literal}{$IMAGES_EXT}{literal}", "{/literal}Images Files ({$IMAGES_EXT}){literal}");
        setFileSizeLimit("{/literal}{$AVATARS_SIZE_LIMIT/1024}{literal}");
        setQueuedLimit({/literal}{$avatarsLeft}{literal});
    }
        
    YAHOO.util.Event.onDOMReady(turnOnSWFUpload);
</script>
{/literal}


{assign var="login" value=$currentUser->getLogin()}

{if $currentUser->getId() == $user->getId()}
	{t var='title'}My Photos{/t}
{else}
	{assign var="title" value=$currentUser->getLogin()|escape:"html"|cat:"'s photos"}
{/if}

<!---BUUU--->
	
<!---BUUU--->
	
{form from=$form name="auForm" id="auForm" enctype="multipart/form-data"}
<!--<form id="form1" action="{$user->getUserPath('avatarupload')}" method="post" enctype="multipart/form-data">-->
{form_hidden id="upload_type" name="upload_type" value="upload"} 
{form_hidden id="do" name="do" value="upload"}
<!--<input type="hidden" name="do" value="upload">-->
<div class="prInner prClr3">

    <div>
		<h2>{t}Upload images.{/t}</h2>
        <p class="prInnerSmallTop">{t}Press "Browse" to locate the picture you would like to display with your group.{/t}</p>
	</div>
	<div class="prInnerSmallTop">		
		<div id="swferror" class="prFormErrors" style="display:none;">
		</div>
		{form_errors_summary}
	</div>
	<div id="fields_table" _style="display:none;" class="prInnerSmallTop">
        <table class="prForm"> 
			<col width="10%" />
			<col width="90%" />
				
			{if $avatarsLeft >=5}
				{assign var=loopvalue value=5}
			{else}
				{assign var=loopvalue value=$avatarsLeft}
			{/if}
    
			{section name=files loop=$loopvalue}
			<tr>
				<td></td>
				<td>
				{form_file name="img_"|cat:$smarty.section.files.index_next size="45"}
				</td>
			</tr>
			{/section}

			{section name=files loop=20 start=$loopvalue}
			<tr title="file_field" style="display:none;">
				<td></td>
				<td>
					{form_file name="img_"|cat:$smarty.section.files.index_next size="45"}	
				</td>
			</tr>
			{/section}
			<tr id="more_avatars_link" {if !($avatarsLeft-$loopvalue)} style="display:none;"{/if}>
				<td></td>
				<td>
					<a href="#" onclick="show_more_advanced({$avatarsLeft}); return false;">+ {t}add another picture{/t}</a>
				</td>
			</tr>
		</table>
	</div>
	{include file="users/avatar/swfupload.tpl"}
	<div class="prInnerSmallTop ">
        <table class="prForm">
			<col width="10%" />
			<col width="90%" />
            <tr>
                <td></td> 
                <td>
				<div class="prButtonPanel"> 
					<div class="prFloatLeft">
						{t var='button_01'}Upload Profile Photos{/t}
						{linkbutton name=$button_01 onclick="uploadandsubmit(function() "|cat:$smarty.ldelim|cat:"document.auForm.submit();"|cat:$smarty.rdelim|cat:"); return false;"}
					</div>
					<div class="prFloatLeft prIndentLeftSmall">{t}or{/t}&nbsp;<a id="btnCancel1" href="#" onclick="cancelupload(function() {$smarty.ldelim}location.href='{$user->getUserPath('avatars')}';{$smarty.rdelim}); return false;">{t}Cancel{/t}</a> {t}and go back to profile photos list.{/t}
					</div>
				</div> 
				</td>
			</tr>
		</table>
	</div>
</div>
{/form}
<!--</form>-->
         


