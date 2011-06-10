<a id="GroupSettingsDeleteGroupAnchor"></a>
<h4 class="prInnerTop">{t}Send a message to the group family giving a brief explanation about why the family is being disbanded:{/t}</h4>
<p class="prInnerTop">
	<input type="text" id="resign_send_message_subject" name="resign_send_message_subject" value="Group family name group family will be disbanded" /><br />
	<textarea id="resign_send_message_body" name="resign_send_message_body" class="prIndentTop prIndentRight"></textarea>
</p>
<div class="prInnerTop">
	<div class="prMarkRequired prText2">{t}WARNING!{/t}</div>
	{t}{tparam value=$currentGroup->getName()}Clicking &quot;Delete group family&quot; below will permanently remove all data, and data relationships in the '%s'
	group family.{/t}
</div>
<div class="prTRight prInnerTop">  
	{t var="in_button"}Go Back{/t}{linkbutton name=$in_button link="#" onclick="xajax_privileges_deletegroup_hide(); return false;"} &#160;
	{t var="in_button_2"}Continue{/t}{linkbutton name=$in_button_2 link="#" onclick="xajax_privileges_deletegroupstep3(); return false;"}
</div>