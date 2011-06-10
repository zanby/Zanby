<a href="{$currentGroup->getGroupPath('photos')}">{t}Back to Photo Galleries{/t}</a>
<h2 class="prInnerTop">{t}Upload Photos{/t}</h2>         
<p class="prInnerTop">{t}Choose a Gallery or create a new gallery{/t}</p>	
{if $galleries}
	<p class="prInnerTop">{t}Choose an existing Gallery in which you would like to place your photos:{/t}</p>
	<div class="prInnerTop prInnerRight prFloatLeft">
	<select name="gallery" id="gallery" OnChange="document.location='{$currentGroup->getGroupPath('gallerycreate')}step/2/gallery/'+this.value;">
			<option>--{t}Select{/t}--</option>
			{foreach item=g key=key from=$galleries}
				<option value="{$key}">{$g|escape:"html"}</option>
			{/foreach}
	</select>
	</div>
	<span class="prInnerTop prFloatLeft">{t}or{/t}</span>
{/if}
<div class="prInnerSmallTop prInnerLeft prFloatLeft"> 
	{t var="in_button"}Create New Gallery{/t} 
    {assign var='createLink' value=$currentGroup->getGroupPath('gallerycreate')|cat:'step/2/'}
	{linkbutton name=$in_button link=$createLink}
</div>