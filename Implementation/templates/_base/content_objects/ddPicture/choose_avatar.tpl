{*popup_item*}
{if $a_thumbs_hash}
	<div class="prText2 prTCenter">{t}Selected photo will be your primary photo{/t}</div>
{/if}        


<!-- popup content -->
<div class="prInnerTop">
    <div class="prCOCentrino prPopupScroll"> <a href="#null"><img id="a_image_preview" src="{$user->getAvatar()->setWidth(150)->getImage()}" name="{$user->getAvatar()->getId()}" title="" alt="" /></a> </div>
    <div class="prCOCentrino" id="a_gallery_thumbs"> {$a_thumbs_content} </div>                
</div>
<!-- /popup content -->

<!-- content object buttons pannel -->
      <div class="prCOCentrino">                  
              {if $a_thumbs_hash}
              {if $smarty.section.thumb.iteration <= 13}
                  {linkbutton name="Choose Photo From Galleries" link="#null" onclick="
                    xajax_avatarLoadFromGalleries('xajax_select_avatar(\'`$cloneId`\',\'\',\'reload\')');
                    "
                  }
                  
                  
                  {* code by Andrey linkbutton name="Choose Photo From Galleries" link="#null" onclick="
                  
                  xajax_avatarLoadFromGalleries('xajax_select_avatar(\'`$cloneId`\')'); 
                  document.getElementById('a_image_preview').parentNode.removeChild(document.getElementById('a_image_preview'));  
                  document.getElementById('a_gallery_thumbs').parentNode.removeChild(document.getElementById('a_gallery_thumbs')); 
                  popup_window.close();"
                  
                  *}
                  
                                                           
                  {linkbutton name="Add picture to profile gallery" link="#null" onclick="xajax_upload_avatar('`$cloneId`');"} 
              {/if}
              {else}
<a class="prButton" href="#null" onclick="popup_window.close(); xajax_upload_avatar('{$cloneId}'); return false;"><span>{t}Create profile gallery{/t}</span></a>                        
               {/if}
      </div>
      <div class="prIndent prCOCentrino">
               {if $a_thumbs_hash}
                <a class="prButton" href="#null" onclick="storeAvatar('{$cloneId}',document.getElementById('a_image_preview').name);popup_window.close();return false;"><span>{t}OK{/t}</span></a> <span class="prIEVerticalAling">{t}or{/t}                  
                {/if} 
               <a href="#" onclick="popup_window.close(); return false;">{t}Cancel{/t}</a>{if $a_thumbs_hash}</span>{/if}
      </div>
<!-- /content object buttons pannel -->
{*popup_item*}
