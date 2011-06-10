<!-- tabs2 begin -->
<!-- tabs2 end -->
<!-- tabs2 area begin -->
<div class="prClr2">
    <h2>{t}Join a Group{/t}</h2>
    <h4 class="prInnerSmallTop">{t}You have chosen to join the following group:{/t}</h4>
</div>
<table class="prResult" cellpadding="0" cellspacing="0" border="0">
    <col width="5%" />
    <col width="95%"/>
    <tr>
        <td class="prVTop"><a href="{$CurrentGroup->getGroupPath('summary')}"><img class="prIndentTop" src="{$CurrentGroup->getAvatar()->setWidth(37)->setHeight(37)->setBorder(1)->getImage()}" title="" /></a></td>
        <td class="prVTop">
            <h4><a href="{$CurrentGroup->getGroupPath('summary')}">{$CurrentGroup->getName()|escape|wordwrap:25:"\n":true}</a></h4>
            <div class="prInnerSmallTop">
                {t}Created on{/t} <span>{$CurrentGroup->getCreateDate()|date_locale:'DATE_MEDIUM'}</span> | {$CurrentGroup->getCity()->name|escape:"html"}, {$CurrentGroup->getState()->name|escape:"html"}
            </div>
            <div>
                {t}{tparam value=$CurrentGroup->getMembers()->setMembersStatus('approved')->getCount()}%s Members | Host:{/t} <a href="{$CurrentGroup->getHost()->getUserPath('profile')}">{$CurrentGroup->getHost()->getLogin()|escape:"html"}</a>
            </div>
            <div class="prInnerSmallTop">
                {$CurrentGroup->getDescription()|escape|wordwrap:18:" ":true}
            </div>
            <div class="prInnerTop prInnerBottom">
                {if !$CurrentGroup->getMembers()->isMemberExistsAndApproved($user->getId())}
                {form from=$form id="joinForm"}
            </div>
            <div class="prIndentBottom"><strong>{t}Group Email Settings{/t}</strong></div>
            <div>{t}{tparam value=$SITE_NAME_AS_STRING}This group is using %s Discussion Server to support email-based discussions. Please select how you would like to recieve group emails :{/t}</div>
            <div class="prInnerTop prInnerBottom">
                <div class="prIndentTopSmall">
                    {form_radio name="digest_type" value="1" checked=$digest_type} {t}No Email - I will read this group on the web{/t}
                </div>
                <div class="prIndentTopSmall">
                    {form_radio name="digest_type" value="2" checked=$digest_type} {t}Subscribe to all content on the discussion boards{/t}
                </div>
                <div class="prInnerTop prInnerBottom">
                    <font>{t}Send as:{/t} </font>
                    {form_select name="digest_type_value_all" selected=$digest_type_value_all id="digest_type_value_all" options=$subscribeContentOptions class="prIndentLeftSmall"}
                </div>
                <div class="prIndentTopSmall prClr2">
                    {t}NOTE : You will be able to subscribe to individual discussions and topics later.{/t}
                </div>
            </div>
            <div>
                <strong>{t}{tparam value=$SITE_NAME_AS_STRING}{tparam value=$user->getEmail()|escape:"html"}NOTICE : This group engages in email-based discussions. If you participate in group discussions,
                your email address on record with %s: %s will be exposed to others in the group.{/t}</strong></div>
            <div class="prInnerTop" >
                {form_errors_summary width="80%" space_after="1px;"}
                {if $CurrentGroup->getJoinMode() == 0}
            </div>
                <div class="prHeaderTools prTRight">
                    <a href="#null" class="prButton" onclick="document.getElementById('joinForm').submit();"><span>{t}Join Group{/t}</span></a>
                </div>
            {elseif $CurrentGroup->getJoinMode() == 1}
                <div>{t}Membership to this group is controlled by the group administrators.{/t}</div>
                <div class="prInnerTop prInnerBottom">{t}Please contact the group's administrators and ask for permission to join.{/t}</div>
                <label for="subject">{t}Subject{/t}</label>
                <div>
                    {form_text id="subject" name="subject" value=$subject|escape:"html"}
                </div>
                <div class="prInnerTop">
                    <label for="text">{t}Text{/t}</label>
                </div>
                <div>
                    {form_textarea id="text" name="text" value=$text|escape:"html"}
                </div>
                <div class="prInnerTop prInnerBottom">
                {t var="in_submit"}Submit Membership Request{/t}
                {form_submit name="SendAndJoin" value=$in_submit}
                {elseif $CurrentGroup->getJoinMode() == 2}
                </div>
                <div class="prInnerTop">{t}Membership to this group is controlled by the group administrators.{/t}</div>
                <div class="prInnerTop">{t}You must submit a membership code in order  to join this group. {/t}</div>
                <div class="prInnerTop"><a href="#null" onclick="xajax_sendMessage()">{t}Send Message to Host{/t}</a> {t}if you don't have one.{/t}</div>
                <div class="prInnerTop">
                    <label for="joinCode">{t}Insert Membership Code:{/t}</label>
                </div>
                <div>
                    {form_text id="joinCode" name="join_code" value=$join_code|escape:"html"}
                </div>
                <div class="prInnerTop prTRight">
                    {t var="in_submit_2"}Join group{/t}
                    {form_submit name="CodeAndJoin" value=$in_submit_2}
                </div>
            {/if}
            {/form}
          {else}
            <div>
            {if $CurrentGroup->getHost()->getId() == $user->getId()}
                {t}You are owner of this group.{/t}
            {else}
                {t}You are already member of this group.{/t}
            {/if}
            </div>
          {/if}
        </td>
    </tr>
</table>
<!-- tabs2 area end -->
