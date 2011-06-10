{*popup_item*}
<!-- user content -->
<label>{t}{tparam value=$gallery->getTitle()|escape:html}Share %s with friends{/t}</label>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<td><input type="radio" name="shareFriendMode" id="shareFriendMode1" value="1" onClick="{$JsApplication}.shareFriendModeChanged(this.value);" onChange="{$JsApplication}.shareFriendModeChanged(this.value);"/></td>
					<td>{t}Share with all friends{/t}</td>
				</tr>
			</table>
		</td>
		<td>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<td><input type="radio" name="shareFriendMode" id="shareFriendMode2" value="2" onClick="{$JsApplication}.shareFriendModeChanged(this.value);" onChange="{$JsApplication}.shareFriendModeChanged(this.value);" /></td>
					<td>{t}Select friends from list{/t}</td>
				</tr>
			</table>		
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr id="shareFriendMode2AddFields">
					<td valign="top">{t}To :{/t} </td>
					<td>
						<div><a href="#null">{t}Insert address from addressbook{/t}</a></div>
						<div>
							<select name="shareFriendUsers" id="shareFriendUsers" multiple="multiple" size="5" style="width:300px;">
								{foreach from=$friendsList item=friend}
								{if $gallery->isShared($friend->getFriend())}
									<option value="{$friend->getFriend()->getId()}" disabled="disabled">{$friend->getFriend()->getLogin()} - {t}Shared on{/t} {$gallery->getShareDate($friend->getFriend())}</option>
								{elseif $gallery->isWatched($friend->getFriend())}
									<option value="{$friend->getFriend()->getId()}" disabled="disabled">{$friend->getFriend()->getLogin()} - {t}Watching{/t}</option>
								{else}
									<option value="{$friend->getFriend()->getId()}">{$friend->getFriend()->getLogin()}</option>
								{/if}								
								{/foreach}
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>{t}Optional - Write a short message to your friends if you wish{/t}</td>
				</tr>
				<tr>
					<td valign="top">{t}Subject :{/t} </td>
					<td><input type="text" name="shareFriendSubject" id="shareFriendSubject" style="width:300px;" /></td>
				</tr>
				<tr>
					<td valign="top">{t}Message :{/t} </td>
					<td><textarea name="shareFriendMessage" id="shareFriendMessage" style="width:300px;" rows="5"></textarea></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2"></td>
	</tr>
</table>

<!-- popup -->
<div class="co-buttons-pannel-pop">
<div style="margin-left: -83px;">
    <!-- minus half of buttons width to center them -->
    <div class="co-button" onClick="{$JsApplication}.showShareFriendsHandle({$gallery->getId()})" onMouseOver="this.className = 'co-button co-btn-active'" onMouseOut="this.className = 'co-button'"><a href="#null">{t}Share{/t}</a></div>
    <div class="co-button" onClick="{$JsApplication}.hideSharePanel(); return false;" onMouseOver="this.className = 'co-button co-btn-active'" onMouseOut="this.className = 'co-button'"><a href="#null">{t}Cancel{/t}</a></div>
</div>
<!-- /popup -->
<!-- /user content -->
{*popup_item*}