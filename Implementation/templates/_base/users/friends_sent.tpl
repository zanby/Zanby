<script type="text/javascript">YAHOO.namespace("example.container");</script> 

    {tab template="tabs2" active="sent"}
      {tabitem link=$currentUser->getUserPath('friends/requests/received') name="received"}{t}Received{/t}{/tabitem}
      {tabitem link=$currentUser->getUserPath('friends/requests/sent') name="sent"}{t}Sent{/t}{/tabitem}
    {/tab}
		<h2>{t}Sent Friend Requests{/t}</h2>

	{if $friends}
			<div class="prInnerSmallTop">{$paging}</div>
		<table cellspacing="0" cellpadding="0" class="prResult">
            <col width="55" />
            <col width="200" />
            <col />
            <col width="60" />
            <thead><tr>
                <th>{t}Name{/t}</th>
                <th>{t}Date / Message{/t}</th>
                <th></th>
				<th></th>
	        </tr></thead>
			<tbody>
            {foreach key=d item=f from=$friends name='friends_sent'}
            <tr{if ($smarty.foreach.friends_sent.iteration % 2) == 0} class="prEvenBg"{else} class="prOddBg"{/if}>
                <td class="prVTop">
                    <a href="{$f->getRecipient()->getUserPath('profile')}"><img src="{$f->getRecipient()->getAvatar()->getSmall()}" /></a>
                </td>
                <td class="prVTop">
                    <span class="prText2 prEllipsis prReceivedFriend"><a class="ellipsis_init" href="{$f->getRecipient()->getUserPath('profile')}" title='{$f->getRecipient()->getLogin()|escape:html}'>{$f->getRecipient()->getLogin()|escape:html}</a></span>
                    <div>{if !$f->getRecipient()->getIsBirthdayPrivate()}{$f->getRecipient()->getAge()} {t}Yr old, {/t}{/if}
                    {if !$f->getRecipient()->getIsGenderPrivate()}
                    <span class="prText5">{if $f->getRecipient()->getGender() eq 'male'}{t}Male{/t}{elseif $f->getRecipient()->getGender() eq 'female'}{t}Female{/t}{/if}</span>
                    {/if}
                    </div>
                    <span class="prEllipsis prReceivedFriend" title='{$f->getSender()->getCity()->name|escape:"html"}, {$f->getSender()->getState()->name|escape:"html"}, {$f->getSender()->getCountry()->name|escape:"html"}'><span class="ellipsis_init">{$f->getSender()->getCity()->name|escape:"html"}, {$f->getSender()->getState()->name|escape:"html"}, {$f->getSender()->getCountry()->name|escape:"html"}</span></span>
                </td>
                <td class="prVTop">
                    {$f->getRequestDate()|date_locale}<br />
                        {if $f->getMessageId()}
                            <div id="mess_{$f->getMessageId()}">
                                <a href="{$currentUser->getUserPath('friends/request')}{$f->getId()}/">{$f->getMessage()->getBody()|wordwrap:50}</a>
                                <script>YAHOO.example.container.mess_{$f->getMessageId()}X = new YAHOO.widget.Tooltip("mess_{$f->getMessageId()}X", {$smarty.ldelim}hidedelay:100, context:"mess_{$f->getMessageId()}",width:200, text:"{$f->getMessage()->getBody()|escape:javascript}"{$smarty.rdelim});</script>
					        </div>
                        {/if}  
                </td>
                <td class="prVTop">
					<div class="prInnerLeft">
						<a href="#null" onclick="xajax_declineFriendRequestConfirm({$f->getId()}, 'sent'); return false;" title="Delete"><img src="{$AppTheme->images}/decorators/ff-close.gif" /></a>
					</div>
                </td>
            </tr>
            {/foreach}
			</tbody>
        </table>
        <!-- table end-->
		<div class="prInnerSmallTop">
			<div class="prInnerSmallTop">{$paging}</div>
		</div>
        <div class="prInnerTop prTRight">
			{t var='button_01'}Delete All{/t}
            {linkbutton onclick="xajax_declineFriendRequestConfirm('all', 'sent'); return false;" name=$button_01}
        </div>
	{else}
		<div>{t}There are no requests.{/t}</div>
    {/if}
