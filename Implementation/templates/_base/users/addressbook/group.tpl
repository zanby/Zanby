<script type="text/javascript" src="/js/checkboxes.js"></script>    
<a href="{$user->getUserPath('addressbook')}">{t}Back to Addressbook{/t}</a>
	<h2>{t}{tparam value=$maillist->getDisplayName()}Address Book - Mailing List: %s{/t}</h2>
	<!-- alf -->
	<div class="prClr2">
		<a href="{$urls.for_filter}">{t}All{/t}</a> -
		{foreach from=$letters item=l}
			{if $l.count}<a href="{$urls.for_filter}filter/{$l.letter}/">{$l.letter}</a> {else}{$l.letter} {/if}
		{/foreach}
	</div>
	<!-- alf end-->  
	<div class="prAddressBookContent">
	<div class='prAddressBookLeft'>
		{if $contacts}
                        <div>
                            <div class="prFloatLeft">{$infoPaging} {$linkPaging}</div>&nbsp;
                        </div>

						<table cellspacing="0" cellpadding="0" class="prResult">
                          <col width="40%" />
                          <col width="18%" />
                          <col width="22%" />
                          <col width="16%" />
                          <thead>
                              <tr>
                                <th>
                                    <a>{t}NAME&nbsp;{/t}(</a><a href="{$urls.for_sort}{$headers.firstname.url}" {if $headers.firstname.active} class="{if $headers.firstname.direction == 'asc'}prArrow2-down{else}prArrow2{/if}"{/if}>{$headers.firstname.output}</a><a>,&nbsp;</a><a href="{$urls.for_sort}{$headers.lastname.url}" {if $headers.lastname.active} class="{if $headers.lastname.direction == 'asc'}prArrow2-down{else}prArrow2{/if}"{/if}>{$headers.lastname.output}</a><a>)</a>
                                </th>
                                <th>
                                  <a href="{$urls.for_sort}{$headers.email.url}" {if $headers.email.active} class="{if $headers.email.direction == 'asc'}prArrow2-down{else}prArrow2{/if}"{/if}>{$headers.email.output}</a>
                                </th>
                                <th><div><a>{$headers.maillists.output}</a></div></th>
                                <th><div><a>{t}Profile{/t}</a></div></th>
                              </tr>
                            </thead>
                            <tbody>
                {foreach item=contact from=$contacts}
                    {assign var="cid" value=$contact->getContactId()}
							<tr>{view_factory without_checkbox=true entity='addressbook' view='default' object=$contact cid=$cid contacts=contacts[] currentUser=$currentUser}
							</tr>
                {foreachelse}
                            <tr>
                                <td colspan="5">
								{if $location == 'addressbook'}
									{t}your addressbook is empty{/t}{else}{t}maillist is empty{/t}
								{/if}
								</td>
                            </tr>
                {/foreach}
                            </tbody>
                        </table>  
						<div class="prFloatLeft">{$infoPaging} {$linkPaging}</div>
           
	    {else}
			<div class="prFormMessage">
				{t}Group doesn't contain members{/t}
			</div>
	    {/if}
		</div>
		<div class='prAddressBookRight'>
			{include file="users/addressbook/mailinglist.tpl"}
			</div>
	    <!-- right column end -->
        
    </div>         