
/* This is an example of how to cancel all the files queued up.  It's made somewhat generic.  Just pass your SWFUpload
object in to this method and it loops through cancelling the uploads. */
var errors = 0;
function cancelQueue(instance) {
	document.getElementById(instance.customSettings.cancelButtonId).disabled = true;
	instance.stopUpload();
	var stats;
	
	do {
		stats = instance.getStats();
		instance.cancelUpload();
	} while (stats.files_queued !== 0);
	document.getElementById('fsUploadProgress1').innerHTML = "";
}

/* **********************
   Event Handlers
   These are my custom event handlers to make my
   web application behave the way I went when SWFUpload
   completes different tasks.  These aren't part of the SWFUpload
   package.  They are part of my application.  Without these none
   of the actions SWFUpload makes will show up in my application.
   ********************** */
function fileDialogStart() {
     errors = 0;
    //emptyErrors();
	/* I don't need to do anything here */
}

function getFileExtension(filename)
{
	if(!filename) return "";
	if( filename.length == 0 ) return "";
	var dot = filename.lastIndexOf(".");
	if( dot == -1 ) return "";
	var extension = filename.substr(dot,filename.length);
	return extension.toLowerCase();
}

function fileQueued(fileObj) {

	var extension = getFileExtension(fileObj.name);
	if (extension.length == 0 || this.getSetting("file_types").indexOf(extension) == -1) {
 		alert('Not a file or has unallowed extension');
 		this.cancelUpload(fileObj.id);
 		return;
 	}

	try {
		// You might include code here that prevents the form from being submitted while the upload is in
		// progress.  Then you'll want to put code in the Queue Complete handler to "unblock" the form
		var progress = new FileProgress(fileObj, this.customSettings.progressTarget);
		progress.SetStatus("Pending...");
		progress.ToggleCancel(true, this);

	} catch (ex) { this.debug(ex); }

}

function fileQueueError(fileObj, error_code, message) {
    if (errors == 0) emptyErrors();
    errors = errors + 1;
	try {
		if (error_code === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
			alert("You have attempted to queue too many files.\n" + (message === 0 ? "You have reached the upload limit." : "You may select " + (message > 1 ? "up to " + message + " files." : "one file.")));
			return;
		}

/*		var progress = new FileProgress(fileObj, this.customSettings.progressTarget);
		progress.SetError();
		progress.ToggleCancel(false);*/
        document.getElementById('files_box').style.height = document.getElementById('files_box_height').value;
		switch(error_code) {
			case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
                addSWFError('File ' + fileObj.name + ' is too big');
				//progress.SetStatus("File is too big.");
				this.debug("Error Code: File too big, File name: " + fileObj.name + ", File size: " + fileObj.size + ", Message: " + message);
				break;
			case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
				addSWFError('File ' + fileObj.name + ' is empty');
                //progress.SetStatus("Cannot upload Zero Byte files.");
				this.debug("Error Code: Zero byte file, File name: " + fileObj.name + ", File size: " + fileObj.size + ", Message: " + message);
				break;
			case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
				addSWFError('File ' + fileObj.name + ' has unallowed extension');
                //progress.SetStatus("Invalid File Type.");
				this.debug("Error Code: Invalid File Type, File name: " + fileObj.name + ", File size: " + fileObj.size + ", Message: " + message);
				break;
			case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
				alert("You have selected too many files.  " +  (message > 1 ? "You may only add " +  message + " more files" : "You cannot add any more files."));
				break;
			default:
				if (fileObj !== null) {
                    addSWFError('File ' + fileObj.name + ' rised with unhandled error');
					progress.SetStatus("Unhandled Error");
				}
				this.debug("Error Code: " + error_code + ", File name: " + fileObj.name + ", File size: " + fileObj.size + ", Message: " + message);
				break;
		}
        showErrors();
	} catch (ex) {
        this.debug(ex);
    }
}

function showMovie(movie)
{
	if (movie.initWidth && movie.initHeight) {
    	movie.style.height = movie.initHeight;
		movie.style.width = movie.initWidth;
	}else{
		movie.initWidth = movie.style.width;
		movie.initHeight = movie.style.height;
	}
}

function hideMovie(movie)
{
	if (movie.initWidth && movie.initHeight) {
		movie.style.height = "1px";
		movie.style.width = "1px";
	}else{
		movie.initWidth = movie.style.width;
		movie.initHeight = movie.style.height;
		movie.style.height = "1px";
		movie.style.width = "1px";
	}
}

function buttonRoutine(SWFUploadObject) {
	var movieElement = SWFUploadObject.getMovieElement();
	var addText = '<span class="theFont">Add Files</span>';
	var chooseText = '<span class="theFont">Choose Files</span>';
	var filesRemain = SWFUploadObject.getSetting('file_queue_limit') - SWFUploadObject.getStats().files_queued;
	if (filesRemain < 1) {
        hideMovie(movieElement);
        return;
	}
	if (filesRemain == 1) {
		addText = '<span class="theFont">Add File</span>';
		chooseText = '<span class="theFont">Choose File</span>';
	}
	if (SWFUploadObject.getStats().files_queued == 0) {
    	SWFUploadObject.setButtonText(chooseText);
        if (document.getElementById('SWFUpload').isHided == false) showMovie(movieElement);
    }else{
    	SWFUploadObject.setButtonText(addText);
        if (document.getElementById('SWFUpload').isHided == false) showMovie(movieElement);
    }
}

function fileDialogComplete(num_files_queued) {    
    try {    
	if (this.getStats().files_queued > 0) {
        if (errors == 0) emptyErrors();
        document.getElementById('files_box').style.height = document.getElementById('files_box_height').value;		
        document.getElementById('filesCount').innerHTML = '<strong>' + this.getStats().files_queued + '</strong> Files';
        var i = 0;
        var totalsize = 0;
        while (i < this.getStats().files_queued) {
            fileObj = this.getFile('SWFUpload_0_' + i);
            totalsize = totalsize + fileObj.size;
			var extension = getFileExtension(fileObj.name);
			if (extension.length == 0 || this.getSetting("file_types").indexOf(extension) == -1) {
		 		this.cancelUpload(fileObj.id);
		 	}
            i++;
        }
        totalsize = totalsize / 1024;
        document.getElementById('totalSize').innerHTML = 'Total: <strong>' + totalsize.toFixed(1) + '</strong> Kb';
        buttonRoutine(this);
        document.getElementById(this.customSettings.cancelButtonId).disabled = false;        
	}
	} catch (ex)  {
        this.debug(ex);
	}
}

function uploadStart(fileObj) {
	try {
		/* I don't want to do any file validation or anything,  I'll just update the UI and return true to indicate that the upload should start */
		var progress = new FileProgress(fileObj, this.customSettings.progressTarget);
		progress.SetStatus("Uploading...");
		progress.ToggleCancel(true, this);
	}
	catch (ex) {}
	
	return true;
}

function uploadProgress(fileObj, bytesLoaded, bytesTotal) {

	try {
		var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
		var progress = new FileProgress(fileObj, this.customSettings.progressTarget);
		progress.SetProgress(percent);
		progress.SetStatus("Uploading...");
	} catch (ex) { this.debug(ex); }
}

function uploadSuccess(fileObj, server_data)
{
    var temp = server_data.split(":");
    if (temp[0] == 'ERROR') {
        addSWFError(temp[1]);
        showErrors();
    }
}

function uploadComplete(fileObj, server_data) {
    try {
        var progress = new FileProgress(fileObj, this.customSettings.progressTarget);
		progress.SetComplete();
		progress.SetStatus("Complete.");
		progress.ToggleCancel(false);
        if (this.getStats().files_queued === 0) {
            document.getElementById(this.customSettings.cancelButtonId).disabled = true;            
        } else {    
            this.startUpload();
        }
	} catch (ex) { this.debug(ex); }
}

function fileComplete(fileObj) {
    try {
		/*  I want the next upload to continue automatically so I'll call startUpload here */
		if (this.getStats().files_queued === 0) {
			document.getElementById(this.customSettings.cancelButtonId).disabled = true;
		} else {	
			this.startUpload();
		}
	} catch (ex) { this.debug(ex); }

}

function uploadError(fileObj, error_code, message) {
	try {
		var flag = false;
		var extension = getFileExtension(fileObj.name);
		if (extension.length == 0 || this.getSetting("file_types").indexOf(extension) == -1) {
			flag = true;
	 	}
		if (flag === false) {
        var progress = new FileProgress(fileObj, this.customSettings.progressTarget);        
		progress.SetError();
		progress.ToggleCancel(false);
		}
        

		switch(error_code) {
			case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
				progress.SetStatus("Upload Error: " + message);
				this.debug("Error Code: HTTP Error, File name: " + fileObj.name + ", Message: " + message);
				break;
			case SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL:
				progress.SetStatus("Configuration Error");
				this.debug("Error Code: No backend file, File name: " + fileObj.name + ", Message: " + message);
				break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
				progress.SetStatus("Upload Failed.");
				this.debug("Error Code: Upload Failed, File name: " + fileObj.name + ", File size: " + fileObj.size + ", Message: " + message);
				break;
			case SWFUpload.UPLOAD_ERROR.IO_ERROR:
				progress.SetStatus("Server (IO) Error");
				this.debug("Error Code: IO Error, File name: " + fileObj.name + ", Message: " + message);
				break;
			case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
				progress.SetStatus("Security Error");
				this.debug("Error Code: Security Error, File name: " + fileObj.name + ", Message: " + message);
				break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
				progress.SetStatus("Upload limit exceeded.");
				this.debug("Error Code: Upload Limit Exceeded, File name: " + fileObj.name + ", File size: " + fileObj.size + ", Message: " + message);
				break;
			case SWFUpload.UPLOAD_ERROR.SPECIFIED_FILE_ID_NOT_FOUND:
				progress.SetStatus("File not found.");
				this.debug("Error Code: The file was not found, File name: " + fileObj.name + ", File size: " + fileObj.size + ", Message: " + message);
				break;
			case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
				progress.SetStatus("Failed Validation.  Upload skipped.");
				this.debug("Error Code: File Validation Failed, File name: " + fileObj.name + ", File size: " + fileObj.size + ", Message: " + message);
				break;
			case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
				if (this.getStats().files_queued === 0) {
					document.getElementById(this.customSettings.cancelButtonId).disabled = true;
					if (this.getStats().successful_uploads == 0) {
                        clearTimeout(timeout);
                    }
                }
 				buttonRoutine(this);
				if (flag === true) break;
                progress.SetStatus("Cancelled");
				progress.SetCancelled();
				break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
				progress.SetStatus("Stopped");
				break;
			default:
				progress.SetStatus("Unhandled Error: " + error_code);
				this.debug("Error Code: " + error_code + ", File name: " + fileObj.name + ", File size: " + fileObj.size + ", Message: " + message);
				break;
		}
        document.getElementById('filesCount').innerHTML = '<strong>' + this.getStats().files_queued + '</strong> Files';
        var i = 0;
        var totalsize = 0;
        while (i < this.getStats().files_queued) {
            fileObj = this.getFile('SWFUpload_0_' + i);
            totalsize = totalsize + fileObj.size;
            i++;
        }
        totalsize = totalsize / 1024;
        document.getElementById('totalSize').innerHTML = 'Total: <strong>' + totalsize.toFixed(1) + '</strong> Kb';        
	} catch (ex) {
        this.debug(ex);
    }
}



function FileProgress(fileObj, target_id) {
	this.file_progress_id = fileObj.id;

	this.opacity = 100;
	this.height = 0;

	this.fileProgressWrapper = document.getElementById(this.file_progress_id);
	if (!this.fileProgressWrapper) {
		this.fileProgressWrapper = document.createElement("div");
		this.fileProgressWrapper.className = "progressWrapper";
		this.fileProgressWrapper.id = this.file_progress_id;

		this.fileProgressContainer = document.createElement("div");
		this.fileProgressContainer.className = "progressContainer";
        
        this.fileProgressDetector = document.createElement("div");
        this.fileProgressDetector.className = "progressDetector";
        this.fileProgressDetector.style.width = "0%";
        
        this.fileProgressElement = document.createElement("div");
        this.fileProgressElement.className = "progressDetector-inner";        
        
        this.fileProgressDetector.appendChild(this.fileProgressElement);
        this.fileProgressContainer.appendChild(this.fileProgressDetector);
        
        
		var progressCancel = document.createElement("a");
		progressCancel.className = "progressCancel";
		progressCancel.href = "#";
		progressCancel.style.visibility = "hidden";
		progressCancel.appendChild(document.createTextNode(" "));

		var progressText = document.createElement("div");
		progressText.className = "progressName";
		progressText.appendChild(document.createTextNode(fileObj.name));

		var progressBar = document.createElement("div");
		progressBar.className = "progressBarInProgress";
        progressBar.style.display = "none";

		var progressStatus = document.createElement("div");
		progressStatus.className = "progressBarStatus";
		progressStatus.innerHTML = "&nbsp;";
        progressStatus.style.display = "none";

		this.fileProgressElement.appendChild(progressCancel);
		this.fileProgressElement.appendChild(progressText);
		this.fileProgressElement.appendChild(progressStatus);
		this.fileProgressElement.appendChild(progressBar);   

		this.fileProgressWrapper.appendChild(this.fileProgressContainer);

		document.getElementById(target_id).appendChild(this.fileProgressWrapper);
	} else {
		this.fileProgressContainer = this.fileProgressWrapper.firstChild;
        this.fileProgressDetector = this.fileProgressWrapper.firstChild.firstChild;
        this.fileProgressElement = this.fileProgressWrapper.firstChild.firstChild.firstChild;
	}

	this.height = this.fileProgressWrapper.offsetHeight;

}
FileProgress.prototype.SetProgress = function(percentage) {
/*	this.fileProgressElement.className = "progressContainer green";
	this.fileProgressElement.childNodes[3].className = "progressBarInProgress";*/
	this.fileProgressDetector.style.width = percentage + "%";
    //this.fileProgressElement.childNodes[3].style.width = percentage + "%";
};
FileProgress.prototype.SetComplete = function() {
/*	this.fileProgressElement.className = "progressContainer green";
	this.fileProgressElement.childNodes[3].className = "progressBarComplete";*/
	this.fileProgressElement.childNodes[3].style.width = "100%";

	var oSelf = this;
	//setTimeout(function() { oSelf.Disappear(); }, 10000);
};
FileProgress.prototype.SetError = function() {
	this.fileProgressContainer.className = "progressContainer red";
	this.fileProgressElement.childNodes[3].className = "progressBarError";
	this.fileProgressElement.childNodes[3].style.width = "";    
	var oSelf = this;
	setTimeout(function() { oSelf.Disappear(); }, 5000);
};
FileProgress.prototype.SetCancelled = function() {
	this.fileProgressContainer.className = "progressContainer";
	this.fileProgressElement.childNodes[3].className = "progressBarError";
	this.fileProgressElement.childNodes[3].style.width = "";

	var oSelf = this;
	setTimeout(function() { oSelf.Disappear(); }, 2000);
};
FileProgress.prototype.SetStatus = function(status) {
	this.fileProgressElement.childNodes[2].innerHTML = status;
};

FileProgress.prototype.ToggleCancel = function(show, upload_obj) {
    this.fileProgressElement.childNodes[0].style.visibility = show ? "visible" : "hidden";
	if (upload_obj) {
		var file_id = this.file_progress_id;
		this.fileProgressElement.childNodes[0].onclick = function() { upload_obj.cancelUpload(file_id); return false; };
	}
};

FileProgress.prototype.Disappear = function() {

	var reduce_opacity_by = 15;
	var reduce_height_by = 4;
	var rate = 30;	// 15 fps

	if (this.opacity > 0) {
		this.opacity -= reduce_opacity_by;
		if (this.opacity < 0) {
			this.opacity = 0;
		}

		if (this.fileProgressWrapper.filters) {
			try {
				this.fileProgressWrapper.filters.item("DXImageTransform.Microsoft.Alpha").opacity = this.opacity;
			} catch (e) {
				// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
				this.fileProgressWrapper.style.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity=" + this.opacity + ")";
			}
		} else {
			this.fileProgressWrapper.style.opacity = this.opacity / 100;
		}
	}

	if (this.height > 0) {
		this.height -= reduce_height_by;
		if (this.height < 0) {
			this.height = 0;
		}

		this.fileProgressWrapper.style.height = this.height + "px";
	}

	if (this.height > 0 || this.opacity > 0) {
		var oSelf = this;
		setTimeout(function() { oSelf.Disappear(); }, rate);
	} else {
		this.fileProgressWrapper.style.display = "none";
	}
};
