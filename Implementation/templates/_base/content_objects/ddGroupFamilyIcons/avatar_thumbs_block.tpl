<table cellpadding="0" cellspacing="0" border="0">
    <tr>
    	<td colspan="5">
            <span>Group Families</span><br /> 
            {if $groupFamilies}
              <select style="width:250px;" onchange="xajax_select_gbgi('{$cloneId}', '', document.getElementById('a_image_preview').name, this.options[this.selectedIndex].value);return false;" onkeydown="">
              {foreach from=$groupFamilies item=gf}
                <option value="{$gf->getId()}" {if $selectedFamily == $gf->getId()}selected="selected"{/if}>{$gf->getName()}</option>
              {/foreach}
              </select>
            {/if}
      	</td>
      </tr>
      
      <tr>
    	
      {section name=thumb loop=$a_thumbs_hash}
        <td> <a href="#null"><img class="image_thumb" border=1  src="{$a_thumbs_hash[thumb]->setWidth(48)->setHeight(48)->getImage()}" onclick="xajax_show_gbgi_preview('{$a_thumbs_hash[thumb]->setWidth(150)->setHeight(0)->getImage()}', '{$a_thumbs_hash[thumb]->title|escape:html}', '{$a_thumbs_hash[thumb]->getId()}');return false;" title="" alt="" /></a> </td>
        
        {if $smarty.section.thumb.last && $smarty.section.thumb.iteration%5 !=0 }
            {section start=$smarty.section.thumb.iteration%5 max=5 loop=5 name=iter}
                <td><div style="width:48px;">&nbsp;</div></td>
            {/section}
        {/if} 
        
        {if $smarty.section.thumb.iteration%5==0} 
        	</tr><tr>
        {/if}
        
        
        {/section} 
      </tr>
</table>
