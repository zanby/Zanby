<script type="text/javascript">YAHOO.namespace("example.container");</script> 
       
{tab template="tabs2" active="received"}
      {tabitem link=$currentUser->getUserPath('friends/requests/received') name="received"}{t}Received{/t}{/tabitem}
      {tabitem link=$currentUser->getUserPath('friends/requests/sent') name="sent"}{t}Sent{/t}{/tabitem}
    {/tab}
		<h2>{t}Received Friend Requests{/t}</h2>
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
            {foreach key=d item=f from=$friends name='friends_rec'}
            <tr{if ($smarty.foreach.friends_rec.iteration % 2) == 0} class="prEvenBg"{else} class="prOddBg"{/if}>
                <td class="prVTop">
                    <a href="{$f->getSender()->getUserPath('profile')}"><img src="{$f->getSender()->getAvatar()->getSmall()}" /></a>
                </td>
                <td class="prVTop">
                    <span class="prText2 prEllipsis prReceivedFriend"><a class="ellipsis_init" href="{$f->getSender()->getUserPath('profile')}" title='{$f->getSender()->getLogin()|escape:html}'>{$f->getSender()->getLogin()|escape:html}</a></span>
                    <div>{if !$f->getSender()->getIsBirthdayPrivate()}{$f->getSender()->getAge()} {t}Yr old, {/t}{/if}
                    {if !$f->getSender()->getIsGenderPrivate()} 
                    <span class="prText5">{if $f->getSender()->getGender() eq 'male'}{t}Male{/t}{elseif $f->getSender()->getGender() eq 'female'}{t}Female{/t}{/if}</span>
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
						<a href="#null" onclick="xajax_acceptFriendRequest({$f->getId()}); return false;" title="Accept"><img src="{$AppTheme->images}/decorators/ff-ok.gif" class="prIndentTop" /></a>
						<a href="#null" onclick="xajax_declineFriendRequestConfirm({$f->getId()}); return false;" title="Delete"><img src="{$AppTheme->images}/decorators/ff-close.gif" class="prIndentTop" /></a>
					</div>
                </td>
            </tr>
            {/foreach}
			</tbody>
		</table>
		<div class="prInnerSmallTop">{$paging}</div>
        <div class="prInnerTop prTRight">
			{t var='button_01'}Accept All{/t}
            {linkbutton onclick="xajax_acceptFriendRequest('all'); return false;" name=$button_01} &nbsp;
			{t var='button_02'}Decline All{/t}
            {linkbutton onclick="xajax_declineFriendRequestConfirm('all'); return false;" name=$button_02}
        </div>
    {else}
			<div class="prTCenter prText2">
				{t}There are no requests.{/t}
			</div>

	{/if}   