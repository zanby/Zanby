{if $cp_view == 'expanded'}
    <!-- form begin -->
    {form from=$form onsubmit="return false;" id="cpForm" name="cpForm"}
    {form_errors_summary}
        <table class="prForm">
            <col width="40%" />
            <col width="60%" />						
            <tbody>
                <tr>
                    <td>
                        <h3 class="prNoColor">{t}Who can contact you?{/t}</h3>
                        <p class="prDefaultText">{t}if you allow it, people will be able to contact you through your profile{/t}</p>
                    </td>
                    <td>
                        {form_radio name="cp_any_members" checked=$privacy->getCpAnyMembers()|default:"0" value="1" id="cp_any_members" onclick="invertCheckBoxes('cp');"}<label for="cp_any_members"> {t} {tparam value=$SITE_NAME_AS_STRING}Any member of %s can contact me anonymously{/t}</label>
                        <div class="prIndentTopSmall">
                            {form_radio name="cp_any_members" checked=$privacy->getCpAnyMembers()|default:"0" value="0" id="cp_own_choices" onclick="invertCheckBoxes('cp');"}<label for="cp_own_choices"> {t}Configure my own choices{/t}</label>
                        </div>
                        <div class="prIndentLeftLarge">
                            <div class="prIndentTopSmall">{form_checkbox name="cp_group_organizers" checked=$privacy->getCpGroupOrganizers()|default:"0" value="1" id="cp_group_organizers"}<label for="cp_group_organizers"> {t} {tparam value=$SITE_NAME_AS_STRING}Any group organizer on %s.org{/t}</label></div>
                            <div class="prIndentTopSmall">{form_checkbox name="cp_my_group_organizers" checked=$privacy->getCpMyGroupOrganizers()|default:"0" value="1" id="cp_my_group_organizers"}<label for="cp_my_group_organizers"> {t}The organizers of my groups{/t}</label></div>
                            <div class="prIndentTopSmall">{form_checkbox name="cp_my_group_members" checked=$privacy->getCpMyGroupMembers()|default:"0" value="1" id="cp_my_group_members"}<label for="cp_my_group_members"> {t}The members my groups{/t}</label></div>
                            <div class="prIndentTopSmall">{form_checkbox name="cp_my_friends" checked=$privacy->getCpMyFriends()|default:"0" value="1" id="cp_my_friends"}<label for="cp_my_friends">  {t}My friends{/t}</label></div>
                            <div class="prIndentTopSmall">{form_checkbox name="cp_my_network" checked=$privacy->getCpMyNetwork()|default:"0" value="1" id="cp_my_network"}<label for="cp_my_network">  {t}People in my network (friends of friends){/t}</label></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
					{t var='button_01'}Save{/t}
                        {linkbutton name=$button_01 onclick="xajax_privacy_cp_save(xajax.getFormValues('cpForm')); return false;"}
						<span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="TitltPaneAppcommunicationPreferences.hide(); return false;">{t}Cancel{/t}</a></span>
                    </td>
                </tr>
            </tbody>
        </table>
    {/form}
    <!-- form end -->
{/if}	