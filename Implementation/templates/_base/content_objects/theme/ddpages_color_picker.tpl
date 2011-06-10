<table border="0" cellspacing="0" cellpadding="15">
  <tr>
    <td>{include file='content_objects/theme/ddpages_palette.tpl' attributes="onclick='set_active_color(this.title); set_color_sample(this.title);'"}</td>
    <td valign="top">
        <label for="cp_hex_code"><strong>{t}Enter Hex Code{/t}</strong></label><br />
        <input type="text" name="cp_hex_code" id="cp_hex_code" value="" style="width:120px;" maxlength="7" onkeyup="on_change_cp_hex();"><br /><br />
        <center>
          <div id="cp_color_sample" style="width:50%; border: 1px solid #00CC00;"><br />{t}Color{/t}<br />{t}sample{/t}<br /><br /></div>
          <br />
          <span id="cp_hex_code_label"> </span>
        </center>
    </td>
  </tr>
</table>
<table width="100" border="0" align="right">
<tr><td>
{t var="in_button"}Apply Color{/t}{linkbutton name=$in_button color="orange" link="#" attributes="" image="" onclick="apply_nav_color('`$refer`'); xajax_ddpages_color_picker_close(); return false;"}
</td>
<td>
{t}or{/t} <a href="#" color="orange" onclick="xajax_ddpages_color_picker_close(); return false;">{t}Cancel{/t}</a>
</td>
</tr>
</table>    
             