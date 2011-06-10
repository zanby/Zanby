<script type="text/javascript" src="/js/yui/dragdrop/dragdrop-min.js" ></script>
<script type="text/javascript" src="/js/yui/animation/animation.js" ></script>
<script type="text/javascript" src="/js/discussion/settings.host.js"></script>
<script type="text/javascript" language="javascript">{$jsCode}</script>
    {form from=$form id="settingsForm"}  
    {TitlePane id='CommunicationPrivilegesContent' showContent=$ContentOpen.CommunicationPrivilegesContent}
        {TitlePane_Title}{t}Communication Privileges{/t}{/TitlePane_Title}
        {TitlePane_Content}
                <!-- Form Slot begin -->
                {if $form->getCustomErrorMessages()}
                    {form_errors_summary}
                {/if}				                
                <div class="prDropBoxInner prIndentBottom">
                    <table class="prForm">
                        <col width="31%" />
                        <col width="39%" />	
						<col width="30%" />
                        <tbody>
                            <tr>
                                <td class="prTRight"><label for="znbDiscSettEmail">{t}Change discussion email address:{/t}</label> 
                                </td>
                                <td>
                                    {form_text name="main_discussion_email" value=$main_discussion->getEmail() id="znbDiscSettEmail" class="prTRight"}
                                </td>
								<td class="prDefaultText">
									@{$DOMAIN_FOR_GROUP_EMAIL}
								</td>
                            </tr>
                            <tr>
								<td></td>
                                <td colspan="2"><div class="prTip">{t}<span class="prTBold">Note:</span> When you change your group's email address, you're changing the address your subscribers will use to send you email. People who email the old address will not reach your group. Changing the email also changes all the discussion emails addresses.{/t}</div></td>
								<td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="prDropBoxInner prIndentBottom">
                    <table class="prForm">
                        <col width="30%" />
                        <col width="40%" />	
						<col width="30%" />
                        <tr>
                            <td class="prTRight"><label for="post_mode_1">{t}Who can post on the message boards?{/t}</label></td>
                            <td>							
                                {form_radio name="post_mode" id="post_mode_1" value=2 checked=$settings->getPostMode()}<label for="post_mode_1"> 
                                {if $CurrentGroup->getDiscussiongroupType() == 'simple'}
                                {t}Only group members{/t}
                                {else}
                                {t}Group members of any sub-group{/t}
                                {/if}</label>
                                <div class="prIndentTopSmall">							
                                {form_radio name="post_mode" id="post_mode_2" value=1 checked=$settings->getPostMode()}<label for="post_mode_2"> {t}Everyone, even non-group members{/t}</label>
                                </div>
                            </td>
							<td>&#160;</td>
                        </tr>
                        <tr>
							<th colspan="3">
                                {if $form->getCustomErrorMessages('NewModerator')}
                                    {form_errors_summary id="NewModerator"}
                                {/if}
                                {form_hidden name="SaveNewModerator" id="SaveNewModerator" value="0"}
                        	</th>
                        </tr>
                        <tr>
                            <td class="prTRight"><label>{t}Appoint Moderators:{/t}</label></td>
                            <td>
                                <div>
                                    <ul>
                                        {foreach name=mlist from=$moderators item=m}
                                        <li>{$m->getModeratorName()|escape:html} <span class="prTip">{t}(Moderator){/t}</span> <a href="{$CurrentGroup->getGroupPath('discussionhostsettings')|cat:'remove/'|cat:$m->getModeratorId()}">{t}Remove{/t}</a></li>
                                        {/foreach}
                                        <li>								
                                            <div class="ieInpuFix-outer">{form_text name="new_moderator_name" value=$new_moderator_name|escape:"html"}</div>						
                                        </li>    
                                    </ul>
                               	</div>
								
                            </td>
							<td><div>
									{t var="in_button"}Save{/t}
									{linkbutton value="" id="" name=$in_button onclick="document.getElementById('SaveNewModerator').value=1;document.getElementById('settingsForm').submit();"}
								</div></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Form Slot end -->  
                <!-- Form Slot begin -->
                <div class="prDropBoxInner prIndentBottom">
                    <table class="prForm">
                        <col width="30%" />
                        <col width="40%" />	
						<col width="30%" />						
                        <tr>
                            <td class="prTRight"><label>{t}User privileges:{/t}</label></td>
                            <td>
                                {form_checkbox name="allow_delete_own" value=1 checked=$settings->getAllowDeleteOwn()}<label for="allow_delete_own"> {t}Users can delete their messages{/t}</label>
							</td>
                                <td class="prTip">{t}(unchecked - only moderators can){/t}</td>
								</tr>
							<tr>
								<td></td>
                                <td>
                                {form_checkbox name="allow_edit_own" value=1 checked=$settings->getAllowEditOwn()}<label for="allow_edit_own"> {t}Users can edit their messages{/t}</label>
                                </td>
                                <td class="prTip">{t}(unchecked - only moderators can){/t}</td>
                            </tr>
                    </table>
                <!-- Form Slot end -->
                </div>
                <div class="prTRight">{t var="in_submit"}Save Changes{/t}{form_submit name="SaveCommunicationPrivileges" value=$in_submit}</div>                
        {/TitlePane_Content}
    {/TitlePane}   
    {TitlePane id='EmailSettingsContent' showContent=$ContentOpen.EmailSettingsContent}
        {TitlePane_Title}{t}Email Settings{/t}{/TitlePane_Title}
        {TitlePane_Content}
            <!-- Form Slot begin -->
            <div class="prDropBoxInner prIndentBottom">
            <table class="prForm">
                    <col width="30%" />
                    <col width="40%" />	
					<col width="30%" />						
                    <tr>
                        <td class="prTRight"><label for="discussion_style1">{t}Discussion Style:{/t}</label></td>
                    <td	colspan="2">
                        {form_radio name="discussion_style" id="discussion_style1" value=1 checked=$settings->getDiscussionStyle()}<label for="discussion_style1"> {t}Allow users to post and read messages on the website or through email.{/t}</label>
                        <div class="prIndentTopSmall">						
                        {form_radio name="discussion_style" id="discussion_style2" value=2 checked=$settings->getDiscussionStyle()}<label for="discussion_style2"> {t}Allow users to post and read messages on the website only.{/t}</label>
                        </div>
                    </td>
					<td></td>
                </tr>
            </table>
            </div>
            <!-- Form Slot end -->
            <!-- Form Slot begin -->
            <div class="prDropBoxInner prIndentBottom">
            <table class="prForm">
                    <col width="30%" />
                    <col width="40%" />
					<col width="30%" />						
                    <tr>
                        <td class="prTRight"><label>{t}Email message subject prefix:{/t}</label></td>
                    <td	colspan="2"	class="prTip">
                        {t}This text will be added to the subject lines of all messages 
                        posted to the group. We recommend surrounding the prefix 
                        with [ ], or {literal}{ }{/literal}. Add '&#37;d' to include the message number
                        in the subject. Leave this field blank to have no subject prefix.{/t}</td></tr>
                     <tr>
						<td class="prTRight"><label for="email_subject_prefix">{t}Prefix:{/t}</label></td> 
							<td>
                            {form_text name="email_subject_prefix" value=$settings->getEmailSubjectPrefix()|escape:"html"}
							</td>
							<td	class="prTip">
								{t}Examples: [Mygroup], {literal}{Mygroup}{/literal}, [Mygroup: &#37;d]{/t}
							</td>
					</tr> 
            </table>
            </div>
            <!-- Form Slot end -->				
            <!-- Form Slot begin -->
            <div class="prDropBoxInner prIndentBottom">
            <table class="prForm">
                    <col width="30%" />
                    <col width="40%" />
					<col width="30%" />						
                    <tr>
                        <td class="prTRight"><label>{t}Message Footer:{/t}</label></td>
						<td>
                        {form_radio name="message_footer_mode" id="message_footer_mode2" value=2 checked=$settings->getMessageFooterMode()}<label for="message_footer_mode2"> {t}Use default footer{/t}</label>
                        <div class="prIndentTopSmall">
                        {form_radio name="message_footer_mode" id="message_footer_mode1" value=1 checked=$settings->getMessageFooterMode()}<label for="message_footer_mode1"> {t}Make a custom footer:{/t}</label>
                        </div>
						</td>
						<td></td>
					</tr>
					<tr>
						<td></td>
                        <td>{form_textarea name="message_footer_content" value=$settings->getMessageFooterContent()|escape:"html" id="znbDiscMessFooterText"}
                        </td>
						<td class="prTip">{t}{tparam value=$SITE_NAME_AS_STRING}This text will be appended to the end of all messages posted to the group, before the %s confidentiality statement.{/t}</td>
					</tr>
            </table>
            </div>			
            <!-- Form Slot end -->
				<div class="prTRight">{t var="in_submit_2"}Save Changes{/t}{form_submit name="SaveEmailSettings" value=$in_submit_2}</div> 				
        {/TitlePane_Content}
    {/TitlePane}

	{TitlePane id='DiscussionSetupContent' showContent=$ContentOpen.DiscussionSetupContent}
        {TitlePane_Title}{t}Discussion Setup{/t}{/TitlePane_Title}
        {TitlePane_Content}
	<!-- toggle section begin -->			
				{if $form->getCustomErrorMessages('CreateNewDiscussion')}
					{form_errors_summary id="CreateNewDiscussion"}
				{/if}
				<!-- Form Slot begin -->
				<div class="prDropBoxInner prIndentBottom">
				<h4>{t}Create a new discussion{/t}</h4>
				<table class="prForm">
						<col width="30%" />
						<col width="40%" />
						<col width="30%" />						
						<tr>
							<td class="prTRight"><label for="new_discussion_name">{t}Name:{/t}</label></td>
							<td>
							{form_text name="new_discussion_name" value=$new_discussion_name|escape:"html"}
							</td>
							<td></td>
						</tr>
						<tr>
							<td class="prTRight"><label for="new_discussion_description">{t}Description:{/t}</label></td>
							<td>
							{form_textarea name="new_discussion_description" value=$new_discussion_description|escape:"html"}
							</td>
							<td></td>
						</tr>
						<tr>
							<td class="prTRight"><label name="new_discussion_email">{t}Email address:{/t}</label></td>
							<td>
							{form_text name="new_discussion_email" value=$new_discussion_email|escape:"html"}
							</td>
							<td style="vertical-align: bottom" class="prText5">.{$main_discussion->getEmail()}@{$DOMAIN_FOR_GROUP_EMAIL}</td>
						</tr>					
				</table>
				<div class="prTRight prIndentTopSmall">{t var="in_submit_3"}Create Discussion{/t}{form_submit name="SaveCreateNewDiscussion" value=$in_submit_3}</div>
				</div>
				<!-- Form Slot end -->
				{if $form->getCustomErrorMessages('DeleteDiscussion')}
					{form_errors_summary id="DeleteDiscussion"}
				{/if}
				<!-- Form Slot begin -->
				<div class="prDropBoxInner prIndentBottom">
				<table class="prForm">
						<col width="30%" />
						<col width="40%" />
						<col width="30%" />						
						<tr>
							<td class="prTRight"><label>{t}Delete a discussion:{/t}</label></td>
						<td>
							<select name="delete_discussion_id">
							<option value="0">{t}Choose Discussion{/t}</option>
							{foreach from=$discussions item=d} <option value="{$d->getId()}">{$d->getTitle()|escape:"html"}</option>{/foreach}
							</select>								
						</td>
						<td class="prTip">{t}Deleting a discussion removes all messages.{/t}</td>
					</tr>
				</table>
				<div class="prTRight">{t var="in_submit_4"}Delete Discussion{/t}{form_submit name="SaveDeleteDiscussion" value=$in_submit_4}</div>				
				<!-- Form Slot end -->  
				</div> 
				{if $form->getCustomErrorMessages('EditDiscussion')}
					{form_errors_summary id="EditDiscussion"}
				{/if}
				<!-- Form Slot begin -->
				<div class="prDropBoxInner prIndentBottom">
				<table class="prForm">
						<col width="30%" />
						<col width="40%" />
						<col width="30%" />						
						<tr>
							<td class="prTRight"><label for="edit_discussion_id">{t}Edit a discussion's display information:{/t}</label></td>
							<td>							                                        
							<select name="edit_discussion_id" id="edit_discussion_id" onchange="choose_discussion_edit(this);">
							<option value="0">{t}Choose Discussion{/t}</option>
							{foreach from=$discussionsAll item=d}<option value="{$d->getId()}"{if $editDiscussion && $editDiscussion->getId() == $d->getId()} {t}selected{/t}{/if}>{$d->getTitle()|escape:"html"}</option>{/foreach}
							</select>
							</td>
							<td></td>
						</tr>
						<tr>
							<td class="prTRight"><label for="edit_discussion_name">{t}Name:{/t}</label></td>
							<td>							
							{if $editDiscussion}
								{form_text name="edit_discussion_name" value=$editDiscussion->getTitle()|escape:"html" id="edit_discussion_name"}
							{else}
								{form_text name="edit_discussion_name" id="edit_discussion_name"}
							{/if}</td>
							<td></td>
						</tr>
						<tr>
							<td class="prTRight"><label for="edit_discussion_description">{t}Description:{/t}</label></td>
							<td>
							{if $editDiscussion}
								{form_textarea name="edit_discussion_description" value=$editDiscussion->getDescription()|escape:"html" id="edit_discussion_description"}
							{else}
								{form_textarea name="edit_discussion_description" id="edit_discussion_description"}
							{/if}
							</td>
							<td></td>
						</tr>
				</table>
				<div class="prTRight">{t var="in_submit_5"}Save Changes{/t}{form_submit name="SaveEditDiscussion" value=$in_submit_5}</div>
				<!-- Form Slot end -->                               
				</div>
				<a href="#" name="DiscussionSetupAnchor" id="DiscussionSetupAnchor"></a>
        
				<!-- Form Slot begin -->
				<div class="prDropBoxInner prIndentBottom">
				<table class="prForm">
						<col width="30%" />
						<col width="55%" />
						<col width="15%" />	
						<tr>
						<td class="prTRight"><label>{t}Order of discussions:{/t}</label></td>
						<td>                    
                        <ul id="OrderDiscussionsTarget">
							{foreach from=$discussionsAll item=d}
							<li id="OrderedDiv{$d->getId()}" class="freeClass" onmouseover="this.className = 'freeClass1'" onmouseout="this.className = 'freeClass2'">
								{$d->getTitle()|escape:html}
							</li>
							<script language="javascript">orderedDivs[orderedDivs.length] = 'OrderedDiv{$d->getId()}';</script>
							{/foreach}
                            </ul>
							<input type="hidden" name="SaveOrderDescussion" id="SaveOrderDescussion" value="0" />
							<input type="hidden" name="OrderString" id="OrderString" value="" />
						</td>
						<td></td>
					</tr>
					<tr>
						<td colspan="3" valign="top" class="prTRight prNoPadding">                    
							{t var="in_button_2"}Save{/t}
                            {linkbutton value="btnOrderDescussion" id="btnOrderDescussion" name=$in_button_2 onclick="WarecorpDDtestApp.saveOrder(); return false;"}
						</td>
					</tr>
				</table>
				</div>
				<!-- Form Slot end -->  
	{/TitlePane_Content}
    {/TitlePane}
	<!-- toggle section end -->                      

    {if $CurrentGroup->getGroupType() neq 'family'}
	{TitlePane id='GroupFamiliesContent' showContent=$ContentOpen.GroupFamiliesContent}
        {TitlePane_Title}{t}Group Families{/t}{/TitlePane_Title}
        {TitlePane_Content}
			{foreach from=$familyGroups item=gr}
				<h3>{t}{tparam value=$gr->getName()|escape:"html"}%s - Host:{/t} <a href="{$gr->getHost()->getUserPath('profile')}">{$gr->getHost()->getLogin()|escape:"html"}</a></h3>
				
				<div>
					{form_hidden name="publishGroupOntoFamily["|cat:$gr->getId()|cat:"]" value=1}
					{form_checkbox name="publishGroupOntoFamilyCh["|cat:$gr->getId()|cat:"]" value=1 checked=$settings->getGroupPublish($CurrentGroup->getId(), $gr->getId())}<label> {t}{tparam value=$CurrentGroup->getName()|escape:"html"|longwords:50}{tparam value=$gr->getName()|escape:"html"|longwords:50}Allow the family owner to promote topics from '%s' onto the '%s' boards.{/t}</label>					
				</div>
			{/foreach}
			{if $familyGroups}
				<div class="prTRight prIndentTop">{t var="in_submit_6"}Save Changes{/t}{form_submit name="SaveFamilyPublishSettings" value=$in_submit_6}</div>
			{/if}
	{/TitlePane_Content}
    {/TitlePane}
    {/if}
{/form}
