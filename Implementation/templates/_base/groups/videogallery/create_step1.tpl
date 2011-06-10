<a href="{$currentGroup->getGroupPath('videos')}">{t}Back to Video Collections{/t}</a>
    <h2 class="prInnerTop">{t}Upload Videos{/t}</h2>
    <p class="prInnerTop">{t}Choose a Collection or create a new collection{/t}</p>
	{if $galleries}
    	<p class="prInnerTop">{t}Choose an existing Collection in which you would like to place your videos:{/t}</p>
		<div class="prInnerSmallTop">
		 	<select class="" name="gallery" id="gallery" OnChange="document.location='/{$LOCALE}/videogallerycreate/step/2/gallery/'+this.value;">
				<option>--{t}Select{/t}--</option>
				{foreach item=g key=key from=$galleries}
					<option value="{$key}">{$g|escape:"html"}</option>
				{/foreach}
			</select>
		</div>
		<p class="prInnerTop">{t}OR{/t}</p>
	{/if}                    
   	<div class="prInnerTop">
		{t var="in_button"}Create New Collection{/t}
		{linkbutton name=$in_button link="/$LOCALE/videogallerycreate/step/2/"}
	</div>
