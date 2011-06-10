
{tab template="tabs2" active="received" exception=1}
  {tabitem link=$currentUser->getUserPath('friends/requests/received') name="received"}{t}Received{/t}{/tabitem}
  {tabitem link=$currentUser->getUserPath('friends/requests/sent') name="sent"}{t}Sent{/t}{/tabitem}
{/tab}
<div>{if $prev} &laquo; <a href="{$currentUser->getUserPath('friends/request')}{$prev}/">{t}Previous{/t}</a>{/if} 
	{if $prev && !$next}
	{elseif !$prev && $next}
	{elseif !$prev && !$next}
	{else} | {/if}
	{if $next} <a href="{$currentUser->getUserPath('friends/request')}{$next}/">{t}Next{/t}</a> &raquo;{/if} 
	<a href="{$currentUser->getUserPath('friends/requests/received')}">{t}Back to Requests List{/t}</a> </div>
	<h2>{t}Friend Request{/t}</h2>
	<!-- frame-dd -->
	<div class="prDropBoxInner prClr3">
<!-- -->
		<div  class="prClr3">
			<div class="prFloatRight">
				<div class="prFloatLeft">
					<a href="{$request->getSender()->getUserPath('profile')}"><img class="prIndentRightSmall"src="{$request->getSender()->getAvatar()->getSmall()}"/></a>
				</div>
				<div class="prFloatLeft">
					<a href="{$request->getSender()->getUserPath('profile')}">{$request->getSender()->getLogin()}</a><br />
					{if !$request->getSender()->getIsBirthdayPrivate()}{$request->getSender()->getAge()} {t}Yr old{/t}{/if}{if $request->getSender()->getGender() eq 'male'},{t} Male{/t}{elseif $request->getSender()->getGender() eq 'female'},{t} Female{/t}{/if}<br />
					{$request->getSender()->getCity()->name|escape:"html"}, {$request->getSender()->getState()->name|escape:"html"}, {$request->getSender()->getCountry()->name|escape:"html"}
				</div>
			</div>
				 <div class="prText2">{$request->getSender()->getLogin()} {t} requested to be your friend on{/t}</div>
				 {$request->getRequestDate()|date_locale:'DATETIME_FULL'}
		</div>
		<div class="prInnerTop">
			{$request->getSender()->getLogin()}{t}’s Note:{/t}</div>
			<div  class="prText2">{$request->getMessage()->getBody()|nl2br}</div>
<!-- -->
	</div>
	<!-- frame-dd -->
	<div class="prTRight prInnerTop">
	{t var='button_01'}Accept{/t}
	  {linkbutton onclick="xajax_acceptFriendRequest("|cat:$request->getId()|cat:"); return false;" name=$button_01}
	  {t var='button_02'}Decline{/t}
	  {linkbutton onclick="xajax_declineFriendRequest("|cat:$request->getId()|cat:"); return false;" name=$button_02}
	</div>