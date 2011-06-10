{*popup_item*}
{*/}
{if $block_layer}
<div id="block_layer_{$div_id}" style="z-index:3000;position:absolute;top:0px;left:0px;width:100%;background-image:URL('{$AppTheme->images}/decorators/block_layer.gif');background-attachment:scroll;/*background-color:#EAEAEA;*/"></div>
{/if}
<div id="main_popup_{$div_id}" style="z-index:4000;position:absolute;">
<table cellpadding="0" cellspacing="0" border=0>
{if $title}
	  <tr>
	    <td>
	      <div id="main_popup_title_position_{$div_id}">
    {if $title_position == "left"}
	  <table cellpadding="0" cellspacing="0" width="100%">
	    <tr>
	      <td align="right">
	      <center><b><div id="main_popup_title_{$div_id}" style="{$style_title|default:"border-left:1px #000000 solid;border-right:1px #000000 solid;border-top:1px #000000 solid; background-color:#EAEAEA;"}padding:5px 20px 0px 20px;">{$title}</div></b></center>
	      </td>
	      <td style="{$style_title2|default:"border-bottom:1px #000000 solid;width:40%;"}">&nbsp;
	      
	      </td>
	    </tr>
	  </table>
    {else}
	  <table cellpadding="0" cellspacing="0" width="100%">
	    <tr>
	      <td style="{$style_title2|default:"border-bottom:1px #000000 solid;width:40%;"}">&nbsp;
	      
	      </td>
	      <td align="right">
	      <center><b><div id="main_popup_title_{$div_id}" style="{$style_title|default:"border-left:1px #000000 solid;border-right:1px #000000 solid;border-top:1px #000000 solid; background-color:#EAEAEA;"}padding:5px 20px 0px 20px;">{$title}</div></b></center>
	      </td>
	    </tr>
	  </table>
    {/if}
	  </div>
	    </td>
      </tr>
{/if}
      <tr>
	    <td width="100%">
	      <div id="main_popup_content_{$div_id}" style="{$style_body|default:"border-left:1px #000000 solid;border-right:1px #000000 solid;border-bottom:1px #000000 solid; background-color:#EAEAEA;"}padding:10px;height:{$height|default:"100px"};width:{$width|default:"100px"};">{$body|default:"&nbsp;"}</div>
	    </td>
      </tr>
    </table>
</div>

{*}


<!-- popup -->
{if $block_layer}
<div id="block_layer_{$div_id}" style="z-index:3000;position:absolute;top:0px;left:0px;width:100%;background-image:URL('{$AppTheme->images}/decorators/block_layer.gif');background-attachment:scroll;/*background-color:#EAEAEA;*/"></div>
{/if}
<div class="sm-popup" id="main_popup_{$div_id}" style="z-index:4000;position:absolute;height:{$height|default:"100px"};width:{$width|default:"100px"};">
  <div class="pu-inner">
    <div class="pu-body"> <a href="#null" class="pu-close" onclick="xajax_closePopup('{$div_id}')">&nbsp;</a>
      <h1>{$title}</h1>
      <div class="clear"><span /></div>
      <!-- popup content -->
      <div class="pu-content" id="main_popup_{$div_id}">
        {$body|default:"&nbsp;"}
      </div>
      <!-- /popup content -->
      <div class="clear"><span /></div>
    </div>
  </div>
</div>
<!-- /popup -->
{*popup_item*}