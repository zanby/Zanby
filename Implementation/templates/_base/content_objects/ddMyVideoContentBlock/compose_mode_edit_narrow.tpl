{include file="content_objects/headline_block_narrow.tpl"}
<input type="hidden" id="single_video_type_select_{$cloneId}" value="0" />

<div class="prInner"><div><a href="#" onclick="xajax_ddMyVideoContentBlock_select_avatar('{$cloneId}');return false;">{t}Select Video{/t}</a> &#187;</div></div>


<div class="prTCenter">
       
        <img width="183" src="{$video->getCover()->setWidth(183)->getImage()}" alt="{$video->getTitle()|escape:html}" title="{$video->getTitle()|escape:html}" />
</div>

{*
<div class="themeA">
	<div id="tinyMCE_{$cloneId}_div_wait" style="display:block;" align="center"><img style="padding:5px;" src="{$IMG_URL}/ddpages/waiting.gif" alt=""/></div>
	<div id="tinyMCE_{$cloneId}_div" style="display:none; width: 155px;"><form  name="tinyMCEform_{$cloneId}" id="tinyMCEform_{$cloneId}" method="post" action=""><textarea id="tinyMCE_{$cloneId}" name="tinyMCE_{$cloneId}" rows="15" cols="80" style="width: 155px;">{$Content|escape:html}</textarea></form></div></div>
 *}
{include file="content_objects/edit_mode_buttons.tpl"}
