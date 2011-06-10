<div class="prClr2 prPhotoBox">
	<div class="prFloatLeft">
		<div class="prInnerSmall">	
			<a href="#null" onclick="PGEApplication.showPreviewPanel('{$photo->setWidth(800)->setHeight(600)->getImage($user)}', '800', '600'); return false;">
				<img src='{$photo->setWidth(100)->setHeight(100)->getImage($user)}'>
			</a>
		</div>
	</div>
	<div class="prFloatLeft">	
	                             
	{form from=$form id="editPhotoForm"|cat:$photo->getId() onsubmit="PGEApplication.editPhotoHandle("|cat:$photo->getId()|cat:"); return false;"}
	{form_errors_summary}
	<table class="prForm">
		<tr>
			<td class="prTRight"><label>{t}Title:{/t}</label></td>
			  <td>			
				  {form_hidden name="gallery_id" value=$gallery->getId()}
				  {form_hidden name="photo_id" value=$photo->getId()}
				  {form_text name="title" id="photoTitle" class="prTinyMceMedia" value=$photo->getTitle()|escape:html}
			  </td>
		</tr>
		<tr>
			<td class="prTRight"><label>{t}Description:{/t}</label></td>	                      
			<td>{form_textarea name="description" id="photoDescription"|cat:$photo->getId() value=$photo->getDescription()}
			</td>
		</tr>
		<tr>
			  <td class="prTRight"><label>{t}Tags:{/t}</label></td>
			  <td>{form_text name="tags" id="photoTags" class="prTinyMceMedia" value=$photo->setForceDbTags()->getPhotoTags()|escape:"html"}</td>
		</tr>
        {if HTTP_CONTEXT == 'zccf' || HTTP_CONTEXT == 'zccf-alt' || HTTP_CONTEXT == 'zccf-base'}
        <tr>
            <td class="prTRight">&nbsp;</td>
            <td><div class="prTip">{t}Tags are a way to group your photos and are separated by spaces. Use quotes around multi-word tags like "Public LIbrary"{/t}</div></td>
        </tr>
        {/if}
        <tr>
            <td></td>
            <td colspan="2">
                    <a class="prButton" href="#null" onclick="PGEApplication.editPhotoHandle({$photo->getId()}); return false;"><span>{t}Save Changes{/t}</span></a> {t}or{/t} 
                    <a href="#null" onclick="xajax_cancel_edit_photo({$gallery->getId()}, {$photo->getId()}); return false;"><span>{t}Cancel{/t}</span></a>              
            </td>
       </tr>

		</table>
	 {/form}	            
	</div>
</div>    

