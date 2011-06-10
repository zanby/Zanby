{if $FACEBOOK_USED}
	{literal}
		<script type="text/javascript">//<![CDATA[ 
			{/literal}{assign_adv var="url_onlink_ready" value="array('controller' => 'facebook', 'action' => 'processlink')"}{literal}
			FBCfg.url_onlink_ready = '{/literal}{$Warecorp->getCrossDomainUrl($url_onlink_ready)}{literal}';
			{/literal}{assign_adv var="url_onunlink" value="array('controller' => 'facebook', 'action' => 'processunlink')"}{literal}
			FBCfg.url_onunlink = '{/literal}{$Warecorp->getCrossDomainUrl($url_onunlink)}{literal}';
			{/literal}{assign_adv var="url_onremove_permission" value="array('controller' => 'facebook', 'action' => 'processremovepermission')"}{literal}
			FBCfg.url_onremove_permission = '{/literal}{$Warecorp->getCrossDomainUrl($url_onremove_permission)}{literal}';
			{/literal}{assign_adv var="url_checksessionstate" value="array('controller' => 'facebook', 'action' => 'checksessionstate')"}{literal}
			FBCfg.url_checksessionstate = '{/literal}{$Warecorp->getCrossDomainUrl($url_checksessionstate)}{literal}';
			$(function(){
				FBApplication.set_permission_handler('canPublishStream', 'publish_stream');
				FBApplication.set_permission_handler('canEmail', 'email');
			})

		//]]></script>
	{/literal}
	
	{if $fbDisplayMode == 'linkedWithFbSession'}
			<div class="prIndentTopLarge">
				{if $facebookUserInfo}
					<span class="prTBold">{$facebookUserInfo.first_name|escape} {$facebookUserInfo.last_name|escape}</span>
				{else}
				{/if}
				<a href="javascript:void(0);" onclick="FBApplication.onunlink(); return false;">{t}Unlink{/t}</a> | <a href="javascript:void(0);" onclick="FBApplication.onsign_out(); return false;">{t}Sign Out{/t}</a>
			</div>
			<div class="prIndentTopLarge">
				<input type="checkbox" name="canPublishStream" id="canPublishStream" value="1"{if $canPublishStream} checked{/if}>
                <label for="canPublishStream">{t}Share on Facebook through your Wall and friends' News Feeds without prompting{/t}</label>
				<div class="prText5 prIndentLeft prInnerLeft">{t}{tparam value=$SITE_NAME_AS_STRING}When checked, %s will post updates to your friends automatically{/t}</div>
			</div>
			<div class="prIndentTopLarge">
				<input type="checkbox" name="canEmail" id="canEmail" value="1"{if $canEmail} checked{/if}>
                <label for="canEmail">{t}{tparam value=$SITE_NAME_AS_STRING}Allow %s to email me{/t}</label>
				<div class="prText5 prIndentLeft prInnerLeft">{t}{tparam value=$SITE_NAME_AS_STRING}{tparam value=$SITE_NAME_AS_STRING}This permission allows %s to send email to its user. When checked, %s will send you updates to e-mail account which is specified in your facebook settings.{/t}</div>
			</div>
			{*popup_item*}
			<div id="unlinkPanel" style="display:none;" title="{t}Unlink Account{/t}">
				<div>
					<form name="unlinkForm" action="" method="post" id="unlinkForm">
					<table class="prForm">
						<tr>
							<td><p class="prTCenter prText2">{t}Are you sure you want to unlink account?{/t}</p></td>
						</tr>
						<tr>
							<td class="prTCenter">
								<span>
								{t var='strButtonName'}Unlink{/t}
								{linkbutton id="btnUnlinkFormSubmit" name=$strButtonName}
								</span> <span class="prIEVerticalAling">{t}or{/t} <a href="#" id="btnUnlinkFormCancel" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
							</td>
						</tr>
					</table>
					</form>
				</div>
			</div>
			{*popup_item*}
		{elseif $fbDisplayMode == 'linkedWithoutFbSession'}
			<div class="prIndentTopLarge prClr">
				{if $facebookUserInfo}
					<div class="prTBold prFloatLeft">{$facebookUserInfo.first_name|escape} {$facebookUserInfo.last_name|escape}</div>
				{else}
				{/if}
				<div class="prIndentLeftLarge prFloatLeft">
				<fb:login-button onlogin="FBApplication.onlink_ready();"  size="medium" length="long"></fb:login-button></div>
			</div>
		{elseif $fbDisplayMode == 'unlinkedWithFbSession'}
			<div class="prIndentTopLarge">
				<fb:login-button onlogin="FBApplication.onlink_ready();"  size="medium" length="long"></fb:login-button>
			</div>
		{elseif $fbDisplayMode == 'unlinkedWithoutFbSession'}
			<div class="prIndentTopLarge">
				<fb:login-button onlogin="FBApplication.onlink_ready();"  size="medium" length="long"></fb:login-button>
			</div>
	{/if}
{/if}
