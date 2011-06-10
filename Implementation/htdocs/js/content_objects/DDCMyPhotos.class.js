function photoTypeSelectChange(elementId)
{
	tmpElement = WarecorpDDblockApp.getObjByID(elementId);
	tmpElement.galleryType = document.getElementById('photo_type_select_'+elementId).selectedIndex;
	WarecorpDDblockApp.redrawElementLight(tmpElement.ID);
}

function galleryShowThumbnailsCountChange(elementId)
{
	tmpElement = WarecorpDDblockApp.getObjByID(elementId);
	tmpElement.galleryShowThumbnailsCount = document.getElementById('gallery_show_thumbnails_count_'+elementId).value;
}

function addDDMyPhotos(elementId)
{
	tmpElement = WarecorpDDblockApp.getObjByID(elementId);
	tmpElement.galleries[tmpElement.galleries.length] = 0;
	tmpElement.galleryCount++;
	WarecorpDDblockApp.redrawElementLight(tmpElement.ID);
}

function removeDDMyPhotosSlot(elementId, gallery_index)
{
	tmpElement = WarecorpDDblockApp.getObjByID(elementId);
	
	if (tmpElement.galleries.length <= 1) {
        return false;
    }
	
	for(k=gallery_index;k<(tmpElement.galleries.length-1);k++) {
		tmpElement.galleries[k]=tmpElement.galleries[k+1];
	}
	tmp=tmpElement.galleries.pop();
	tmpElement.galleryCount--;
	
	WarecorpDDblockApp.redrawElementLight(tmpElement.ID);
	return false;
}

function getGalleriesParams(elementId)
{
	tmpElement = WarecorpDDblockApp.getObjByID(elementId);
	
	if (tmpElement) {
		tmpArr = tmpElement.getParams();
		return tmpArr.Data;
	}
	
	return false;
}

function showAsIconsChange(elementId, index)
{
	tmpElement = WarecorpDDblockApp.getObjByID(elementId);

	if (tmpElement && index) {
		document.getElementById('ddMyPhotos_thumbnails_count_'+elementId).style.display = 'block';
		tmpElement.galleryShowAsIcons = 1;
	} else {
		tmpElement.galleryShowAsIcons = 0;
		document.getElementById('ddMyPhotos_thumbnails_count_'+elementId).style.display = 'none';
	}

	return false;
}


DDCMyPhotos = function(id, sGroup, config) {
    if (id) {
        this.init(id, sGroup, config);
    }
};

YAHOO.extend(DDCMyPhotos, DDC);

DDCMyPhotos.prototype.getParams = function () {
    
    var item = this.getGlobalParams();
    
    item.Data.gallery_count = this.galleryCount;
    item.Data.gallery_type = this.galleryType;
    item.Data.gallery_show_as_icons = this.galleryShowAsIcons;
    item.Data.gallery_show_thumbnails_count = this.galleryShowThumbnailsCount;

    item.Data.galleries = [];

    for (var i=0; i < item.Data.gallery_count; i++) {
        if (this.galleries[i]) {
            item.Data.galleries[i] = this.galleries[i];
        } else {
            item.Data.galleries[i] = 0;
        }
    }

    return item;
};
//--------------------------------------------------------------------------------------------
DDCMyPhotos.prototype.backupParams = function () {
    this.backupGlobalParams();
    
    this.bckGalleryCount = this.galleryCount;
    this.bckGalleryType = this.galleryType;
    this.bckGalleryShowAsIcons = this.galleryShowAsIcons;
    this.bckGalleryShowThumbnailsCount = this.galleryShowThumbnailsCount;
    
    this.bckGalleries = [];

    for (var i=0; i < this.galleryCount; i++) {
        this.bckGalleries[i] = this.galleries[i];
    }
};
//--------------------------------------------------------------------------------------------
DDCMyPhotos.prototype.restoreParams = function () {
    this.restoreGlobalParams();
    
    this.galleryCount = this.bckGalleryCount;
    this.galleryType = this.bckGalleryType;
    this.galleryShowAsIcons = this.bckGalleryShowAsIcons;
    this.galleryShowThumbnailsCount = this.bckGalleryShowThumbnailsCount;
    
    this.galleries = [];

    for (var i=0; i < this.bckGalleryCount; i++) {
        this.galleries[i] = this.bckGalleries[i];
    }
};
