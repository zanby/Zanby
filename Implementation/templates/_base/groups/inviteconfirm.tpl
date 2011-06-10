<p class="prText2 prTCenter">{t}{tparam value=$invitedGroup->getName()|escape:"html"}{tparam value=$group->getName()|escape:"html"}Do you agree to join your group %s to %s group?{/t}</p>
<div class="prInnerTop prTCenter">	
		{t var="in_button"}Yes{/t}
		{linkbutton name=$in_button link=$group->getGroupPath('inviteconfirm')|cat:"id/"|cat:$invitedGroup->getId()|cat:"/yes/1/"}
		<span class="prIndentLeftSmall">{t var="in_button_2"}No{/t}{linkbutton name=$in_button_2 link=$group->getGroupPath('inviteconfirm')|cat:"id/"|cat:$invitedGroup->getId()|cat:"/no/1/"}</span>	
</div>
