{*popup_item*}
{if $gallery_hash}
	<label for="gallery_select" class="prTBold">{t}Gallery Names{/t}</label>
	<div>
			<select name="gallery_select" id="gallery_select" onchange="xajax_ddMyPhotos_load_gallery(this.value); return false;" class="prLargeFormItem">
			{foreach from=$gallery_hash item=gallery}
				<option value={$gallery->getId()}>{$gallery->getTitle()|escape:'html'}</option>
			{/foreach}      
			</select>
	 </div>
	 <div class="prTCenter">
	 <div class="prText2 prInnerTop">{t}Preview{/t}</div>
			<i id="image_preview_title">{if $thumbs_hash}{$thumbs_hash[0]->getTitle()|escape:'html'}{$thumbs_hash[0]}{$preview_title|escape:'html'}{/if}</i></div>
	<div class="prCOCentrino prIndent">
	
		<img id="image_preview" src="{$image_preview}" name="{$preview_nid}"/>
	
	</div>
	
	<input type="hidden" id = "div_id" name="div_id" value="{$div_id}">
	<div class="prCOCentrino">
		<span class="prIndentLeftSmall">{t var="in_button"}Ok{/t}{linkbutton name=$in_button color="orange" link="#" onclick="$onclickattr"}</span>
		 <span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a></span>
	</div>   
{else}
	<p>{t}You have no public galleries{/t}</p>       
{/if}
{*popup_item*}