<div id="record_rank_{$record->getId()}" class="prMarkRequired">
{if $thanks}
	<span>{t}Thanks for ranking!{/t}</span><br />
{/if}
	<div class="prClr3">
		<ul class="ranking" style="width: 75px; float: left;">
			<li class="current-rank" style="width:{math equation="x * y" x=$record->getRank() y=15 format="%.2f"}px;"></li>
			<li><a href="#" class="star1" onclick="xajax_list_view_rank_record({$record->getId()}, 1); return false;"></a></li>
			<li><a href="#" class="star2" onclick="xajax_list_view_rank_record({$record->getId()}, 2); return false;"></a></li>
			<li><a href="#" class="star3" onclick="xajax_list_view_rank_record({$record->getId()}, 3); return false;"></a></li>
			<li><a href="#" class="star4" onclick="xajax_list_view_rank_record({$record->getId()}, 4); return false;"></a></li>
			<li><a href="#" class="star5" onclick="xajax_list_view_rank_record({$record->getId()}, 5); return false;"></a></li>
		</ul>
		<div class="prFloatLeft prInnerLeft">
        {if $record->getRankCnt()!=1}{t}{tparam value=$record->getRankCnt()} %s votes{/t}
        {else}
        	{t}{tparam value=$record->getRankCnt()} %s vote{/t}
		{/if}
        </div>
    </div>
</div>