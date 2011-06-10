<div class="prListContent">	
	<div class="prListContentLeft">
		{if $listsList || $type} 	
		<!-- toggle section begin -->
			{if $type eq 0}
				{foreach item=listsSet name='listsSet' from=$listsList}
				  <div class="prListBox">
				  {assign var="ListTypePrinted" value="0"}
					{foreach item=l name='lists' from=$listsSet}
						{if $ListTypePrinted == 0}
							{view_factory entity="lists" object=$l
								editListLink=$editListLink 
								currentOwner=$currentUser 
								list_Access=$Warecorp_List_AccessManager
								user=$user
								listsList = 0
								ListTypePrinted = 0
							}
							{assign var="ListTypePrinted" value="1"}
						{else}
							{view_factory entity="lists" object=$l
								editListLink=$editListLink 
								currentOwner=$currentUser 
								list_Access=$Warecorp_List_AccessManager
								user=$user
								listsList = 0
								ListTypePrinted = 1
						}
						{/if}
					{/foreach}
				  </div>
				{/foreach}
			{else}
				{if $listsList}
				  <div class="prListBox">
				  {assign var="ListTypePrinted" value="0"}
					{foreach item=l name='lists' from=$listsList}
						{if $ListTypePrinted == 0}
							{view_factory entity="lists" object=$l
								editListLink=$editListLink 
								currentOwner=$currentUser 
								list_Access=$Warecorp_List_AccessManager
								user=$user
								listsList = 0
								ListTypePrinted = 0
							}
							{assign var="ListTypePrinted" value="1"}
						{else}
							{view_factory entity="lists" object=$l
								editListLink=$editListLink 
								currentOwner=$currentUser 
								list_Access=$Warecorp_List_AccessManager
								user=$user
								listsList = 0
								ListTypePrinted = 1
							}
						{/if}
					{/foreach}
				  </div>
				{else}
					<div>{t}No Lists{/t}</div>
				{/if}
			{/if}
		{else}
			<div>{t}No Lists{/t}</div>
		{/if}
	<!-- toggle section end -->                      
    </div> 
    <div class="prListContentRight">
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