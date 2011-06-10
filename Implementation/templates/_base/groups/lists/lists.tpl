<div class="prListContent prClr3">	
	<div class="prListContentLeft prClr3">
		{if $listsList || $type}
			{assign var="SomethingPresent" value="0"}
			{foreach item=listsSet name='listsSet' from=$listsList}
				{assign var="ListTypePrinted" value="0"}
				{if $listsSet.plain}
				<div class="prListBox">
					{foreach item=l name='lists' from=$listsSet.plain}
						{assign var="SomethingPresent" value="1"}
						{if $ListTypePrinted == 0}
							{view_factory entity="lists" object=$l
								editListLink=$editListLink 
								currentOwner=$currentGroup 
								list_Access=$Warecorp_List_AccessManager
								user=$user
								listsList = 0
								ListTypePrinted = 0
							}
							{assign var="ListTypePrinted" value="1"}
						{else}
							{view_factory entity="lists" object=$l
								editListLink=$editListLink 
								currentOwner=$currentGroup 
								list_Access=$Warecorp_List_AccessManager
								user=$user
								listsList = 0
								ListTypePrinted = 1
						}
						{/if}
					{/foreach}
					</div>
				{/if}
			{/foreach}
			{if $SomethingPresent == 0}
				<div><p>{t}No Lists{/t}</p></div>
			{/if}
		{else}
			<div><p>{t}No Lists{/t}</p></div>
		{/if}
	</div>
	<div class="prListContentRight prClr3">
		<h3>{t}All List tags:{/t}</h3>
		{if $listsTags}
			<ul>		           
			{foreach item=t from=$listsTags}
				<li class="prIndentTopSmall">
				<a href="{$BASE_URL}/{$LOCALE}/search/lists/new/1/keywords/{$t.name|escape:html}/">({$t.count}) {$t.name|escape}</a>
				</li>		                
			{/foreach}
			</ul>
		{else}
			{t}No Tags{/t}
		{/if}
	</div>      
</div>