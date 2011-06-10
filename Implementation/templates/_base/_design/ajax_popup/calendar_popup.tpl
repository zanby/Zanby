{*popup_item*}
{if $block_layer}
<div id="block_layer_{$div_id}" style="z-index:3000;position:absolute;top:0;left:0;width:100%;background-image:URL('{$AppTheme->images}/decorators/block_layer.gif');background-attachment:scroll;/*background-color:#EAEAEA;*/"></div>
{/if}
<div id="main_popup_{$div_id}" style="z-index:4000;position:absolute;top:{$pos_top|default:"0"};left:{$pos_left|default:"0"};">
<div class="event-bg-calendar">
    <div class="event-top-calendar">
        <div class="event-month-calendar">{$title}</div>
        <div class="event-close-calendar" ><a href="#" onclick="xajax_closePopup('{$div_id}'); return false;"><img src="{$AppTheme->images}/decorators/event/event-close-calendar.gif" alt="" border="0"></a></div>
    </div>

    <div class="event-content-calendar">
    {$body|default:"&nbsp;"}
    </div>
</div>
</div>
{*popup_item*}