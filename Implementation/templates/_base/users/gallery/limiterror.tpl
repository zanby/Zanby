<a href="{$currentUser->getUserPath('photos')}">{t}Back to Photo Galleries{/t}</a>
<h2 class="prInnerTop">{t}Upload Photos{/t}</h2>


<div class="prInnerTop3">	
	<table>
	<tr>
		<td width="{$percent}%" style="background:#93B920" align="center"> 
		{if $percent <50} </td>
		<td width="100%"> {/if}
		&nbsp;{t}{tparam value=$percent}{tparam value=%}%s %s of 20 MB{/t}&nbsp; </td>
		<td width=1px></td>
	</tr>
	</table>
</div>              
<div>
{t}You have exceeded the limit of galleries size{/t}
</div>
<div class="prInnerTop">
{t var='button'}Go back to galleries{/t}
{linkbutton name=$button link=$currentUser->getUserPath('photos')}
</div>
