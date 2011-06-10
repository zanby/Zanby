<div class="freeClass">
		{if $currentUser->getId() == $user->getId()}
			 <h3>
				{if $frNums > 1 || $frNums == 0}
					{if $frrNums != 1}
						{t}
						{tparam value=$frNums}
						{tparam value=$frrNums}
						{tparam value=$user->getUserPath('friends/requests/received')}
						You have <span class="prMarkRequired">%s</span> friends and <span class="prMarkRequired">%s</span> <a href="%s">Friend Requests</a>
						{/t}
					{else}
						{t}
						{tparam value=$frNums}
						{tparam value=$frrNums}
						{tparam value=$user->getUserPath('friends/requests/received')}
						You have <span class="prMarkRequired">%s</span> friends and <span class="prMarkRequired">%s</span> <a href="%s">Friend Request</a>
						{/t}
					{/if}
				{else}
					{if $frrNums != 1}
						{t}
						{tparam value=$frNums}
						{tparam value=$frrNums}
						{tparam value=$user->getUserPath('friends/requests/received')}
						You have <span class="prMarkRequired">%s</span> friend and <span class="prMarkRequired">%s</span> <a href="%s">Friend Requests</a>
						{/t}
					{else}
						{t}
						{tparam value=$frNums}
						{tparam value=$frrNums}
						{tparam value=$user->getUserPath('friends/requests/received')}
						You have <span class="prMarkRequired">%s</span> friend and <span class="prMarkRequired">%s</span> <a href="%s">Friend Request</a>
						{/t}
					{/if}    
				{/if}
			</h3>
		{else}
			<h3>
			{if $frNums > 1 || $frNums == 0}
				{t}
				{tparam value=$currentUser->getLogin()|escape:"html"}
				{tparam value=$frNums}
				%s has <span class="prMarkRequired">%s</span> friends
				{/t}
			{else}
				{t}
				{tparam value=$currentUser->getLogin()|escape:"html"}
				{tparam value=$frNums}
				%s has <span class="prMarkRequired">%s</span> friend
				{/t}
			{/if}       
			</h3>
		{/if}
</div>
    {if $friends}
		{$paging}
					
			<table cellspacing="0" cellpadding="0" class="prResult">
			  <col width="35%" />
			  <col width="45%" />
			  <col width="25%" />
			  <thead>
			  <tr>
				<th><div {if $order == 'name'} class="prRActive{if $direction == 'asc'} prRActive-top{else} prRActive-bottom{/if}"{/if}><a href="{$_url}order/name/direction/{if $order==name && $direction=='asc'}desc{else}asc{/if}/page/1/">{t}Name{/t}</a></div></th>
				<th><div {if $order=='location'} class="prRActive{if $direction == 'asc'} prRActive-top{else} prRActive-bottom{/if}"{/if}><a href="{$_url}order/location/direction/{if $order==location && $direction=='asc'}desc{else}asc{/if}/page/1/">{t}Location{/t}</a> </div></th>
				<th>&#160;</th>
			  </tr>
			  </thead>
			  <tbody>
			   {foreach key=d item=f from=$friends name='friends_list'}
				   {if $f->getFriend()->getStatus() == 'active'}
				  <tr{if ($smarty.foreach.friends_list.iteration % 2) == 0} class="prEvenBg"{else} class="prOddBg"{/if}>
					<td>
					  <img class="prFloatLeft" src="{$f->getFriend()->getAvatar()->getSmall()}" border="0" /> 
					  <div class="prFloatLeft">
						  <span class="prEllipsis prFriendsName">
							  <a class="ellipsis_init" title="{$f->getFriend()->getLogin()|escape:html}" class="prLink2" href="{$f->getFriend()->getUserPath('profile')}">{$f->getFriend()->getLogin()|escape:html}</a></span>
							  <div class="prText5">{if !$f->getFriend()->getIsBirthdayPrivate()}{$f->getFriend()->getAge()} {t}Yr old {/t}{/if}{if $f->getFriend()->getGender() eq 'male'}{t}Male{/t}{elseif $f->getFriend()->getGender() eq 'female'}{t}Female{/t}{/if}</div></div></td>
							  <td><span title="{$f->getFriend()->getCity()->name|escape:"html"}, {$f->getFriend()->getState()->name|escape:"html"}, {$f->getFriend()->getCountry()->name|escape:"html"}" class="prEllipsis prFriendsLocation"><span class="ellipsis_init">{$f->getFriend()->getCity()->name|escape:"html"}, {$f->getFriend()->getState()->name|escape:"html"}, {$f->getFriend()->getCountry()->name|escape:"html"}</span></span>
					  
					</td>
					<td>
						
						  <div><a class="prLink3" href="#null" onclick="xajax_sendMessage({$f->getFriend()->getId()}); return false;">{t}Send a message{/t}</a></div>
						  <div><a class="prLink3" href="{$f->getFriend()->getUserPath('friends')}">{t}View Friends{/t}</a></div>
						  {if $currentUser->getId() == $user->getId()}
						      <div><a class="prLink3" href="#null" onclick="xajax_deleteFriend({$f->getFriend()->getId()}, getMouseCoordinateX(event), getMouseCoordinateY(event)); return false;">{t}Delete Friend{/t}</a></div>
						  {/if}
					   
					</td>
				  </tr>
				  {/if}
			  {/foreach}
			</tbody>
			</table>
		{$paging}
    {/if}                
