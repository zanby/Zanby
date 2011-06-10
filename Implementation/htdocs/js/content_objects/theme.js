//------------------------------------------------------------------------------------------------------
function select_left_tab(id)
{
    switch(id) {
        case "theme_background_tab":
            document.getElementById("theme_background_div").style.display = 'block';
			document.getElementById("theme_defaults_div").style.display = 'none';
            document.getElementById("theme_background_button").className = 'active';
            document.getElementById("theme_defaults_button").className = '';
        break;
        case "theme_defaults_tab":
            document.getElementById("theme_background_div").style.display = 'none';
			document.getElementById("theme_defaults_div").style.display = 'block';
            document.getElementById("theme_background_button").className = '';
            document.getElementById("theme_defaults_button").className = 'active';
        break;
	}
}
//------------------------------------------------------------------------------------------------------
function validateHexColor(color)
{
    return (/^#?([0-9a-fA-F]{3}){1,2}$/).test(color)
}
//------------------------------------------------------------------------------------------------------
function apply_hex_code(value){
	/*if (value!==0)
	{
		document.getElementById("hex_code").value = value;	
	}
	if (validateHexColor(document.getElementById("hex_code").value))
	{
		document.getElementById("layout_content").style.backgroundColor = document.getElementById("hex_code").value;
	}
	
	//update_css();
	return false;*/
}
//------------------------------------------------------------------------------------------------------


