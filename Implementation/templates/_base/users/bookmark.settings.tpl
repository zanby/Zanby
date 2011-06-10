<div class="prInnerTop">
	<p><strong>{t}{tparam value=$SITE_NAME_AS_STRING}Select  bookmark services for use in %s:{/t}</strong></p>
	<!-- form begin -->
	{form from=$form}
		<table class="prForm">
			<col width="33%" />
			<col width="33%" />
			<col width="33s%" />
			<tbody>
				<tr>
				{foreach item=b name='bookmark' key=key from=$bookmarkServicesList}
					{assign var=curr value=$b->getId()}
					{if $smarty.foreach.bookmark.iteration % 10 == 1}
						<td>
					{/if}
					<!-- -->
					<div{if $smarty.foreach.bookmark.iteration % 10 != 1} class="prIndentTopSmall"{/if}>
						{form_checkbox name="bservice_"|cat:$b->getId() checked=$userBookmarkServicesList[$curr]|default:"0" value=$userBookmarkServicesList[$curr]}
						<label for="bservice_{$b->getId()}"> <img src="{$AppTheme->images}/decorators/{$b->getIconPath()}" class="prLabelIcon" alt="{$b->getName()}" /> {$b->getName()}</label>
					</div>
					<!-- / -->
					{if $smarty.foreach.bookmark.iteration % 10 == 0}
						</td>
					{/if}
				{/foreach}
				{if $smarty.foreach.bookmark.iteration % 10 != 0}
						</td>
				{/if}
				</tr>
				<tr>
					<td>
					{t var='button'}Save Settings{/t}
					{form_submit name="form_bookmark" value=$button }</td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	{/form}
	<!-- form end -->
{if $showMessage}
{literal}
	<script type="text/javascript">
		MainApplication.init();
		MainApplication.showAjaxAlert({width:250, height: 50, timeout: 1500, content: 'Saved'});
	</script>
{/literal}
{/if}	
