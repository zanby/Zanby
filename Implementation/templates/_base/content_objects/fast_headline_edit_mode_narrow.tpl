    
    <div class="prCO-editpanel" style="border:9px solid #f1f1f1;">
    
		<ul class="prCO-editpanel-toolbox">
			<li class="prWithoutInnerLeft">
    			<select id="{$cloneId}_fastHeadline_fontNameSelect" onchange="ddFastHeadline_change_font_family('{$cloneId}', this.options[this.selectedIndex].value);" onkeyup="ddFastHeadline_change_font_family('{$cloneId}', this.options[this.selectedIndex].value);" style="width:60px;">
                    <option value="0" {if !$font_family}selected="selected"{/if}>{t}Default{/t}</option>
                    
                    <option value="helvetica,arial,sans-serif" {if $font_family=="helvetica,arial,sans-serif"}selected="selected"{/if}>{t}Arial{/t}</option>
                    <option value="tahoma,arial,sans-serif" {if $font_family=="tahoma,arial,sans-serif"}selected="selected"{/if}>{t}Tahoma{/t}</option>
                    <option value="verdana,arial,sans-serif" {if $font_family=="verdana,arial,sans-serif"}selected="selected"{/if}>{t}Verdana{/t}</option>
                    <option value="lucida console,arial" {if $font_family=="lucida console,arial"}selected="selected"{/if}>{t}Lucida Console{/t}</option>
                    
                    
                    {*<option value="andale mono,times" {if $font_family=="andale mono,times"}selected="selected"{/if}>{t}Andale Mono{/t}</option>
                    <option value="arial,helvetica,sans-serif" {if $font_family=="arial,helvetica,sans-serif"}selected="selected"{/if}>{t}Arial{/t}</option>
                    <option value="arial black,avant garde" {if $font_family=="arial black,avant garde"}selected="selected"{/if}>{t}Arial Black{/t}</option>
                    <option value="book antiqua,palatino" {if $font_family=="book antiqua,palatino"}selected="selected"{/if}>{t}Book Antiqua{/t}</option>
                    <option value="comic sans ms,sand" {if $font_family=="comic sans ms,sand"}selected="selected"{/if}>{t}Comic Sans MS{/t}</option>
                    <option value="courier new,courier" {if $font_family=="courier new,courier"}selected="selected"{/if}>{t}Courier New{/t}</option>
                    <option value="georgia,palatino" {if $font_family=="georgia,palatino"}selected="selected"{/if}>{t}Georgia{/t}</option>
                    <option value="helvetica" {if $font_family=="helvetica"}selected="selected"{/if}>{t}Helvetica{/t}</option>
                    <option value="impact,chicago" {if $font_family=="impact,chicago"}selected="selected"{/if}>{t}Impact{/t}</option>
                    <option value="symbol" {if $font_family=="symbol"}selected="selected"{/if}>{t}Symbol{/t}</option>
                    <option value="tahoma,arial,helvetica,sans-serif" {if $font_family=="tahoma,arial,helvetica,sans-serif"}selected="selected"{/if}>{t}Tahoma{/t}</option>
                    <option value="terminal,monaco" {if $font_family=="terminal,monaco"}selected="selected"{/if}>{t}Terminal{/t}</option>
                    <option value="times new roman,times" {if $font_family=="times new roman,times"}selected="selected"{/if}>{t}Times New Roman{/t}</option>
                    <option value="trebuchet ms,geneva" {if $font_family=="trebuchet ms,geneva"}selected="selected"{/if}>{t}Trebuchet MS{/t}</option>
                    <option value="verdana,geneva" {if $font_family=="verdana,geneva"}selected="selected"{/if}>{t}Verdana{/t}</option>
                    <option value="webdings" {if $font_family=="webdings"}selected="selected"{/if}>{t}Webdings{/t}</option>
                    <option value="wingdings,zapf dingbats" {if $font_family=="wingdings,zapf dingbats"}selected="selected"{/if}>{t}Wingdings{/t}</option>*}
                </select>
            </li>
            <li>
                <select id="{$cloneId}_fastHeadline_fontSizeSelect" onchange="ddFastHeadline_change_font_size('{$cloneId}', this.options[this.selectedIndex].value);" onkeyup="ddFastHeadline_change_font_size('{$cloneId}', this.options[this.selectedIndex].value);" style="width:40px;">
                    <option value="0" {if !$font_size}selected="selected"{/if}>{t}Default{/t}</option>
                    <option value="8" {if $font_size=="8"}selected="selected"{/if}>8</option>
                    <option value="10" {if $font_size=="10"}selected="selected"{/if}>10</option>
                    <option value="12" {if $font_size=="12"}selected="selected"{/if}>12</option>
                    <option value="14" {if $font_size=="14"}selected="selected"{/if}>14</option>
                    <option value="18" {if $font_size=="18"}selected="selected"{/if}>18</option>
                    <option value="24" {if $font_size=="24"}selected="selected"{/if}>24</option>
                    <option value="36" {if $font_size=="36"}selected="selected"{/if}>36</option>
                </select>
            </li>
			<li>
            	<a id="ddFastHeadline_indicator_bold_{$cloneId}" href="#null" onclick="ddFastHeadline_change_weight_bold('{$cloneId}');return false;" class="mceButtonNormal" style="background:url({$AppTheme->images}/decorators/co/co-ep-bold.gif) no-repeat 50% center"></a>
            </li>
			<li>
            	<a id="ddFastHeadline_indicator_italic_{$cloneId}" href="#null" onclick="ddFastHeadline_change_style_italic('{$cloneId}');return false;" class="mceButtonNormal" style="background:url({$AppTheme->images}/decorators/co/co-ep-italic.gif) no-repeat 50% center"></a>
            </li>
		
			<li>
            	<a id="ddFastHeadline_decoration_underline_{$cloneId}" href="#null" onclick="ddFastHeadline_change_decoration_underline('{$cloneId}');return false;" class="mceButtonNormal" style="background:url({$AppTheme->images}/decorators/co/co-ep-underline.gif) no-repeat 50% center"></a>
            </li>
		
			<li>
            	<a id="ddFastHeadline_change_text_align_left_{$cloneId}" href="#null" onclick="ddFastHeadline_change_text_align('{$cloneId}', 'left');return false;" class="mceButtonNormal" style="background:url({$AppTheme->images}/decorators/co/co-ep-left.gif) no-repeat 50% center"></a>
            </li>
			<li>
            	<a id="ddFastHeadline_change_text_align_center_{$cloneId}" href="#null" onclick="ddFastHeadline_change_text_align('{$cloneId}', 'center');return false;"class="mceButtonNormal" style="background:url({$AppTheme->images}/decorators/co/co-ep-center.gif) no-repeat 50% center"></a>
            </li>
			<li>
            	<a id="ddFastHeadline_change_text_align_right_{$cloneId}" href="#null" onclick="ddFastHeadline_change_text_align('{$cloneId}', 'right');return false;" class="mceButtonNormal" style="background:url({$AppTheme->images}/decorators/co/co-ep-right.gif) no-repeat 50% center"></a>
            </li>
			<style type="text/css">
 	           #fff{$cloneId}:hover {$smarty.ldelim}
					border:1px solid #F1F1F1;
				{$smarty.rdelim}
            </style>
			<li style="width:32px; background:none;">
            	<a href="#null" style="background: none;" id="fff{$cloneId}" onclick="var _tmpColor='{$color}'; showAKColorPickerMCEStyle('{$cloneId}CP3', _tmpColor, getElementPosition(this)[0], getElementPosition(this)[1] + 30, 'ddFastHeadline_change_color(\'{$cloneId}\', \'#\')');"><div id="{$cloneId}CP3_indicator" style="position:absolute; width: 17px; height: 4px; margin-top: 13px; font-size:0px;  margin-left:2px; background-color:{$color}"></div><span onmouseover="this.style.border='1px solid #0A246A'" onmouseout="this.style.border='1px solid #F1F1F1'" style="display:block; border:1px solid #F1F1F1; width:32px; height:20px;"><img src="{$AppTheme->images}/decorators/forecolor.gif"/><img src="{$AppTheme->images}/decorators/button_menu.gif"/></span></a>
            </li>
    
    		<li style="padding-top:1px; width:2px; background:none;"><img border="0" src="{$AppTheme->images}/decorators/separator.gif"/></li>
            
    		<li style="padding-left:0px; ">
            	<a href="#null" onclick="ddFastHeadline_clear_formatting('{$cloneId}'); return false;"  class="mceButtonNormal" style="background:url({$AppTheme->images}/decorators/removeformat.gif) no-repeat 50% center"></a>
            </li>
		
		</ul>

		<div class="themeA">
			<div id="ddFastHeadline_{$cloneId}"
            	 style="
                 	width:140px; height:91px; overflow:auto;
                    {if $font_family}font-family:'{$font_family}';{/if}
                    {if $font_size}font-size:{$font_size}px;{/if}
                    {if $font_weight_bold}font-weight:bold;{/if}
                    {if $font_style_italic}font-style:italic;{/if}
                    {if $text_decoration_underline}text-decoration:underline;{/if}
                    {if $text_align}text-align:{$text_align};{/if}
                    {if $color}color:{$color};{/if}
                    
                    ">{$Content|escape:"html"}</div>
		</div>
 </div>

   