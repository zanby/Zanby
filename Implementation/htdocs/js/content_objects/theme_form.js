function select_left_tab(id)
{
    switch(id) {
        case "bg_color_tab":
            document.getElementById("bg_image_tab").style.background = '#EAEAEA';
            document.getElementById("bg_image_tab").style.color = '#000000';
            document.getElementById("nav_bar_tab").style.background = '#EAEAEA';
            document.getElementById("nav_bar_tab").style.color = '#000000';

            document.getElementById("bg_color_title").style.display = 'block';
            document.getElementById("bg_color_title").style.visibility = 'visible';
            document.getElementById("bg_image_title").style.display = 'none';
            document.getElementById("bg_image_title").style.visibility = 'hidden';
            document.getElementById("nav_bar_title").style.display = 'none';
            document.getElementById("nav_bar_title").style.visibility = 'hidden';

            document.getElementById("bg_color_column").style.display = 'block';
            document.getElementById("bg_color_column").style.visibility = 'visible';
            document.getElementById("bg_image_column").style.display = 'none';
            document.getElementById("bg_image_column").style.visibility = 'hidden';
            document.getElementById("nav_bar_column").style.display = 'none';
            document.getElementById("nav_bar_column").style.visibility = 'hidden';
            
            document.getElementById("bgimage_bottom").style.display = 'none';
            document.getElementById("bgimage_bottom").style.visibility = 'hidden';
            document.getElementById("nav_bottom").style.display = 'none';
            document.getElementById("nav_bottom").style.visibility = 'hidden';
            
        break;
        case "bg_image_tab":
            document.getElementById("bg_color_tab").style.background = '#EAEAEA'; 
            document.getElementById("bg_color_tab").style.color = '#000000'; 
            document.getElementById("nav_bar_tab").style.background = '#EAEAEA'; 
            document.getElementById("nav_bar_tab").style.color = '#000000'; 

            document.getElementById("bg_color_title").style.display = 'none';
            document.getElementById("bg_color_title").style.visibility = 'hidden';
            document.getElementById("bg_image_title").style.display = 'block';
            document.getElementById("bg_image_title").style.visibility = 'visible';
            document.getElementById("nav_bar_title").style.display = 'none';
            document.getElementById("nav_bar_title").style.visibility = 'hidden';

            document.getElementById("bg_color_column").style.display = 'none';
            document.getElementById("bg_color_column").style.visibility = 'hidden';
            document.getElementById("bg_image_column").style.display = 'block';
            document.getElementById("bg_image_column").style.visibility = 'visible';
            document.getElementById("nav_bar_column").style.display = 'none';
            document.getElementById("nav_bar_column").style.visibility = 'hidden';

            document.getElementById("bgimage_bottom").style.display = 'block';
            document.getElementById("bgimage_bottom").style.visibility = 'visible';
            document.getElementById("nav_bottom").style.display = 'none';
            document.getElementById("nav_bottom").style.visibility = 'hidden';
            
        break;
        case "nav_bar_tab":
            document.getElementById("bg_color_tab").style.background = '#EAEAEA'; 
            document.getElementById("bg_color_tab").style.color = '#000000'; 
            document.getElementById("bg_image_tab").style.background = '#EAEAEA'; 
            document.getElementById("bg_image_tab").style.color = '#000000'; 

            document.getElementById("bg_color_title").style.display = 'none';
            document.getElementById("bg_color_title").style.visibility = 'hidden';
            document.getElementById("bg_image_title").style.display = 'none';
            document.getElementById("bg_image_title").style.visibility = 'hidden';
            document.getElementById("nav_bar_title").style.display = 'block';
            document.getElementById("nav_bar_title").style.visibility = 'visible';

            document.getElementById("bg_color_column").style.display = 'none';
            document.getElementById("bg_color_column").style.visibility = 'hidden';
            document.getElementById("bg_image_column").style.display = 'none';
            document.getElementById("bg_image_column").style.visibility = 'hidden';
            document.getElementById("nav_bar_column").style.display = 'block';
            document.getElementById("nav_bar_column").style.visibility = 'visible';

            document.getElementById("bgimage_bottom").style.display = 'none';
            document.getElementById("bgimage_bottom").style.visibility = 'hidden';
            document.getElementById("nav_bottom").style.display = 'block';
            document.getElementById("nav_bottom").style.visibility = 'visible';
            
        break;
        case "css_tab": 
            document.getElementById("bg_color_tab").style.display = 'none'; 
            document.getElementById("bg_color_tab").style.visibility = 'hidden'; 
            document.getElementById("bg_image_tab").style.display = 'none'; 
            document.getElementById("bg_image_tab").style.visibility = 'hidden'; 
            document.getElementById("nav_bar_tab").style.display = 'none'; 
            document.getElementById("nav_bar_tab").style.visibility = 'hidden'; 
            document.getElementById("edit_css_tab").style.display = 'block'; 
            document.getElementById("edit_css_tab").style.visibility = 'visible'; 
                
            //document.getElementById("css_tab").className  = 'active'; 
            //document.getElementById("edit_theme_tab").className = ''; 
			document.getElementById("css_tab").style.display  = 'block'; 
            document.getElementById("edit_theme_tab").style.display = 'none'; 
                
            document.getElementById("edit_theme_content").style.display = 'none';
            document.getElementById("edit_theme_content").style.visibility = 'hidden';
            document.getElementById("edit_css_content").style.display = 'block';
            document.getElementById("edit_css_content").style.visibility = 'visible';

        break;
        case "edit_theme_tab":
            set_css();
            document.getElementById("bg_color_tab").style.display = 'block'; 
            document.getElementById("bg_color_tab").style.visibility = 'visible'; 
            document.getElementById("bg_image_tab").style.display = 'block'; 
            document.getElementById("bg_image_tab").style.visibility = 'visible'; 
            document.getElementById("nav_bar_tab").style.display = 'block'; 
            document.getElementById("nav_bar_tab").style.visibility = 'visible'; 
            document.getElementById("edit_css_tab").style.display = 'none'; 
            document.getElementById("edit_css_tab").style.visibility = 'hidden'; 

            //document.getElementById("css_tab").className  = ''; 
            //document.getElementById("edit_theme_tab").className  = 'active';
			document.getElementById("css_tab").style.display  = 'none'; 
            document.getElementById("edit_theme_tab").style.display = 'block'; 

            document.getElementById("edit_theme_content").style.display = 'block';
            document.getElementById("edit_theme_content").style.visibility = 'visible';
            document.getElementById("edit_css_content").style.display = 'none';
            document.getElementById("edit_css_content").style.visibility = 'hidden';
            
        break;
    }
    if (id!='css_tab' && id!='edit_theme_tab') {
        document.getElementById(id).style.background = '#666699';
        document.getElementById(id).style.color = '#FFFFFF';
    }

	return false;
}

function set_hex_code(hex){
    document.getElementById("hex_code").value=hex;
    return false;
}

function get_by_classname (parent_id, tagname, classname) {
    var result = new Array();
    
    
}

function apply_hex_code(){
    document.getElementById("layout_content").style.backgroundColor =document.getElementById("hex_code").value;
    update_css();
    return false;
}
    
function change_repeat_option(option){
    document.getElementById("layout_content").style.backgroundRepeat = option;
    update_css();
    return false;
}

function clear_bgimage(){
    document.getElementById("bg_image_form").style.display = 'none';
    document.getElementById("bg_image_form").style.visibility = 'hidden';
    document.getElementById("bg_image_src").src = '';
    document.getElementById("layout_content").style.backgroundImage ='';
    document.getElementById("layout_content").style.backgroundRepeat = '';
    document.getElementById("clear_bgimage_button").style.display = 'none';
    document.getElementById("clear_bgimage_button").style.visibility = 'hidden';
    update_css();
    return false;
}

function clear_nav_format() {
    var css_text = document.getElementById("css_text").value;
    reg = new RegExp('([^\\w\}]|^)+\.nav_title_bar(\\s)*\{[^\}]*\}','gi');
    css_text = css_text.replace(reg,'');
    reg = new RegExp('([^\\w\}]|^)+\.nav_text(\\s)*\{[^\}]*\}','gi');
    css_text = css_text.replace(reg,'');
    document.getElementById("css_text").value = css_text;
    set_css();
}

function place_background_image(src){
    document.getElementById("bg_image_form").style.display = 'block';
    document.getElementById("bg_image_form").style.visibility = 'visible';
    document.getElementById("bg_image_src").src = src;
    document.getElementById("layout_content").style.backgroundImage ='url('+src+')';
    if (document.getElementById("layout_content").style.backgroundRepeat=='') {
        document.getElementById("layout_content").style.backgroundRepeat = 'repeat';
        document.getElementById("repeat_option").checked=true;
    }
    document.getElementById("bgimage_bottom").style.display = 'block';
    document.getElementById("bgimage_bottom").style.visibility = 'visible';
    document.getElementById("clear_bgimage_button").style.display = 'block';
    document.getElementById("clear_bgimage_button").style.visibility = 'visible';
    update_css();
    return false;
}

function update_css(){
    // body
    var css_text = document.getElementById("css_text").value;
    reg = new RegExp('([^\\w\}]|^)+\.body(\\s)*\{[^\}]*\}','gi');
    matches = css_text.match(reg);

    body_css = "";
    if (document.getElementById("layout_content").style.backgroundColor!=''){
        body_css = body_css + "background-color:"+document.getElementById("layout_content").style.backgroundColor+";\n";
    }
    if (document.getElementById("layout_content").style.backgroundImage!=''){
        body_css = body_css + "background-image:"+document.getElementById("layout_content").style.backgroundImage+";\n";
    }
    if (document.getElementById("layout_content").style.backgroundRepeat!=''){
        body_css = body_css + "background-repeat:"+document.getElementById("layout_content").style.backgroundRepeat+";\n";
    }

    if (matches && matches.length){
        matches[0] = matches[0].replace(/background-color\s*:[^\{\}\;]*;\n*/gi, '');
        matches[0] = matches[0].replace(/background-image\s*:[^\{\}\;]*;\n*/gi, '');
        matches[0] = matches[0].replace(/background-repeat\s*:[^\{\}\;]*;\n*/gi, '');
        matches[0] = matches[0].replace(/background\s*:[^\{\}\;]*;\n*/gi, '');
        pos = matches[0].search(/\{/);
        body_css = matches[0].substr(0,pos+1)+'\n'+body_css+matches[0].substr(pos+1);
        body_css = body_css.replace(/;/gi, ';\n');
        body_css = body_css.replace(/\n\s+/gi, '\n');
        body_css = css_text.replace(reg, body_css);
    } else {
        body_css  = css_text + "\n.body {\n"+body_css;
        body_css = body_css + "}\n";
    }
    css_text = body_css;
    // nav_title
    reg = new RegExp('([^\\w\}]|^)+\.nav_title_bar(\\s)*\{[^\}]*\}','gi');
    matches = css_text.match(reg);
    nav_title_css="";
    if (document.getElementById("nav_title_bar").style.backgroundColor!=''){
        nav_title_css = nav_title_css + "background-color:"+document.getElementById("nav_title_bar").style.backgroundColor+";\n";
    }
    if (document.getElementById("nav_title_bar").style.color!=''){
        nav_title_css = nav_title_css + "color:"+document.getElementById("nav_title_bar").style.color+";\n";
    }
    if (matches && matches.length){
        matches[0] = matches[0].replace(/background-color\s*:[^\{\}\;]*;\n*/gi, '');
        matches[0] = matches[0].replace(/color\s*:[^\{\}\;]*;\n*/gi, '');
        matches[0] = matches[0].replace(/background\s*:[^\{\}\;]*;\n*/gi, '');
        pos = matches[0].search(/\{/);
        nav_title_css = matches[0].substr(0,pos+1)+'\n'+nav_title_css+matches[0].substr(pos+1);
        nav_title_css = nav_title_css.replace(/;/gi, ';\n');
        nav_title_css = nav_title_css.replace(/\n\s+/gi, '\n');
        nav_title_css = css_text.replace(reg, nav_title_css);
    } else {
        nav_title_css  = css_text + "\n.nav_title_bar {\n"+nav_title_css;
        nav_title_css = nav_title_css + "}\n";
    }
    css_text = nav_title_css;
    // nav_text
    reg = new RegExp('([^\\w\}]|^)+\.nav_text(\\s)*\{[^\}]*\}','gi');
    matches = css_text.match(reg);
    nav_text_css="";
    if (document.getElementById("nav_text").style.color!=''){
        nav_text_css = nav_text_css + "color:"+document.getElementById("nav_text").style.color+";\n";
    }
    if (matches && matches.length){
        matches[0] = matches[0].replace(/color\s*:[^\{\}\;]*;\n*/gi, '');
        pos = matches[0].search(/\{/);
        nav_text_css = matches[0].substr(0,pos+1)+'\n'+nav_text_css+matches[0].substr(pos+1);
        nav_text_css = nav_text_css.replace(/;/gi, ';\n');
        nav_text_css = nav_text_css.replace(/\n\s+/gi, '\n');
        nav_text_css = css_text.replace(reg, nav_text_css);
    } else {
        nav_text_css  = css_text + "\n.nav_text {\n"+nav_text_css;
        nav_text_css = nav_text_css + "}\n";
    }
    
    document.getElementById("css_text").value = nav_text_css;
}

function set_css(){
    var css_text = document.getElementById("css_text").value;
    var css_properties = new Array('background', 'backgroundAttachment', 'backgroundColor', 'backgroundImage', 'backgroundPosition', 'backgroundRepeat', 'border', 'borderCollapse', 'borderColor', 'borderSpacing', 'borderStyle', 'borderTop', 'borderRight', 'borderBottom', 'borderLeft', 'borderTopColor', 'borderRightColor', 'borderBottomColor', 'borderLeftColor', 'borderTopStyle', 'borderRightStyle', 'borderBottomStyle', 'borderLeftStyle', 'borderTopWidth', 'borderRightWidth', 'borderBottomWidth', 'borderLeftWidth', 'borderWidth', 'bottom', 'captionSide', 'clear', 'color', 'content', 'cue', 'cueAfter', 'cueBefore', 'cursor', 'direction', 'display', 'elevation', 'emptyCells', 'cssFloat', 'fontFamily', 'fontSize', 'fontSizeAdjust', 'fontStretch', 'fontStyle', 'fontVariant', 'fontWeight', 'height', 'left', 'letterSpacing', 'lineHeight', 'listStyle', 'listStyleImage', 'listStylePosition', 'listStyleType', 'margin', 'marginTop', 'marginRight', 'marginBottom', 'marginLeft', 'markerOffset', 'marks', 'maxHeight', 'maxWidth', 'minHeight', 'minWidth', 'orphans', 'outline', 'outlineColor', 'outlineStyle', 'outlineWidth', 'overflow', 'padding', 'paddingTop', 'paddingRight', 'paddingBottom', 'paddingLeft', 'page', 'pageBreakAfter', 'pageBreakBefore', 'pageBreakInside', 'pause', 'pauseAfter', 'pauseBefore', 'pitch', 'pitchRange', 'position', 'quotes', 'richness', 'right', 'size', 'speak', 'speakHeader', 'speakNumeral', 'speakPunctuation', 'speechRate', 'stress', 'tableLayout', 'textAlign', 'textDecoration', 'textIndent', 'textShadow', 'textTransform', 'top', 'unicodeBidi', 'verticalAlign', 'visibility', 'voiceFamily', 'volume', 'whiteSpace', 'widows', 'width', 'wordSpacing', 'zIndex'); // 
    var layout_content_height = document.getElementById("layout_content").style.height;
    var result = document.getElementById("nav_text").getElementsByTagName('a');

    // restore defaults
    for (i = 0; i < css_properties.length; i++){
        eval('document.getElementById("layout_content").style.'+css_properties[i]+'="";');
        eval('document.getElementById("nav_title_bar").style.'+css_properties[i]+'="";');
        eval('document.getElementById("nav_text").style.'+css_properties[i]+'="";');
        for (j = 0; j < result.length; j++) {
            if (result[j].className == 'nav_text_a') {
                eval('result[j].style.'+css_properties[i]+'="";');
            }
        }
    }
    document.getElementById("layout_content").style.width = "380px";
    document.getElementById("layout_content").style.height = layout_content_height;
    document.getElementById("layout_content").style.border = "solid 1px #00CC00";
    document.getElementById("layout_content").style.visibility = "visible";
    
    document.getElementById("nav_title_bar").style.width = "100%";
    document.getElementById("nav_title_bar").style.height = "15px";
    document.getElementById("nav_title_bar").style.borderBottom = "solid 1px #00CC00";
    document.getElementById("nav_title_bar").style.backgroundColor = "#597B40";
    document.getElementById("nav_title_bar").style.fontSize = "9px";
    document.getElementById("nav_title_bar").style.color = "#FFFFFF";
    document.getElementById("nav_title_bar").style.paddingTop = "3px";

    document.getElementById("nav_text").style.width = "100%";
    document.getElementById("nav_text").style.height = "15px";
    document.getElementById("nav_text").style.fontSize = "9px";
    document.getElementById("nav_text").style.color = "#003399";
    document.getElementById("nav_text").style.padding = "3px 0px 3px 0px;";
    for (j = 0; j < result.length; j++) {
        if (result[j].className == 'nav_text_a') {
            result[j].style.color="#003399";
        }
    }
    // end of restore defaults
    
    document.getElementById("bg_image_form").style.display = 'none';
    document.getElementById("bg_image_form").style.visibility = 'hidden';
    document.getElementById("clear_bgimage_button").style.display = 'none';
    document.getElementById("clear_bgimage_button").style.visibility = 'hidden';
    document.getElementById("repeat_option").checked=false;
    document.getElementById("repeat_option_x").checked=false;
    document.getElementById("repeat_option_y").checked=false;
    //document.getElementById("layout_content").style.backgroundRepeat = 'repeat';

    css_text = css_text.replace(/\n|\r/gi,'');
    css_text = '\n'+css_text.replace(/\}/gi,'}\n');
    
    // body
    reg = new RegExp('(\\s)+\.body(\\s)*\{.*\}','gi');
    matches = css_text.match(reg);
    if (matches && matches.length){
        body_css = matches[0].replace(/(;|\}|\{)/gi,'\n');
        reg = new RegExp('\n.*:.*','gi');
        matches = body_css.match(reg);
        if (matches && matches.length){
            for (i = 0; i < matches.length; i++){
                reg = new RegExp('([A-Za-z\\-]*)\\s*:\\s*(.*)','gi');
                row = reg.exec(matches[i]);
                row[1] = row[1].toLowerCase();
                while ((pos = row[1].search(/-/))>0) {
                    row[1] = row[1].substr(0,pos)+ (row[1].substr(pos+1,1)).toUpperCase()+row[1].substr(pos+2);
                }
                row[2] = row[2].replace("'","");
                row[2] = row[2].replace("'","");
                eval('document.getElementById("layout_content").style.'+row[1]+'=\''+row[2]+'\'');
            }
        }
    }

    // nav_title_bar
    reg = new RegExp('(\\s)+\.nav_title_bar(\\s)*\{.*\}','gi');
    matches = css_text.match(reg);
    if (matches && matches.length){
        nav_title_bar_css = matches[0].replace(/(;|\}|\{)/gi,'\n');
        reg = new RegExp('\n.*:.*','gi');
        matches = nav_title_bar_css.match(reg);
        if (matches && matches.length){
            for (i = 0; i < matches.length; i++){
                reg = new RegExp('([A-Za-z\\-]*)\\s*:\\s*(.*)','gi');
                row = reg.exec(matches[i]);
                row[1] = row[1].toLowerCase();
                while ((pos = row[1].search(/-/))>0) {
                    row[1] = row[1].substr(0,pos)+ (row[1].substr(pos+1,1)).toUpperCase()+row[1].substr(pos+2);
                }
                row[2] = row[2].replace("'","");
                row[2] = row[2].replace("'","");
                eval('document.getElementById("nav_title_bar").style.'+row[1]+'=\''+row[2]+'\'');
            }
        }
    }

    // nav_text
    reg = new RegExp('(\\s)+\.nav_text(\\s)*\{.*\}','gi');
    matches = css_text.match(reg);
    if (matches && matches.length){
        nav_text_css = matches[0].replace(/(;|\}|\{)/gi,'\n');
        reg = new RegExp('\n.*:.*','gi');
        matches = nav_text_css.match(reg);
        if (matches && matches.length){
            for (i = 0; i < matches.length; i++){
                reg = new RegExp('([A-Za-z\\-]*)\\s*:\\s*(.*)','gi');
                row = reg.exec(matches[i]);
                row[1] = row[1].toLowerCase();
                while ((pos = row[1].search(/-/))>0) {
                    row[1] = row[1].substr(0,pos)+ (row[1].substr(pos+1,1)).toUpperCase()+row[1].substr(pos+2);
                }
                row[2] = row[2].replace("'","");
                row[2] = row[2].replace("'","");
                eval('document.getElementById("nav_text").style.'+row[1]+'=\''+row[2]+'\'');
                result = document.getElementById("nav_text").getElementsByTagName('a');
                for (i = 0; i < result.length; i++) {
                    if (result[i].className == 'nav_text_a') {
                        eval('result[i].style.'+row[1]+'=\''+row[2]+'\'');
                    }
                }
            }
        }
    }
    
    if (document.getElementById("layout_content").style.backgroundImage != ''){
        document.getElementById("bg_image_form").style.display = 'block';
        document.getElementById("bg_image_form").style.visibility = 'visible';
        bgimage_url = document.getElementById("layout_content").style.backgroundImage;
        document.getElementById("bg_image_src").src = bgimage_url.replace(/url\([\x22]*([^\x22]*)[\x22]*\)/i,'$1');
        document.getElementById("clear_bgimage_button").style.display = 'block';
        document.getElementById("clear_bgimage_button").style.visibility = 'visible';
        switch (document.getElementById("layout_content").style.backgroundRepeat) {  
            case 'repeat':
                document.getElementById("repeat_option").checked=true;
            break;
            case 'repeat-x':
                document.getElementById("repeat_option_x").checked=true;
            break;
            case 'repeat-y':
                document.getElementById("repeat_option_y").checked=true;
            break;
        }
    }
    return;
}

function set_active_color(id) {
    for (i=0; i<palette_values.length; i++) {
        document.getElementById("c"+palette_values[i]).height = palette_img_height;
        document.getElementById("c"+palette_values[i]).width = palette_img_width;
        document.getElementById("c"+palette_values[i]).style.border="0px solid #FFFFFF";
    }

    document.getElementById("c"+id).height = palette_img_height-4;
    document.getElementById("c"+id).width = palette_img_width-4;
    if (id =='#FF0000') {
        document.getElementById("c"+id).style.border="2px solid #FFFFFF";
    } else {
        document.getElementById("c"+id).style.border="2px solid #FF0000";
    }
}

function set_color_sample(hex) {
    document.getElementById("cp_color_sample").style.backgroundColor = hex;
    document.getElementById("cp_hex_code").value=hex;
    document.getElementById("cp_hex_code_label").innerHTML=hex;
}

function apply_nav_color(refer) {
    
    hex = document.getElementById("cp_hex_code").value;
    if (!(/^#?([0-9a-fA-F]{3}){1,2}$/).test(hex)) {
        return;
    }
    
    switch (refer){
        case "title_bar":
            document.getElementById("nav_title_bar").style.backgroundColor = hex;    
        break;
        case "title_bar_text":
            document.getElementById("nav_title_bar").style.color = hex;    
        break;
        case "nav_text":
            var result = new Array();
            result = document.getElementById("nav_text").getElementsByTagName('a');
            for (i = 0; i < result.length; i++) {
                if (result[i].className == 'nav_text_a') {
                    result[i].style.color=hex;
                }
            }
            document.getElementById("nav_text").style.color = hex;    
        break;

    }
    update_css();
}

function on_change_cp_hex() {
    hex = document.getElementById("cp_hex_code").value;
    if ((/^#?([0-9a-fA-F]{3}){1,2}$/).test(hex)) {
        document.getElementById("cp_color_sample").style.backgroundColor = hex;
        document.getElementById("cp_hex_code_label").innerHTML=hex;
    }
}


