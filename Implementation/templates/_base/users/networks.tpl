{if $FACEBOOK_USED}
	{TitlePane id='facebookSettings' showContent=1}
		{TitlePane_Title}{t}Manage Your Facebook Settings{/t}{/TitlePane_Title}
		{TitlePane_Note}{t}Associate with Facebook Account, Sharing permissions{/t}{/TitlePane_Note}
		{TitlePane_Toggle type='show'}{t}Show{/t}{/TitlePane_Toggle}
		{TitlePane_Toggle type='hide'}{t}Hide{/t}{/TitlePane_Toggle}
		{TitlePane_Content}
			{include file="users/networks.facebookSettings.tpl"}
		{/TitlePane_Content}
	{/TitlePane}
	
	{if 0}
		{if $fbDisplayMode == 'linkedWithFbSession'}
		{literal}
			<script type="text/javascript">//<![CDATA[ 
				{/literal}{assign_adv var="url_onpublish_stream" value="array('controller' => 'facebook', 'action' => 'publishstream')"}{literal}
				FBCfg.url_onpublish_stream = '{/literal}{$Warecorp->getCrossDomainUrl($url_onpublish_stream)}{literal}';

				$(function(){
					$('#btnFbPublishFormSubmit').unbind().bind('click', function(){
						FBApplication.check_seesion_state(function() {
							$.post(FBCfg.url_onpublish_stream, {text: $("#fbPublishStreamText").val()}, function(data) { 
								xajax.processResponse(data); 
							}, 'xml');
						});
						return false;
					})
				})
			//]]></script>
		{/literal}
		{TitlePane id='facebookPublishStream' showContent=0}
			{TitlePane_Title}{t}Publish post to Facebook wall{/t}{/TitlePane_Title}
			{TitlePane_Note}{t}Publish post to Facebook wall{/t}{/TitlePane_Note}
			{TitlePane_Toggle type='show'}{t}Show{/t}{/TitlePane_Toggle}
			{TitlePane_Toggle type='hide'}{t}Hide{/t}{/TitlePane_Toggle}
			{TitlePane_Content}
				<form name="fbPublishForm" id="fbPublishForm">
					<div class="prIndentTop">
						<label>{t}Message to post{/t} : </label> <textarea class="prIndentTopSmall" rows="10" name="fbPublishStreamText" id="fbPublishStreamText"></textarea>
					</div>
					<div class="prIndentTop">
						{t var='strButtonName'}Publish{/t}
						{linkbutton id="btnFbPublishFormSubmit" name=$strButtonName}
					</div>
				</form>
			{/TitlePane_Content}
		{/TitlePane}
		{/if}
	{/if}
	
	{if 0}
		{TitlePane id='facebookInvite' showContent=0}
			{TitlePane_Title}{t}Invite Your Facebook Friends{/t}{/TitlePane_Title}
			{TitlePane_Note}{t}{/t}Invite Your Facebook Friends{/TitlePane_Note}
			{TitlePane_Toggle type='show'}{t}Show{/t}{/TitlePane_Toggle}
			{TitlePane_Toggle type='hide'}{t}Hide{/t}{/TitlePane_Toggle}
			{TitlePane_Content}
				<fb:serverfbml style="width: 776px;"> 
					{literal}
					<script type="text/fbml"> 
					<fb:fbml> 
						<fb:request-form 
							action="#null" 
							method="POST" 
							invite="false" 
							type="{/literal}{$SITE_NAME_AS_STRING}{literal}" 
							content="<fb:name uid='{/literal}{$facebookUser->getFacebookId()}{literal}' useyou='false' /> is a member of {/literal}{$SITE_NAME_AS_STRING}{literal} and would like to share that experience with you. To register, simply click on the 'Register' button below.<fb:req-choice url='http://socialtoo.com?facebook_login=1' label='Register' />"> 
							<fb:multi-friend-selector 
								showborder="false" 
								actiontext="Invite your Facebook Friends to use {/literal}{$SITE_NAME_AS_STRING}{literal}" 
							/> 
						</fb:request-form> 
					</fb:fbml> 
					</script> 
					{/literal}
					
					{*literal}
					<script type="text/fbml"> 
					<fb:fbml> 
						<fb:request-form 
							action="http://socialtoo.com/ignore/fb_friends_msg" 
							method="POST" 
							invite="true" 
							type="{/literal}{$SITE_NAME_AS_STRING}{literal}" 
							content="<fb:name uid='{/literal}{$facebookUser->getFacebookId()}{literal}' useyou='false' /> is a member of {/literal}{$SITE_NAME_AS_STRING}{literal} and would like to share that experience with you. To register, simply click on the 'Register' button below.<fb:req-choice url='http://socialtoo.com?facebook_login=1' label='Register' />"> 
							<fb:multi-friend-input width="350px" border_color="#8496ba" exclude_ids="4,5,10,15" />
							<fb:request-form-submit />
						</fb:request-form> 
					</fb:fbml> 
					</script> 
					{/literal*}


					{*literal}
					<script type="text/fbml"> 
					<fb:fbml> 
						<fb:request-form 
							action="http://socialtoo.com/ignore/fb_friends_msg" 
							method="POST" 
							invite="true" 
							type="{/literal}{$SITE_NAME_AS_STRING}{literal}" 
							content="<fb:name uid='{/literal}{$facebookUser->getFacebookId()}{literal}' useyou='false' /> is a member of {/literal}{$SITE_NAME_AS_STRING}{literal} and would like to share that experience with you. To register, simply click on the 'Register' button below.<fb:req-choice url='http://socialtoo.com?facebook_login=1' label='Register' />"> 
							<input type="hidden" value="100000200723009" name="ids[]" fb_protected="true" class="fb_token_hidden_input"/>
							<fb:request-form-submit />
						</fb:request-form> 
					</fb:fbml> 
					</script> 
					{/literal*}
				</fb:serverfbml>
			{/TitlePane_Content}
		{/TitlePane}
	{/if}
{/if}