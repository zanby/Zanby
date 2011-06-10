{literal}
	<script language="javascript">

	function GroupSettingsPrivilegies_over() {
		document.getElementById("GroupSettingsPrivilegiesTitle").style.textDecoration = "underline";
		document.getElementById("GroupSettingsPrivilegiesImage").src = "{/literal}{$AppTheme->images}{literal}/decorators/gsett_arrow.gif";

	}
	function GroupSettingsPrivilegies_out() {
		document.getElementById("GroupSettingsPrivilegiesTitle").style.textDecoration = "none";
		document.getElementById("GroupSettingsPrivilegiesImage").src = "{/literal}{$AppTheme->images}{literal}/decorators/gsett_arrow_off.gif";
	}
	function  ShowHideModerators(radio, moderator_value, section) {
		if ( radio.value == moderator_value ) {
			document.getElementById(section).style.display = "";
		} else {
			document.getElementById(section).style.display = "none";
		}
	}
	function addNewModerator(gid, content_type, moderator, html_container) {
		if ( moderator == "" ) {
			alert("Enter name of new moderater.");
			return false;
		}
		xajax_privileges_add_new_moderator(gid, content_type, moderator, html_container);
	}
	function deleteModerator(gid, content_type, moderator_uid, html_container) {
		if ( moderator_uid ) {
			xajax_privileges_delete_moderator(gid, content_type, moderator_uid, html_container);
		}
	}
	function savePrilelegies(gid) {
		var Privilegies = new Array();
		// for order details see content types in privilegies
		Privilegies[0] = null;
		Privilegies[1] = "privilegies_lists_access";
		Privilegies[2] = "privilegies_calendar_access";
		Privilegies[3] = "privilegies_documents_access";
		Privilegies[4] = "privilegies_photos_access";
        Privilegies[5] = "privilegies_videos_access";
		Privilegies[6] = "privilegies_forum_post_access";
		Privilegies[7] = "privilegies_email_access";
		Privilegies[8] = "privilegies_polls_access";
		Privilegies[9] = "privilegies_members_access";
		Privilegies[10] = "privilegies_group_families_access";
		Privilegies[11] = "privilegies_layout_access";
		Privilegies[12] = "privilegies_forum_moderate_access";

		var Result = new Array();
		Result[0] = null;
		for ( j = 1; j < Privilegies.length; j++ ) {
			eval ( "Result[j] = 2;" );
			var coll = document.getElementsByName(Privilegies[j]);
			for ( i = 0; i < coll.length; i ++ ) {
				if ( coll[i].checked == true ) {
					eval ( "Result[j] = " + coll[i].value + ";" );
				}
			}
		}
		xajax_privileges_privileges_save(gid, Result);
		return false;
	}
	</script>
{/literal}
{if $visibility_details == "privileges"}
	<script>xajax_privileges_privileges_show('{$groupId}');</script>
{else}
		{if $visibility == true}
			{form from=$form id="gpForm" name="gpForm" onsubmit="xajax_privileges_privileges_save(xajax.getFormValues('gpForm')); return false;"}
				{assign var="maxValue" value="2"}
				<table cellspacing="0" cellpadding="0" class="prTableTools prIndentTop">
					<col width="20%" />
					<col width="16%" />
					<col width="16%" />
					<col width="16%" />
					<col width="30%" />
					<thead>
						<th>{t}Tool{/t}</th>
						<th class="prTCenter">{t}All Members{/t}</th>
						<th class="prTCenter2">{t}Host(s) Only{/t}</th>
						<th class="prTLeft" colspan="2">{t}Host(s) plus these Members{/t}</th>
					</thead>
					<tbody>
						<tr>
							<td>
								<label for="gpCalendar_access0">{t}Calendar{/t}</label>
								<p>{t}(create events){/t}</p>
							</td>
							<td class="prTCenter">
								{form_radio id="gpCalendar_access0" name="gpCalendar_access"  value="0" checked=$privileges->getCalendar() onclick="turn_offUserList(this);"}
							</td>
							<td class="prTCenter">
								{form_radio id="gpCalendar_access1" name="gpCalendar_access"  value="1" checked=$privileges->getCalendar() onclick="turn_offUserList(this);"}
							</td>
							<td class="prTCenter">
								{form_radio id="gpCalendar_access2" name="gpCalendar_access"  value="2" checked=$privileges->getCalendar() onclick="turn_onUserList(this);"}
							</td>
							<td id="gpCalendar_access_td">
								{include file="groups/users.tpl" tool = "gpCalendar" option = $privileges->getCalendar()}
								{form_hidden name="gpEmail_access"  value="0"}
							</td>
						</tr>
						<tr class="prZebraBg2">
							<td>
								<label for="gpCalendar_access0">{t}Photos{/t}</label>
								<p>{t}(Upload){/t}</p>
							</td>
							<td class="prTCenter">
								{form_radio id="gpPhotos_access0" name="gpPhotos_access"  value="0" checked=$privileges->getPhotos() onclick="turn_offUserList(this);"}
							</td>
							<td class="prTCenter">
								{form_radio id="gpPhotos_access1" name="gpPhotos_access"  value="1" checked=$privileges->getPhotos() onclick="turn_offUserList(this);"}
							</td>
							<td class="prTCenter">
								{form_radio id="gpPhotos_access2" name="gpPhotos_access"  value="2" checked=$privileges->getPhotos() onclick="turn_onUserList(this);"}
							</td>
							<td id="gpPhotos_access_td">
								{include file="groups/users.tpl" tool = "gpPhotos" option = $privileges->getPhotos()}
							</td>
						</tr>
						<tr>
                            <td>
                                <label for="gpVideos_access0">{t}Videos{/t}</label>
								<p>{t}(Upload){/t}</p>
                            <td class="prTCenter">
                                {form_radio id="gpVideos_access0" name="gpVideos_access"  value="0" checked=$privileges->getVideos() onclick="turn_offUserList(this);"}
                            </td>
                            <td class="prTCenter">
                                {form_radio id="gpVideos_access1" name="gpVideos_access"  value="1" checked=$privileges->getVideos() onclick="turn_offUserList(this);"}
                            </td>
                            <td class="prTCenter">
                                {form_radio id="gpVideos_access2" name="gpVideos_access"  value="2" checked=$privileges->getVideos() onclick="turn_onUserList(this);"}
                            </td>
                            <td id="gpVideos_access_td">
                                {include file="groups/users.tpl" tool = "gpVideos" option = $privileges->getVideos()}
                            </td>
                        </tr>
						<tr  class="prZebraBg2">
							<td>
								<label for="gpCalendar_access0">{t}Documents{/t}</label>
								<p>{t}(Upload){/t}</p>
							</td>
							<td class="prTCenter">
								{form_radio id="gpDocuments_access0" name="gpDocuments_access"  value="0" checked=$privileges->getDocuments() onclick="turn_offUserList(this);"}
							</td>
							<td class="prTCenter">
								{form_radio id="gpDocuments_access1" name="gpDocuments_access"  value="1" checked=$privileges->getDocuments() onclick="turn_offUserList(this);"}
							</td>
							<td class="prTCenter">
								{form_radio id="gpDocuments_access2" name="gpDocuments_access"  value="2" checked=$privileges->getDocuments() onclick="turn_onUserList(this);"}
							</td>
							<td id="gpDocuments_access_td">
								{include file="groups/users.tpl" tool = "gpDocuments" option = $privileges->getDocuments()}
							</td>
						</tr>
						<tr>
							<td>
								<label for="gpCalendar_access0">{t}Lists{/t}</label>
								<p>{t}(Create){/t}</p>
							</td>
							<td class="prTCenter">
								{form_radio id="gpLists_access0" name="gpLists_access"  value="0" checked=$privileges->getLists() onclick="turn_offUserList(this);"}
							</td>
							<td class="prTCenter">
								{form_radio id="gpLists_access1" name="gpLists_access"  value="1" checked=$privileges->getLists() onclick="turn_offUserList(this);"}
							</td>
							<td class="prTCenter">
								{form_radio id="gpLists_access2" name="gpLists_access"  value="2" checked=$privileges->getLists() onclick="turn_onUserList(this);"}
							</td>
							<td id="gpLists_access_td">
								{include file="groups/users.tpl" tool = "gpLists" option = $privileges->getLists()}
								{form_hidden name="gpPolls_access"  value="0"}
							</td>
						</tr>
						<tr  class="prZebraBg2">
							<td>
								<label for="gpCalendar_access0">{t}Manage Members{/t}</label>
								<p>{t}(invite/approve/decline){/t}</p>
							</td>
							<td class="prTCenter">
								{form_radio id="gpManageMembers_access0" name="gpManageMembers_access"  value="0" checked=$privileges->getManageMembers() onclick="turn_offUserList(this);"}
							</td>
							<td class="prTCenter">
								{form_radio id="gpManageMembers_access1" name="gpManageMembers_access"  value="1" checked=$privileges->getManageMembers() onclick="turn_offUserList(this);"}
							</td>
							<td class="prTCenter">
								{form_radio id="gpManageMembers_access2" name="gpManageMembers_access"  value="2" checked=$privileges->getManageMembers() onclick="turn_onUserList(this);"}
							</td>
							<td id="gpManageMembers_access_td">
								{include file="groups/users.tpl" tool = "gpManageMembers" option = $privileges->getManageMembers()}
							</td>
						</tr>
						<tr>
							<td>
								<label for="gpCalendar_access0">{t}Manage Group Families{/t}</label>
							</td>
							<td class="prTCenter">
								{form_radio id="gpManageGroupFamilies_access0" name="gpManageGroupFamilies_access"  value="0" checked=$privileges->getManageGroupFamilies() onclick="turn_offUserList(this);"}
							</td>
							<td class="prTCenter">
								{form_radio id="gpManageGroupFamilies_access1" name="gpManageGroupFamilies_access"  value="1" checked=$privileges->getManageGroupFamilies() onclick="turn_offUserList(this);"}
							</td>
							<td class="prTCenter">
								{form_radio id="gpManageGroupFamilies_access2" name="gpManageGroupFamilies_access"  value="2" checked=$privileges->getManageGroupFamilies() onclick="turn_onUserList(this);"}
							</td>
							<td id="gpManageGroupFamilies_access_td">
								{include file="groups/users.tpl" tool = "gpManageGroupFamilies" option = $privileges->getManageGroupFamilies()}
							</td>
						</tr>
						<tr class="prZebraBg2">
							<td>
								<label for="gpCalendar_access0">{t}Modify Layout and Theme{/t}</label>
							</td>
							<td class="prTCenter">
								{form_radio id="gpModifyLayout_access0" name="gpModifyLayout_access"  value="0" checked=$privileges->getModifyLayout() onclick="turn_offUserList(this);"}
							</td>
							<td class="prTCenter">
								{form_radio id="gpModifyLayout_access1" name="gpModifyLayout_access"  value="1" checked=$privileges->getModifyLayout() onclick="turn_offUserList(this);"}
							</td>
							<td class="prTCenter">
								{form_radio id="gpModifyLayout_access2" name="gpModifyLayout_access"  value="2" checked=$privileges->getModifyLayout() onclick="turn_onUserList(this);"}
							</td>
							<td id="gpModifyLayout_access_td">
								{include file="groups/users.tpl" tool = "gpModifyLayout" option = $privileges->getModifyLayout()}
							</td>
						</tr>
					</tbody>
				</table>
				<div class="prIndentTop">{form_checkbox id="gpSendEmail" name="gpSendEmail" checked=$privileges->getSendEmail()|default:"1" value="1"}<label for="gpSendEmail"> {t}Send me an email whenever new content is uploaded to the group area or the look and feel of the workspace changes.{/t}</label></div>
				<div class="prTRight prIndentTopSmall">{t var="in_submit"}Save Changes{/t}{form_submit name="form_save" value=$in_submit}</div>
			{/form}
		{/if}
{/if}