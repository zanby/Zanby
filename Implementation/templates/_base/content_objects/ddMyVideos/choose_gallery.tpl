{*popup_item*}
<table width=220 border=0 cellpadding="2" cellspacing="2" align="center">
    {if $gallery_hash}
    <tr>
        <td width=150>
            <strong>{t}Gallery Names{/t}</strong><br>
        </td>
    </tr>
    <tr>
        <td width="150">
            <select name="gallery_select" id="gallery_select" style='width:220px; visibility:' onchange="xajax_ddMyVideos_load_gallery(this.value); return false;">
                
{foreach from=$gallery_hash item=gallery}
        
                <option value={$gallery->getId()}>{$gallery->getTitle()|escape:'html'}</option>
                
{/foreach}
      
            </select>
        </td>
    </tr>
    <tr>
        <td width=150>
            <strong>{t}Preview{/t}</strong><br>
            <i id="image_preview_title">{if $thumbs_hash}{$thumbs_hash[0]->getTitle()|escape:'html'}{$thumbs_hash[0]}{$preview_title|escape:'html'}{/if}</i><br>
        </td>
    </tr>
    <tr>
        <td style="border:1px #000000 dashed;" align="center">
            <img id="image_preview" src="{$image_preview}" name="{$preview_nid}"/>
        </td>
    </tr>
</table>
<br>
<input type="hidden" id = "div_id" name="div_id" value="{$div_id}">
<table width="100" border="0" align="center">
    <tr>
        <td> {t var="in_button"}Ok{/t}{linkbutton name=$in_button color="orange" link="#" onclick=`$onclickattr`} </td>
        <td> {t}or{/t} <a href="#" color="orange" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a> </td>
    </tr>
    {else}
    <tr>
        <td width=150>
            <strong>{t}You have no public galleries{/t}</strong><br>
        </td>
    </tr>
    {/if}
</table>
{*popup_item*}