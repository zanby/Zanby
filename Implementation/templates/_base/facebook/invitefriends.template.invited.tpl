{if $formParams.event_invitations_fbfriends_tojson}
{literal}
	<script type="text/javascript">//<![CDATA[ 
		$(function(){ FBApplication.targetsToInvite = {/literal}{$formParams.event_invitations_fbfriends_tojson}{literal}; })
	//]]></script>
{/literal}
{/if}
<td class="prTRight"><label>Facebook Members:</label></td>
<td colspan="2" class="prNoInner">
<div class="prInnerSmallTop prClr3">
	{foreach from=$formParams.event_invitations_fbfriends item='fbuser' name='fbforeach'}
	<div class="prFloatLeft" id="fb_invited_contact_{$fbuser.uid}">
		<input type="hidden" name="event_invitations_fbfriends[]" value="{$fbuser.uid}" class="events-object-fbfriend-hidden" />
		<a href="http://www.facebook.com/profile.php?id={$fbuser.uid}" target="_blank">{$fbuser.first_name|escape} {$fbuser.last_name|escape}</a> 
		<a href="javascript:void(0);" onclick="FBApplication.onremove_from_eventinvite({$fbuser.uid}, 1);"><img alt="" src="{$AppTheme->images}/buttons/bgCO-close.gif" /></a>
		{if !$smarty.foreach.fbforeach.last},&nbsp;{/if}
	</div>
	{/foreach}
</div>
</td>
<td></td>

