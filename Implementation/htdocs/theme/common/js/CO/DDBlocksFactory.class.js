DDCBlockFactory = function() {
	return {
	
		clone : function (targetId,contentType) {

				var d = this.factory(contentType);
  				
				objectsArrayLength = WarecorpDDblockApp.contentObjects.length;
				WarecorpDDblockApp.contentObjects[objectsArrayLength] = d;
				WarecorpDDblockApp.contentObjects[objectsArrayLength].ID = "ddContentObject"+WarecorpDDblockApp.index;
				WarecorpDDblockApp.contentObjects[objectsArrayLength].targetID = targetId;
				WarecorpDDblockApp.contentObjects[objectsArrayLength].editMode = 0;
				
				//new YAHOO.example.DDList("ddContentObject"+WarecorpDDblockApp.index);
				
			},

            load : function (item) {
				
			var tId	= 'ddTarget'+item['positionHorizontal'];
					
			var newObj = WarecorpDDblockApp.createHTMLObject(item['ContentType'], tId);
			xajax_get_block_content(newObj.targetID,newObj.id,'cb-content-'+newObj.id,item['ContentType'],0,item);
            },

            factory : function (contentType) {
                //ddProfileHeadline
				//if ( contentType == "ddProfileHeadline" ) {
                //    var newObj = new DDCHeadline("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                //    newObj.contentType = contentType;
                //    return newObj;
				//ddGroupHeadline
				//} else
				
				//ddGroupHeadline
				if ( contentType == "ddGroupHeadline" ) {
                    var newObj = new DDCFastHeadline("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
                    return newObj;
				//ddGroupDescription
				} else if ( contentType == "ddGroupDescription" ) {
                    var newObj = new DDCFastHeadline("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
                    return newObj;
				
				//ddMogulus
				} else if ( contentType == "ddMogulus" ) {
                    var newObj = new DDCMogulus("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.channel = 'theuptake';
					newObj.startOnInit = 0;
                    return newObj;
				//ddIframe
				} else if ( contentType == "ddIframe" ) {
                    var newObj = new DDCIframe("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.altSrc = 'f5d439c0b3';
                    return newObj;
				//ddScript
				} else if ( contentType == "ddScript" ) {
                    var newObj = new DDCScript("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.altSrc = '';
                    return newObj;	
				//ddProfileIntroduction
				//} else if ( contentType == "ddProfileIntroduction" ) {
                 //   var newObj = new DDCHeadline("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                 //   newObj.contentType = contentType;
                 //   return newObj;
				
				//ddContentBlock
				} else if ( contentType == "ddContentBlock" ) {
                    var newObj = new DDCTextBlock("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.innerText = '';
                    return newObj;
				//ddFamilyVideoContentBlock
				} else if ( contentType == "ddFamilyVideoContentBlock" ) {
                    var newObj = new DDCFamilyVideoContentBlock("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.innerText = '';
					newObj.videoId = 0;
                    return newObj;
				//ddMyVideoContentBlock
				} else if ( contentType == "ddMyVideoContentBlock" ) {
                    var newObj = new DDCMyVideoContentBlock("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.innerText = '';
					newObj.videoId = 0;
                    return newObj;
				//ddProfileDetails	
				} else if ( contentType == "ddProfileDetails" ) {
                    var newObj = new DDCProfileDetails("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.hide = new Array();
					return newObj;
				//ddProfileDetailsAT	
				} else if ( contentType == "ddProfileDetailsAT" ) {
                    var newObj = new DDCProfileDetailsAT("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.hide = new Array();
					return newObj;
				//ddMyGroups	
                } else if ( contentType == "ddMyGroups" ) {
                    var newObj = new DDCMyGroups("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.unhide = new Array();
					newObj.family_unhide = new Array();
                    return newObj;
				//ddPicture
				} else if ( contentType == "ddPicture" ) {
                    var newObj = new DDCPicture("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.avatarId = 0;
                    return newObj;
				//ddGroupAvatar
				} else if ( contentType == "ddGroupAvatar" ) {
                    var newObj = new DDCGroupAvatar("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.avatarId = 0;
                    return newObj;
				//ddFamilyAvatar
				} else if ( contentType == "ddFamilyAvatar" ) {
                    var newObj = new DDCFamilyAvatar("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.avatarId = 0;
                    return newObj;
				//ddFamilyIcons
				} else if ( contentType == "ddFamilyIcons" ) {
                    var newObj = new DDCFamilyIcons("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.avatarId = 0;
                    return newObj;
				//ddGroupFamilyIcons
				} else if ( contentType == "ddGroupFamilyIcons" ) {
                    var newObj = new DDCGroupFamilyIcons("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.avatarId = 0;
                    return newObj;
				//ddGroupImage
				} else if ( contentType == "ddGroupImage" ) {
                    var newObj = new DDCGroupImage("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.avatarId = 0;
                    return newObj;
				//ddImage
				} else if ( contentType == "ddImage" ) {
                    var newObj = new DDCImage("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.avatarId = 0;
                    return newObj;
                //ddMyPhotos
				} else if ( contentType == "ddMyPhotos" ) {
                    var newObj = new DDCMyPhotos("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.galleryCount = 3;
        			newObj.galleryType = 1;//?
					newObj.galleryShowAsIcons =0;
					newObj.galleryShowThumbnailsCount = 20;
					newObj.galleries = new Array();
                    return newObj;
				//ddMyVideos
				} else if ( contentType == "ddMyVideos" ) {
                    var newObj = new DDCMyVideos("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.galleryCount = 3;
        			newObj.galleryType = 1;//?
					newObj.galleryShowAsIcons =0;
					newObj.galleryShowThumbnailsCount = 20;
					newObj.galleries = new Array();
                    return newObj;	
				//ddGroupPhotos
				} else if ( contentType == "ddGroupPhotos" ) {
                    var newObj = new DDCGroupPhotos("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.galleryCount = 3;
        			newObj.galleryType = 1;//?
					newObj.galleryShowAsIcons =0;
					newObj.galleries = new Array();
                    return newObj;
				//ddRSSFeed	
                } else if ( contentType == "ddRSSFeed" ) {
                    var newObj = new DDCRSSFeed("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.rssUrl = '';
					newObj.rssTitle = '';
					newObj.rssMaxLines = 5;
                    newObj.rssView = 0;
                    newObj.rssHeaderColor = '';
                    newObj.rssHeaderFont = '';
                    newObj.rssDescriptionFont = '';
                    newObj.rssDescriptionColor = '';
                    newObj.rssHeaderFontSize = '';
                    newObj.rssDescriptionFontSize = '';
                   
                   return newObj;
				//ddMyDocuments	
				} else if ( contentType == "ddMyDocuments" ) {
                    var newObj = new DDCMyDocuments("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.documents = new Array();
                    return newObj;
				//ddGroupDocuments	
				} else if ( contentType == "ddGroupDocuments" ) {
                    var newObj = new DDCGroupDocuments("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.documents = new Array();
                    return newObj;
				//ddFamilyDiscussions	
				} else if ( contentType == "ddFamilyDiscussions" || contentType == "ddMyDiscussions") {
                    var newObj = new DDCFamilyDiscussions("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.discussions_threads = new Array();
					newObj.discussions_threads[0] = 0;
					newObj.discussions_threads[1] = 0;
					newObj.discussions_show_thread_summaries = 1;
					newObj.discussions_show_thread_summaries2 = 1;
					newObj.discussions_display_most_active = 1;
					newObj.discussions_display_most_recent = 1;
					newObj.discussions_show_threads_number = 0;
                    return newObj;
				//ddFamilyTopVideos	
				} else if ( contentType == "ddFamilyTopVideos") {
                    var newObj = new DDCFamilyTopVideos("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.topvideos_display_most_active = 1;
					newObj.topvideos_display_most_recent = 1;
					newObj.topvideos_display_most_upped = 1;
					newObj.topvideos_show_threads_number = 0;
                    return newObj;
				//ddMyLists	
				} else if ( contentType == "ddMyLists" ) {
                    var newObj = new DDCMyLists("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.listDisplayType = 0; // list index, individual list
					newObj.listCategoriesToDisplay = new Array();
					newObj.listDefaultIndexSort = 1; // Most Ranked,Most Items to least,Newest to Oldest
					newObj.listDisplayNumberInEachCategory = 3;
					newObj.listShowSummaries = 1;
                    return newObj;
				//ddGroupLists	
				} else if ( contentType == "ddGroupLists" ) {
                    var newObj = new DDCGroupLists("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.listDisplayType = 0; // list index, individual list
					newObj.listCategoriesToDisplay = new Array();
					newObj.listDefaultIndexSort = 1; // Most Ranked,Most Items to least,Newest to Oldest
					newObj.listDisplayNumberInEachCategory = 3;
					newObj.listShowSummaries = 1;
                    return newObj;
				//ddFamilyLists	
				} else if ( contentType == "ddFamilyLists" ) {
                    var newObj = new DDCFamilyLists("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.listDisplayType = 0; // list index, individual list
					newObj.listCategoriesToDisplay = new Array();
					newObj.listDefaultIndexSort = 1; // Most Ranked,Most Items to least,Newest to Oldest
					newObj.listDisplayNumberInEachCategory = 3;
					newObj.listShowSummaries = 1;
                    return newObj;
				//ddMyFriends	
				} else if ( contentType == "ddMyFriends" ) {
                    var newObj = new DDCMyFriends("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.displayType = 0; 
					newObj.defaultIndexSort = 1; 
					newObj.displayNumberInEachRegion = 3;
					newObj.hide = new Array();
                    return newObj;
				//ddGroupMembers	
				} else if ( contentType == "ddGroupMembers" ) {
                    var newObj = new DDCGroupMembers("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.displayType = 0; 
					newObj.defaultIndexSort = 1; 
					newObj.displayNumberInEachRegion = 3;
					newObj.hide = new Array();
                    return newObj;
				//ddFamilyPeople	
				} else if ( contentType == "ddFamilyPeople" ) {
                    var newObj = new DDCFamilyPeople("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.displayType = 0; 
					newObj.defaultIndexSort = 1; 
					newObj.displayNumberInEachRegion = 3;
					newObj.hide = new Array();
					newObj.entityToDisplay = 1;
                    return newObj;
				//ddFamilyMemberIndex	
				} else if ( contentType == "ddFamilyMemberIndex" ) {
                    var newObj = new DDCFamilyMemberIndex("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.displayType = 0; 
					newObj.defaultIndexSort = 1; 
					newObj.displayNumberInEachRegion = 3;
					newObj.hide = new Array();
                    return newObj;
				//ddMyEvents	
				} else if ( contentType == "ddMyEvents" ) {
                    var newObj = new DDCEvents("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.eventDisplayStyle = 1;
					newObj.eventsFuteredDisplayNumber = 1;
					newObj.eventsDisplayNumber = 3;
					newObj.eventsShowSummaries = 0;
					newObj.eventsShowVenues = 0;
					newObj.eventsShowCalendar = 1;
                    return newObj;
				} else if ( contentType == "ddGroupEvents" ) {
                    var newObj = new DDCGroupEvents("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.eventDisplayStyle = 1;
					newObj.eventsFuteredDisplayNumber = 1;
					newObj.eventsDisplayNumber = 3;
					newObj.eventsShowSummaries = 0;
					newObj.eventsShowVenues = 0;
					newObj.eventsShowCalendar = 1;
                    return newObj;
				} else if ( contentType == "ddFamilyEvents" ) {
                    var newObj = new DDCFamilyEvents("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.eventDisplayStyle = 1;
					newObj.eventsFuteredDisplayNumber = 1;
					newObj.eventsDisplayNumber = 3;
					newObj.eventsShowSummaries = 0;
					newObj.eventsShowVenues = 0;
					newObj.eventsShowCalendar = 1;
                    return newObj;
				
				} else if ( contentType == "ddGroupWidgetMap" || contentType == "ddFamilyWidgetMap") {
                    var newObj = new DDCGroupWidgetMap("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
					newObj.defaultDisplayType = 1;
					newObj.displayRange = 0;
					newObj.eventsDisplayType = 0;
					newObj.eventToDisplayId = 0;
					
                    return newObj;
				
				} else if ( contentType == "ddElectedOfficial" ) {
                    var newObj = new DDCElectedOfficial("eDDContentObject"+WarecorpDDblockApp.index, "group1");
                    newObj.contentType = contentType;
                    newObj.eventDisplayStyle = 1;
                    newObj.eventsFuteredDisplayNumber = 1;
                    newObj.eventsDisplayNumber = 3;
                    newObj.eventsShowSummaries = 0;
                    newObj.eventsShowVenues = 0;
                    newObj.eventsShowCalendar = 1;
                    return newObj;
				} else if ( contentType == "ddRoundInfo" ) {
                    var newObj = new DDCRoundInfo("eDDContentObject"+WarecorpDDblockApp.index, "group1")
                    newObj.contentType = contentType;
					newObj.displayType = 0; 
					newObj.hide = new Array();
                    return newObj;
				} else if ( contentType == "ddRoundEvents" ) {
                    var newObj = new DDCRoundEvents("eDDContentObject"+WarecorpDDblockApp.index, "group1")
                    newObj.contentType = contentType;
					newObj.displayType = 0;
					newObj.hide = new Array();
                    return newObj;
				}else {
                    newObj = new DDC("eDDContentObject"+WarecorpDDblockApp.index, "group1")
                    newObj.contentType = "ddContentBlock";
                    return newObj;
                }
            }

        }
    }
    ();
