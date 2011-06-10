{literal}

{/literal}

{if $visibility_details == "privileges"}
	<script>xajax_privileges_privileges_show('{$groupId}');</script>
{else}
{if $visibility == true}
	{assign var="maxValue" value="3"}
	{form from=$form id="gpForm" name="gpForm" onsubmit="xajax_privileges_privileges_save(xajax.getFormValues('gpForm')); return false;"}
	<table class="prTableTools prIndentTop" cellpadding="0" cellspacing="0" border="0">
					<col width="20%" />
					<col width="13%" />
					<col width="17%" />
					<col width="15%" />
					<col width="5%" />
					<col width="30%" />
	  	<tr>
			<th>{t}Tool{/t}</th>
			<th class="prTCenter">{t}Owner(s)<br />Only{/t}</th>
			<th class="prTCenter">{t}Owner(s) plus<br />Group Hosts{/t}</th>
			<th class="prTCenter">{t}All<br />Members{/t}</th>
			<th class="prTLeft" colspan="2">{t}Owner(s) plus these Members{/t}</th>
		</tr>
		<tr>
			<td class="">
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
				{form_radio id="gpCalendar_access2" name="gpCalendar_access"  value="2" checked=$privileges->getCalendar() onclick="turn_offUserList(this);"}
			</td>
			<td nowrap="nowrap" class="prTCenter">
				{form_radio id="gpCalendar_access3" name="gpCalendar_access"  value="3" checked=$privileges->getCalendar() onclick="turn_onUserList(this);"}
			</td>
			<td id="gpCalendar_access_td" class="">
				{include file="groups/users.tpl" tool="gpCalendar" option=$privileges->getCalendar()}
			</td>
		</tr>
	{form_hidden name="gpEmail_access"  value="0"}

		<tr class="prZebraBg2">
			<td class="">
				<label for="gpPhotos_access0">{t}Photos{/t}</label>
				<p>{t}(Upload){/t}</p>
			</td>
			<td class="prTCenter">
				{form_radio id="gpPhotos_access0" name="gpPhotos_access"  value="0" checked=$privileges->getPhotos() onclick="turn_offUserList(this);"}
			</td>
			<td class="prTCenter">
				{form_radio id="gpPhotos_access1" name="gpPhotos_access"  value="1" checked=$privileges->getPhotos() onclick="turn_offUserList(this);"}
			</td>
			<td class="prTCenter">
				{form_radio id="gpPhotos_access2" name="gpPhotos_access"  value="2" checked=$privileges->getPhotos() onclick="turn_offUserList(this);"}
			</td>
			<td nowrap="nowrap" class="prTCenter">
				{form_radio id="gpPhotos_access3" name="gpPhotos_access"  value="3" checked=$privileges->getPhotos() onclick="turn_onUserList(this);"}
			</td>
			<td id="gpPhotos_access_td" class="">
				&#160;{include file="groups/users.tpl" tool="gpPhotos" option=$privileges->getPhotos()}
			</td>
		</tr>
        <tr>
			<td  class="">
                <label for="gpVideos_access0">{t}Videos{/t}</label>
			    <p>{t}(Upload){/t}</p>
			</td>
			<td class="prTCenter">
				{form_radio id="gpVideos_access0" name="gpVideos_access"  value="0" checked=$privileges->getVideos() onclick="turn_offUserList(this);"}
			</td>
			<td class=" prTCenter">
				{form_radio id="gpVideos_access1" name="gpVideos_access"  value="1" checked=$privileges->getVideos() onclick="turn_offUserList(this);"}
			</td>
			<td class="prTCenter">
				{form_radio id="gpVideos_access2" name="gpVideos_access"  value="2" checked=$privileges->getVideos() onclick="turn_offUserList(this);"}
			</td>
            <td nowrap="nowrap" class="prTCenter">
				{form_radio id="gpVideos_access3" name="gpVideos_access"  value="3" checked=$privileges->getVideos() onclick="turn_onUserList(this);"}
			</td>
			<td id="gpVideos_access_td" class="">
				&#160;{include file="groups/users.tpl" tool="gpVideos" option=$privileges->getVideos()}
			</td>
		</tr>
		<tr class="prZebraBg2">
			<td class="">
			<label for="gpDocuments_access0">{t}Documents{/t}</label>
			<p>{t}(Upload){/t}</p>
			</td>
			<td class="prTCenter">
				{form_radio id="gpDocuments_access0" name="gpDocuments_access"  value="0" checked=$privileges->getDocuments() onclick="turn_offUserList(this);"}
			</td>
			<td class="prTCenter">
				{form_radio id="gpDocuments_access1" name="gpDocuments_access"  value="1" checked=$privileges->getDocuments() onclick="turn_offUserList(this);"}
			</td>
			<td class="prTCenter">
				{form_radio id="gpDocuments_access2" name="gpDocuments_access"  value="2" checked=$privileges->getDocuments() onclick="turn_offUserList(this);"}
			</td>
			<td nowrap="nowrap" class="prTCenter">
				{form_radio id="gpDocuments_access3" name="gpDocuments_access"  value="3" checked=$privileges->getDocuments() onclick="turn_onUserList(this);"}
			</td>
			<td id="gpDocuments_access_td" class="">
				&#160;{include file="groups/users.tpl" tool="gpDocuments" option=$privileges->getDocuments()}
			</td>
		</tr>
		<tr>
			<td class="">
				<label for="gpLists_access0">{t}Lists{/t}</label>
				<p>{t}(Create){/t}</p>
			</td>
			<td class="prTCenter">
				{form_radio id="gpLists_access0" name="gpLists_access"  value="0" checked=$privileges->getLists() onclick="turn_offUserList(this);"}
			</td>
			<td class="prTCenter">
				{form_radio id="gpLists_access1" name="gpLists_access"  value="1" checked=$privileges->getLists() onclick="turn_offUserList(this);"}
			</td>
			<td class="prTCenter">
				{form_radio id="gpLists_access2" name="gpLists_access"  value="2" checked=$privileges->getLists() onclick="turn_offUserList(this);"}
			</td>
			<td nowrap="nowrap"  class="prTCenter">
				{form_radio id="gpLists_access3" name="gpLists_access"  value="3" checked=$privileges->getLists() onclick="turn_onUserList(this);"}
			</td>
			<td id="gpLists_access_td" class="">
				&#160;{include file="groups/users.tpl" tool="gpLists" option=$privileges->getLists()}
			</td>
		</tr>
		{form_hidden name="gpPolls_access"  value="0"}
		<tr class="prZebraBg2">
			<td class="">
				<label for="gpManageMembers_access0">{t}Manage Groups{/t}</label>
				<p>{t}(approve/decline){/t}</p>
			</td>
			<td class="prTCenter">
				{form_radio id="gpManageMembers_access0" name="gpManageMembers_access"  value="0" checked=$privileges->getManageMembers() onclick="turn_offUserList(this);"}
			</td>
			<td class="prTCenter">
				{form_radio id="gpManageMembers_access1" name="gpManageMembers_access"  value="1" checked=$privileges->getManageMembers() onclick="turn_offUserList(this);"}
			</td>
			<td class="prTCenter">
				{form_radio id="gpManageMembers_access2" name="gpManageMembers_access"  value="2" checked=$privileges->getManageMembers() onclick="turn_offUserList(this);"}
			</td>
			<td nowrap="nowrap" class="prTCenter">
				{form_radio id="gpManageMembers_access3" name="gpManageMembers_access"  value="3" checked=$privileges->getManageMembers() onclick="turn_onUserList(this);"}
			</td>
			<td id="gpManageMembers_access_td" class="">
				&#160;{include file="groups/users.tpl" tool="gpManageMembers" option=$privileges->getManageMembers()}
			</td>
		</tr>
        <tr>
            <td class=""><label for="gpModifyLayout_access0">{t}Modify Layout and Theme{/t}</label></td>
            <td class="prTCenter">
                {form_radio id="gpModifyLayout_access0" name="gpModifyLayout_access"  value="0" checked=$privileges->getModifyLayout() onclick="turn_offUserList(this);"}
            </td>
            <td class="prTCenter">
                {form_radio id="gpModifyLayout_access1" name="gpModifyLayout_access"  value="1" checked=$privileges->getModifyLayout() onclick="turn_offUserList(this);"}
            </td>
            <td class="prTCenter">
                {form_radio id="gpModifyLayout_access2" name="gpModifyLayout_access"  value="2" checked=$privileges->getModifyLayout() onclick="turn_offUserList(this);"}
            </td>
            <td nowrap="nowrap" class="prTCenter">
                {form_radio id="gpModifyLayout_access3" name="gpModifyLayout_access"  value="3" checked=$privileges->getModifyLayout() onclick="turn_onUserList(this);"}
            </td>
            <td id="gpModifyLayout_access_td" class="">
                &#160;{include file="groups/users.tpl" tool="gpModifyLayout" option=$privileges->getModifyLayout()}
            </td>
        </tr>
        <tr>
            <td class=""><label for="gpModifyLayout_access0">{t}Create a Group{/t}</label></td>
            <td class="prTCenter">
                {form_radio id="gpCreateGroup_access0" name="gpCreateGroup_access"  value="0" checked=$privileges->getGroupsCreation() onclick="turn_offUserList(this);"}
            </td>
            <td class="prTCenter">
                {form_radio id="gpCreateGroup_access1" name="gpCreateGroup_access"  value="1" checked=$privileges->getGroupsCreation() onclick="turn_offUserList(this);"}
            </td>
            <td class="prTCenter">
                {form_radio id="gpCreateGroup_access2" name="gpCreateGroup_access"  value="2" checked=$privileges->getGroupsCreation() onclick="turn_offUserList(this);"}
            </td>
            <td nowrap="nowrap" class="prTCenter">
                {form_radio id="gpCreateGroup_access3" name="gpCreateGroup_access"  value="3" checked=$privileges->getGroupsCreation() onclick="turn_onUserList(this);"}
            </td>
            <td id="gpCreateGroup_access_td" class="">
                &#160;{include file="groups/users.tpl" tool="gpCreateGroup" option=$privileges->getModifyLayout()}
            </td>
        </tr>
        <tr>
            <td class=""><label for="gpModifyLayout_access0">{t}Share content to all {$group->getName()|escape}'s groups{/t}</label></td>
            <td class="prTCenter">
                {form_radio id="gpShareToFamily_access0" name="gpShareToFamily_access"  value="0" checked=$privileges->getShareToFamily() onclick="turn_offUserList(this);"}
            </td>
            <td class="prTCenter">
                {form_radio id="gpShareToFamily_access1" name="gpShareToFamily_access"  value="1" checked=$privileges->getShareToFamily() onclick="turn_offUserList(this);"}
            </td>
            <td class="prTCenter">
                {form_radio id="gpShareToFamily_access2" name="gpShareToFamily_access"  value="2" checked=$privileges->getShareToFamily() onclick="turn_offUserList(this);"}
            </td>
            <td nowrap="nowrap" class="prTCenter">
                {form_radio id="gpShareToFamily_access3" name="gpShareToFamily_access"  value="3" checked=$privileges->getShareToFamily() onclick="turn_onUserList(this);"}
            </td>
            <td id="gpShareToFamily_access_td" class="">
                &#160;{include file="groups/users.tpl" tool="gpShareToFamily" option=$privileges->getShareToFamily()}
            </td>
        </tr>
	</table>

	 <div class="prIndentTop">
	 	{form_checkbox id="gpSendEmail" name="gpSendEmail" checked=$privileges->getSendEmail()|default:"1" value="1"}
		<label for="gpSendEmail">{t}Send me an email whenever new content is uploaded to the group area or the look and feel of the workspace changes.{/t}</label>
	</div>

		 <div class="prTRight prIndentTop">{t var="in_submit"}Save Changes{/t}{form_submit name="form_save" value=$in_submit}</div>

	{/form}
{/if}
{/if}