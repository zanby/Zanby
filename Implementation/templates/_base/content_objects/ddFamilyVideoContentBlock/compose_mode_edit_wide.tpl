{*<select id="single_video_type_select_{$cloneId}" name="single_video_type" onchange="singleVideoTypeSelectChange('{$cloneId}'); return false;">
   		<option value="single_video_man" {if $gallery_type == 0} selected="selected" {/if}>{t}Manual Selection{/t}</option>
   		<option value="single_video_time" {if $gallery_type == 1} selected="selected" {/if}>{t}Other Selection{/t}</option>
</select>
*}

<input type="hidden" name="single_video_type" value="single_video_man" />

<div class="prIndent">
    <a href="#" class="prLink2" onclick="xajax_ddFamilyVideoContentBlock_select_avatar('{$cloneId}');return false;">{t}Select Video{/t} &#187;</a>
</div>

<div class="themeA">
    <div class="prInnerTop prCOCentrino">
        <img width="400" src="{$video->getCover()->setWidth(400)->getImage()}" alt="{$video->getTitle()|escape:html}" title="{$video->getTitle()|escape:html}" />
    </div>
</div>

{*include file="content_objects/headline_block_wide.tpl"}

<div id="tinyMCE_{$cloneId}_div_wait" style="display:block;" align="center"><img class="prInner" src="{$AppTheme->images}/decorators/waiting.gif" alt=""/></div>

<div id="tinyMCE_{$cloneId}_div" style="display:none; width: 407px;"><form  name="tinyMCEform_{$cloneId}" id="tinyMCEform_{$cloneId}" method="post" action=""><textarea id="tinyMCE_{$cloneId}" name="tinyMCE_{$cloneId}" rows="16" cols="80" style=" line-height:0px; font-size:0px; width: 405px;">{$Content|escape:html}</textarea></form></div></div>
*}

{include file="content_objects/edit_mode_buttons.tpl"}
