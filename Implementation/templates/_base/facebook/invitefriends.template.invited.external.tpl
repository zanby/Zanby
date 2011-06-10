{if $formParams.event_invitations_fbfriends_tojson}
{literal}
	<script type="text/javascript">
		$(function(){ FBApplication.targetsToInvite = {/literal}{$formParams.event_invitations_fbfriends_tojson}{literal}; })
        function doRemoveFBUser( obj ) {
            var toRemove, toCheck, id;
            toRemove = $(obj).parent();
            toCheck = $(toRemove).parent();
            toRemove.remove();
            id = toRemove.attr('id');
            id = id.slice(id.search(/_\d+/)+1);
            FBApplication.targetsToInvite = $.grep(FBApplication.targetsToInvite, function (el, idx) { return el !== id; });
            if ( toCheck.children().size() == 0 ) {
                $('#EventInviteFBFriendsObjects').empty();
            }
        }
	</script>
{/literal}
{/if}
<td class="prTRight"><label>Facebook Members:</label></td>
<td colspan="2" class="prNoInner">
<div class="prInnerSmallTop prClr3">
	{foreach from=$formParams.event_invitations_fbfriends item='fbuser' name='fbforeach'}
	<div class="prFloatLeft" id="fb_invited_contact_{$fbuser.uid}">
		<input type="hidden" name="event_invitations_fbfriends[]" value="{$fbuser.uid}" class="events-object-fbfriend-hidden" />
		<a href="http://www.facebook.com/profile.php?id={$fbuser.uid}" target="_blank">{$fbuser.first_name|escape} {$fbuser.last_name|escape}</a> 
		<a href="javascript:void(0);" onclick="doRemoveFBUser(this)"><img alt="" src="{$AppTheme->images}/buttons/bgCO-close.gif" /></a>
		{if !$smarty.foreach.fbforeach.last}&nbsp;{/if}
	</div>
	{/foreach}
</div>
</td>
<td></td>

