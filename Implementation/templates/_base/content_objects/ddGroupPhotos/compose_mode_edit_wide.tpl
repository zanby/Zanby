{include file="content_objects/edit_mode_settings_wide.tpl"}
{include file="content_objects/headline_block_wide.tpl"}
<div class="prIndent">
    <select id="show_type_select_{$cloneId}" name="show_type" onchange="showAsIconsChange('{$cloneId}',this.selectedIndex); return false;">
        <option value="0" {if !$gallery_show_as_icons} selected="selected" {/if}>{t}Display as Galleries{/t}</option>
        <option value="1" {if $gallery_show_as_icons} selected="selected" {/if}>{t}Display as Thumbnails{/t}</option>
    </select>
    <select id="photo_type_select_{$cloneId}" name="photo_type" onchange="photoTypeSelectChange('{$cloneId}'); return false;">
        <option value="photo_man" {if $gallery_type == 0} selected="selected" {/if}>{t}Manually Select Galleries{/t}</option>
        <option value="photo_time" {if $gallery_type == 1} selected="selected" {/if}>{t}Timed Gallery Selection{/t}</option>
    </select>
    <div id="ddMyPhotos_thumbnails_count_{$cloneId}" style="display:{if !$gallery_show_as_icons}none{else}block{/if};">
        <strong>{t}Set the number of photos you wish to display:{/t}</strong><br />
        <select id="gallery_show_thumbnails_count_{$cloneId}" onchange="galleryShowThumbnailsCountChange('{$cloneId}');" name="gallery_show_thumbnails_count"  >
            <option value="1" {if $gallery_show_thumbnails_count==1}selected="selected"{/if}>{t}Show 1 thumbnail{/t}</option>
            <option value="2" {if $gallery_show_thumbnails_count==2}selected="selected"{/if}>{t}Show 2 thumbnails{/t}</option>
            <option value="3" {if $gallery_show_thumbnails_count==3}selected="selected"{/if}>{t}Show 3 thumbnails{/t}</option>
            <option value="4" {if $gallery_show_thumbnails_count==4}selected="selected"{/if}>{t}Show 4 thumbnails{/t}</option>
            <option value="5" {if $gallery_show_thumbnails_count==5}selected="selected"{/if}>{t}Show 5 thumbnails{/t}</option>
            <option value="6" {if $gallery_show_thumbnails_count==6}selected="selected"{/if}>{t}Show 6 thumbnails{/t}</option>
            <option value="7" {if $gallery_show_thumbnails_count==7}selected="selected"{/if}>{t}Show 7 thumbnails{/t}</option>
            <option value="8" {if $gallery_show_thumbnails_count==8}selected="selected"{/if}>{t}Show 8 thumbnails{/t}</option>
            <option value="9" {if $gallery_show_thumbnails_count==9}selected="selected"{/if}>{t}Show 9 thumbnails{/t}</option>
            <option value="10" {if $gallery_show_thumbnails_count==10}selected="selected"{/if}>{t}Show 10 thumbnails{/t}</option>
            <option value="20" {if $gallery_show_thumbnails_count==20}selected="selected"{/if}>{t}Show 20 thumbnails{/t}</option>
            <option value="30" {if $gallery_show_thumbnails_count==30}selected="selected"{/if}>{t}Show 30 thumbnails{/t}</option>
            <option value="40" {if $gallery_show_thumbnails_count==40}selected="selected"{/if}>{t}Show 40 thumbnails{/t}</option>
            <option value="50" {if $gallery_show_thumbnails_count==50}selected="selected"{/if}>{t}Show 50 thumbnails{/t}</option>
        </select>
    </div>
</div>
<div class="prIndent">
    <h3 class="prFloatLeft">
        <a href="#null" onclick="addDDMyPhotos('{$cloneId}');return false;">{t}Add gallery{/t}</a>
    </h3>
</div>
<div class="prClearer"></div>

<div class="themeA" id="light_{$cloneId}">
    {include file="content_objects/ddGroupPhotos/light_block_wide.tpl"}
</div>

{include file="content_objects/edit_mode_buttons.tpl"} 