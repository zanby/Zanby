    var upload1;
    var swfversion = false;
    var timeout;
    var uploadfunc;
    var hasError = 0;
    var callFunctions = new Array();
    var settings = function (){
			return {
            // Backend Settings
            upload_url: "",
            post_params: {"SWFUploadID" : ''},

            // File Upload Settings
            file_size_limit : "0",    // 2MB
            file_types : "*.*",
            file_types_description : "All Files",
            file_upload_limit : "0",
            file_queue_limit : "0",

	 			button_placeholder_id : "browse",
				button_image_url : AppTheme.images+"/decorators/background/flashUplBg2.gif",
				button_width : 102,
				button_height : 24,
				button_text : '<div class="theFont">Choose Files</div>',
				//button_text_style: ".theFont {color:#ffffff; font-size: 13px; font-family: Arial, Tahoma,Geneva,sans-serif; text-align: center;}",
                button_text_style: ".theFont {"+AppTheme.swfupload.button_text_style+"}",
				button_text_left_padding : 0,
				button_text_top_padding : 2,
				button_action : SWFUpload.BUTTON_ACTION.SELECT_FILES,
				button_disable : false,

            // Event Handler Settings (all my handlers are in the Handler.js file)
            swfupload_loaded_handler        : SWFUploadUploaded,
            file_dialog_start_handler       : fileDialogStart,
            file_queued_handler             : fileQueued,
            file_queue_error_handler        : fileQueueError,
            file_dialog_complete_handler    : fileDialogComplete,
            upload_start_handler            : uploadStart,
            upload_progress_handler         : uploadProgress,
            upload_error_handler            : uploadError,
            upload_complete_handler         : uploadComplete,
            file_complete_handler           : fileComplete,
            upload_success_handler          : uploadSuccess,

            // Flash Settings
            flash_url : "/js/SWFUpload/swfupload.swf",    // Relative to this file (or you can use absolute paths)

            swfupload_element_id : "SWFUpload",        // Setting from graceful degradation plugin
            degraded_element_id : "fields_table",    // Setting from graceful degradation plugin
            // Debug Settings
            debug: false
            }
        };

    function SWFUploadUploaded()
    {
        document.getElementById('upload_type').value = 'swfupload';
        swfversion = true;
        setSWFUploadParams();
		upload1.setButtonImageURL( AppTheme.images+"/decorators/background/flashUplBg2.gif");
		upload1.setButtonDimensions(settings().button_width, settings().button_height);
		buttonRoutine(upload1);
		upload1.setButtonTextPadding(settings().button_text_left_padding, settings().button_text_top_padding);
		showSWFUpload();
    }

    function turnOnSWFUpload()
    {
        document.getElementById('SWFUpload').style.display = "block";
		hideSWFUpload();
		loadSWFUpload();
        return false;
    }

    function hideSWFUpload()
    {
		if (typeof originalHeight === 'undefined' || typeof originalWidth === 'undefined') {
			originalHeight = document.getElementById('SWFUpload').style.height;
			originalWidth = document.getElementById('SWFUpload').style.width;
		}
		document.getElementById('SWFUpload').style.height = "1px";
		document.getElementById('SWFUpload').style.width = "1px";
  		document.getElementById('SWFUpload').style.overflow = "hidden";
		document.getElementById('SWFUpload').className = "fuFix";
		if (typeof upload1 !== 'undefined' && upload1 !== null)
			hideMovie(upload1.getMovieElement());
        document.getElementById('SWFUpload').isHided = true;
	}

    function showSWFUpload()
    {
    	if (typeof originalHeight === 'undefined' || typeof originalWidth === 'undefined') {
			originalHeight = document.getElementById('SWFUpload').style.height;
			originalWidth = document.getElementById('SWFUpload').style.width;
		}
        document.getElementById('SWFUpload').className = "";
		document.getElementById('SWFUpload').style.height = originalHeight;
		document.getElementById('SWFUpload').style.width = originalWidth;
        if (typeof upload1 !== 'undefined' && upload1 !== null)
			showMovie(upload1.getMovieElement());
        document.getElementById('SWFUpload').isHided = false;
    }

    function setQueuedLimit(limit)
    {
        if (swfversion == true){
			upload1.setFileQueueLimit(limit);
            setUploadLimit(limit);
		}
    }

    function setUploadLimit(limit)
    {
        if (swfversion == true)
			upload1.setFileUploadLimit(limit);
    }

    function setUploadURL(url)
    {
        if (swfversion == true)
            upload1.setUploadURL(url);
    }

    function setPostParams(params)
    {
        if (swfversion == true)
            upload1.setPostParams(params);
    }

    function setFileTypes(types, desc)
    {
        if (swfversion == true) {
            if (!desc) desc = upload1.getSetting('file_types_description');
            upload1.setFileTypes(types, desc);
        }
    }

    function setFileSizeLimit(limit)
    {
        if (swfversion == true) {
            upload1.setFileSizeLimit(limit);
        }
    }

    function loadSWFUpload()
    {
		upload1 = new SWFUpload(settings());
        upload1.customSettings.progressTarget = "fsUploadProgress1";    // Add an additional setting that will later be used by the handler.
        upload1.customSettings.cancelButtonId = "btnCancel1";            // Add an additional setting that will later be used by the handler.

    }

    function showErrors()
    {
        document.getElementById('swferror').style.display = "";
    }

    function hideErrors()
    {
        document.getElementById('swferror').style.display = "none";
    }

    function emptyErrors()
    {
        document.getElementById('swferror').innerHTML = "";
        document.getElementById('swferror').style.display = "none";
    }

    function addSWFError(message)
    {
        document.getElementById('swferror').innerHTML = document.getElementById('swferror').innerHTML + '<p><strong class="znTColor6">ERROR:</strong> ' + message + '</p>';
        ++hasError;
    }

    function uploadrSupported()
    {
        if(window.location.href.match(/nocheck/i)){
            return true;
        }
        var r=deconcept.SWFObjectUtil.getPlayerVersion();
        if(navigator.platform.match(/linux/i)&&r.major==9&&r.minor==0) {
            if(r.rev<60){
                return {result:false,reason:"needupgrade"};
            }else{
                return {result:true};
            }
        }
        if(document.getElementById&&r.major>0){
            if(r.major<9){
                return {result:false,reason:"needupgrade"};
            }else{
                if(r.major==9&&r.minor==0&&r.rev==16) {
                    return {result:false,reason:"badversion"};
                }
                if(r.major==9&&r.minor==0&&r.rev==0){return {result:true};}
                return {result:true};
            }
        }else{
            return {result:false,reason:"noflash"};
        }
    }

    function checkFlash()
    {
/*        res = uploadrSupported();
        if (res.result === true) {
            document.getElementById('SWFUpload').style.display = '';
            document.getElementById('upload_type').value = 'swfupload';
            swfversion = true;
        } else {
            document.getElementById('fields_table').style.display = '';
            document.getElementById('upload_type').value = 'upload';
            swfversion = false;
        }  */
    }

    function ttt()
    {
        if (upload1.getStats().files_queued === 0) {
            clearTimeout(timeout);
            if (hasError) {
                --hasError;
                setTimeout(ttt, 1500);
            }
            else {
                uploadfunc();
            }
        } else {
            timeout = setTimeout(ttt, 4000);
        }
    }
    function uploadandsubmit(func)
    {
        document.getElementById('swferror').style.display="none";
        uploadfunc = func;
        if (swfversion == true) {
            if (upload1.getStats().files_queued == 0){
                emptyErrors();
                addSWFError('Please select files to upload');
                showErrors();
                return false;
            }
            upload1.startUpload();
			hideMovie(upload1.getMovieElement());
            timeout = setTimeout(ttt, 4000);
        } else {
            uploadfunc();
        }

        return false;
    }

    function cancelupload(func)
    { //alert (func);
        if (swfversion == true) {
            clearTimeout(timeout);
			if (upload1 !== null) {
            cancelQueue(upload1);
            upload1.destroy();
            upload1 = null;
        }
        }
        func();
    }