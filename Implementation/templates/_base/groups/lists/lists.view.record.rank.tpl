{*popup_item*} {*probably AJAX*}
<div id="record_rank_{$record->getId()}">
{if $thanks}<span>{t}Thanks for ranking!{/t}</span><br />{/if}
<div class="prClr3 prIndentTop">
	<ul class="ranking prFloatLeft">
		<li style="width:{math equation="x * y" x=$record->getRank() y=15 format="%.2f"}px;"></li>
		
        {if $Warecorp_List_AccessManager->canRankRecord($record, $CurrentGroup, $user)}
            <li><a href="#" class="star1" onclick="xajax_list_view_rank_record({$record->getId()}, 1); return false;"></a></li>
            <li><a href="#" class="star2" onclick="xajax_list_view_rank_record({$record->getId()}, 2); return false;"></a></li>
            <li><a href="#" class="star3" onclick="xajax_list_view_rank_record({$record->getId()}, 3); return false;"></a></li>
            <li><a href="#" class="star4" onclick="xajax_list_view_rank_record({$record->getId()}, 4); return false;"></a></li>
            <li><a href="#" class="star5" onclick="xajax_list_view_rank_record({$record->getId()}, 5); return false;"></a></li>
        {else}
            <li class="star1"></a></li>
            <li class="star2"></a></li>
            <li class="star3"></a></li>
            <li class="star4"></a></li>
            <li class="star5"></a></li>
        {/if}
	</ul>
	<div class="prFloatLeft prInnerLeft">
		{if $record->getRankCnt()!=1}
			{t}{tparam value=$record->getRankCnt()}%s votes{/t}
		{else}
			{t}{tparam value=$record->getRankCnt()}%s vote{/t}
		{/if}
	</div>
</div>
</div>
{*popup_item*}