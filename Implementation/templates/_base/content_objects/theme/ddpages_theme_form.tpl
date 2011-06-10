<style type="text/css" media="all">@import "/css/ddpages.css";</style>
<table width="715px" border=0>
	<tr>
        <td align="right" colspan="2">
        {t var=""}Save Edits{/t}{linkbutton name=$in_button color="orange" link="#" onclick = "xajax_ddpages_save_theme_css(document.getElementById('css_text').value,`$entity_id`); return false;"}
        </td>
    </tr>
	<tr>
        <td width="170" valign="top">
            {t}Personalize your theme{/t} <br /><br />
			<a href="#" id="bg_color_tab" onclick="return select_left_tab(this.id);" class="ddpages_theme_left_tab_active">{t}Background Color{/t}</a>
			<a href="#" id="bg_image_tab" onclick="return select_left_tab(this.id);" class="ddpages_theme_left_tab">{t}Background Image{/t}</a>
			<a href="#" id="nav_bar_tab" onclick="return select_left_tab(this.id);" class="ddpages_theme_left_tab">{t}Masthead and Nav Bar{/t}</a>
			<a href="#" id="edit_css_tab" onclick="return false;" class="ddpages_theme_left_tab_active" style="display:none; visibility:hidden;" >{t}Edit CSS{/t}</a>
        </td>
        <td valign="top">
		    
			{*<ul class="tabs primary">
                <li class="active" id="edit_theme_tab"><a href="#" onclick="return select_left_tab('edit_theme_tab');">{t}Edit Theme{/t}</a></li>
                <li id="css_tab"><a href="#" onclick="return select_left_tab('css_tab');"> {t}CSS{/t} </a></li>
            </ul>*}
			
			{*<div id="edit_theme_tab">
			{tab  template="tabs1" active="et"}
				{tabitem link="#" onclick="return select_left_tab('edit_theme_tab');" name="et"}{t}Edit Theme{/t}{/tabitem}
				{tabitem link="#" onclick="return select_left_tab('css_tab');" name="css"} {t}CSS{/t} {/tabitem}
		    {/tab}
			</div>
			<div id="css_tab" style="display:none;">
			{tab  template="tabs1" active="css"}
				{tabitem link="#" onclick="return select_left_tab('edit_theme_tab');" name="et"}{t}Edit Theme{/t}{/tabitem}
				{tabitem link="#" onclick="return select_left_tab('css_tab');" name="css"} {t}CSS{/t} {/tabitem}
		    {/tab}
			</div>*}
			
			<div id="edit_theme_tab">
			<a href="#" onclick="return select_left_tab('edit_theme_tab');"><strong>{t}Edit Theme{/t}</strong></a>
			<a href="#" onclick="return select_left_tab('css_tab');">{t}CSS{/t}</a>
		   
			</div>
			<div id="css_tab" style="display:none;">
			<a href="#" onclick="return select_left_tab('edit_theme_tab');">{t}Edit Theme{/t}</a>
				<a href="#" onclick="return select_left_tab('css_tab');"><strong>{t}CSS{/t}</strong></a>
		    
			</div>
			
            <table border="0" cellpadding="5" cellspacing="0" width="100%" id="edit_theme_content">
               <tr><td colspan="2">
                 <h5 id="bg_color_title">{t}Select your Background Color{/t}</h5>
                 <h5 id="bg_image_title" style="display:none; visibility:hidden;">{t}Place background image{/t}</h5>
                 <h5 id="nav_bar_title" style="display:none; visibility:hidden;">{t}Customize your nav bar{/t}</h5>
                 </td>
               </tr>
               <tr>
                  <td valign="top" width="150">
                     <div id="bg_color_column">
                         <strong>{t}Click to select{/t}</strong><br /><br />
                         {include file='content_objects/theme/ddpages_palette.tpl' attributes="onclick='set_hex_code(this.title);'"}<br />
                         <label for="hex_code"><strong>{t}Enter Hex Code{/t}</strong></label><br />
                         <input type="text" name="edit[hex_code]" id="hex_code" value="" style="width:120px;" maxlength="7"><br /><br />
                         {t var="in_hex"}Apply Hex Code{/t}{linkbutton name=$in_hex color="orange" link="#" onclick="apply_hex_code();return false;"}
                     </div>
                     <div id="bg_image_column" style="display:none; visibility:hidden; text-align:justify;">
                         <strong>{t}Chose a photo{/t}</strong><br /><br />
                         {t}Note: I MB limit.  Please choose carefully to maximize legibility. We recommend a .gif or .png file format.{/t} <br /><br />
                         {t}Your background image must be drawn from one of your personal galleries.{/t} <br /><br />
                         
                         {t var="in_image"}Place Image{/t}{linkbutton name=$in_image color="orange" link="#" onclick="xajax_select_image(getMouseCoordinateX(event), getMouseCoordinateY(event),false,'theme_form');return false;"} <br /><br />
                         <div id="bg_image_form" style="display:none; visibility:hidden;">
                            <form name="bg_img_form" onsubmit="return false;">
                            <img id="bg_image_src" src="{$AppTheme->images}/decorators/px.gif" border="0"><br /><br />
                            <strong>{t}Display:{/t} </strong><br />
                            <input type="radio" name="bg_image_repeat" onclick="change_repeat_option('repeat');" id="repeat_option"> <label for="repeat_option">{t}Repeat{/t}</label> <br />
                            <input type="radio" name="bg_image_repeat" onclick="change_repeat_option('repeat-x');"  id="repeat_option_x"> <label for="repeat_option_x">{t}X axis (Horizontal){/t}</label> <br />
                            <input type="radio" name="bg_image_repeat" onclick="change_repeat_option('repeat-y');"  id="repeat_option_y"> <label for="repeat_option_y">{t}Y axis (Vertical){/t}</label> <br />
                            </form>
                         </div>
                     </div>
                     <div id="nav_bar_column" style="display:none; visibility:hidden;">
			            <a href="#" onclick="xajax_ddpages_color_picker(getMouseCoordinateX(event), getMouseCoordinateY(event), 'title_bar' ,'Edit color of Title Bar'); return false;" class="ddpages_theme_left_tab_active">{t}Title Bar  Color{/t}</a> <!---->
			            <a href="#" onclick="xajax_ddpages_color_picker(getMouseCoordinateX(event), getMouseCoordinateY(event), 'title_bar_text', 'Edit color of Title Bar Text'); return false;" class="ddpages_theme_left_tab_active">{t}Title  Bar Text Color{/t}</a>
			            <a href="#" onclick="xajax_ddpages_color_picker(getMouseCoordinateX(event), getMouseCoordinateY(event), 'nav_text', 'Edit color of Nav Text'); return false;" class="ddpages_theme_left_tab_active">{t}Nav Text Color{/t}</a>
                     </div>
                  </td>
                  <td valign="top">
                     <strong>{t}Your Layout{/t}</strong><br /><br />                     
                     <div id="layout_content" style="width:380px; height:{if $layout_content_height}{math equation='height+45' height=$layout_content_height}{else}285{/if}px; border:solid 1px #00CC00; visibility:hidden;">
                     <div id="nav_title_bar" style="width:100%; height:15px; border-bottom:solid 1px #00CC00; background-color:#597B40; font-size:9px; color:#FFFFFF; padding-top:3px;">&nbsp;&nbsp;&nbsp;{$title|upper}</div>
                       <div id="nav_text" style="width:100%; height:15px; font-size:9px; color:#003399; padding:3px 0px 3px 0px;">&nbsp;&nbsp;&nbsp;
                        
						{foreach name=links item=link from=$links}
                            <a href="{$link.link}" class="nav_text_a" style="color:#003399;">{$link.title}</a>{if !$smarty.foreach.links.last} | {/if}
                        {/foreach}
						
                      </div>                                                                                                
                      <div style="position:relative;" >{$layout_content}</div>
                     </div>
                     <div id="bgimage_bottom" style="display:none; visibility:hidden;">

                       <div id="clear_bgimage_button" align="right" style="display:none; visibility:hidden;"><br />{t var="in_bgimage"}Clear Background Image{/t}{linkbutton name=$in_bgimage color='orange' link="#" onclick = 'clear_bgimage();return false;'}</div>
                     </div>
                     <div id="nav_bottom" style="display:none; visibility:hidden; text-align:right;">

                       <div align="right"><br />{t var="in_formatting"}Clear formatting{/t}{linkbutton name=$in_formatting color='orange' link="#" onclick='clear_nav_format();return false;'}</div>
                     </div>
                  </td>
               </tr>
            </table>
            <div id="edit_css_content" style="display:none; visibility:hidden;">
                <h5 id="edit_css_title">{t}Edit CSS{/t}</h5>
                    <textarea name="css_text" id="css_text" style="width:100%" rows="20">{$css_text}</textarea>
            </div>
        </td>
    </tr>
	<tr>
        <td align="right" colspan="2"><br>
        {t var="in_edits"}Save Edits{/t}{linkbutton name=$in_edits color="orange" link="#" onclick = "xajax_ddpages_save_theme_css(document.getElementById('css_text').value,`$entity_id`); return false;"}
        </td>
    </tr>
</table>
<script src = "/js/content_objects/theme_form.js"></script>
<script>
set_css();
</script>