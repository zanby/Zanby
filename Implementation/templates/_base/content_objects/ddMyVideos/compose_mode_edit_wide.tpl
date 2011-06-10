{include file="content_objects/edit_mode_settings_wide.tpl"}

{include file="content_objects/headline_block_wide.tpl"}


<div class="prCO-subsettings">

    <div class="prCO-subsetting-slot prCO-subsetting-slot-last">
    <select id="show_type_select_{$cloneId}" name="show_type" onchange="showAsIconsChange('{$cloneId}',this.selectedIndex); return false;" style="width:159px">
   		<option value="0" {if !$gallery_show_as_icons} selected="selected" {/if}>{t}Display as Galleries{/t}</option>
   		<option value="1" {if $gallery_show_as_icons} selected="selected" {/if}>{t}Display as Thumbnails{/t}</option>
    </select>
    <select id="video_type_select_{$cloneId}" name="video_type" onchange="videoTypeSelectChange('{$cloneId}'); return false;" style="width:159px">
   		<option value="video_man" {if $gallery_type == 0} selected="selected" {/if}>{t}Manually Select Galleries{/t}</option>
   		<option value="video_time" {if $gallery_type == 1} selected="selected" {/if}>{t}Timed Gallery Selection{/t}</option>
    </select>
    
    <div id="ddMyVideos_thumbnails_count_{$cloneId}" style="display:{if !$gallery_show_as_icons}none{else}block{/if};">
    <label>{t}Set the number of videos you wish to display:{/t}</label><br />
    <select id="gallery_show_thumbnails_count_{$cloneId}" onchange="galleryShowThumbnailsCountChange('{$cloneId}');" name="gallery_show_thumbnails_count" style="width:159px" >
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
</div>


<div class="prCO-section">
    <!-- content section headline -->
    <h3 class="prCO-section-headline">
        <span><a href="#null" onclick="addDDMyVideos('{$cloneId}');return false;">{t}Add gallery{/t}</a></span>
    </h3>
    <!-- /content section headline -->
</div>

{include file="content_objects/ddMyVideos/light_block_wide.tpl"}

{include file="content_objects/edit_mode_buttons.tpl"}
