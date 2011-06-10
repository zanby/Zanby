{assign_adv var="palette" value="array(array('#FFFFFF','#CCCCCC','#C0C0C0','#999999','#666666','#333333','#000000'),
                                       array('#FFCCCC','#FF6666','#FF0000','#CC0000','#990000','#660000','#330000'),
                                       array('#FFCC99','#FF9966','#FF9900','#FF6600','#CC6600','#993300','#663300'),
                                       array('#FFFF99','#FFFF66','#FFCC66','#FFCC33','#CC9933','#996633','#663333'),
                                       array('#FFFFCC','#FFFF33','#FFFF00','#FFCC00','#999900','#666600','#333300'),
                                       array('#99FF99','#66FF99','#33FF33','#33CC00','#009900','#006600','#003300'),
                                       array('#99FFFF','#33FFFF','#66CCCC','#00CCCC','#339999','#336666','#003333'),
                                       array('#CCFFFF','#66FFFF','#33CCFF','#3366FF','#3333FF','#000099','#000066'),
                                       array('#CCCCFF','#9999FF','#6666CC','#6633FF','#6600CC','#333399','#330099'),
                                       array('#FFCCFF','#FF99FF','#CC66CC','#CC33CC','#993399','#663366','#330033')
                                      )"}
									  
									  
								
								
<table cellpadding="0" cellspacing="0" style="border: 1px solid #666666;">
{foreach item=row from=$palette}
<tr>
    {foreach item=cell from=$row}
        <td style="border: 1px solid #666666; width:10px; height:10px; font-size:0px;" bgcolor="{$cell}"><img id="c{$cell}" src="{$AppTheme->images}/decorators/px.gif" border="0" width="10" height="10" style="cursor:pointer;" {$attributes} title="{$cell}" alt="&nbsp;&nbsp;"></td>
    {/foreach}
</tr>
{/foreach}
</table>
<script type="text/javascript">
var palette_values = new Array({foreach name=cols item=row from=$palette}{foreach name=rows item=cell from=$row}'{$cell}'{if !($smarty.foreach.cols.last && $smarty.foreach.rows.last)},{/if}{/foreach}{/foreach});
var palette_img_width = 16;
var palette_img_height = 13;
</script>