{section name=thumb loop=$a_thumbs_hash}
 <div class="prFloatLeft prInnerSmall"><a href="#null"><img class="image_thumb" src="{$a_thumbs_hash[thumb]->setWidth(48)->setHeight(48)->getImage()}" onclick="xajax_show_avatar_preview('{$a_thumbs_hash[thumb]->setWidth(150)->setHeight(0)->getImage()}', '{$a_thumbs_hash[thumb]->title|escape:html}', '{$a_thumbs_hash[thumb]->getId()}');return false;" title="" alt="" /></a> </div>       
 {/section}
 <div class="prClearer"></div> 