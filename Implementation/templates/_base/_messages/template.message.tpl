{capture name="_from_"}
{$sender->getLogin()} <{$sender->getLogin()}@{$DOMAIN_FOR_EMAIL}>
{/capture}

{capture name="_subject_"}
Message to {$recipient->getLogin()} from {$sender->getLogin()}
{/capture}

{capture name="_pmb_subject_"}
Message to {$recipient->getLogin()} from {$sender->getLogin()}
{/capture}

{capture name="_mail_text_part_"}
Hi, {$recipient->getLogin()}!
This is message from {$sender->getLogin()}.
{/capture}

{capture name="_mail_html_part_"}
Hi, {$recipient->getLogin()}! <br>
This is message from {$sender->getLogin()}.
{/capture}

{capture name="_pmb_part_"}
Hi, {$recipient->getLogin()}!
This is message from {$sender->getLogin()}.
{/capture}


{****************}
{****************   RESIGN_REQUEST_NEW_HOST   ******************************}
{****************}
{*
Хост группы отправляет это письмо, когда хочет отказаться от хоста
и просит другого пользователя стать новым хостом.
See old : host_resigns_and_asks_someone.tpl
*}

{capture name="_from_"}
{$SITE_NAME_AS_STRING}@{$DOMAIN_FOR_EMAIL}
{/capture}

{capture name="_subject_"}
You have been nominated as host for '{$Group->getName()}'
{/capture}

{capture name="_pmb_subject_"}
You have been nominated as host for '{$Group->getName()}'
{/capture}

{capture name="_mail_text_part_"}
Hello {$recipient->getFirstname()} {$recipient->getLastname()}!
The host of {$Group->getName()}, {$Group->getHost()->getLogin()}, has resigned and has nominated you as the new host.
Please indicate whether you accept or reject this nomination by going to this web address:
{$Group->getGroupPath('setnewhost')}access_code/{$AccessCode}

In most mail programs, this should appear as a blue link which you can click on.
If that doesn't work,then cut and paste the address into the address line at the top of your web browser window.
For more information about {$Group->getName()}, please contact {$Group->getHost()->getLogin()} directly.

Thanks,
{$SITE_NAME_AS_STRING} Groups
------------------------------------------------------

NO SPAM
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_DOMAIN}, please forward the message immediately to feedback@{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING}'s {$PrivacyLink}
If you found this email in your junk/bulk folder, please add {$SenderLink} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox.

DISCLAIMER
{$SITE_NAME_AS_DOMAIN} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_DOMAIN} terms of service.

HOW TO UNSUBSCRIBE
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here:

{$UnsubscribeLink}.

Then click, Cancel membership and remove profile.
This is an automated e-mail. Please do not respond.
{/capture}

{capture name="_mail_html_part_"}
{/capture}

{capture name="_pmb_part_"}
Hello {$recipient->getFirstname()} {$recipient->getLastname()}!
The host of {$Group->getName()}, {$Group->getHost()->getLogin()}, has resigned and has nominated you as the new host.
Please indicate whether you accept or reject this nomination by going to this web address:
{$Group->getGroupPath('setnewhost')}access_code/{$AccessCode}

In most mail programs, this should appear as a blue link which you can click on.
If that doesn't work,then cut and paste the address into the address line at the top of your web browser window.
For more information about {$Group->getName()}, please contact {$Group->getHost()->getLogin()} directly.
{/capture}



{****************}
{****************   RESIGN_MEMBERS_INFORMATION   ******************************}
{****************}
{*
Это письмо отправляется всем членам группы, когда отказывается от хоста перманентно.
Письмо предлагает любому пользователю группы стать новым хостом.
See old : host_resigns_asks.tpl & host_resigns_letter.tpl
*}

{capture name="_from_"}
{$SITE_NAME_AS_STRING}@{$DOMAIN_FOR_EMAIL}
{/capture}

{capture name="_subject_"}
{$Group->getHost()->getLogin()} has resigned as host of {$Group->getName()}
{/capture}

{capture name="_pmb_subject_"}
{$Group->getHost()->getLogin()} has resigned as host of {$Group->getName()}
{/capture}

{capture name="_mail_text_part_"}
Hello {$recipient->getFirstname()} {$recipient->getLastname()}!

The host of {$Group->getName()}, {$Group->getHost()->getLogin()}, is resigning.
{if $MessageBoby neq ''}Here is a message from  {$Group->getHost()->getLogin()}:
{if $MessageSubject}{$MessageSubject}{/if}
{if $MessageBoby}{$MessageBoby}{/if}{/if}

If {$Group->getName()} is to continue, the group needs to nominate another host.
If no host is named within the next {$DaysNumber} day(s), this group will be closed.
To become the host for this group or to nominate others, click here :
{$SetNewHostLink}.

Thanks,
{$SITE_NAME_AS_STRING} Groups
------------------------------------------------------

NO SPAM
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_DOMAIN}, please forward the message immediately to feedback@{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING}'s {$PrivacyLink}
If you found this email in your junk/bulk folder, please add {$SenderLink} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox.

DISCLAIMER
{$SITE_NAME_AS_DOMAIN} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_DOMAIN} terms of service.

HOW TO UNSUBSCRIBE
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here:

{$UnsubscribeLink}.

Then click, Cancel membership and remove profile.
This is an automated e-mail. Please do not respond.
{/capture}

{capture name="_mail_html_part_"}
{/capture}

{capture name="_pmb_part_"}
Hello {$recipient->getFirstname()} {$recipient->getLastname()}!

The host of {$Group->getName()}, {$Group->getHost()->getLogin()}, is resigning.
{if $MessageBoby neq ''}Here is a message from  {$Group->getHost()->getLogin()}:
{if $MessageSubject}{$MessageSubject}{/if}
{if $MessageBoby}{$MessageBoby}{/if}{/if}

If {$Group->getName()} is to continue, the group needs to nominate another host.
If no host is named within the next {$DaysNumber} day(s), this group will be closed.
To become the host for this group or to nominate others, click here :
{$SetNewHostLink}.
{/capture}


{****************}
{****************   RESIGN_THANK_NEW_HOST   ******************************}
{****************}
{*
Это письмо отправляется члену группы, который становиться новым хостом группы, когда старый либо
отказался от членства перманентно, либо попросил пользователя стать новым хостом и тот согласился.
See old : host_thanks_new.tpl
*}

{capture name="_from_"}
{$SITE_NAME_AS_STRING}@{$DOMAIN_FOR_EMAIL}
{/capture}

{capture name="_subject_"}
Thank you for agreeing to host a {$SITE_NAME_AS_STRING} Group
{/capture}

{capture name="_pmb_subject_"}
Thank you for agreeing to host a {$SITE_NAME_AS_STRING} Group
{/capture}

{capture name="_mail_text_part_"}
Hello {$recipient->getFirstname()} {$recipient->getLastname()}!

Thank your for agreeing to host {$Group->getName()}.
You can manage your group by visiting {$Group->getGroupPath('settings')} and adjusting who can
post on the message board, post photos, make lists, create group events and use the group email.
We recommend contacting your group members and introducing yourself as soon as possible.

Thanks,
{$SITE_NAME_AS_STRING} Groups
------------------------------------------------------

NO SPAM
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_DOMAIN}, please forward the message immediately to feedback@{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING}'s {$PrivacyLink}
If you found this email in your junk/bulk folder, please add {$SenderLink} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox.

DISCLAIMER
{$SITE_NAME_AS_DOMAIN} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_DOMAIN} terms of service.

HOW TO UNSUBSCRIBE
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here:

{$UnsubscribeLink}.

Then click, Cancel membership and remove profile.
This is an automated e-mail. Please do not respond.
{/capture}

{capture name="_mail_html_part_"}
{/capture}

{capture name="_pmb_part_"}
Hello {$recipient->getFirstname()} {$recipient->getLastname()}!

Thank your for agreeing to host {$Group->getName()}.
You can manage your group by visiting {$Group->getGroupPath('settings')} and adjusting who can
post on the message board, post photos, make lists, create group events and use the group email.
We recommend contacting your group members and introducing yourself as soon as possible.
{/capture}


{****************}
{****************   RESIGN_NEW_HOST_MEMBERS_INFORMATION   ******************************}
{****************}
{*
Это письмо отправляется членам группы, когда новый пользователь становиться новым хостом.
*}

{capture name="_from_"}
{$SITE_NAME_AS_STRING}@{$DOMAIN_FOR_EMAIL}
{/capture}

{capture name="_subject_"}
{$Group->getHost()->getLogin()} is the new host of {$Group->getName()}
{/capture}

{capture name="_pmb_subject_"}
{$Group->getHost()->getLogin()} is the new host of {$Group->getName()}
{/capture}

{capture name="_mail_text_part_"}
Hello {$recipient->getFirstname()} {$recipient->getLastname()}!

{$Group->getHost()->getLogin()} has volunteered to host,
{$Group->getName()} a group you belong to on {$SITE_NAME_AS_DOMAIN}.
You can find out more about your new host at:
{$Group->getHost()->getUserPath('profile')}

Thanks,
{$SITE_NAME_AS_STRING} Groups
------------------------------------------------------

NO SPAM
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_DOMAIN}, please forward the message immediately to feedback@{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING}'s {$PrivacyLink}
If you found this email in your junk/bulk folder, please add {$SenderLink} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox.

DISCLAIMER
{$SITE_NAME_AS_DOMAIN} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_DOMAIN} terms of service.

HOW TO UNSUBSCRIBE
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here:

{$UnsubscribeLink}.

Then click, Cancel membership and remove profile.
This is an automated e-mail. Please do not respond.
{/capture}

{capture name="_mail_html_part_"}
{/capture}

{capture name="_pmb_part_"}
Hello {$recipient->getFirstname()} {$recipient->getLastname()}!

{$Group->getHost()->getLogin()} has volunteered to host,
{$Group->getName()} a group you belong to on {$SITE_NAME_AS_DOMAIN}.
You can find out more about your new host at:
{$Group->getHost()->getUserPath('profile')}
{/capture}


{****************}
{****************   RESIGN_MEMBER_FROM_GROUP_TO_HOST   ******************************}
{****************}
{*
Это письмо отправляется хосту группы, когда какой-либо член группы отказыватеся
от членства в данной группе
See old : user_resign_from_group.tpl
*}

{capture name="_from_"}
{$SITE_NAME_AS_STRING}@{$DOMAIN_FOR_EMAIL}
{/capture}

{capture name="_subject_"}
A member of {$Group->getName()} has resigned
{/capture}

{capture name="_pmb_subject_"}
A member of {$Group->getName()} has resigned
{/capture}

{capture name="_mail_text_part_"}
Hello {$recipient->getFirstname()} {$recipient->getLastname()}!

You are host of '{$Group->getName()}' {$SITE_NAME_AS_STRING} Group.
A member of your group, {$Group->getName()}, from {$Member->getCity()->name}, {$Member->getState()->name} has resigned.
If you wish to discuss this further, please contact {$Member->getLogin()} directly.

Thanks,
{$SITE_NAME_AS_STRING} Groups
------------------------------------------------------

NO SPAM
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_DOMAIN}, please forward the message immediately to feedback@{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING}'s {$PrivacyLink}
If you found this email in your junk/bulk folder, please add {$SenderLink} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox.

DISCLAIMER
{$SITE_NAME_AS_DOMAIN} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_DOMAIN} terms of service.

HOW TO UNSUBSCRIBE
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here:

{$UnsubscribeLink}.

Then click, Cancel membership and remove profile.
This is an automated e-mail. Please do not respond.
{/capture}

{capture name="_mail_html_part_"}
{/capture}

{capture name="_pmb_part_"}
Hello {$recipient->getFirstname()} {$recipient->getLastname()}!

You are host of '{$Group->getName()}' {$SITE_NAME_AS_STRING} Group.
A member of your group, {$Group->getName()}, from {$Member->getCity()->name}, {$Member->getState()->name} has resigned.
If you wish to discuss this further, please contact {$Member->getLogin()} directly.
{/capture}


{****************}
{****************   CREATE_NEW_GROUP_THANK   ******************************}
{****************}
{*
Это письмо отправляется пользователю, создавшему новую группу.
See old : create_group_email_to_host.tpl
*}

{capture name="_from_"}
{$SITE_NAME_AS_STRING}@{$DOMAIN_FOR_EMAIL}
{/capture}

{capture name="_subject_"}
Group Creation Confirmation
{/capture}

{capture name="_pmb_subject_"}
Group Creation Confirmation
{/capture}

{capture name="_mail_text_part_"}
Hello {$recipient->getFirstname()} {$recipient->getLastname()}!

Thank you for creating your new group "{$Group->getName()}" on {$SITE_NAME_AS_DOMAIN}!
You can manage your group, create events, send emails, run a group message board,
create lists, add photos or documents, and invite members at {$Group->getGroupPath('summary')}.
{if $invite == true}Your has been sent  message to your member invitee list.{/if}

Please be sure to distribute privileges to your group members at {$Group->getGroupPath('settings')}.

Thanks,
{$SITE_NAME_AS_STRING} Groups
------------------------------------------------------

NO SPAM
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_DOMAIN}, please forward the message immediately to feedback@{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING}'s {$PrivacyLink}
If you found this email in your junk/bulk folder, please add {$SenderLink} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox.

DISCLAIMER
{$SITE_NAME_AS_DOMAIN} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_DOMAIN} terms of service.

HOW TO UNSUBSCRIBE
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here:

{$UnsubscribeLink}.

Then click, Cancel membership and remove profile.
This is an automated e-mail. Please do not respond.
{/capture}

{capture name="_mail_html_part_"}
{/capture}

{capture name="_pmb_part_"}
Hello {$recipient->getFirstname()} {$recipient->getLastname()}!

Thank you for creating your new group "{$Group->getName()}" on {$SITE_NAME_AS_DOMAIN}!
You can manage your group, create events, send emails, run a group message board,
create lists, add photos or documents, and invite members at {$Group->getGroupPath('summary')}.
{if $invite == true}Your has been sent  message to your member invitee list.{/if}

Please be sure to distribute privileges to your group members at {$Group->getGroupPath('settings')}.
{/capture}



{****************}
{****************   GROUP_INVITATION_TO_MEMBER_FORM_HOST   ******************************}
{****************}
{*
Это письмо отправляется пользователю, которого пригласил присоедениться в группу хост группы.
See old : host_create_invite_email.tpl
*}


{capture name="_from_"}
{$SITE_NAME_AS_STRING}@{$DOMAIN_FOR_EMAIL}
{/capture}

{capture name="_subject_"}
{$Group->getHost()->getLogin()} has invited you to join {$Group->getName()}
{/capture}

{capture name="_pmb_subject_"}
{$Group->getHost()->getLogin()} has invited you to join {$Group->getName()}
{/capture}

{capture name="_mail_text_part_"}
Hello {$recipient->getFirstname()} {$recipient->getLastname()}!

{$Group->getHost()->getLogin()}, the host of the {$SITE_NAME_AS_STRING} Group {$Group->getName()} has invited you to join.

{if $Message neq ''}
Here is a message from {$Group->getHost()->getLogin()}:
{$Message}
{/if}

To join, copy this url into your browser:
{$Group->getGroupPath('joingroup')}

Group details:
Name: {$Group->getName()}
Headline: {$Group->getHeadline()}
Description: {$Group->getDescription()}

Thanks,
{$SITE_NAME_AS_STRING} Groups
------------------------------------------------------

NO SPAM
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_DOMAIN}, please forward the message immediately to feedback@{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING}'s {$PrivacyLink}
If you found this email in your junk/bulk folder, please add {$SenderLink} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox.

DISCLAIMER
{$SITE_NAME_AS_DOMAIN} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_DOMAIN} terms of service.

HOW TO UNSUBSCRIBE
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here:

{$UnsubscribeLink}.

Then click, Cancel membership and remove profile.
This is an automated e-mail. Please do not respond.
{/capture}

{capture name="_mail_html_part_"}
{/capture}

{capture name="_pmb_part_"}
Hello {$recipient->getFirstname()} {$recipient->getLastname()}!

{$Group->getHost()->getLogin()}, the host of the {$SITE_NAME_AS_STRING} Group {$Group->getName()} has invited you to join.

{if $Message neq ''}
Here is a message from {$Group->getHost()->getLogin()}:
{$Message}
{/if}

To join, copy this url into your browser:
{$Group->getGroupPath('joingroup')}

Group details:
Name: {$Group->getName()}
Headline: {$Group->getHeadline()}
Description: {$Group->getDescription()}
{/capture}



{****************}
{****************   GROUP_INVITATION_TO_MEMBER_FORM_MEMBER   ******************************}
{****************}
{*
Это письмо отправляется пользователю, которого пригласил присоедениться в группу другой пользователь.
See old : invite_join_group.tpl
*}





{****************}
{****************   GROUP_JOIN_REQUEST_APPROVE_USER_TO_HOST   ******************************}
{****************}
{*
Это письмо отправляется хосту группы от пользователя, когда пользователь просит его
разрешить вступить в группу. Письмо о том, что хост должен либо принять либо отклонить запрос
See old : approve_join_host_mail.tpl
*}


{capture name="_from_"}
{$SITE_NAME_AS_STRING}@{$DOMAIN_FOR_EMAIL}
{/capture}

{capture name="_subject_"}
Request for membership in {$Group->getName()}
{/capture}

{capture name="_pmb_subject_"}
Request for membership in {$Group->getName()}
{/capture}

{capture name="_mail_text_part_"}
Hello {$recipient->getFirstname()} {$recipient->getLastname()}!

The following individual has requested to join {$Group->getName()}:

Name - {$NewMember->getFirstname()} {$NewMember->getLastname()}
E-mail - {$NewMember->getEmail()}
Username - {$NewMember->getLogin()}

{if $Message != ''}
{$NewMember->getLogin()} has included the following message:
{$Message}
{/if}

Please accept or reject this request by clicking on this link
{$Group->getGroupPath('members')}mode/pending/

Thanks,
{$SITE_NAME_AS_STRING} Groups
------------------------------------------------------

NO SPAM
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_DOMAIN}, please forward the message immediately to feedback@{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING}'s {$PrivacyLink}
If you found this email in your junk/bulk folder, please add {$SenderLink} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox.

DISCLAIMER
{$SITE_NAME_AS_DOMAIN} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_DOMAIN} terms of service.

HOW TO UNSUBSCRIBE
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here:

{$UnsubscribeLink}.

Then click, Cancel membership and remove profile.
This is an automated e-mail. Please do not respond.
{/capture}

{capture name="_mail_html_part_"}
{/capture}

{capture name="_pmb_part_"}
Hello {$recipient->getFirstname()} {$recipient->getLastname()}!

The following individual has requested to join {$Group->getName()}:

Name - {$NewMember->getFirstname()} {$NewMember->getLastname()}
E-mail - {$NewMember->getEmail()}
Username - {$NewMember->getLogin()}

{if $Message != ''}
{$NewMember->getLogin()} has included the following message:
{$Message}
{/if}

Please accept or reject this request by clicking on this link
{$Group->getGroupPath('members')}mode/pending/
{/capture}



{****************}
{****************   GROUP_JOIN_NEW_MEMBER_IS_JOINED   ******************************}
{****************}
{*
Это письмо отправляется администратору, когда новый пользователь присоединенился к группе
See old : public_join_mail_to_host.tpl
*}


{capture name="_from_"}
{$SITE_NAME_AS_STRING}@{$DOMAIN_FOR_EMAIL}
{/capture}

{capture name="_subject_"}
You have a new member of  {$Group->getName()}
{/capture}

{capture name="_pmb_subject_"}
 You have a new member of  {$Group->getName()}
{/capture}

{capture name="_mail_text_part_"}
Hello {$recipient->getFirstname()} {$recipient->getLastname()}!

You are Host of '{$Group->getName()}' {$SITE_NAME_AS_STRING} Group.

Good News! You have a new member in your group, {$Group->getName()}.

{$NewMember->getLogin()}, from {$NewMember->getCity()->name}, {$NewMember->getState()->name} has joined your group.

{if $MessageBoby neq ''}
Here is a message from {$NewMember->getLogin()}:
{$MessageSubject}
{$MessageBoby}
{/if}

To view more information about your new member, please visit their profile at:
{$NewMember->getUserPath('profile')}

Thanks,
{$SITE_NAME_AS_STRING} Groups
------------------------------------------------------

NO SPAM
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_DOMAIN}, please forward the message immediately to feedback@{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING}'s {$PrivacyLink}
If you found this email in your junk/bulk folder, please add {$SenderLink} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox.

DISCLAIMER
{$SITE_NAME_AS_DOMAIN} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_DOMAIN} terms of service.

HOW TO UNSUBSCRIBE
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here:

{$UnsubscribeLink}.

Then click, Cancel membership and remove profile.
This is an automated e-mail. Please do not respond.
{/capture}

{capture name="_mail_html_part_"}
{/capture}

{capture name="_pmb_part_"}
Hello {$recipient->getFirstname()} {$recipient->getLastname()}!

You are Host of '{$Group->getName()}' {$SITE_NAME_AS_STRING} Group.

Good News! You have a new member in your group, {$Group->getName()}.

{$NewMember->getLogin()}, from {$NewMember->getCity()->name}, {$NewMember->getState()->name} has joined your group.

{if $MessageBoby neq ''}
Here is a message from {$NewMember->getLogin()}:
{$MessageSubject}
{$MessageBoby}
{/if}

To view more information about your new member, please visit their profile at:
{$NewMember->getUserPath('profile')}
{/capture}


{****************}
{****************   GROUP_JOIN_NEW_MEMBER_IS_APPROVED   ******************************}
{****************}
{*
Это письмо отправляется пользователю, когда хост заапрувил его запрос на присоединение к группе
See old : approve_member_to_member.tpl
*}



{****************}
{****************   GROUP_JOIN_NEW_MEMBER_IS_DECLINED   ******************************}
{****************}
{*
Это письмо отправляется пользователю, когда хост деклайнил его запрос на присоединение к группе
See old : deny_member_to_member.tpl
*}




{****************}
{****************   USERS_FRIEND_INVITE   ******************************}
{****************}
{*
Это письмо отправляется пользователю, когда его зафрендили

@author Alexey Loshkarev
*}

{capture name="_from_"}
{$sender->getLogin()} <{$sender->getLogin()}@{$DOMAIN_FOR_EMAIL}>
{/capture}

{capture name="_subject_"}
{$sender->getLogin()} invites you to be his friend
{/capture}

{capture name="_pmb_subject_"}
{$sender->getLogin()} invites you to be his friend
{/capture}

{capture name="_mail_text_part_"}
Hi, {$recipient->getLogin()}!

User {$sender->getLogin()} invites you to be his friend!

To accept invitation, please follow this link - {$recipient->getUserPath('friend')}cmd/add/user/{$sender->getId()}

To decline invitation, please do nothing, ok? :)

You may view your friends following this link - {$recipient->getUserPath('friends')}

{/capture}

{capture name="_mail_html_part_"}
Hi, {$recipient->getLogin()}!<br />

User {$sender->getLogin()} invites you to be his friend!<br />

To accept invitation, please follow this link - {$recipient->getUserPath('friend')}cmd/add/user/{$sender->getId()}<br />

To decline invitation, please do nothing, ok? :)<br />

You may view your friends following this link - {$recipient->getUserPath('friends')}<br />
{/capture}

{capture name="_pmb_part_"}
Hi, {$recipient->getLogin()}!

User {$sender->getLogin()} invites you to be his friend!

To accept invitation, please follow this link - {$recipient->getUserPath('friend')}cmd/add/user/{$sender->getId()}

To decline invitation, please do nothing, ok? :)

You may view your friends following this link - {$recipient->getUserPath('friends')}

{/capture}


{****************}
{****************   USERS_FRIEND_CONGRATULATION   ******************************}
{****************}
{*
Это письмо отправляется пользователю, когда он зафрендил в ответ (друзья, типа)

@author Alexey Loshkarev
*}

{capture name="_from_"}
{$sender->getLogin()} <{$sender->getLogin()}@{$DOMAIN_FOR_EMAIL}>
{/capture}

{capture name="_subject_"}
{$sender->getLogin()} is your mutual friend
{/capture}

{capture name="_pmb_subject_"}
{$sender->getLogin()} is your mutual friend
{/capture}

{capture name="_mail_text_part_"}
Hi, {$recipient->getLogin()}!
User {$sender->getLogin()} is now your mutual friend!
You may view your friends following this link - {$recipient->getUserPath('friends')}

{/capture}

{capture name="_mail_html_part_"}
Hi, {$recipient->getLogin()}!<br />
User {$sender->getLogin()} is now your mutual friend!<br />
You may view your friends following this link - {$recipient->getUserPath('friends')}<br />
{/capture}

{capture name="_pmb_part_"}
Hi, {$recipient->getLogin()}!
User {$sender->getLogin()} is now your mutual friend!
You may view your friends following this link - {$recipient->getUserPath('friends')}
{/capture}


{****************}
{****************   USERS_FRIEND_FRIENDSHIP_END   ******************************}
{****************}
{*
Это письмо отправляется пользователю, когда он отфрендил чела и они больше не друзья

@author Alexey Loshkarev
*}

{capture name="_from_"}
{$sender->getLogin()} <{$sender->getLogin()}@{$DOMAIN_FOR_EMAIL}>
{/capture}

{capture name="_subject_"}
{$sender->getLogin()} is not your friend now and your friendship ends
{/capture}

{capture name="_pmb_subject_"}
{$sender->getLogin()} is not your friend now and your friendship ends
{/capture}

{capture name="_mail_text_part_"}
Hi, {$recipient->getLogin()}!
User {$sender->getLogin()} is not your friend now and your friendship ends!
You may view your friends following this link - {$recipient->getUserPath('friends')}

{/capture}

{capture name="_mail_html_part_"}
Hi, {$recipient->getLogin()}!<br />
User {$sender->getLogin()} is not your friend now and your friendship ends!<br />
You may view your friends following this link - {$recipient->getUserPath('friends')}<br />
{/capture}

{capture name="_pmb_part_"}
Hi, {$recipient->getLogin()}!
User {$sender->getLogin()} is not your friend not and your friendship ends!
You may view your friends following this link - {$recipient->getUserPath('friends')}
{/capture}


{****************}
{****************   USERS_INVITE_EXTERNAL   ******************************}
{****************}
{*
Это письмо отправляется на внешний ящик, когда чела добавляют (импортом) в адресбук и приглашают в Занбу

@author Alexey Loshkarev
*}

{capture name="_from_"}
{$sender->getLogin()} <{$sender->getLogin()}@{$DOMAIN_FOR_EMAIL}>
{/capture}

{capture name="_subject_"}
{$sender->getLogin()} invites you to join {$SITE_NAME_AS_STRING}
{/capture}

{capture name="_pmb_subject_"}
{$sender->getLogin()} invites you to join {$SITE_NAME_AS_STRING}
{/capture}

{capture name="_mail_text_part_"}
Hi, {$recipient->getLogin()}!

User {$sender->getLogin()} invites you to join {$SITE_NAME_AS_STRING}

{$SITE_NAME_AS_STRING} is a very-very good thing. You must register there as quick as possible or I'll hit you with a 10-tons brick.

Please, visit {$SITE_NAME_AS_FULL_DOMAIN}

{/capture}

{capture name="_mail_html_part_"}
Hi, {$recipient->getLogin()}!<br />

User {$sender->getLogin()} invites you to join {$SITE_NAME_AS_STRING}<br />

{$SITE_NAME_AS_STRING} is a very-very good thing. You must register there as quick as possible or I'll hit you with a 10-tons brick. <br />

Please, visit {$SITE_NAME_AS_FULL_DOMAIN} <br />


{/capture}

{capture name="_pmb_part_"}
Hi, {$recipient->getLogin()}!

User {$sender->getLogin()} invites you to join {$SITE_NAME_AS_STRING}

{$SITE_NAME_AS_STRING} is a very-very good thing. You must register there as quick as possible or I'll hit you with a 10-tons brick.

Please, visit {$SITE_NAME_AS_FULL_DOMAIN}


{/capture}

{****************}
{****************   EVENT_INVITE_GROUP   ******************************}
{****************}
{*
@author Ivan Meleshko
*}

{capture name="_from_"}
{$sender->getLogin()} <{$sender->getLogin()}@{$DOMAIN_FOR_EMAIL}>
{/capture}

{capture name="_subject_"}
Message to {$recipient->getLogin()} from {$sender->getLogin()}
{/capture}

{capture name="_pmb_subject_"}
Message to {$recipient->getLogin()} from {$sender->getLogin()}
{/capture}

{capture name="_mail_text_part_"}
Hi, {$recipient->getLogin()}!
This is message from {$sender->getLogin()}.
{/capture}

{capture name="_mail_html_part_"}
Hi, {$recipient->getLogin()}! <br>
This is message from {$sender->getLogin()}.
{/capture}

{capture name="_pmb_part_"}
Hi, {$recipient->getLogin()}!
This is message from {$sender->getLogin()}.
{/capture}

{****************}
{****************   EVENT_INVITE_GUEST   ******************************}
{****************}
{*
@author Ivan Meleshko
*}

{capture name="_from_"}
{$sender->getLogin()} <{$data.from_email}>
{/capture}

{capture name="_subject_"}
Message to {$recipient->getLogin()} from {$sender->getLogin()}
{/capture}

{capture name="_pmb_subject_"}
Message to {$recipient->getLogin()} from {$sender->getLogin()}
{/capture}

{capture name="_mail_text_part_"}
Hello {$recipient->getLogin()}:

{$sender->getLogin()} from {$SITE_NAME_AS_DOMAIN} has invited you to:

{$event->title} 
Date: {$data.next_date}
Time: {$data.next_time}
Details: {$event->notes}

{if $event->invitations_message}
Here is a message from {$sender->getLogin()}:
{if $event->invitations_subject}
{$event->invitations_subject}

{/if}
{$event->invitations_message}
{/if}

If you are already a {$SITE_NAME_AS_STRING} Member, you can RSVP  to the invitation by clicking the link below:

{$sender->getUserPath('calendarviewevent')}id/{$event->id}/

If you are not a {$SITE_NAME_AS_STRING} Member, you may RSVP to the invitation after registering at: {$BASE_URL}/{$LOCALE}/registration/, and then entering
{$sender->getUserPath('calendarviewevent')}id/{$event->id}/ in your browser. 

For additional information, please contact {$sender->getLogin()} directly.  

Thanks,

{$SITE_NAME_AS_STRING} Calendars
------------------------------------------------------
NO SPAM
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_DOMAIN}, please forward the message immediately to feedback@{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING}'s {$BASE_URL}/{$LOCALE}/info/privacy/
If you found this email in your junk/bulk folder, please add {$sender->getEmail()} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox.

DISCLAIMER 
{$SITE_NAME_AS_DOMAIN} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_DOMAIN} terms of service.

HOW TO UNSUBSCRIBE 
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here:

{$recipient->getUserPath('edit')}. 

Then click, Cancel membership and remove profile. 
This is an automated e-mail. Please do not respond.
{/capture}

{capture name="_mail_html_part_"}
Hello {$recipient->getLogin()}:<br>
<br>
{$sender->getLogin()} from {$SITE_NAME_AS_DOMAIN} has invited you to:<br>
<br>
{$event->title} <br>
Date: {$date}<br>
Time: {$time}<br>
Details: {$event->notes}<br>
<br>
{if $event->invitations_message}
Here is a message from {$sender->getLogin()}:<br>
{if $event->invitations_subject}
{$event->invitations_subject}<br>
<br>
{/if}
{$event->invitations_message}<br>
{/if}
<br>
If you are already a {$SITE_NAME_AS_STRING} Member, you can RSVP  to the invitation by clicking the link below: <br>
<br>
<a href="{$sender->getUserPath('calendarviewevent')}id/{$event->id}/">{$sender->getUserPath('calendarviewevent')}id/{$event->id}/</a><br>
<br>
If you are not a {$SITE_NAME_AS_STRING} Member, you may RSVP to the invitation after registering at: <a href="{$BASE_URL}/{$LOCALE}/registration/">{$BASE_URL}/{$LOCALE}/registration/</a>, and then entering <br>
<a href="{$sender->getUserPath('calendarviewevent')}id/{$event->id}/">{$sender->getUserPath('calendarviewevent')}id/{$event->id}/</a> in your browser. <br>
<br>
For additional information, please contact {$sender->getLogin()} directly.  <br>
<br>
Thanks,<br>
<br>
{$SITE_NAME_AS_STRING} Calendars<br>
------------------------------------------------------<br>
NO SPAM<br>
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_DOMAIN}, please forward the message immediately to feedback@{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING}'s <a href="{$BASE_URL}/{$LOCALE}/info/privacy/">{$BASE_URL}/{$LOCALE}/info/privacy/</a><br>
If you found this email in your junk/bulk folder, please add {$sender->getEmail()} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox.<br>
<br>
DISCLAIMER <br>
{$SITE_NAME_AS_DOMAIN} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_DOMAIN} terms of service. <br>
<br>
HOW TO UNSUBSCRIBE <br>
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here: <br>
<br>
<a href="{$recipient->getUserPath('edit')}">{$recipient->getUserPath('edit')}</a>. <br>
<br>
Then click, Cancel membership and remove profile. <br>
This is an automated e-mail. Please do not respond.<br>
{/capture}

{capture name="_pmb_part_"}
Hello {$recipient->getLogin()}:

{$sender->getLogin()} from {$SITE_NAME_AS_DOMAIN} has invited you to:

{$event->title} 
Date: {$date}
Time: {$time}
Details: {$event->notes}

{if $event->invitations_message}
Here is a message from {$sender->getLogin()}:
{if $event->invitations_subject}
{$event->invitations_subject}

{/if}
{$event->invitations_message}
{/if}

If you are already a {$SITE_NAME_AS_STRING} Member, you can RSVP  to the invitation by clicking the link below:

{$sender->getUserPath('calendarviewevent')}id/{$event->id}/

If you are not a {$SITE_NAME_AS_STRING} Member, you may RSVP to the invitation after registering at: {$BASE_URL}/{$LOCALE}/registration/, and then entering
{$sender->getUserPath('calendarviewevent')}id/{$event->id}/ in your browser. 

For additional information, please contact {$sender->getLogin()} directly.  

Thanks,

{$SITE_NAME_AS_STRING} Calendars
------------------------------------------------------
NO SPAM
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_DOMAIN}, please forward the message immediately to feedback@{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING}'s {$BASE_URL}/{$LOCALE}/info/privacy/
If you found this email in your junk/bulk folder, please add {$sender->getEmail()} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox.

DISCLAIMER 
{$SITE_NAME_AS_DOMAIN} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_DOMAIN} terms of service.

HOW TO UNSUBSCRIBE 
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here:

{$recipient->getUserPath('edit')}. 

Then click, Cancel membership and remove profile. 
This is an automated e-mail. Please do not respond.
{/capture}

{****************}
{****************   EVENT_INVITE_USER   ******************************}
{****************}
{*
@author Ivan Meleshko
*}

{capture name="_from_"}
{$sender->getLogin()} <{$data.from_email}>
{/capture}

{capture name="_subject_"}
Message to {$recipient->getLogin()} from {$sender->getLogin()}
{/capture}

{capture name="_pmb_subject_"}
Message to {$recipient->getLogin()} from {$sender->getLogin()}
{/capture}

{capture name="_mail_text_part_"}
Hello {$recipient->getLogin()}:

{$sender->getLogin()} from {$SITE_NAME_AS_DOMAIN} has invited you to:

{$event->title} 
Date: {$data.next_date}
Time: {$data.next_time}
Details: {$event->notes}

{if $event->invitations_message}
Here is a message from {$sender->getLogin()}:
{if $event->invitations_subject}
{$event->invitations_subject}

{/if}
{$event->invitations_message}
{/if}

If you are already a {$SITE_NAME_AS_STRING} Member, you can RSVP  to the invitation by clicking the link below:

{$sender->getUserPath('calendarviewevent')}id/{$event->id}/

If you are not a {$SITE_NAME_AS_STRING} Member, you may RSVP to the invitation after registering at: {$BASE_URL}/{$LOCALE}/registration/, and then entering
{$sender->getUserPath('calendarviewevent')}id/{$event->id}/ in your browser. 

For additional information, please contact {$sender->getLogin()} directly.  

Thanks,

{$SITE_NAME_AS_STRING} Calendars
------------------------------------------------------
NO SPAM
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_DOMAIN}, please forward the message immediately to feedback@{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING}'s {$BASE_URL}/{$LOCALE}/info/privacy/
If you found this email in your junk/bulk folder, please add {$sender->getEmail()} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox.

DISCLAIMER 
{$SITE_NAME_AS_DOMAIN} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_DOMAIN} terms of service.

HOW TO UNSUBSCRIBE 
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here:

{$recipient->getUserPath('edit')}. 

Then click, Cancel membership and remove profile. 
This is an automated e-mail. Please do not respond.
{/capture}

{capture name="_mail_html_part_"}
Hello {$recipient->getLogin()}:<br>
<br>
{$sender->getLogin()} from {$SITE_NAME_AS_DOMAIN} has invited you to:<br>
<br>
{$event->title} <br>
Date: {$date}<br>
Time: {$time}<br>
Details: {$event->notes}<br>
<br>
{if $event->invitations_message}
Here is a message from {$sender->getLogin()}:<br>
{if $event->invitations_subject}
{$event->invitations_subject}<br>
<br>
{/if}
{$event->invitations_message}<br>
{/if}
<br>
If you are already a {$SITE_NAME_AS_STRING} Member, you can RSVP  to the invitation by clicking the link below: <br>
<br>
<a href="{$sender->getUserPath('calendarviewevent')}id/{$event->id}/">{$sender->getUserPath('calendarviewevent')}id/{$event->id}/</a><br>
<br>
If you are not a {$SITE_NAME_AS_STRING} Member, you may RSVP to the invitation after registering at: <a href="{$BASE_URL}/{$LOCALE}/registration/">{$BASE_URL}/{$LOCALE}/registration/</a>, and then entering <br>
<a href="{$sender->getUserPath('calendarviewevent')}id/{$event->id}/">{$sender->getUserPath('calendarviewevent')}id/{$event->id}/</a> in your browser. <br>
<br>
For additional information, please contact {$sender->getLogin()} directly.  <br>
<br>
Thanks,<br>
<br>
{$SITE_NAME_AS_STRING} Calendars<br>
------------------------------------------------------<br>
NO SPAM<br>
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_DOMAIN}, please forward the message immediately to feedback@{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING}'s <a href="{$BASE_URL}/{$LOCALE}/info/privacy/">{$BASE_URL}/{$LOCALE}/info/privacy/</a><br>
If you found this email in your junk/bulk folder, please add {$sender->getEmail()} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox.<br>
<br>
DISCLAIMER <br>
{$SITE_NAME_AS_DOMAIN} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_DOMAIN} terms of service. <br>
<br>
HOW TO UNSUBSCRIBE <br>
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here: <br>
<br>
<a href="{$recipient->getUserPath('edit')}">{$recipient->getUserPath('edit')}</a>. <br>
<br>
Then click, Cancel membership and remove profile. <br>
This is an automated e-mail. Please do not respond.<br>
{/capture}

{capture name="_pmb_part_"}
Hello {$recipient->getLogin()}:

{$sender->getLogin()} from {$SITE_NAME_AS_DOMAIN} has invited you to:

{$event->title} 
Date: {$date}
Time: {$time}
Details: {$event->notes}

{if $event->invitations_message}
Here is a message from {$sender->getLogin()}:
{if $event->invitations_subject}
{$event->invitations_subject}

{/if}
{$event->invitations_message}
{/if}

If you are already a {$SITE_NAME_AS_STRING} Member, you can RSVP  to the invitation by clicking the link below:

{$sender->getUserPath('calendarviewevent')}id/{$event->id}/

If you are not a {$SITE_NAME_AS_STRING} Member, you may RSVP to the invitation after registering at: {$BASE_URL}/{$LOCALE}/registration/, and then entering
{$sender->getUserPath('calendarviewevent')}id/{$event->id}/ in your browser. 

For additional information, please contact {$sender->getLogin()} directly.  

Thanks,

{$SITE_NAME_AS_STRING} Calendars
------------------------------------------------------
NO SPAM
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_DOMAIN}, please forward the message immediately to feedback@{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING}'s {$BASE_URL}/{$LOCALE}/info/privacy/
If you found this email in your junk/bulk folder, please add {$sender->getEmail()} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox.

DISCLAIMER 
{$SITE_NAME_AS_DOMAIN} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_DOMAIN} terms of service.

HOW TO UNSUBSCRIBE 
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here:

{$recipient->getUserPath('edit')}. 

Then click, Cancel membership and remove profile. 
This is an automated e-mail. Please do not respond.
{/capture}

{****************}
{****************   EVENT_REMINDER   ******************************}
{****************}
{*
@author Ivan Meleshko
*}

{capture name="_from_"}
{$sender->getEmail()} <{$sender->getEmail()}>
{/capture}

{capture name="_subject_"}
Message to {$recipient->getLogin()} from {$sender->getLogin()}
{/capture}

{capture name="_pmb_subject_"}
Message to {$recipient->getLogin()} from {$sender->getLogin()}
{/capture}

{capture name="_mail_text_part_"}
Hello {$recipient->getLogin()}:

This is a scheduled event reminder from {$SITE_NAME_AS_STRING}.

{$event->title} 
Date: {$data.next_date}
Time: {$data.next_time}
Details: {$event->notes}

Thanks,

{$SITE_NAME_AS_STRING} Calendars
------------------------------------------------------
NO SPAM
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_STRING}, please forward the message immediately to feedback@{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING} {$BASE_URL}/{$LOCALE}/info/privacy/
If you found this email in your junk/bulk folder, please add {$sender->getEmail()} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox. 

DISCLAIMER 
{$SITE_NAME_AS_STRING} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_STRING} terms of service. 

HOW TO UNSUBSCRIBE 
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here:

{$recipient->getUserPath('edit')}. 

Then click, Cancel membership and remove profile. 
This is an automated e-mail. Please do not respond.
{/capture}

{capture name="_mail_html_part_"}
Hello {$recipient->getLogin()}:<br>
<br>
{$event->title} <br>
Date: {$date}<br>
Time: {$time}<br>
Details: {$event->notes}<br>
<br>
Thanks,<br>
<br>
{$SITE_NAME_AS_STRING} Calendars<br>
------------------------------------------------------<br>
NO SPAM<br>
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_STRING}, please forward the message immediately to feedback@{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING} <a href="{$BASE_URL}/{$LOCALE}/info/privacy/">{$BASE_URL}/{$LOCALE}/info/privacy/</a><br>
If you found this email in your junk/bulk folder, please add {$sender->getEmail()} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox.<br> 
<br>
DISCLAIMER <br>
{$SITE_NAME_AS_STRING} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_STRING} terms of service. <br>
<br>
HOW TO UNSUBSCRIBE <br>
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_STRING} and the {$SITE_NAME_AS_STRING} service as a whole, by clicking click here: <br>
<br>
<a href="{$recipient->getUserPath('edit')}">{$recipient->getUserPath('edit')}</a>. <br>
<br>
Then click, Cancel membership and remove profile. <br>
This is an automated e-mail. Please do not respond.<br>
{/capture}

{capture name="_pmb_part_"}
Hello {$recipient->getLogin()}:

{$event->title} 
Date: {$date}
Time: {$time}
Details: {$event->notes}

Thanks,

{$SITE_NAME_AS_STRING} Calendars
------------------------------------------------------
NO SPAM
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_STRING}, please forward the message immediately to feedback@{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING} {$BASE_URL}/{$LOCALE}/info/privacy/
If you found this email in your junk/bulk folder, please add {$sender->getEmail()} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox. 

DISCLAIMER 
{$SITE_NAME_AS_STRING} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_STRING} terms of service. 

HOW TO UNSUBSCRIBE 
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here:

{$recipient->getUserPath('edit')}. 

Then click, Cancel membership and remove profile. 
This is an automated e-mail. Please do not respond.
{/capture}

{****************}
{****************   USERS_MESSAGE_EXTERNAL   ******************************}
{****************}
{*
@author Vitaly Targonsky
*}

{capture name="_from_"}
{$sender->getEmail()} <{$sender->getEmail()}>
{/capture}

{capture name="_subject_"}
Message to {$recipient->getEmail()} from {$sender->getLogin()}
{/capture}

{capture name="_pmb_subject_"}
Message to {$recipient->getEmail()} from {$sender->getLogin()}
{/capture}

{capture name="_mail_text_part_"}
Hello {$recipient->getEmail()}:

{if $messageOut->message}Here is a message from  {$sender->getLogin()}:
{$messageOut->message}
{/if}

Thanks,

{$SITE_NAME_AS_STRING}
------------------------------------------------------
NO SPAM
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_DOMAIN}, please forward the message immediately to feedback@{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING}'s {$BASE_URL}/{$LOCALE}/info/privacy/
If you found this email in your junk/bulk folder, please add {$sender->getEmail()} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox.

DISCLAIMER 
{$SITE_NAME_AS_DOMAIN} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_DOMAIN} terms of service.

HOW TO UNSUBSCRIBE 
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here:

{$recipient->getUserPath('edit')}. 

Then click, Cancel membership and remove profile. 
This is an automated e-mail. Please do not respond.
{/capture}

{capture name="_mail_html_part_"}
Hello {$recipient->getEmail()}:<br>
<br>
{if $messageOut->message}Here is a message from  {$sender->getLogin()}:<br>
{$messageOut->message}<br>
{/if}
<br>
Thanks,<br>
<br>
{$SITE_NAME_AS_STRING}<br>
------------------------------------------------------<br>
NO SPAM<br>
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_DOMAIN}, please forward the message immediately to feedback@{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING}'s <a href="{$BASE_URL}/{$LOCALE}/info/privacy/">{$BASE_URL}/{$LOCALE}/info/privacy/</a><br>
If you found this email in your junk/bulk folder, please add {$sender->getEmail()} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox.<br>
<br>
DISCLAIMER <br>
{$SITE_NAME_AS_DOMAIN} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_DOMAIN} terms of service. <br>
<br>
HOW TO UNSUBSCRIBE <br>
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here: <br>
<br>
<a href="{$recipient->getUserPath('edit')}">{$recipient->getUserPath('edit')}</a>. <br>
<br>
Then click, Cancel membership and remove profile. <br>
This is an automated e-mail. Please do not respond.<br>
{/capture}

{capture name="_pmb_part_"}
Hello {$recipient->getEmail()}:

Hello {$recipient->getEmail()}:

{if $messageOut->message}Here is a message from  {$sender->getLogin()}:
{$messageOut->message}
{/if}

Thanks,

{$SITE_NAME_AS_STRING}
------------------------------------------------------
NO SPAM
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_DOMAIN}, please forward the message immediately to feedback{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING}'s {$BASE_URL}/{$LOCALE}/info/privacy/
If you found this email in your junk/bulk folder, please add {$sender->getEmail()} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox.

DISCLAIMER 
{$SITE_NAME_AS_DOMAIN} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_DOMAIN} terms of service.

HOW TO UNSUBSCRIBE 
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here:

{$recipient->getUserPath('edit')}. 

Then click, Cancel membership and remove profile. 
This is an automated e-mail. Please do not respond.
{/capture}

{****************}
{****************   USER_REMIND_PASS   ******************************}
{****************}
{*
@author Ivan Meleshko
*}

{capture name="_from_"}
{$sender->getEmail()} <{$sender->getEmail()}>
{/capture}

{capture name="_subject_"}
    Replacement login information for {$recipient->getLogin()} at {$SITE_NAME_AS_DOMAIN}
{/capture}

{capture name="_pmb_subject_"}
    Replacement login information for {$recipient->getLogin()} at {$SITE_NAME_AS_DOMAIN}
{/capture}

{capture name="_mail_text_part_"}
Hello {$recipient->getLogin()}:

Here is your new password for {$SITE_NAME_AS_DOMAIN}.

You may now login to {$BASE_URL}/{$LOCALE}/users/login/ using the following username and password:

username:{$recipient->getLogin()}

password:{$password}


After logging in, you may wish to change your password at {$recipient->getUserPath('settings')}.

Thanks,

{$SITE_NAME_AS_STRING} Groups
-----------------------------------
NO SPAM
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_DOMAIN}, please forward the message immediately to feedback{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING}'s {$BASE_URL}/{$LOCALE}/info/privacy/
If you found this email in your junk/bulk folder, please add {$sender->getEmail()} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox.

DISCLAIMER 
{$SITE_NAME_AS_DOMAIN} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_DOMAIN} terms of service.

HOW TO UNSUBSCRIBE 
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here:

{$recipient->getUserPath('settings')}.

Then click, Cancel membership and remove profile. 
This is an automated e-mail. Please do not respond.
{/capture}

{capture name="_mail_html_part_"}
Hello {$recipient->getLogin()}:<br>
<br>
Here is your new password for {$SITE_NAME_AS_DOMAIN}.<br>
<br>
You may now login to {$BASE_URL}/{$LOCALE}/users/login/ using the following username and password:<br>
<br>
username:{$recipient->getLogin()}<br>
<br>
password:{$password}<br>
<br>
<br>
After logging in, you may wish to change your password at {$recipient->getUserPath('settings')}.<br>
<br>
Thanks,<br>
<br>
{$SITE_NAME_AS_STRING} Groups<br>
-----------------------------------<br>
NO SPAM<br>
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_DOMAIN}, please forward the message immediately to feedback{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING}'s {$BASE_URL}/{$LOCALE}/info/privacy/<br>
If you found this email in your junk/bulk folder, please add {$sender->getEmail()} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox. <br>
<br>
DISCLAIMER <br>
{$SITE_NAME_AS_DOMAIN} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_DOMAIN} terms of service. <br>
<br>
HOW TO UNSUBSCRIBE <br>
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here: <br>
<br>
{$recipient->getUserPath('settings')}. <br>
<br>
Then click, Cancel membership and remove profile. <br>
This is an automated e-mail. Please do not respond.<br>
{/capture}

{capture name="_pmb_part_"}
Hello {$recipient->getLogin()}:

Here is your new password for {$SITE_NAME_AS_DOMAIN}.

You may now login to {$BASE_URL}/{$LOCALE}/user/login/ using the following username and password:

username:{$recipient->getLogin()}

password:{$password}


After logging in, you may wish to change your password at {$recipient->getUserPath('edit')}.

Thanks,

{$SITE_NAME_AS_STRING} Groups
-----------------------------------
NO SPAM
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_DOMAIN}, please forward the message immediately to feedback{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING}'s {$BASE_URL}/{$LOCALE}/info/privacy/
If you found this email in your junk/bulk folder, please add {$sender->getEmail()} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox.

DISCLAIMER 
{$SITE_NAME_AS_DOMAIN} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_DOMAIN} terms of service.

HOW TO UNSUBSCRIBE 
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here:

{$recipient->getUserPath('settings')}.

Then click, Cancel membership and remove profile. 
This is an automated e-mail. Please do not respond.
{/capture}

{****************}
{****************   USER_REGISTER   ******************************}
{****************}
{*
@author Ivan Meleshko
*}
{capture name="_from_"}
"{$SITE_NAME_AS_DOMAIN} Registration" <Zanbyregistration@{$DOMAIN_FOR_EMAIL}>
{/capture}

{capture name="_subject_"}
Confirm your {$SITE_NAME_AS_DOMAIN} registration
{/capture}

{capture name="_pmb_subject_"}
Message to {$recipient->getLogin()} from {$sender->getLogin()}
{/capture}

{capture name="_mail_text_part_"}
Hello {$recipient->getLogin()}:

Thank you for joining {$SITE_NAME_AS_DOMAIN}!

Click this link to confirm your {$SITE_NAME_AS_STRING} Registration and join {$SITE_NAME_AS_STRING} Group: {$BASE_URL}/{$LOCALE}/registration/index/code/{$recipient->getRegisterCode()}/

Thanks, 

The {$SITE_NAME_AS_DOMAIN} Team.
-----------------------------------
NO SPAM
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_STRING}, please forward the message immediately to feedback{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING}'s {$BASE_URL}/{$LOCALE}/info/privacy/
If you found this email in your junk/bulk folder, please add {$sender->getEmail()} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox.

DISCLAIMER 
{$SITE_NAME_AS_DOMAIN} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_DOMAIN} terms of service.

HOW TO UNSUBSCRIBE 
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here:

{$recipient->getUserPath('settings')}. 

Then click, Cancel membership and remove profile. 
This is an automated e-mail. Please do not respond.

{/capture}

{capture name="_mail_html_part_"}
Hello {$recipient->getLogin()}:<br>
<br>
Thank you for joining {$SITE_NAME_AS_DOMAIN}! <br>
<br>
Click this link to confirm your {$SITE_NAME_AS_STRING} Registration and join {$SITE_NAME_AS_STRING} Group: <a href="{$BASE_URL}/{$LOCALE}/confirm/code/{$user->getRegisterCode()}/">{$BASE_URL}/{$LOCALE}/registration/index/code/{$recipient->getRegisterCode()}/</a><br>
<br>
Thanks, <br>
<br>
The {$SITE_NAME_AS_DOMAIN} Team.<br>
-----------------------------------<br>
NO SPAM<br>
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_DOMAIN}, please forward the message immediately to feedback{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING}'s {$BASE_URL}/{$LOCALE}/info/privacy/<br>
If you found this email in your junk/bulk folder, please add {$sender->getEmail()} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox. <br>
<br>
DISCLAIMER <br>
{$SITE_NAME_AS_DOMAIN} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_DOMAIN} terms of service. <br>
<br>
HOW TO UNSUBSCRIBE <br>
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here: <br>
<br>
{$recipient->getUserPath('edit')}. <br>
<br>
Then click, Cancel membership and remove profile. <br>
This is an automated e-mail. Please do not respond.<br>
{/capture}

{capture name="_pmb_part_"}
Hello {$recipient->getLogin()}:

Thank you for joining {$SITE_NAME_AS_DOMAIN}!

Click this link to confirm your {$SITE_NAME_AS_STRING} Registration and join {$SITE_NAME_AS_STRING} Group: {$BASE_URL}/{$LOCALE}/registration/index/code/{$recipient->getRegisterCode()}/

Thanks, 

The {$SITE_NAME_AS_DOMAIN} Team.
-----------------------------------
NO SPAM
If you receive an email that you find offensive or contains advertisements for products or services other than {$SITE_NAME_AS_DOMAIN}, please forward the message immediately to feedback{$DOMAIN_FOR_EMAIL}. Please review {$SITE_NAME_AS_STRING}'s {$BASE_URL}/{$LOCALE}/info/privacy/
If you found this email in your junk/bulk folder, please add {$sender->getEmail()} to ensure that you'll receive all future {$SITE_NAME_AS_STRING} invitations and messages in your Inbox.

DISCLAIMER 
{$SITE_NAME_AS_DOMAIN} does not screen private email between members, nor are we liable for the content of these messages.  All members are bound by the {$SITE_NAME_AS_DOMAIN} terms of service.

HOW TO UNSUBSCRIBE 
If you are a {$SITE_NAME_AS_STRING} member, you may unsubscribe from {$SITE_NAME_AS_DOMAIN} and the {$SITE_NAME_AS_DOMAIN} service as a whole, by clicking click here:

{$recipient->getUserPath('settings')}. 

Then click, Cancel membership and remove profile. 
This is an automated e-mail. Please do not respond.
{/capture}
{****************}
{****************   EVENT_MESSSAGE_TO_GUEST   ******************************}
{****************}
{*
@author Ivan Meleshko
*}
{capture name="_from_"}
    {$sender->getEmail()}
{/capture}

{capture name="_subject_"}
Message to guest
{/capture}

{capture name="_pmb_subject_"}
Message to {$recipient->getLogin()} from {$sender->getLogin()}
{/capture}

{capture name="_mail_text_part_"}
Hello {$recipient->getLogin()}:
{$message}
{/capture}

{capture name="_mail_html_part_"}
Hello {$recipient->getLogin()}:<br>
{$message}
{/capture}

{capture name="_pmb_part_"}
Hello {$recipient->getLogin()}:
{$message}
{/capture}
{****************}
{****************   EVENT_MESSSAGE_TO_REMOVE_GUEST   ******************************}
{****************}
{*
@author Ivan Meleshko
*}
{capture name="_from_"}
    {$sender->getEmail()}
{/capture}

{capture name="_subject_"}
Message to remove guest
{/capture}

{capture name="_pmb_subject_"}
Message to remove {$recipient->getLogin()} from {$sender->getLogin()}
{/capture}

{capture name="_mail_text_part_"}
Hello {$recipient->getLogin()}:
{$message}
{/capture}

{capture name="_mail_html_part_"}
Hello {$recipient->getLogin()}:<br>
{$message}
{/capture}

{capture name="_pmb_part_"}
Hello {$recipient->getLogin()}:
{$message}
{/capture}