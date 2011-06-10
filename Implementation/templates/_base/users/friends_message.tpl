<div class="tab2_frame_silver">
	<table border="0" width="100%">
		<tr>
			<td></td>
			<td><table width="100%">
					<tr>
						<td> {if $mode == "tjm"} <a href="{$currentUser->getUserPath('friends/mode/tjm')}">{t}Received Friend Requests{/t}</a> {elseif $mode == "ijt"} <a href="{$currentUser->getUserPath('friends/mode/ijt')}">{t}Sent Friend Requests{/t}</a> {/if} </td>
						<td align="right"> {if $prev} <a href="{$currentUser->getUserPath('friend/mode')}{$mode}/cmd/viewmessage/user/{$prev}/">{t}Previous{/t}</a> {/if} | 
							{if $next} <a href="{$currentUser->getUserPath('friend/mode')}{$mode}/cmd/viewmessage/user/{$next}/">{t}Next{/t}</a> {/if} </td>
					</tr>
				</table></td>
		</tr>
		<tr>
			<td width="100px" valign="top"><table border="1" bgcolor="#CCCCCC">
					<tr>
						<td><b>{t}Invitations{/t}</b></td>
					</tr>
					<tr>
						<td> {if $mode == "ijt"} <a href="{$currentUser->getUserPath('friends')}mode/tjm/">{t}Received{/t}</a> {else} <i>{t}Received{/t}</i> {/if} </td>
					</tr>
					<tr>
						<td> {if $mode == "tjm"} <a href="{$currentUser->getUserPath('friends')}mode/ijt/">{t}Sent{/t}</a> {else} <i>{t}Sent{/t}</i> {/if} </td>
					</tr>
				</table></td>
			<td><table width="100%" border="1">
					<tr>
						<td><a href="{$user->getUserPath('profile')}"> <img src="{$user->getAvatar()->getSmall()}" border="0"></a> </td>
						<td><div class="list"><a href="{$user->getUserPath('profile')}"><b>{$user->getLogin()}</b></a></div>
							<div class="list">{if !$user->getIsBirthdayPrivate()}{$user->getAge()}{t} Yr old{/t}{/if}{if $user->getGender() eq 'male'} , {t} Male{/t}{elseif $user->getGender() eq 'female'} , {t} Female{/t}{/if}</div>
							<div class="list">{$user->getCity()->name|escape:"html"}, {$user->getState()->name|escape:"html"}, {$user->getCountry()->name|escape:"html"}</div></td>
						<td>{t}Requested to be your friend on{/t}<br>
							{$addDate}<br>
						</td>
					</tr>
					<tr>
						<td colspan="3"><b>{t}{tparam value=$user->getLogin()}%s's Note:{/t}</b><br>
							<br>
							{$message|escape:'html'|wordwrap:30:'<br>
							'} <br>
						</td>
					</tr>
					<tr>
						<td colspan="3"> {if $mode == "ijt"}
							<input type="button" value="Delete" onclick="document.location='{$currentUser->getUserPath('friend')}mode/ijt/cmd/del/user/{$user->getId()}';">
							{elseif $mode == "tjm"}
							<input type="button" value="Accept" onclick="document.location='{$currentUser->getUserPath('friend')}mode/tjm/cmd/add/user/{$user->getId()}/';">
							<input type="button" value="Decline" onclick="document.location='{$currentUser->getUserPath('friend')}mode/tjm/cmd/delrequest/user/{$user->getId()}/';">
							{/if} </td>
					</tr>
				</table></td>
		</tr>
	</table>
</div>
