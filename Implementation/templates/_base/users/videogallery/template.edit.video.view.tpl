<div>
	<table class="prFullWidth">
		<col width="83%" />
		<col width="17%" />
		<tr>
			<td><div class="prClr2">
					<div class="prFloatLeft">
						<div class="prGrayBorder prIndentBottom14"> <img height="100" width="100" src='{$video->getCover()->setWidth(100)->setHeight(100)->getImage()}' /> </div>
					</div>
					<div class="prFloatLeft prIndentBottom">
						<div>
							<h3>{$video->getTitle()|escape:'html'}</h3>
							<div style="overflow:auto;">
								<p>{$video->getDescription()}</p>
							</div>
						</div>
					</div>
				</div></td>
			<td><div class="prTCenter"> <a href="#null" onClick="xajax_edit_photo({$gallery->getId()}, {$video->getId()})">{t}Edit{/t}</a> &nbsp;|&nbsp; <a href="#null" onclick="PGEApplication.showDeletePhotoPanel({$video->getGalleryId()}, {$video->getId()})">{t}Delete{/t}</a> </div></td>
		</tr>
	</table>
</div>
