<!---BUUU--->
<a href="{$currentUser->getUserPath('videos')}">{t}Back to Video Collections{/t}</a>
<h2 class="prInnerTop">{t}Upload Videos{/t}</h2>
<p class="prInnerTop">{t}Choose a Collection or create a new collection{/t}</p>
{if $galleries}
<p class="prInnerTop">{t}Choose an existing Collection in which you would like to place your video:{/t}</p>
<div class="prInnerSmallTop">
	<select name="gallery" id="gallery" onchange="document.location='/{$LOCALE}/videogallerycreate/step/2/gallery/'+this.value+'/';">
		  <option>--{t}Select{/t}--</option>
		  {foreach item=g key=key from=$galleries}
		  <option value="{$key}">{$g|escape:"html"}</option>
		  {/foreach}
	 </select>
</div>
<p class="prInnerTop">{t}OR{/t}</p>
{/if}
<div class="prInnerTop">
{t var="in_botton"}Create New Collection{/t}           
	 {linkbutton name=$in_botton link="/$LOCALE/videogallerycreate/step/2/"}
</div>