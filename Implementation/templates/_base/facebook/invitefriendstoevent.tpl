{*popup_item*}
{strip}
{literal}
	<script type="text/javascript">
		$(function(){
			$('#checkAllFbFriends').unbind().bind('click', function(){
				if ( $('#checkAllFbFriends').attr('checked') == true ) $(':checkbox[name="targetToInvite[]"]').attr('checked', true);
				else $(':checkbox[name="targetToInvite[]"]').attr('checked', false);
				$("#contactsCount").html($(':checkbox[name="targetToInvite[]"]:checked').size());
			});
            {/literal}{if $newEventInvitation}{literal}
			$('#btnInviteFriends').unbind('click').bind('click', function() {
                $('#confirmForm').unbind('submit').bind('submit', function() { return false; });
                var fbUids = [];
                fbUids.event_invitations_fbfriends = [];
                $('#confirmForm input:checkbox[name="targetToInvite[]"]:checked').each(function () {
                    fbUids.event_invitations_fbfriends.push(this.value);
                });
                {/literal}xajax_doEventInvite({$eventId}, {$eventUid}, null, fbUids);{literal}
			});
            {/literal}{else}{literal}
			$('#btnInviteFriends').unbind('click').bind('click', function(){ 
				FBApplication.oninvite_friends_toevent_handle('confirmForm');
				return false; 
			});
            {/literal}{/if}{literal}
			$(':checkbox[name="targetToInvite[]"]').unbind().bind('click', function() {
				$("#contactsCount").html($(':checkbox[name="targetToInvite[]"]:checked').size());
			});
		})
        
        Array.prototype.find = function(searchStr) {
          var returnArray = false;
          for (i=0; i<this.length; i++) {
              if (searchStr.test(this[i])) {
                if (!returnArray) { returnArray = [] }
                returnArray.push(i);
              }
          }
          return returnArray;
        }    
        
        var friendsArray = [];
        function friendsFilter(value){
            var result = [];
            var pattern = new RegExp("\^"+value,"i");
            result = friendsArray.find(pattern);
            for (i=1; i <= friendsArray.length; i++) { 
                object = document.getElementById('f_popup_'+i);    
                if (object) {
                    if(result != false && result.indexOf(i) != -1 ){
                        object.style.display = '';
                    }else{
                        object.style.display = 'none';
                    }
                }
            }
        }
	</script>
{/literal}

         <script>
        {foreach from=$friends item=f name='fbfriends'} 
             friendsArray[{$smarty.foreach.fbfriends.iteration}]= "{$f.first_name} {$f.last_name}";   
        {/foreach}
        </script>	
<div>                 
	{form from=$form id="confirmForm"}
		<input type="hidden" name="mode" value="{$mode|default:''}">
		<div class="prClr3 prInnerLeft prInnerSmallBottom"><label>Filter</label> <input type='input' value='' id='searchString' onkeyup='friendsFilter(this.value)'></div>
		<div class="prClr3">
			<div class="prFloatLeft prInnerLeft">
				<input type="checkbox" name="checkAllFbFriends" id="checkAllFbFriends" value="1" class="prNoBorder"> <label for="checkAllFbFriends">Select All</label> 
            </div>
			<div class="prFloatRight">Inviting <span id="contactsCount" class="prTBold">{$invitedFriendsCount}</span> contacts</div>
		</div>
		{if $mode && $mode == 'external'}
		<div style="min-height:200px;max-height:200px; height: 200px; overflow: hidden; overflow-y: auto;" class="prGrayBorder prIndentTop">
		{else}
		<div style="min-height:300px;max-height:300px; height: 300px; overflow: hidden; overflow-y: auto;" class="prGrayBorder prIndentTop">
		{/if}
			<table class="prResult prNoIndent" cellspacing="0" cellpadding="0">
				<col width="3%"/>
				<col width="10%"/>
				<col width="87%"/>
				{foreach from=$friends item=f name='fbfriends'}
				<tr class="{if ($smarty.foreach.fbfriends.iteration % 2) != 0}prEvenBg{else}prOddBg{/if}" id="f_popup_{$smarty.foreach.fbfriends.iteration}">
					<td class="prVTop">
						<input type="checkbox" name="targetToInvite[]" value="{$f.uid}" {if in_array($f.uid, $invitedFriends)} checked{/if} class="prNoBorder prVTop"> 
					</td>
					<td class="prTCenter prVMiddle">
						{if $f.pic_square_with_logo}<img class="prNoIndent" src="{$f.pic_square_with_logo}" width="25" height="25">{elseif $f.pic_square}<img class="prVMiddle" src="{$f.pic_square_with_logo}" width="25" height="25">{else}&#151; {/if}
					</td>
					<td class="prVMiddle">
						{$f.first_name} {$f.last_name}<br/>
					</td>
				</tr>
				{/foreach}
			</table>
		</div>

        
		{if $mode && $mode == 'external'}
		{*
		<table width="90%">
			<col width="20%"/>
			<tr>
				<td><label>{t}From{/t} :</label></td>
				<td><input type="text" name="from"></td>
			</tr>
			<tr>
				<td><label>{t}Subject{/t} :</label></td>
				<td><input type="text" name="subject"></td>
			</tr>
			<tr>
				<td><label>{t}Message{/t} :</label></td>
				<td><textarea name="message"></textarea></td>
			</tr>
		</table>
		<div class="prInnerTop prIndentTop">
		</div>
		*}
		{/if}
		<div class="prInnerTop prTCenter">
			{t var='button'}Invite Checked Friends{/t}
			{form_submit name="confirm" id="btnInviteFriends" value=$button} {t}or{/t} <a href="javascript:void(0);" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a>
		</div>
	{/form}
</div>
{/strip}
{*popup_item*}
