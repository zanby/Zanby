{*popup_item*}
<table class="prFullWidth" cellspacing="0" cellpadding="0">          
  <col width="83%" />
  <col width="17%" />
  <tr>
	<td>
		<div class="prClr2">
			<div class="prFloatLeft">
				<div class="prInnerSmall"> 	        				
					<img class="img" height="100" width="100" src='{$video->getCover()->setWidth(100)->setHeight(100)->getImage()}'> 	        			
				</div>
			</div>
			<div class="prFloatLeft prInnerLeft prInnerRight">
				<div class="prClr2">
				<h3>{$video->getTitle()|escape:'html'}</h3>
				<p>{$video->getSize('kbyte')|string_format:"%d"}{t}k{/t}</p>
				<p>{$video->getDescription()}</p>
				</div>
			</div> 
		</div>
	</td>           
	<td>
		<div class="prTCenter"> 		            
				<a href="#null" onClick="xajax_edit_photo({$gallery->getId()}, {$video->getId()})">{t}Edit{/t}</a> &nbsp;|&nbsp; 
				<a href="#null" onclick="PGEApplication.showDeletePhotoPanel({$video->getGalleryId()}, {$video->getId()})">{t}Delete{/t}</a>		           
		</div>
	</td>
  </tr>
</table>
{*popup_item*}