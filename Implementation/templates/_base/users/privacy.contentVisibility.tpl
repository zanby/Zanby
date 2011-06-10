{literal}
<script type="text/javascript">        	    
</script>
{/literal}
    {if $cv_view == 'expanded'}
		{form from=$form onsubmit="xajax_privacy_cv_save(xajax.getFormValues('cvForm')); return false;" id="cvForm"}
		{form_errors_summary}
			<table class="prForm">
				<col width="40%" />
				<col width="60%" />				
				<tbody>
					<tr>
						<td>
							<h3 class="prNoColor">{t}Who can see your profile{/t}</h3>
							<p class="prDefaultText">{t}This determines which who can see your main profile page. Even if you restrict access to your profile, your username and default photo will still appear.{/t}</p>
						</td>
						<td>
							{form_radio name="cv_radio" checked=$cvRadio value="2" id="cv_anyone" onclick="invertCheckBoxes('cv');" }<label for="cv_anyone"> {t}Anyone can see my profile, including non-members{/t}</label>
							<div class="prIndentTopSmall">
								{form_radio name="cv_radio" checked=$cvRadio|default:"0" value="0" id="cv_own_choices" onclick="invertCheckBoxes('cv');"}<label for="cv_own_choices"> {t}Configure my own choices{/t}</label>
							</div>
							<div class="prIndentLeftLarge">
								<div class="prIndentTopSmall">{form_checkbox name="cv_group_organizers" checked=$privacy->getCvGroupOrganizers()|default:"0" value="1" id="cv_group_organizers"}<label for="cv_group_organizers"> {t} {tparam value=$SITE_NAME_AS_STRING}Any group organizer on %s.org{/t}</label></div>
								<div class="prIndentTopSmall">{form_checkbox name="cv_my_group_organizers" checked=$privacy->getCvMyGroupOrganizers()|default:"0" value="1" id="cv_my_group_organizers"}<label for="cv_my_group_organizers"> {t}The organizers of my groups{/t}</label></div>
								<div class="prIndentTopSmall">{form_checkbox name="cv_my_group_members" checked=$privacy->getCvMyGroupMembers()|default:"0" value="1" id="cv_my_group_members"}<label for="cv_my_group_members"> {t} The members of my groups{/t}</label></div>
								<div class="prIndentTopSmall">{form_checkbox name="cv_my_friends" checked=$privacy->getCvMyFriends()|default:"0" value="1" id="cv_my_friends"}<label for="cv_my_friends"> {t}My friends{/t}</label></div>
								<div class="prIndentTopSmall">{form_checkbox name="cv_my_network" checked=$privacy->getCvMyNetwork()|default:"0" value="1" id="cv_my_network"}<label for="cv_my_network"> {t}People in my network (friends of friends){/t}</label></div>
								<div class="prIndentTopSmall">{form_checkbox name="cv_my_address_book" checked=$privacy->getCvMyAddressBook()|default:"0" value="1" id="cv_my_address_book"}<label for="cv_my_address_book"> {t}People in my addressbook{/t}</label></div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>		
		<div class="prIndentTop">
			<table class="prForm">
				<col width="40%" />
				<col width="60%" />
				<tbody>
					<tr>
						<td>
							<h3 class="prNoColor">{t}What does &quot;Public&quot; mean to you?{/t}</h3>
							<p class="prDefaultText">{t}Determine what &quot;Public&quot; means. Each account comes with an archive of Photos, Lists, Documents, and Events. When you add content to these archives - upload a photo, for example - you will be asked if the content is public or private. Private means only you will be able to see the content. "Public" is defined by the settings at right.{/t}</p>
						</td>
						<td>
						<!-- subform begin -->
							<table class="prForm">
								<col width="25%" />
								<col width="49%" />
								<col width="26%" />
								<tbody>
									<tr class="prTRight">
										<td><label for="cv_public_photos"><strong>{t}Photos:{/t}</strong></label></td>
										<td>
											{form_select name="cv_public_photos" options=$cvSelectOptions selected=$privacy->getCvPublicPhotos()}
										</td>
										<td class="prTip prTLeft">{t}View and Share{/t}</td>
									</tr>
									<tr class="prTRight">
										<td><label for="cv_public_lists"><strong>{t}Lists:{/t}</strong></label></td>
										<td>
											{form_select name="cv_public_lists" options=$cvSelectOptions selected = $privacy->getCvPublicLists()}
										</td>
										<td class="prTip prTLeft">{t}View and Share{/t}</td>
									</tr>
									<tr class="prTRight">
										<td><label for="cv_public_documents"><strong>{t}Documents:{/t}</strong></label></td>
										<td>
											{form_select name="cv_public_documents" options=$cvSelectOptions selected= $privacy->getCvPublicDocuments()}
										</td>
										<td class="prTip prTLeft">{t}View and Share{/t}</td>
									</tr>
									<tr class="prTRight">
										<td><label for="cv_public_events"><strong>{t}Events:{/t}</strong></label></td>
										<td>
											{form_select name="cv_public_events" options=$cvSelectOptions selected=$privacy->getCvPublicEvents()}
										</td>
										<td class="prTip prTLeft">{t}Calendar{/t}</td>
									</tr>
									<tr class="prTRight">
										<td><label for="cv_public_tags"><strong>{t}Tags:{/t}</strong></label></td>
										<td>
											{form_select name="cv_public_tags" options=$cvSelectOptions selected = $privacy->getCvPublicTags()}
										</td>
										<td class="prTip"></td>
									</tr>
									<tr>
										<td class="prTRight">
										  <label for="cv_public_friends"><strong>{t}Friends:{/t}</strong></label>
                                        </td>
										<td>
											{form_select name="cv_public_friends" options=$cvSelectOptions selected = $privacy->getCvPublicFriends()}
										</td>
										<td class="prTip"></td>
									</tr>
									<tr>
                                        <td class="prTRight">
										  <label for="cv_public_videos"><strong>{t}Videos:{/t}</strong></label>
                                        </td>
                                        <td>
                                            {form_select name="cv_public_videos" options=$cvSelectOptions selected = $privacy->getCvPublicVideos()}
                                        </td>
                                        <td class="prTip"></td>
                                    </tr>
								</tbody>
							</table>
							<!-- subform and -->
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
						{t var='button_01'}Save{/t}
							{linkbutton name=$button_01 onclick="xajax_privacy_cv_save(xajax.getFormValues('cvForm')); return false;"}
							<span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="TitltPaneAppcontentVisibility.hide(); return false;">{t}Cancel{/t}</a></span>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		{/form}
    {/if}	