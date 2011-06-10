<!-- init menu -->
<script type="text/javascript">
	initMessagesMenu('prMessages-menu');
</script>
<!-- /init menu -->
{if isset($message)}
	{assign var="sender" value=$message->getSender()}
	{$linkPaging}
	<div class="prClr3">
		<div class="prFloatRight">
			{if $previous}
			&laquo; <a href='{$currentUser->getUserPath("messageview/order/`$order`/id/`$previous`")}'>{t}Prev{/t}</a></li>
			{else}
			&laquo; {t}Prev{/t}
			{/if}
			&nbsp;|&nbsp;
			{if $next}
			<a href='{$currentUser->getUserPath("messageview/order/`$order`/id/`$next`")}'>{t}Next{/t}</a> &raquo;
			{else}
			{t}Next{/t} &raquo;
			{/if}
			&nbsp;&nbsp;<a href="{$currentUser->getUserPath('messagelist')}folder/{$folder}/order/{$order}/">{t}{tparam value=$folder|capitalize}Back to %s{/t}</a>
		</div>
	</div>
	
		<!-- result begin -->
		
		<div class="prMessagesButtonBlock">
			{if $canReply && $sender->getId() neq ""}
				{t var='button_01'}Reply{/t}
                {assign var="reply_link" value=$currentUser->getUserPath("messagecompose/load/reply/id")}
				{linkbutton name=$button_01 link=""|cat:$reply_link|cat:$message->getId()|cat:"/"}&nbsp;
			{/if}
			{t var='button_02'}Forward{/t}
            {assign var="forward_link" value=$currentUser->getUserPath("messagecompose/load/forward/id")}
			{linkbutton name=$button_02 link=""|cat:$forward_link|cat:$message->getId()|cat:"/"}&nbsp;
			{if !$message->getIsRequest()}
				{t var='button_03'}Delete{/t}
				{linkbutton name=$button_03 onclick="xajax_deleteMessage('"|cat:$message->getId()|cat:"', false, getMouseCoordinateX(event), getMouseCoordinateY(event)); return false;"}
			{/if}
		</div>
		<!-- message text begin -->
		
			<div class="prIndentTop">
				<div class="prClr3">
					<span class="prText4">{t}From:{/t}</span> 
					<span class="prIndentLeft">
                        {if $sender->getId() eq ""}
                            Event guest
                        {else}
                            {$sender->getSenderDisplayName()|escape:html}
                        {/if}
                    </span>
				</div>
				<div class="prClr3">
					<span class="prText4">{t}To:{/t}</span>
					<span class="prIndentLeft">{$message->getRecipientsStringName()|escape:"html"}</span>
				</div>
				<div class="prClr3 prIndentTop">
					<span class="prText4">{t}Subject:{/t}</span>
					<span class="prIndentLeft">{$message->getSubject()|wordwrap:20:" ":true|escape:"html"}</span>
				</div>
				<div class="prClr3">
					<span class="prText4">{t}Date:{/t}</span>
					<span class="prIndentLeft">{$messageCreateDate}</span>
				</div>
			</div>
				<div class="prIndentTop prGrayBorder prInner">{$message->getBody()}</div>
			
		<!-- message text end -->
		<div class="prMessagesButtonBlock">
			{if $canReply && $sender->getId() neq ""}
				{t var='button_04'}Reply{/t}
                {assign var="reply_link" value=$currentUser->getUserPath("messagecompose/load/reply/id")}
				{linkbutton name=$button_04 link=""|cat:$reply_link|cat:$message->getId()|cat:"/"}&nbsp;
			{/if}
			{t var='button_05'}Forward{/t}
            {assign var="forward_link" value=$currentUser->getUserPath("messagecompose/load/forward/id")}
			{linkbutton name=$button_05 link=""|cat:$forward_link|cat:$message->getId()|cat:"/"}&nbsp;
			{if !$message->getIsRequest()}
				{t var='button_06'}Delete{/t}
				{linkbutton name=$button_06 onclick="xajax_deleteMessage('"|cat:$message->getId()|cat:"', false, getMouseCoordinateX(event), getMouseCoordinateY(event)); return false;"}
			{/if}
		</div>
		
		<!-- result end -->
	
{/if}
