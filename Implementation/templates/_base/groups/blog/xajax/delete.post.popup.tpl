{*popup_item*}
<input type="hidden" name="id" id="id" value="{$topic->getId()}">
<p class="prText2 prTCenter">{t}Are you sure you want to delete this post?{/t}</p>
<!-- popup -->
<div class="prInnerTop prTCenter">
 	<a class="prButton" href="#null" onClick="xajax_remove_blog_post({$topic->getId()}, 1); return false;"><span>{t}Remove Post{/t}</span></a>
	<span class="prIndentLeftSmall"><a href="#null" onClick="popup_window.close(); return false;"><span>{t}Cancel{/t}</span></a></span>
</div>
<!-- /popup -->
{*popup_item*}