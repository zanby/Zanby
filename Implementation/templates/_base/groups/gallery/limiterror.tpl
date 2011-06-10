{if IS_GLOBAL_GROUP}
	<h2 class="prInnerBottom">{t}{tparam value=$currentGroup->getName()}Photos on %s{/t}</h2>
{/if}

<div class="prFloatRight">
	{t var="in_button"}Back to Photo Galleries{/t}
	{linkbutton name=$in_button link=$currentGroup->getGroupPath('photos')}
</div>

<div class="prIndentTopSmall">   	
	<table>
	<tr>
		<td width="{$percent}%" align="center"> {if $percent <50} </td>
		<td width="100%"> {/if}
			&nbsp;{t}{tparam value=$percent}{tparam value=%}%s %s of 20 MB{/t}&nbsp; </td>
		<td width="1"></td>
	</tr>
	</table>
</div>    

<div class="prIndentTopSmall">
	 {t}You have exceeded the limit of galleries size{/t}
</div>
<div class="prInnerTop">
	{t var="in_button_2"}Go back to galleries{/t}
	{linkbutton name=$in_button_2 link=$currentGroup->getGroupPath('photos')}
</div>