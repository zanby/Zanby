{literal}
<script type="text/javascript">
	var targetToInvite = '';
	{/literal}{assign_adv var="facebook_oninvitefriendsready_action" value="array('controller' => 'facebook', 'action' => 'invitefriendsready')"}{literal}
	$('#btnInviteFriends').unbind('click').bind('click', function(){
		var callback = {success: function(oResponse){
			xajax.processResponse(oResponse.responseXML);
			FB.Bootstrap.requireFeatures(["Connect"], function() { 
				FB.init("{/literal}{$FACEBOOK_API_KEY}{literal}", "/xd_receiver.htm");  
				/*
				FB.Connect.streamPublish('', {}, {}, targetToInvite, 'What\'s on your mind?', function() {					
					//$.post('{/literal}{*$Warecorp->getCrossDomainUrl($facebook_onpost_action)*}{literal}', {}, function(data) { 
					//	xajax.processResponse(data); 
					//}, 'xml');											
				});
				*/
				FB.Connect.showFeedDialog( 133446657484, '', targetToInvite, '', null, FB.RequireConnect.require, function() {
					$.post('{/literal}{$facebook_oninvitefriendsready_action}{literal}', {}, function(data) { 
						xajax.processResponse(data); 
					}, 'xml');						
				});				
			});

		}}
		var oForm = YAHOO.util.Dom.get('formInviteFriends');
		YAHOO.util.Connect.setForm(oForm);
		var cObj = YAHOO.util.Connect.asyncRequest('POST', $('#formInviteFriends').attr('action'), callback);
		return false;
	})   
</script>
{/literal}
<div>
	{form from=$form id="formInviteFriends"}
		<div>
		{foreach from=$friends item=f}
			<div class="prInnerTop"><input type="checkbox" name="targetToInvite[]" value="{$f.uid}"> {$f.first_name} {$f.last_name} <br/></div>
		{/foreach}
		</div>
		<div class="prInnerTop">
			{t var='button'}Invite Friends{/t}
			{form_submit name="confirm" id="btnInviteFriends" value=$button} {t}or{/t} <a href="javascript:void(0);" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a>
		</div>
	{/form}
</div>
