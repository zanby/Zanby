<div class="prPhotoBox">
	<div class="prPhotoBoxLeft prClr2">
		<a href="#null" onclick="PGEApplication.showPreviewPanel('{$photo->setWidth(800)->setHeight(600)->getImage($user)}', '800', '600'); return false;">
			<img class="prFloatLeft" src='{$photo->setWidth(100)->setHeight(100)->getImage($user)}' />
		</a>

		<div class="prIndentLeft prPhotoBoxLeft">
			<h3>{$photo->getTitle()|escape:'html'}</h3>
			<p>{$photo->getSize('kbyte')|string_format:"%d"}k</p>
			<div style="overflow:auto">
				{$photo->getDescription()|escape:"html"|nl2br}
			</div>
		</div>
	</div>
	<div class="prPhotoBoxRight">
		<div class="prInner">
	        {if $AccessManager->canEditPhoto($photo, $currentGroup, $user)}
				<a href="#null" onClick="xajax_edit_photo({$gallery->getId()}, {$photo->getId()})">{t}Edit{/t}</a> &nbsp;|&nbsp;
			{/if}
			{if $AccessManager->canDeletePhoto($photo, $currentGroup, $user)}
				<a href="#null" onclick="PGEApplication.showDeletePhotoPanel({$photo->getGalleryId()}, {$photo->getId()}, 400, 80)">{t}Delete{/t}</a>
			{/if}
		</div>
	</div>
</div>
