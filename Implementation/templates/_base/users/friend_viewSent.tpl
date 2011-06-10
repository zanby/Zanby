{tab template="tabs2" active="sent"}
  {tabitem link=$currentUser->getUserPath('friends/requests/received') name="received"}{t}Received{/t}{/tabitem}
  {tabitem link=$currentUser->getUserPath('friends/requests/sent') name="sent"}{t}Sent{/t}{/tabitem}
{/tab}
<div>{if $prev} &laquo; <a href="{$currentUser->getUserPath('friends/request')}{$prev}/">{t}Previous{/t}</a>{/if} 
	{if $prev && !$next}
	{elseif !$prev && $next}
	{elseif !$prev && !$next}
	{else} | {/if}
	{if $next} <a href="{$currentUser->getUserPath('friends/request')}{$next}/">{t}Next{/t}</a> &raquo;{/if} <a href="{$currentUser->getUserPath('friends/requests/received')}">{t}Back to Requests List{/t}</a> </div>
	<h2>{t}Sent Friend Request{/t}</h2>
	<div class="justify"></div>
	<!-- frame-dd -->
	<div class="prDropBoxInner prClr3">
	<div class="prClr3">
			<div class="prFloatRight">
			<div class="prFloatLeft"> <a href="{$request->getRecipient()->getUserPath('profile')}"><img class="prIndentRightSmall" src="{$request->getRecipient()->getAvatar()->getSmall()}"/></a> </div>
			<div class="prFloatLeft"> <a href="{$request->getRecipient()->getUserPath('profile')}">{$request->getRecipient()->getLogin()}</a><br />
					{if !$request->getRecipient()->getIsBirthdayPrivate()}{$request->getRecipient()->getAge()} {t}Yr old{/t}{/if}{if $request->getRecipient()->getGender() eq 'male'}, {t} Male{/t}{elseif $request->getRecipient()->getGender() eq 'female'} , {t} Female{/t}{/if}<br />
					{$request->getRecipient()->getCity()->name|escape:"html"}, {$request->getRecipient()->getState()->name|escape:"html"}, {$request->getRecipient()->getCountry()->name|escape:"html"} </div>
		</div>
			<div class="prText2">{$request->getRequestDate()|date_locale:'DATETIME_FULL'}</div>
			<div>{t} {tparam value=$request->getRecipient()->getLogin()}You sent %s a friend request{/t}</div>
		</div>
	<div class="prIndentTop">{t}Your Note:{/t}</div>
	<div class="prText2">{$request->getMessage()->getBody()|nl2br}</div>
</div>
	<!-- frame-dd -->
	<div class="prTRight prInnerTop">
	{t var='button_01'}Delete{/t}
	{linkbutton onclick="xajax_declineFriendRequest("|cat:$request->getId()|cat:", 'sent'); return false;" name=$button_01} </div>
