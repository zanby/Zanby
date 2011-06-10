{if $sr_view == 'expanded'}
    {form from=$form onsubmit="xajax_privacy_sr_save(xajax.getFormValues('srForm')); return false;" id="srForm"}
    {form_errors_summary}		
        <table class="prForm">
            <col width="40%" />
            <col width="60%" />				
            <tbody>
                <tr>
                    <td>
                        <h3>{t}Who can find you in search results{/t}</h3>
                        <p class="prDefaultText">{t} {tparam value=$SITE_NAME_AS_STRING}This determines who can find you by searching %s and what they can<br />do when they find you.{/t}</p>
                    </td>
                    <td>
                        {if $privacy->getSrAnyone()==1}
                            {assign var="srRadio" value="2"}
                        {elseif $privacy->getSrAnyMembers()==1}
                            {assign var="srRadio" value="1"}
                        {else}
                            {assign var="srRadio" value="0"}
                        {/if}
                        <!-- -->
                        {form_radio name="sr_radio" checked=$srRadio value="2" id="sr_anyone" onclick="invertCheckBoxes('sr');"}<label for="sr_anyone"> {t}Anyone can find me through search, including non-members{/t}</label>
                        <!-- / -->
                        <!-- -->
                        <div class="prIndentTopSmall">
                            {form_radio name="sr_radio" checked=$srRadio value="1" id="sr_any_members" onclick="invertCheckBoxes('sr');"}<label for="sr_any_members" > {t} {tparam value=$SITE_NAME_AS_STRING}Any member of %s can find me through search{/t}</label>
                        </div>
                        <!-- / -->
                        <!-- -->
                        <div class="prIndentTopSmall">
                            {form_radio name="sr_radio" checked=$srRadio|default:"0" value="0" id="sr_own_choices" onclick="invertCheckBoxes('sr');"}<label for="sr_own_choices"> {t} Configure my own choices{/t}</label>
                        </div>
                        <!-- / -->
                        <!-- -->
                        <div class="prIndentLeftLarge">
                            <!-- -->
                            <div class="prIndentTopSmall">{form_checkbox name="sr_group_organizers" checked=$privacy->getSrGroupOrganizers()|default:"0" value="1" id="sr_group_organizers"}<label for="sr_group_organizers"> {t} {tparam value=$SITE_NAME_AS_STRING}Any group organizer on %s.org{/t}</label></div>
                            <!-- / -->
                            <!-- -->
                            <div class="prIndentTopSmall">{form_checkbox name="sr_my_group_organizers" checked=$privacy->getSrMyGroupOrganizers()|default:"0" value="1" id="sr_my_group_organizers"}<label for="sr_my_group_organizers"> {t} The organizers of my groups{/t}</label></div>
                            <!-- / -->
                            <!-- -->
                            <div class="prIndentTopSmall">{form_checkbox name="sr_my_group_members" checked=$privacy->getSrMyGroupMembers()|default:"0" value="1" id="sr_my_group_members"}<label for="sr_my_group_members"> {t} The members my groups {/t}</label></div>
                            <!-- / -->
                            <!-- -->
                            <div class="prIndentTopSmall">{form_checkbox name="sr_my_friends" checked=$privacy->getSrMyFriends()|default:"0" value="1" id="sr_my_friends"}<label for="sr_my_friends"> {t}My friends{/t}</label></div>
                            <!-- / -->
                            <!-- -->
                            <div class="prIndentTopSmall">{form_checkbox name="sr_my_network" checked=$privacy->getSrMyNetwork()|default:"0" value="1" id="sr_my_network"}<label for="sr_my_network"> {t}People in my network (friends of friends){/t}</label></div>
                            <!-- / -->
                            <!-- -->
                            <div class="prIndentTopSmall">{form_checkbox name="sr_my_address_book" checked=$privacy->getSrMyAddressBook()|default:"0" value="1" id="sr_my_address_book"}<label for="sr_my_address_book"> {t}People in my addressbook{/t}</label></div>
                            <!-- / -->
                        </div>
                        <!-- / -->
                    </td>
                </tr>
            </tbody>
        </table>
    <!-- ************************* -->
    <div class="prIndentTop">
        <table class="prForm">
            <col width="40%" />
            <col width="60%" />
            <tbody>
                <tr>
                    <td>
                        <h3>{t}What is visible in my search result{/t}</h3>
                        <p class="prDefaultText">{t}What can people do with your search result{/t}</p>
                    </td>
                    <td>
                        <!-- -->
                        <div class="prIndentLeft">
                            <!-- -->
                            {form_checkbox name="sr_view_profile_photo" checked=$privacy->getSrViewProfilePhoto()|default:"0" value="1" id="sr_view_profile_photo"}<label for="sr_view_profile_photo"> {t}View my profile picture {/t}</label>
                            <!-- / -->
                            <!-- -->
                            <div class="prIndentTopSmall">{form_checkbox name="sr_view_send_message" checked=$privacy->getSrViewSendMessage()|default:"0" value="1" id="sr_view_send_message"}<label for="sr_view_send_message"> {t}Send me a message{/t}</label></div>
                            <!-- / -->
                            <!-- -->
                            <div class="prIndentTopSmall">{form_checkbox name="sr_view_add_to_friend" checked=$privacy->getSrViewAddToFriend()|default:"0" value="1" id="sr_view_add_to_friend"}<label for="sr_view_add_to_friend"> {t}Add you as a friend{/t}</label></div>
                            <!-- / -->
                            <!-- -->
                            <div class="prIndentTopSmall">{form_checkbox name="sr_view_my_friends" checked=$privacy->getSrViewMyFriends()|default:"0" value="1" id="sr_view_my_friends"}<label for="sr_view_my_friends"> {t}View your friends list{/t} </label></div>
                            <!-- / -->
                        </div>
                        <!-- / -->
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
					{t var='button_01'}Save{/t}
                        {linkbutton name=$button_01 onclick="xajax_privacy_sr_save(xajax.getFormValues('srForm')); return false;"}
						<span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="TitltPaneAppsearchResultSettings.hide(); return false;">{t}Cancel{/t}</a></span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    {/form}
{/if}