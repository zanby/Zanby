<script type="text/javascript" src="{$BASE_URL}/js/simple_checkboxes.js"></script>

<div class="prFloatLeft prInnerRight"> <a href="{$CurrentGroup->getGroupPath('summary')}"><img src="{$CurrentGroup->getAvatar()->setWidth(37)->setHeight(37)->setBorder(1)->getImage()}" alt="" /></a> </div>
<h2>{t}{tparam value=$CurrentGroup->getName()|escape:html}The %s Group Family{/t}&#8482;</h2>
<div class="prHeaderHelper">{if $CurrentGroup->getJoinMode() == 0}{t}Membership is open to any group.{/t}{else}{t}Membership is controlled by its administrators.{/t}{/if}</div>
{form from=$form name="join_form" id="join_form"}
	{form_errors_summary}
	<table cellspacing="0" cellpadding="0" class="prResult prIndentBottom">
		<col width="2%"/>
		<col width="40%"/>
		<col />
		<thead>
			<tr>
				<th><div>{form_checkbox name="select_all" onclick="check_all_checkboxes(document.getElementById('join_form'), this);" value=1 checked=0}</div></th>
				<th><div>{t}Group Name{/t}</div></th>
				<th><div>{t}Members{/t}</div></th>
	        </tr>
		</thead>
		<tbody>
	
		{foreach from=$groups item=g}
		<tr id="groupRow{$g->getId()}">
			<td><input type="checkbox" id="groupId{$g->getId()}" name="groupId[]" value="{$g->getId()}" {if in_array($g->getId(), $groupsId)}checked="checked"{/if} /></td>
			<td>{$g->getName()|escape}</td>
			<td class="prTCenter" id="groupMembersCnt{$g->getId()}">{$g->getMembers()->getCount()}</td>
	    </tr>
		{foreachelse}
		<tr>
			<td colspan="3" class="prTCenter">{t}No groups{/t}</td>
		</tr>
		{/foreach}
		</tbody>
	</table>

	{if $CurrentGroup->getJoinMode() == 2}
		<div class="prClr2 prInnerTop">
			<h2 class="prFloatLeft">{t}Insert Membership Code{/t}</h2>
		</div>
		<p>{t}You must submit a membership code in order to join this group family{/t}</p>
		<!-- form begin -->
		<table class="prForm">
			<col />
			<tbody>
				<tr>
					<td>{form_text name="join_code" value=$join_code|escape:"html"}</td>
				</tr>
			</tbody>
		</table>
		<!-- form end -->
	{/if}
	
	{TitlePane id='GroupSettingsDeleteGroup' showContent=$showContent}
		{TitlePane_Title}{t}Write a note to the group family owner (Optional){/t}{/TitlePane_Title}
		{TitlePane_ToggleCallback type='show' request_type='ajax'}xajax_privileges_deletegroup_show();{/TitlePane_ToggleCallback}
		{TitlePane_ToggleCallback type='hide' request_type='ajax'}xajax_privileges_deletegroup_hide('{$gid}');{/TitlePane_ToggleCallback}
		{TitlePane_Content}
		<table class="prForm">
			<col width="30%" />
			<col width="40%" />
			<col width="30%" />
			<tbody>
				<tr>
					<td class="prTRight"><label for="subject">{t}Subject:{/t}</label></td>
					<td>{form_text name="subject" value=$subject|escape:"html"}</td>
					<td class="prTip"></td>
				</tr>
				<tr>
					<td class="prTRight"><label for="text">{t}Message:{/t}</label></td>
					<td>{form_textarea name="text" value=$text|escape:"html" rows="9"}</td>
					<td class="prTip"></td>
				</tr>
			</tbody>
		</table>
	    {/TitlePane_Content}
	{/TitlePane}
	
	<div class="prInnerTop prTCenter"> 
	    {capture name=buttonText}{t}Join Group Family{/t}{/capture}
		{t var="in_button"}{tparam value=$smarty.capture.buttonText}%s{/t}{linkbutton onclick="document.forms['join_form'].submit(); return false;" name=$in_button} 
	</div>
{/form}

