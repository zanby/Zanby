  ThemeApplication = function () {
	return {
		fillColor : null,
		fillColorTransparent : null,
		bodyTextColor : null,
		bodyTextFontFamily : null,
		headlineTextColor : null,
		headlineTextFontFamily : null,
		commentColor : null,
		commentFontFamily : null,
		headerColor : null,
		headerFontFamily : null,
		linkColor : null,
		outlineColor : null,
		outlineStyle : null,
		backgroundColor : null,
		backgroundImage : null,
    	backgroundTile : null,
		backgroundUrl: null,
		
		fontFamilies: null,
		
		bfillColor : null,
		bfillColorTransparent : null,
		bbodyTextColor : null,
		bbodyTextFontFamily : null,
		bheadlineTextColor : null,
		bheadlineTextFontFamily : null,
		bcommentColor : null,
		bcommentFontFamily : null,
		bheaderColor : null,
		bheaderFontFamily : null,
		blinkColor : null,
		boutlineColor : null,
		boutlineStyle : null,
		bbackgroundColor : null,
		bbackgroundImage : null,
    	bbackgroundTile : null,
		bbackgroundUrl: null,
		
		bUrlStorage: null,
		
		init : function () {
			/* Add Photo, Gallery to My Photos menu initialisation */
			this.bUrlStorage = new Array();
			
			var handlerShowAddPhotoFromGallery = {
				fn: ThemeApplication.showAddPhotoFromGallery,
				obj: ThemeApplication,
				scope: null
			}
			var handlerShowAddPhotoFromComputer = {
				fn: ThemeApplication.showAddPhotoFromComputer,
				obj: ThemeApplication,
				scope: null
			}
			ThemeApplication.oAddMenu = new YAHOO.widget.Menu("basicmenuAddMenu");
			ThemeApplication.oAddMenu.addItems([
					{ text: "From Your Galleries",  onclick: handlerShowAddPhotoFromGallery },
					{ text: "From My Computer",  onclick: handlerShowAddPhotoFromComputer },
				]);
			ThemeApplication.oAddMenu.render("addMenuTarget");
			
			/**/
			ThemeApplication.addMenuPanel = new YAHOO.widget.Panel('addMenuPanel',
				{width : "450px", visible:false, constraintoviewport:true, fixedcenter:true, modal:true, effect:{effect:YAHOO.widget.ContainerEffect.FADE,duration:0.3}}
			);
			ThemeApplication.addMenuPanel.render();
		},
		//-------------------------------------------------------------------------------------------------------------------
		applyBackgroundColor : function (value) {
			document.getElementById("hex_code").value = value;	
			this.backgroundColor = value; 
			if (validateHexColor(value))
			{
				document.getElementById("layout_content").style.backgroundColor = this.backgroundColor;
			}
			this.refreshTransparency();
		},
		//-------------------------------------------------------------------------------------------------------------------
		applyFillColor : function (value) {
			document.getElementById("fillColorIndicator").value = value;	
			this.fillColor = value; 
			if (validateHexColor(value))
			{
				document.getElementById("outlineColorIndicator2").style.backgroundColor = this.fillColor;
				document.getElementById("zndTheme-live-simple6").style.backgroundColor = this.fillColor;
			}
			this.refreshTransparency();
		},
		//-------------------------------------------------------------------------------------------------------------------
		applyBodyTextColor : function (value) {
			this.bodyTextColor = value; 
			if (validateHexColor(value))
			{
				document.getElementById("bodyTextColorIndicator").style.color = this.bodyTextColor;
				var currentTarget = document.getElementById('ddTarget2');
				var _collection = currentTarget.getElementsByTagName('p');
				for (var i=0; i<_collection.length; i++) {
					_collection[i].style.color = this.bodyTextColor;
				}
			}
		},
		//-------------------------------------------------------------------------------------------------------------------
		applyHeadlineTextColor : function (value) {
			this.headlineTextColor = value; 
			if (validateHexColor(value))
			{
				document.getElementById("headlineTextColorIndicator").style.color = this.headlineTextColor;
				var currentTarget = document.getElementById('ddTarget2');
				var _collection = currentTarget.getElementsByTagName('h2');
				for (var i=0; i<_collection.length; i++) {
					_collection[i].style.color = this.headlineTextColor;
				}
			}
		},
		//-------------------------------------------------------------------------------------------------------------------
		applyCommentColor : function (value) {
			this.commentColor = value; 
			if (validateHexColor(value))
			{
				document.getElementById("commentColorIndicator").style.color = this.commentColor;
				var currentTarget = document.getElementById('ddTarget2');
				var spanCollection = currentTarget.getElementsByTagName('span');
				var _collection = [];
				var j =0;
				for (var i=0; i<spanCollection.length; i++) {
					if ((spanCollection[i].className.substring(0, 10) == 'znbCO-hint') || (spanCollection[i].className.substring(0, 10) == 'znbCO-date')) {
						_collection[j] = spanCollection[i];
						j++;
					}	
				}
				for (var i=0; i<_collection.length; i++) {
					_collection[i].style.color = this.commentColor;
				}
			}
		},
		//-------------------------------------------------------------------------------------------------------------------
		applyHeaderColor : function (value) {
			this.headerColor = value; 
			if (validateHexColor(value))
			{
				document.getElementById("headerColorIndicator").style.color = this.headerColor;
				var currentTarget = document.getElementById('ddTarget2');
				var _collection = currentTarget.getElementsByTagName('h3');
				for (var i=0; i<_collection.length; i++) {
					_collection[i].style.color = this.headerColor;
				}
			}	
		},
		//-------------------------------------------------------------------------------------------------------------------
		applyLinkColor : function (value) {
			this.linkColor = value; 
			if (validateHexColor(value))
			{
				document.getElementById("linkColorIndicator").style.color = this.linkColor;
				document.getElementById("linkColorIndicator1").style.backgroundColor = this.linkColor;
				var currentTarget = document.getElementById('ddTarget2');
				var _collection = currentTarget.getElementsByTagName('a');
				for (var i=0; i<_collection.length; i++) {
					_collection[i].style.color = this.linkColor;
				}
			}	
		},
		//-------------------------------------------------------------------------------------------------------------------
		applyOutlineColor : function (value) {
			this.outlineColor = value; 
			if (validateHexColor(value))
			{
				document.getElementById("outlineColorIndicator").style.borderBottomColor = this.outlineColor;
				document.getElementById("outlineColorIndicator1").style.backgroundColor = this.outlineColor;
				document.getElementById("outlineColorIndicator2").style.borderColor = this.outlineColor;
			}	
		},
		//-------------------------------------------------------------------------------------------------------------------
		applyOutlineStyle : function (value) {
			this.outlineStyle = value; 
			document.getElementById("outlineColorIndicator").style.borderStyle = this.outlineStyle;
			document.getElementById("outlineColorIndicator2").style.borderStyle = this.outlineStyle;
		},
		//-------------------------------------------------------------------------------------------------------------------
		applyBodyTextStyle : function (value) {
			this.bodyTextFontFamily = value; 
			document.getElementById('bodyTextStyleSelector').selectedIndex = this.bodyTextFontFamily-1;
			var currentTarget = document.getElementById('ddTarget2');
			var _collection = currentTarget.getElementsByTagName('p');
			for (var i=0; i<_collection.length; i++) {
				_collection[i].style.fontFamily = this.fontFamilies[this.bodyTextFontFamily];
			}
		},
		//-------------------------------------------------------------------------------------------------------------------
		applyHeadlineTextStyle : function (value) {
			this.headlineTextFontFamily = value; 
			document.getElementById('headlineTextStyleSelector').selectedIndex = this.headlineTextFontFamily-1;
			var currentTarget = document.getElementById('ddTarget2');
			var _collection = currentTarget.getElementsByTagName('h2');
			for (var i=0; i<_collection.length; i++) {
				_collection[i].style.fontFamily = this.fontFamilies[this.headlineTextFontFamily];
			}
		},
		//-------------------------------------------------------------------------------------------------------------------
		applyCommentTextStyle : function (value) {
			this.commentFontFamily = value;
			document.getElementById('commentTextStyleSelector').selectedIndex = this.commentFontFamily-1;
			var currentTarget = document.getElementById('ddTarget2');
			var spanCollection = currentTarget.getElementsByTagName('span');
			var _collection = [];
			var j =0;
			for (var i=0; i<spanCollection.length; i++) {
				if ((spanCollection[i].className.substring(0, 10) == 'znbCO-hint') || (spanCollection[i].className.substring(0, 10) == 'znbCO-date')) {
					_collection[j] = spanCollection[i];
					j++;
				}	
			}
			for (var i=0; i<_collection.length; i++) {
				_collection[i].style.fontFamily = this.fontFamilies[this.commentFontFamily];
			}
		},
		//-------------------------------------------------------------------------------------------------------------------
		applyHeaderTextStyle : function (value) {
			this.headerFontFamily = value; 
			document.getElementById('headerTextStyleSelector').selectedIndex = this.headerFontFamily-1;
			var currentTarget = document.getElementById('ddTarget2');
			var _collection = currentTarget.getElementsByTagName('h3');
			for (var i=0; i<_collection.length; i++) {
				_collection[i].style.fontFamily = this.fontFamilies[this.headerFontFamily];
			}
		},
		//-------------------------------------------------------------------------------------------------------------------
		applyBackgroundImage : function (title, bUrl) {
			
			this.bUrlStorage[this.bUrlStorage.length] = bUrl;
			this.backgroundImage = title;
			this.backgroundUrl = bUrl;
	
			document.getElementById("backgroundImageIndicator").value = this.backgroundImage;
			document.getElementById("backgroundImageIndicator1").innerHTML = this.backgroundImage+'<br/>';
			
			if(!this.backgroundImage)
			{
				document.getElementById("layout_content").style.backgroundImage = '';
				document.getElementById("backgroundImageIndicator2").src = AppTheme.images+'/decorators/fakeImage.gif';
				document.getElementById("avatarinfoblock").style.display="none";
				document.getElementById("avatarinfoblock2").style.display="block";
			}else{	
			
				document.getElementById("layout_content").style.backgroundImage = 'url('+this.backgroundUrl.replace("_orig.","_medium.")+')';
				document.getElementById("backgroundImageIndicator2").src = this.backgroundUrl.replace("_orig.","_small.");
				document.getElementById("avatarinfoblock").style.display="block";
				document.getElementById("avatarinfoblock2").style.display="none";
			}
			this.refreshTransparency();
			
		},
		//-------------------------------------------------------------------------------------------------------------------
		removeBackgroundImage : function () {
			xajax_remove_bckg_image(this.backgroundUrl);
			this.applyBackgroundImage('', '');
			document.getElementById("avatarinfoblock").style.display="none";
			document.getElementById("avatarinfoblock2").style.display="block";
			
		},
		//-------------------------------------------------------------------------------------------------------------------
		bremoveBackgroundImage : function () {
			//this.bbackgroundUrl = this.backgroundUrl;
			this.applyBackgroundImage('', '');
			document.getElementById("avatarinfoblock").style.display="none";
			document.getElementById("avatarinfoblock2").style.display="block";
			
		},
		//-------------------------------------------------------------------------------------------------------------------
		applyBackgroundTile : function (value) {
			this.backgroundTile = value;
			if (this.backgroundTile == 1){
				document.getElementById("layout_content").style.backgroundRepeat = 'repeat';
				document.getElementById("backgroundTileCh").checked = "checked";
			}else{
				document.getElementById("layout_content").style.backgroundRepeat = 'no-repeat';
				document.getElementById("backgroundTileCh").checked = "";
			}
			this.refreshTransparency();
		},
		//-------------------------------------------------------------------------------------------------------------------
		applyFillColorTransparent : function () {
			if (this.fillColorTransparent == 1){
				document.getElementById("FillColorTransparentCh").checked = "checked";
				document.getElementById("fillColorHeader").style.color = "#999999";
				document.getElementById("fillColorIndicator").disabled = true;
				document.getElementById("fillColorSelector").style.display = "none";
			}else{
				document.getElementById("FillColorTransparentCh").checked = "";
				document.getElementById("fillColorHeader").style.color = "";
				document.getElementById("fillColorIndicator").disabled = false;
				document.getElementById("fillColorSelector").style.display = "block";
			}
			this.refreshTransparency();
		},
		//-------------------------------------------------------------------------------------------------------------------
		applyApplyChangesToSavedLayout : function (value) {
			if (value !=0) {
				document.getElementById("clearOldLayout").checked = "checked";
			} else {;
				document.getElementById("clearOldLayout").checked = 0;
			}
		},
		//-------------------------------------------------------------------------------------------------------------------
		changeFillColorTransparent : function () {
			hideAllAKPopups();
			if (this.fillColorTransparent == 1){
				this.fillColorTransparent = 0;
				document.getElementById("fillColorHeader").style.color = "";
				document.getElementById("fillColorIndicator").disabled = false;
				document.getElementById("fillColorSelector").style.display = "block";
			}else{
				this.fillColorTransparent = 1;
				document.getElementById("fillColorHeader").style.color = "#999999";
				document.getElementById("fillColorIndicator").disabled = true;
				document.getElementById("fillColorSelector").style.display = "none";
			}
			this.refreshTransparency();
		},
		//-------------------------------------------------------------------------------------------------------------------
		changeBackgrouundTile : function () {
			if (this.backgroundTile == 1){
				this.backgroundTile = 0;
			}else{
				this.backgroundTile = 1;
			}
			this.applyBackgroundTile(this.backgroundTile);
		},
		//-------------------------------------------------------------------------------------------------------------------
		clearOldLayoutCh : function () {
			if (document.getElementById("clearOldLayout").checked) {return 1;} else {return 0;}
		},
		
		//-------------------------------------------------------------------------------------------------------------------
		refreshTransparency : function () {
			if (this.fillColorTransparent == 1){
				document.getElementById("outlineColorIndicator2").style.backgroundColor = this.backgroundColor;
				document.getElementById("outlineColorIndicator2").style.backgroundImage = 'url('+this.backgroundUrl+')';
				if (this.backgroundTile == 1){
					document.getElementById("outlineColorIndicator2").style.backgroundRepeat = 'repeat';
				}else{
					document.getElementById("outlineColorIndicator2").style.backgroundRepeat = 'no-repeat';
				}
			}else{
				document.getElementById("outlineColorIndicator2").style.backgroundColor = this.fillColor;
				document.getElementById("outlineColorIndicator2").style.backgroundImage = '';
			}
		},
		
		//-------------------------------------------------------------------------------------------------------------------
		loadVariables : function (themeVariables) {

			var _vars = YAHOO.ccf.json.parseJSON(themeVariables);
			
			this.fillColor = _vars.fillColor;
			this.fillColorTransparent = _vars.fillColorTransparent;
			this.bodyTextColor = _vars.bodyTextColor;
			this.bodyTextFontFamily = _vars.bodyTextFontFamily;
			this.headlineTextColor = _vars.headlineTextColor;
			this.headlineTextFontFamily = _vars.headlineTextFontFamily;
			this.commentColor = _vars.commentColor;
			this.commentFontFamily = _vars.commentFontFamily;
			this.headerColor = _vars.headerColor;
			this.headerFontFamily = _vars.headerFontFamily;
			this.linkColor = _vars.linkColor;
			this.outlineColor = _vars.outlineColor;
			this.outlineStyle = _vars.outlineStyle;
			this.backgroundColor = _vars.backgroundColor;
			this.backgroundImage = _vars.backgroundImage;
			this.backgroundUrl = _vars.backgroundUrl;
    		this.backgroundTile = _vars.backgroundTile;
			this.applyChangesToSavedLayout = _vars.applyChangesToSavedLayout;
			
			this.fontFamilies = _vars.fontFamilies;
			
			this.makeBackup();
						
			this.applyCurrentVariables();	
		},
		//-------------------------------------------------------------------------------------------------------------------
		makeBackup : function () {

			this.makeBackupDefaults();
			this.makeBackupBackground();
		},
		//-------------------------------------------------------------------------------------------------------------------
		makeBackupDefaults : function () {

			this.bfillColor = this.fillColor;
			this.bfillColorTransparent = this.fillColorTransparent;
			this.bbodyTextColor = this.bodyTextColor;
			this.bbodyTextFontFamily = this.bodyTextFontFamily;
			this.bheadlineTextColor = this.headlineTextColor;
			this.bheadlineTextFontFamily = this.headlineTextFontFamily;
			this.bcommentColor = this.commentColor;
			this.bcommentFontFamily = this.commentFontFamily;
			this.bheaderColor = this.headerColor;
			this.bheaderFontFamily = this.headerFontFamily;
			this.blinkColor = this.linkColor;
			this.boutlineColor = this.outlineColor;
			this.boutlineStyle = this.outlineStyle;
			
			this.bapplyChangesToSavedLayout = this.applyChangesToSavedLayout;	
		},
		//-------------------------------------------------------------------------------------------------------------------
		makeBackupBackground : function () {

			this.bbackgroundColor = this.backgroundColor;
			this.bbackgroundImage = this.backgroundImage;
    		this.bbackgroundTile = this.backgroundTile;
			this.bbackgroundUrl = this.backgroundUrl;
			
			//delete other urls
			for (var i=0; i<this.bUrlStorage.length; i++ )
			{
				if(this.backgroundUrl != this.bUrlStorage[i])
				{
				
					xajax_remove_bckg_image(this.bUrlStorage[i]);	
				}
			}
			this.bUrlStorage = new Array(); 
		},
		
		//-------------------------------------------------------------------------------------------------------------------
		applyCurrentVariables : function () {
			this.applyBackgroundColor(this.backgroundColor);
			this.applyFillColor (this.fillColor);
			this.applyFillColorTransparent();
			this.applyBodyTextColor (this.bodyTextColor);
			this.applyHeadlineTextColor (this.headlineTextColor);
			this.applyCommentColor (this.commentColor);
			this.applyHeaderColor (this.headerColor);
			this.applyLinkColor (this.linkColor);
			this.applyOutlineColor (this.outlineColor);
			this.applyOutlineStyle (this.outlineStyle);
			
			this.applyBodyTextStyle (this.bodyTextFontFamily);
			this.applyHeadlineTextStyle (this.headlineTextFontFamily);
			this.applyCommentTextStyle (this.commentFontFamily);
			this.applyHeaderTextStyle(this.headerFontFamily);
			
			this.applyBackgroundImage(this.backgroundImage, this.backgroundUrl);
			this.applyBackgroundTile(this.backgroundTile);
			this.applyApplyChangesToSavedLayout(this.applyChangesToSavedLayout);
	
		},
		//-------------------------------------------------------------------------------------------------------------------
		restoreDefaultColors : function () {
			this.fillColor = '#FFFFFF';
			this.fillColorTransparent = 1;
			this.bodyTextColor = '#000000';
			this.bodyTextFontFamily =2;
			this.headlineTextColor = '#333333';
			this.headlineTextFontFamily =2;
			this.commentColor = '#666666';
			this.commentFontFamily = 2;
			this.headerColor = '#FF6600';
			this.headerFontFamily = 2;
			this.linkColor = '#004488'; 
			this.outlineColor = '#CFCFCF'; 
			this.outlineStyle = 'solid';
			
			this.applyCurrentVariables();	
			
		},
		//-------------------------------------------------------------------------------------------------------------------
		restoreDefaultBackground : function () {
			this.removeBackgroundImage();
			
			this.backgroundColor = '#FFFFFF';
			this.backgroundImage = '';
			this.backgroundUrl = '';
    		this.backgroundTile = 0;
			
			this.applyCurrentVariables();	
		},
		//-------------------------------------------------------------------------------------------------------------------
		restoreColors : function () {
			this.fillColor = this.bfillColor;
			this.fillColorTransparent = this.bfillColorTransparent;
			this.bodyTextColor = this.bbodyTextColor;
			this.bodyTextFontFamily =this.bbodyTextFontFamily;
			this.headlineTextColor = this.bheadlineTextColor;
			this.headlineTextFontFamily =this.bheadlineTextFontFamily;
			this.commentColor = this.bcommentColor;
			this.commentFontFamily = this.bcommentFontFamily;
			this.headerColor = this.bheaderColor;
			this.headerFontFamily = this.bheaderFontFamily;
			this.linkColor = this.blinkColor; 
			this.outlineColor = this.boutlineColor; 
			this.outlineStyle = this.boutlineStyle;
			this.applyChangesToSavedLayout = this.bapplyChangesToSavedLayout
			
			this.applyCurrentVariables();	
		
		},
		//-------------------------------------------------------------------------------------------------------------------
		restoreBackground : function () {
			//this.removeBackgroundImage();
			
			this.backgroundColor = this.bbackgroundColor;
			this.backgroundImage = this.bbackgroundImage;
			this.backgroundUrl = this.bbackgroundUrl;
    		this.backgroundTile = this.bbackgroundTile;
			
			this.applyCurrentVariables();	
		},
		//-------------------------------------------------------------------------------------------------------------------
		getParams : function () {
			mparams = new Array();
			mparams['fillColor'] = this.fillColor;
			mparams['fillColorTransparent'] = this.fillColorTransparent;
			mparams['bodyTextColor'] = this.bodyTextColor;
			mparams['bodyTextFontFamily'] = this.bodyTextFontFamily;
			mparams['headlineTextColor'] = this.headlineTextColor;
			mparams['headlineTextFontFamily'] = this.headlineTextFontFamily;
			mparams['commentColor'] = this.commentColor;
			mparams['commentFontFamily'] = this.commentFontFamily;
			mparams['headerColor'] = this.headerColor;
			mparams['headerFontFamily'] = this.headerFontFamily;
			mparams['linkColor'] = this.linkColor;
			mparams['outlineColor'] = this.outlineColor;
			mparams['outlineStyle'] = this.outlineStyle;
			mparams['backgroundColor'] = this.backgroundColor;
			mparams['backgroundImage'] = this.backgroundImage;
			mparams['backgroundTile'] = this.backgroundTile;
			mparams['backgroundUrl'] = this.backgroundUrl;
			mparams['applyChangesToSavedLayout'] = this.clearOldLayoutCh();
			
			this.applyChangesToSavedLayout = this.clearOldLayoutCh();
			
			return(mparams);
		},
		//-------------------------------------------------------------------------------------------------------------------
		getParamsDS : function () {
			mparams = new Array();
			mparams['fillColor'] = this.fillColor;
			mparams['fillColorTransparent'] = this.fillColorTransparent;
			mparams['bodyTextColor'] = this.bodyTextColor;
			mparams['bodyTextFontFamily'] = this.bodyTextFontFamily;
			mparams['headlineTextColor'] = this.headlineTextColor;
			mparams['headlineTextFontFamily'] = this.headlineTextFontFamily;
			mparams['commentColor'] = this.commentColor;
			mparams['commentFontFamily'] = this.commentFontFamily;
			mparams['headerColor'] = this.headerColor;
			mparams['headerFontFamily'] = this.headerFontFamily;
			mparams['linkColor'] = this.linkColor;
			mparams['outlineColor'] = this.outlineColor;
			mparams['outlineStyle'] = this.outlineStyle;
			mparams['applyChangesToSavedLayout'] = this.clearOldLayoutCh();
			
			mparams['backgroundColor'] = this.bbackgroundColor;
			mparams['backgroundImage'] = this.bbackgroundImage;
			mparams['backgroundTile'] = this.bbackgroundTile;
			mparams['backgroundUrl'] = this.bbackgroundUrl;
			
			
			this.applyChangesToSavedLayout = this.clearOldLayoutCh();
			
			return(mparams);
		},
		//-------------------------------------------------------------------------------------------------------------------
		getParamsBS : function () {
			mparams = new Array();
			mparams['fillColor'] = this.bfillColor;
			mparams['fillColorTransparent'] = this.bfillColorTransparent;
			mparams['bodyTextColor'] = this.bbodyTextColor;
			mparams['bodyTextFontFamily'] = this.bbodyTextFontFamily;
			mparams['headlineTextColor'] = this.bheadlineTextColor;
			mparams['headlineTextFontFamily'] = this.bheadlineTextFontFamily;
			mparams['commentColor'] = this.bcommentColor;
			mparams['commentFontFamily'] = this.bcommentFontFamily;
			mparams['headerColor'] = this.bheaderColor;
			mparams['headerFontFamily'] = this.bheaderFontFamily;
			mparams['linkColor'] = this.blinkColor;
			mparams['outlineColor'] = this.boutlineColor;
			mparams['outlineStyle'] = this.boutlineStyle;
			mparams['applyChangesToSavedLayout'] = this.bapplyChangesToSavedLayout
			
			mparams['backgroundColor'] = this.backgroundColor;
			mparams['backgroundImage'] = this.backgroundImage;
			mparams['backgroundTile'] = this.backgroundTile;
			mparams['backgroundUrl'] = this.backgroundUrl;
			
			return(mparams);
		},
	/**
	* 
	*/
		showAddMenu : function (obj, galleryId, photoId) {
			ThemeApplication.galleryId = galleryId;
			ThemeApplication.photoId = photoId;
			YAHOO.util.Dom.get('addMenuTarget').style.display = '';
			var region = YAHOO.util.Dom.getRegion(obj);
			ThemeApplication.oAddMenu.cfg.setProperty("x", region.left);
			ThemeApplication.oAddMenu.cfg.setProperty("y", region.top + 20);
			ThemeApplication.oAddMenu.cfg.setProperty("width", "");
			ThemeApplication.oAddMenu.show();
		},
		showAddPhotoFromGallery : function () {
			xajax_ddImage_select_avatar();
		},
		
		showAddPhotoFromComputer : function () {
			xajax_upload_avatar();
		},
		
		hideAddPanel : function () {
			ThemeApplication.addMenuPanel.hide();                    
		}
	}
}();
