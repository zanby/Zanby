{t}{tparam value=$list->getCreationDate()|user_date_format:$user->getTimezone()|date_locale:'DATETIME_SHORT'}{tparam value=$TIMEZONE}<span>Posted</span>
%s %s 
<span>by</span>{/t}<br />
<a href="{$list->getCreator()->getUserPath('profile')}">{$list->getCreator()->getLogin()|escape}</a>

{if ($list->getOwnerType() =='user') && ($user->getId() == $list->getOwnerId())}
	<div class="prInnerTop">{t}My List{/t}</div>
{else}
	<div id="listUpdateBlock" class="prInnerTop">
		{include file="users/lists/lists.view.update.tpl"}
	</div>
{/if}


{if $Warecorp_List_AccessManager->canShareList($list, $currentUser, $user)}
	<div class="prInnerTop prClr">
	 <a href="#" onclick="xajax_list_share_popup_show({$list->getId()}, getMouseCoordinateX(event), getMouseCoordinateY(event)); return false;">{t}Share this List{/t}</a>
	</div>
{/if}
	
{if $Warecorp_List_AccessManager->canManageList($list, $currentUser, $user)}
	<div class="prInnerSmallTop"><a href="{$editListLink}listid/{$list->getId()}/">{t}Edit{/t}</a>
	</div>
{/if}
<div class="prInnerSmallTop">
	<a href="{$currentUser->getUserPath('listsexport')}id/{$list->getId()}/">{t}Export to CSV{/t}</a>
</div>

<div class="prInnerTop">
	{if $user->getId()}
		{if $list->getAdding()}
			<p class="prInnerTop"><a href="#add_form">{t}+ Add Item to list{/t}</a>
			</p>
		{/if}
	{else}		
		{$list->getListTypeName()}
	{/if}
</div>