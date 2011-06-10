<div class="themeA" id="light_{$cloneId}">

{if !$list_display_type}
{foreach from=$listsCategories item=item key=key}
{if $displayCategories[$key]}
			<div class="prInnerTop">
				<div class="prClr">
					<h3 class="prFloatLeft">{$item|escape:'html'}</h3>
					<div class="prHeaderTools"><span>
							<a class="prCOHeaderClose" onclick="list_categories_check_change('{$cloneId}',{$key}, 0);" href="#null" title="remove section">&nbsp;</a>
					</span></div>
				</div>
        {foreach from=$listsList->getListsListByTypeSorted($key, $list_default_index_sort) item=list name=ll}
        {if $smarty.foreach.ll.iteration <= $list_display_number_in_each_category}
						<div class="prInner">
							<h4>
								<a href="{$list->getListPath()}">{$list->getTitle()|escape:'html'}</a>&nbsp;&nbsp;
								{$list->getRecordsCount()} {t}items{/t}
							</h4>
							<p>{$list->getCreationDate()|user_date_format:$user->getTimezone()|date_locale:'DATETIME'} {$TIMEZONE}</p>
                {if $list_show_summaries} 
								<p class="prInnerSmall">
									{$list->getDescription()|escape:'html'}&nbsp;&nbsp;<a href="{$list->getListPath()}">{t}More{/t}&nbsp;&raquo;</a>
								</p>
                {/if}
						</div>
        {/if}
        {foreachelse}
					<div class="prInner">{t}Empty list{/t}</div>
        {/foreach}
    </div>
{/if}
{/foreach}
{elseif $currentList}
	<div class="prInner">
		<h3>{$currentList->getTitle()|escape:html}</h3>
		<p>{$currentList->getDescription()}</p>
        {if $currentList->getRecordsCount() > 1}
		<p> {t}There are{/t} {$currentList->getRecordsCount()} {t}items in this list{/t}</p>
        {else}
        <p> {t}There is{/t} {$currentList->getRecordsCount()} {t}item in this list{/t}</p>
        {/if}
		<ol class="prListOutside">
        {foreach from=$currentList->getRecordsList($aSort[$list_default_sort]) item=item key=key name=cl}
        {if $smarty.foreach.cl.iteration <= $list_display_number_in_each_category}
				<li class="prInnerTop">
					<h4 class="prInline"><a href="{$currentList->getListPath()}">{$item->getTitle()|escape:html}</a></h4>
					<p><span class="prText4">by {$item->getCreator()->getLogin()}</span></p>
            {if $list_show_summaries && $item->getEntry()}
						<p class="prInnerSmall">{$item->getEntry()|escape:'html'} <a href="{$currentList->getListPath()}">{t}More{/t}&nbsp;&raquo;</a></p>
            {/if}
            	{if $currentList->getRanking()}
						<div class="prInnerSmall">
							<ul class="prCO-Ranking">
	                            <li style="width:{math equation="x * y" x=$item->getRank() y=15 format="%.2f"}px;">&nbsp;</li>
                    </ul>
							<a href="{$currentList->getListPath()}">{$item->getCommentsCount()}&nbsp;{t}comments{/t}</a>
                </div>
                {/if}
				</li>
        {/if}	
        {/foreach}
		</ol>
</div>
{/if}

<div class="prInnerTop">
  <a class="prLink2" href="{$currentUser->getUserPath('lists')}">{t}Browse all lists{/t} &raquo;</a>
</div>

</div>
