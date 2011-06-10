{*popup_item*}         
{if !$errors}  
<p class="prText2 prTCenter">{t}{tparam value=$friend->getLogin()}Invite %s to be your friend.{/t}</p>
{if $alredySent}            
<p class="prMarkRequired prTCenter">{t}{tparam value=$friend->getLogin()}You already sent invitation to %s.<br /> Send another invite?{/t}</p> 
{/if}
	<table class="prForm">
	<tr>
		<td>
		<label for="message">{t}Add a comment (optional){/t}</label>
		<div class="prInnerSmallTop">
		<textarea name="message" id="message"></textarea>
		</div>
		<script type="text/javascript">
		<!--
			var a = {$empty};
		//-->
		</script>
		</td>
	</tr>
	<tr>
		<td class="prTCenter">
			{if $alredySent}
				<span class="prIndentLeftSmall">
				{t var='button_01'}OK{/t}
				{linkbutton onclick="xajax_addToFriendsDo("|cat:$friend->getId()|cat:", document.getElementById('message').value, true); return false;" name=$button_01}</span>
			{else}
				<span class="prIndentLeftSmall">
				{t var='button_02'}Send{/t}
				{linkbutton onclick="xajax_addToFriendsDo("|cat:$friend->getId()|cat:", document.getElementById('message').value); return false;" name=$button_02}</span>
			{/if}
			<span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
		</td>
	</tr>
	</table>
	   
{else} 
<div id='popup_item'>              
    {foreach from=$errors item=e}
        {$e}  
    {/foreach}
 <div class="prTCenter prInnerSmallTop">
 	{t var='button_03'}Ok{/t}  
    {linkbutton onclick="popup_window.close(); return false;" name=$button_03}   
 </div>   
 </div>       
{/if}
{*popup_item*}