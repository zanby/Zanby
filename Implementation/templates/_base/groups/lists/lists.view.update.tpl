{if $lastImportData && $lastTargetList}
{if $lastImportData.import_type=='merge'}
	{t}You merged this list with{/t}
	<div class="prIndentTopSmall">
	[<a href="{$lastTargetList->getListPath()}">{$lastTargetList->getTitle()|escape|wordwrap:15:"\n":true}</a>]
	{t}{tparam value=$lastImportData.import_date|user_date_format:$user->getTimezone()|date_locale:'DATETIME_SHORT'}{tparam value=$TIMEZONE}on %s %s{/t}
	</div>
	<div class="prIndentTopSmall">
	{if $lastImportData.import_date>$list->getUpdateDate()}
		{t}It has not changed since you added it.{/t}
	{else}
		{t}It has changed since you added it.{/t}
	{/if}
	</div>
	<div class="prIndentTopSmall">
	<a href="#" onclick="xajax_list_add_popup_show({$list->getId()}, getMouseCoordinateX(event), getMouseCoordinateY(event)); return false;">{t}Update{/t}</a>
	</div>
{elseif $lastImportData.import_type=='new'}
	{t}You saved this list as{/t} [<a href="{$lastTargetList->getListPath()}">{$lastTargetList->getTitle()|escape|wordwrap:15:"\n":true}</a>]
	{t}{tparam value=$lastImportData.import_date|user_date_format:$user->getTimezone()|date_locale:'DATETIME_SHORT'}{tparam value=$TIMEZONE}on %s %s{/t}
	<div class="prIndentTopSmall">
	{if $lastImportData.import_date>$list->getUpdateDate()}
		{t}It has not changed since you saved it.{/t}
	{else}
		{t}It has changed since you saved it.{/t}
	{/if}
	</div>
	<div class="prIndentTopSmall">
	<a href="#" onclick="xajax_list_add_popup_show({$list->getId()}, getMouseCoordinateX(event), getMouseCoordinateY(event)); return false;">{t}Update{/t}</a>
	</div>
{elseif $lastImportData.import_type=='watch'}
	{t}You are watching this list!{/t}
	<div class="prIndentTopSmall">
		<a href="#" onclick="xajax_list_add_popup_show({$list->getId()}, getMouseCoordinateX(event), getMouseCoordinateY(event)); return false;">{t}Update{/t}</a>
	</div>
{/if}
{else}
{if $Warecorp_List_AccessManager->canAddToMyLists($user)}
	<div class="prClr">
		<a href="#" onclick="xajax_list_add_popup_show({$list->getId()}, getMouseCoordinateX(event), getMouseCoordinateY(event)); return false;">{t}Add To My Lists{/t}</a>
	</div>
{/if}
{/if}