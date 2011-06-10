<font class="black_title">{t}You have chosen to join the following group:{/t}</font>
<table width="100%" cellpadding="5" cellspacing="0" border="0">
	<tr>
		<td width="20%" class="prVTop">
			<table width="100%" cellpadding="3" cellspacing="0" border="0">
				<tr>
					<td align="center">
						<a href="{$CurrentGroup->getHost()->getUserPath('profile')}"><img src="{$CurrentGroup->getHost()->getAvatar()->getSmall()}" border="0"></a>
					</td>
				</tr>
				<tr>
					<td align="center">
						<a href="{$CurrentGroup->getHost()->getUserPath('profile')}">{$CurrentGroup->getHost()->getLogin()|escape:"html"}</a>
					</td>
				</tr>
			</table>
		</td>
		<td class="prVTop">
			{form from=$form}
				<table width="100%" cellpadding="1" cellspacing="0" border="0">
					<tr>
						<td><font class="black_title"><a href="{$CurrentGroup->getGroupPath('summary')}"><b>{$CurrentGroup->getName()|escape:"html"}</b></a></font></td>
					</tr>
					<tr>
						<td>
							{t}Created on{/t} {$CurrentGroup->getCreateDate()|date_locale:'DATE_MEDIUM'} | {$CurrentGroup->getCity()->name|escape:"html"}, {$CurrentGroup->getState()->name|escape:"html"} | <a href="{$CurrentGroup->getGroupPath('members')}">{$CurrentGroup->getMembers()->setMembersStatus('approved')->getCount()} {t}Members{/t}</a>
						</td>
					</tr>
					<tr>
						<td>{*$CurrentGroup->getDescription()|escape:"html"|nl2br*}</td>
					</tr>
					{if $CurrentGroup->getMembers()->isMemberExistsAndPending($user->getId())}
					<td>
						<b>{t}Your request for group membership has been already sent to the group host and is under consideration.{/t}</b>
					</td>
					{elseif $CurrentGroup->getMembers()->isMemberExistsAndApproved($user->getId())}
					<td>
						<b>{t}You are already member.{/t}</b>
					</td>
					{else}
					{if $CurrentGroup->getJoinMode() == 0}
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>
							<fieldset class="fieldset">
								<legend class="legend"><b>{t}Join Group{/t}</b></legend>
								<table cellpadding="1" cellspacing="0" width="100%" border="0">
									<tr>
										<td>{t}This group is open to anyone. To join, simply click the button below.{/t}</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td align="right">
										{t var="in_submit"}Join Group{/t}
										{form_submit name="Join" value=$in_submit}
										</td>
									</tr>
								</table>

							</fieldset>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>
							<fieldset class="fieldset">
								<legend class="legend"><b>{t}Write a note{/t}</b></legend>
								<table cellpadding="1" cellspacing="0" width="100%" border="0">
									<tr>
										<td>{t}You may write a note to the host if you wish:{/t}</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td class="form_value">{t}Subject{/t}</td>
									</tr>
									<tr>
										<td>{form_input type="text" name="subject" value=$subject|escape:"html"}</td>
									</tr>
									<tr>
										<td class="form_value">{t}Text{/t}</td>
									</tr>
									<tr>
										<td>{form_textarea name="text" value=$text|escape:"html" rows="10"}</td>
									</tr>
									<tr>
										<td align="right">
										{t var="in_submit_2"}Send Email and Join Group{/t}
										{form_submit value=$in_submit_2 name="SendAndJoin"}
										</td>
									</tr>
								</table>

							</fieldset>
						</td>
					</tr>
					{elseif $CurrentGroup->getJoinMode() == 1}
					<tr>
						<td>
							<fieldset class="fieldset">
								<legend class="legend"><b>{t}Join Group{/t}</b></legend>
								<table cellpadding="1" cellspacing="0" width="100%" border="0">
									<tr>
										<td>{t}Membership to this group is controlled by the group administrators.{/t}</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td>{t}Use the form below to contact the group's administrator and ask for permission to join.{/t}</td>
									</tr>
									<tr>
										<td class="form_value">{t}Subject{/t}</td>
									</tr>
									<tr>
										<td>{form_input type="text" name="subject" value=$subject|escape:"html"|default:"Membership Request"}</td>
									</tr>
									<tr>
										<td class="form_value">{t}Text{/t}</td>
									</tr>
									<tr>
										<td>{form_textarea name="text" value=$text|escape:"html" rows="10"}</td>
									</tr>
									<tr>
										<td align="right">
										{t var="in_submit_3"}Submit Membership Request{/t}
										{form_submit value=$in_submit_3 name="SendAndJoin"}
										</td>
									</tr>
								</table>
							</fieldset>
						</td>
					</tr>
					{elseif $CurrentGroup->getJoinMode() == 2}
					<tr>
						<td>
							<fieldset class="fieldset">
								<legend class="legend"><b>{t}Join Group{/t}</b></legend>
								<table cellpadding="1" cellspacing="0" width="100%" border="0">
									<tr>
										<td>{t}Membership to this group is controlled by the group administrators.{/t}</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td>{t}You must submit a membership code in order to join this group.{/t}</td>
									</tr>
									<tr>
										<td class="form_value">{t}Insert Membership Code:{/t}</td>
									</tr>
									<tr>
										<td>{form_input type="text" name="join_code" value=$join_code|escape:"html"}</td>
									</tr>
									<tr>
										<td align="right">
										{t var="in_submit_4"}Join this group{/t}
										{form_submit value=$in_submit_4 name="CodeAndJoin"}
										</td>
									</tr>
								</table>
							</fieldset>
						</td>
					</tr>
					{/if}
					{/if}
					<tr>
						<td></td>
					</tr>
					<tr>
						<td></td>
					</tr>
				</table>
			{/form}
		</td>
	</tr>
</table>