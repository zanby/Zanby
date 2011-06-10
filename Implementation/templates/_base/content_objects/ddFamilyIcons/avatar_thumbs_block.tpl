{*popup_item*}
<table cellpadding="0" cellspacing="0" border="0">
    <tr> 
    	{section name=thumb loop=$a_thumbs_hash}
        <td> <a href="#null"><img class="image_thumb" border=1  src="{$a_thumbs_hash[thumb]->setWidth(48)->setHeight(48)->getImage()}" onclick="xajax_show_bgi_preview('{$a_thumbs_hash[thumb]->setWidth(150)->setHeight(0)->getImage()}', '{$a_thumbs_hash[thumb]->title|escape:html}', '{$a_thumbs_hash[thumb]->getId()}');return false;" title="" alt="" /></a> </td>
        {if $smarty.section.thumb.last && $smarty.section.thumb.iteration%7 !=0 }
            {section start=$smarty.section.thumb.iteration%7 max=7 loop=77 name=iter}
                <td>&nbsp;</td>
            {/section}
        {/if} 
        
        {if $smarty.section.thumb.iteration%7==0} 
        	</tr><tr>
        {/if}
        {/section}
	</tr>
</table>
<div class="prClearer"></div>
<div class="prInnerTop prTCenter">
{if $a_thumbs_hash}

	{if $smarty.section.thumb.iteration <= 13}
		{t var="in_button"}Add picture to Family Icons gallery{/t}
		{linkbutton color="orange" name=$in_button  onclick="xajax_upload_bgi('`$cloneId`'); return false;"}
    {/if}

{else}
	{t var="in_create"}Create Family Icons gallery{/t}
	{linkbutton color="orange" name=$in_create  onclick="xajax_upload_bgi('`$cloneId`'); return false;"}
{/if} 
</div>
{*popup_item*}