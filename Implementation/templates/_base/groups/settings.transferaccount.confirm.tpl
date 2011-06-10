<div class="prTCenter">
    <div class="prInnerTop">
        <b>We have sent a message to {$objUser->getLogin()|escape} asking to replace you as the Owner of of {$currentGroup->getName()|escape}</b>
    </div>

    <div class="prInnerTop">
        A copy of your message can be viewed in the sent messages folder 
    </div>

    <div class="prInnerTop"><a href="{$CurrentGroup->getGroupPath('settings')}">{t}Back to Group Family Tools{/t}</a></div>
                            
    <div class="prInnerTop">
        If {$objUser->getLogin()|escape} accepts the invitation, you will no longer be able to access this account. 
    </div>

    <div class="prInnerTop">{t}{tparam value=$BASE_URL}{tparam value=$LOCALE}If you have a special question or suggestion, please email <a href="%s/%s/info/contactus/">Contact Us</a>{/t}</div>
</div>